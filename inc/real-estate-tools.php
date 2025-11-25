<?php

/****************************************************************************************************************
 * E A S Y B R O K E R
 ****************************************************************************************************************/

/**
 * Fetches property data from the EasyBroker API.
 *
 * This function connects to the EasyBroker REST API and retrieves a list of properties 
 * according to the specified filters. It is primarily used to display property listings 
 * on the website (for example, in sales or rental sections). 
 *
 * Parameters:
 * - $operation_type (string|null): Optional filter for operation type ('sale', 'rent', etc.).
 * - $limit (int): Maximum number of properties to fetch (default: 12).
 *
 * Returns:
 * - array: An associative array containing property data from EasyBroker.
 *           Returns an empty array if the API request fails or if no data is available.
 *
 * Example usage:
 *   $properties = eb_get_properties('sale', 10);
 *   foreach ($properties as $property) {
 *       echo $property['title'];
 *   }
 */
function eb_get_properties($operation_type = null, $limit = 12) {
    $api_key = EASYBROKER_API_KEY;
    $url = 'https://api.easybroker.com/v1/properties?limit=' . intval($limit);

    // Add operation type filter if specified (e.g., 'sale', 'rent')
    if ($operation_type) {
        $url .= '&operation_type=' . urlencode($operation_type);
    }

    $args = array(
        'headers' => array(
            'X-Authorization' => $api_key
        ),
        'timeout' => 15,
    );

    $response = wp_remote_get($url, $args);

    // Return empty array if API request fails
    if (is_wp_error($response)) return [];

    // Decode JSON response and return property content if available
    $body = json_decode(wp_remote_retrieve_body($response), true);
    return $body['content'] ?? [];
}

/****************************************************************************************************************
 * P R O P E R T I E S
 ****************************************************************************************************************/

/**
 * Retrieves and caches a list of property locations grouped by state and city.
 *
 * This function collects location data (state, city, and neighborhood) from published
 * property posts, organizes them into a structured array, removes duplicates, and
 * sorts them alphabetically. The result is cached using a transient for performance.
 *
 * @return array An associative array of locations grouped by state.
 */
function get_property_locations() {
    $locations = get_transient('property_locations');

    // Return cached data if available
    if ($locations !== false) {
        return $locations;
    }

    // Get a limited number of published properties
    $properties = get_posts([
        'post_type' => 'property',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    ]);

    $locations = [];

    if ($properties) {
        foreach ($properties as $prop) {
            $loc = get_post_meta($prop->ID, 'eb_location', true);
            if ($loc) {
                // Split the location string and trim extra spaces
                $parts = array_map('trim', explode(',', $loc));

                // Expected format: [neighborhood, city, state]
                $neighborhood = $parts[0] ?? '';
                $city         = $parts[1] ?? '';
                $state        = $parts[2] ?? '';

                // Group cities by state
                if ($state && $city) {
                    $locations[$state][] = $city;
                }
            }
        }

        // Remove duplicates and sort alphabetically
        foreach ($locations as $state => $cities) {
            $locations[$state] = array_unique($cities);
            sort($locations[$state]);
        }
        ksort($locations);
    }

    // Cache the results for one day
    set_transient('property_locations', $locations, DAY_IN_SECONDS);

    return $locations;
}

// Clear the cached data when a property is saved or updated
add_action('save_post_property', function() {
    delete_transient('property_locations');
    delete_transient('property_price_range');
    delete_transient('property_construction_range');
    delete_transient('property_land_range');
});

/**
 * Retrieves the minimum and maximum price range from all published properties.
 *
 * Caches the result in a transient for performance optimization.
 *
 * @return array An associative array with 'min' and 'max' keys containing price values.
 */
function get_property_price_range() {
    $cached = get_transient('property_price_range');
    
    if ($cached !== false) {
        return $cached;
    }

    global $wpdb;
    
    $result = $wpdb->get_results(
        "SELECT 
            MIN(CAST(meta_value AS UNSIGNED)) as min_price,
            MAX(CAST(meta_value AS UNSIGNED)) as max_price
        FROM {$wpdb->postmeta} pm
        INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
        WHERE pm.meta_key = 'eb_price_num'
        AND p.post_type = 'property'
        AND p.post_status = 'publish'"
    );

    $range = [
        'min' => isset($result[0]->min_price) && $result[0]->min_price ? (int) $result[0]->min_price : 0,
        'max' => isset($result[0]->max_price) && $result[0]->max_price ? (int) $result[0]->max_price : 0,
    ];

    // Cache for 24 hours
    set_transient('property_price_range', $range, DAY_IN_SECONDS);

    return $range;
}

/**
 * Retrieves the minimum and maximum construction size range from all published properties.
 *
 * Caches the result in a transient for performance optimization.
 *
 * @return array An associative array with 'min' and 'max' keys containing construction size values.
 */
function get_property_construction_range() {
    $cached = get_transient('property_construction_range');
    
    if ($cached !== false) {
        return $cached;
    }

    global $wpdb;
    
    $result = $wpdb->get_results(
        "SELECT 
            MIN(CAST(meta_value AS UNSIGNED)) as min_construction,
            MAX(CAST(meta_value AS UNSIGNED)) as max_construction
        FROM {$wpdb->postmeta} pm
        INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
        WHERE pm.meta_key = 'eb_construction_size'
        AND p.post_type = 'property'
        AND p.post_status = 'publish'"
    );

    $range = [
        'min' => isset($result[0]->min_construction) && $result[0]->min_construction ? (int) $result[0]->min_construction : 0,
        'max' => isset($result[0]->max_construction) && $result[0]->max_construction ? (int) $result[0]->max_construction : 0,
    ];

    // Cache for 24 hours
    set_transient('property_construction_range', $range, DAY_IN_SECONDS);

    return $range;
}

/**
 * Retrieves the minimum and maximum land/lot size range from all published properties.
 *
 * Caches the result in a transient for performance optimization.
 *
 * @return array An associative array with 'min' and 'max' keys containing land size values.
 */
function get_property_land_range() {
    $cached = get_transient('property_land_range');
    
    if ($cached !== false) {
        return $cached;
    }

    global $wpdb;
    
    $result = $wpdb->get_results(
        "SELECT 
            MIN(CAST(meta_value AS UNSIGNED)) as min_land,
            MAX(CAST(meta_value AS UNSIGNED)) as max_land
        FROM {$wpdb->postmeta} pm
        INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
        WHERE pm.meta_key = 'eb_lot_size'
        AND p.post_type = 'property'
        AND p.post_status = 'publish'"
    );

    $range = [
        'min' => isset($result[0]->min_land) && $result[0]->min_land ? (int) $result[0]->min_land : 0,
        'max' => isset($result[0]->max_land) && $result[0]->max_land ? (int) $result[0]->max_land : 0,
    ];

    // Cache for 24 hours
    set_transient('property_land_range', $range, DAY_IN_SECONDS);

    return $range;
}

/**
 * Registers the "Property" custom post type (CPT) for real estate listings.
 * 
 * This function is used as a fallback in case the SCF plugin is not available
 * to register the 'property' post type. It sets up basic labels, supports,
 * archive behavior, REST API availability, and the admin menu icon.
 */
// function eb_register_post_type() {
//     register_post_type('property', [
//         'label' => 'Propiedades',
//         'public' => true,
//         'menu_icon' => 'dashicons-admin-home',
//         'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
//         'has_archive' => true,
//         'rewrite' => ['slug' => 'properties'],
//         'show_in_rest' => true,
//     ]);
// }
// add_action('init', 'eb_register_post_type');

/****************************************************************************************************************
 * A J A X   P R O P E R T I E S
 ****************************************************************************************************************/

function enqueue_property_filter_script() {
    wp_enqueue_script('property-filter', get_template_directory_uri() . '/assets/js/ajax-properties.js', ['jquery'], null, true);
    wp_localize_script('property-filter', 'ajaxurlObj', [
        'ajax_url' => admin_url('admin-ajax.php')
    ]);
}
add_action('wp_enqueue_scripts', 'enqueue_property_filter_script');

/**
 * AJAX property filter handler.
 *
 * Handles AJAX requests to filter property listings based on various criteria:
 * operation type, property type, location, bedrooms, bathrooms, price, 
 * construction size, and lot size. Builds a dynamic WP_Query based on 
 * user-submitted filters and returns matching property templates.
 *
 * @since 1.0.0
 * @return void
 */
add_action('wp_ajax_filter_properties', 'ajax_filter_properties');
add_action('wp_ajax_nopriv_filter_properties', 'ajax_filter_properties');

function ajax_filter_properties() {
    global $wpdb;

    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;

    // Keyword search
    $search_term = !empty($_POST['search']) ? sanitize_text_field($_POST['search']) : '';

    // Initialize args
    $args = [
        'post_type'      => 'property',
        'posts_per_page' => 12,
        'post_status'    => 'publish',
        'order'          => 'DESC',
        'paged'          => $paged,
    ];

    if ($search_term) {
        $args['s'] = $search_term;
        // Filter search to title only
        add_filter('posts_search', function($search, $wp_query) use ($wpdb) {
            if ($term = $wp_query->get('s')) {
                $search = $wpdb->prepare(
                    " AND {$wpdb->posts}.post_title LIKE %s ",
                    '%' . $wpdb->esc_like($term) . '%'
                );
            }
            return $search;
        }, 10, 2);
    }

    // Build meta_query
    $meta_query = ['relation' => 'AND'];

    // Operation type
    if (!empty($_POST['operation'])) {
        $meta_query[] = [
            'key'     => 'eb_operation',
            'value'   => (array) $_POST['operation'],
            'compare' => 'IN',
        ];
    }

    // Property type
    if (!empty($_POST['type'])) {
        $meta_query[] = [
            'key'     => 'eb_property_type',
            'value'   => (array) $_POST['type'],
            'compare' => 'IN',
        ];
    }

    // Location (state / city)
    $location_meta = [];
    if (!empty($_POST['state'])) {
        foreach ((array) $_POST['state'] as $state) {
            $location_meta[] = [
                'key'     => 'eb_location',
                'value'   => sanitize_text_field($state),
                'compare' => 'LIKE',
            ];
        }
    }
    if (!empty($_POST['city'])) {
        foreach ((array) $_POST['city'] as $city) {
            $location_meta[] = [
                'key'     => 'eb_location',
                'value'   => sanitize_text_field($city),
                'compare' => 'LIKE',
            ];
        }
    }
    if (!empty($location_meta)) {
        $meta_query[] = array_merge(['relation' => 'OR'], $location_meta);
    }

    // Bedrooms
    if (!empty($_POST['bedrooms'])) {
        $meta_query[] = [
            'key'     => 'eb_bedrooms',
            'value'   => intval($_POST['bedrooms']),
            'compare' => '=',
            'type'    => 'NUMERIC',
        ];
    }

    // Bathrooms
    if (!empty($_POST['bathrooms'])) {
        $meta_query[] = [
            'key'     => 'eb_bathrooms',
            'value'   => intval($_POST['bathrooms']),
            'compare' => '=',
            'type'    => 'NUMERIC',
        ];
    }

    // Price range
    $price_min = isset($_POST['price_min']) && $_POST['price_min'] !== '' ? floatval($_POST['price_min']) : 0;
    $price_max = isset($_POST['price_max']) && $_POST['price_max'] !== '' ? floatval($_POST['price_max']) : PHP_INT_MAX;
    if ($price_min > 0 || $price_max < PHP_INT_MAX) {
        $meta_query[] = [
            'key'     => 'eb_price_num',
            'value'   => [$price_min, $price_max],
            'compare' => 'BETWEEN',
            'type'    => 'NUMERIC',
        ];
    }

    // Construction size range
    $construction_min = isset($_POST['construction_min']) && $_POST['construction_min'] !== '' ? floatval($_POST['construction_min']) : 0;
    $construction_max = isset($_POST['construction_max']) && $_POST['construction_max'] !== '' ? floatval($_POST['construction_max']) : PHP_INT_MAX;
    if ($construction_min > 0 || $construction_max < PHP_INT_MAX) {
        $meta_query[] = [
            'key'     => 'eb_construction_size',
            'value'   => [$construction_min, $construction_max],
            'compare' => 'BETWEEN',
            'type'    => 'NUMERIC',
        ];
    }

    // Lot size range
    $lot_min = isset($_POST['land_min']) && $_POST['land_min'] !== '' ? floatval($_POST['land_min']) : 0;
    $lot_max = isset($_POST['land_max']) && $_POST['land_max'] !== '' ? floatval($_POST['land_max']) : PHP_INT_MAX;
    if ($lot_min > 0 || $lot_max < PHP_INT_MAX) {
        $meta_query[] = [
            'key'     => 'eb_lot_size',
            'value'   => [$lot_min, $lot_max],
            'compare' => 'BETWEEN',
            'type'    => 'NUMERIC',
        ];
    }

    // Assign meta_query if not empty
    if (!empty($meta_query)) {
        $args['meta_query'] = $meta_query;
    }

    // Execute query
    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('template-parts/loop/content', 'property');
        }

        // Pagination
        echo '<nav class="navigation pagination" aria-label="Posts pagination">';
        echo '<h2 class="screen-reader-text">Posts pagination</h2>';
        echo '<div class="nav-links">';
        echo paginate_links([
            'total'   => $query->max_num_pages,
            'current' => $paged,
            'format'  => '?paged=%#%',
            'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"/></svg>',
            'next_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-circle" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z"/></svg>',
        ]);
        echo '</div></nav>';
    } else {
        echo '<p>No se encontraron propiedades.</p>';
    }

    wp_reset_postdata();
    wp_die();
}

/****************************************************************************************************************
 * C H E C K B O X   F E A T U R E D   O N  P R O P E R T I E S
 ****************************************************************************************************************/
// Añadir la columna
add_filter('manage_property_posts_columns', function($columns) {
    $columns['featured'] = __('Featured', 'inmobiliaria');
    return $columns;
});

// Mostrar la columna con toggle AJAX
add_action('manage_property_posts_custom_column', function($column, $post_id) {
    if ($column === 'featured') {
        $is_featured = get_field('featured', $post_id);
        $checked = $is_featured ? 'checked' : '';
        echo '<input type="checkbox" class="acf-featured-toggle" data-id="' . $post_id . '" ' . $checked . ' />';
    }
}, 10, 2);

add_action('admin_footer-edit.php', function() {
    $screen = get_current_screen();
    if ($screen->post_type !== 'property') return;
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggles = document.querySelectorAll('.acf-featured-toggle');
        toggles.forEach(toggle => {
            toggle.addEventListener('change', () => {
                const postId = toggle.dataset.id;
                const value = toggle.checked ? 1 : 0;
                fetch(ajaxurl, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: new URLSearchParams({
                        action: 'toggle_featured',
                        post_id: postId,
                        value: value,
                        _ajax_nonce: '<?php echo wp_create_nonce('toggle_featured_nonce'); ?>'
                    })
                }).then(r => r.json()).then(res => {
                    if (!res.success) alert('Error al actualizar');
                });
            });
        });
    });
    </script>
    <style>
        .acf-featured-toggle { transform: scale(1.2); cursor: pointer; }
    </style>
    <?php
});

add_action('wp_ajax_toggle_featured', function() {
    check_ajax_referer('toggle_featured_nonce');
    $post_id = intval($_POST['post_id']);
    $value = intval($_POST['value']);

    if (!current_user_can('edit_post', $post_id)) {
        wp_send_json_error('Permiso denegado');
    }

    update_field('featured', $value, $post_id); // ACF actualiza el campo
    wp_send_json_success();
});

/****************************************************************************************************************
 * P R O P E R T Y   M E T A D A T A
 ****************************************************************************************************************/
/**
 * Format numeric values with thousand separators
 * 
 * Formats numbers with ' (apostrophe) for thousands and , (comma) for decimals.
 * Example: 1500000 becomes "1'500'000"
 *
 * @param int|float $number The number to format
 * @return string Formatted number
 */
function format_numeric($number) {
    if (empty($number) || !is_numeric($number)) {
        return $number;
    }
    
    // Convert to number and format with ' for thousands and , for decimals
    return number_format((float) $number, 0,  "'", ',');
}

/****************************************************************************************************************
 * M E T A D A T A   F O R   P R O P E R T I E S
 ****************************************************************************************************************/

/**
 * Property Metadata Helpers
 * 
 * Functions for rendering property metadata items with consistent
 * SVG icons and formatting across templates.
 */

/**
 * Get SVG icon for metadata item
 * 
 * @param string $type Type of metadata (bedroom, bathroom, construction, lot, parking)
 * @return string SVG markup
 */
function stories_get_metadata_icon($type) {
    $icons = [
        'id' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-heading" viewBox="0 0 16 16"><path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2z"/><path d="M3 8.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5m0-5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5z"/></svg>',
        'location' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt" viewBox="0 0 16 16"><path d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A32 32 0 0 1 8 14.58a32 32 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10"/><path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4m0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/></svg>',
        'bedroom' => '<svg width="19" height="19" viewBox="0 0 16 16" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linecap:round;stroke-linejoin:round;"><path d="M13,9.5l-10,0l0,-3.25c0.002,-0.685 0.565,-1.248 1.25,-1.25l7.5,0c0.685,0.002 1.248,0.565 1.25,1.25l0,3.25Zm-11.5,5.5l0,-3.5c0.003,-1.096 0.904,-1.997 2,-2l9,0c1.096,0.003 1.997,0.904 2,2l0,3.5" style="fill:none;fill-rule:nonzero;stroke:currentColor;stroke-width:.8px;"/><path d="M1.5,15l0,-0.25c0.001,-0.411 0.339,-0.749 0.75,-0.75l11.5,0c0.411,0.001 0.749,0.339 0.75,0.75l0,0.25m-11,-5.5l0,-0.5c0.002,-0.548 0.452,-0.998 1,-1l2.5,0c0.548,0.002 0.998,0.452 1,1l0,0.5m0,0l0,-0.5c0.002,-0.548 0.452,-0.998 1,-1l2.5,0c0.548,0.002 0.998,0.452 1,1l0,0.5" style="fill:none;fill-rule:nonzero;stroke:currentColor;stroke-width:.8px;"/></svg>',
        'bathroom' => '<svg fill="currentColor" width="16" height="16" viewBox="0 0 512 512" id="Layer_1" enable-background="new 0 0 512 512" xmlns="http://www.w3.org/2000/svg"><g><path d="m496 288c-38.154 0-437.487 0-448 0v-56h32c8.837 0 16-7.164 16-16v-40c0-8.836-7.163-16-16-16s-16 7.164-16 16v24h-16v-138.745c0-25.903 31.562-39.064 49.941-20.686l16.94 16.94c-13.424 23.401-10.164 53.835 9.805 73.805l8 8c6.247 6.248 16.379 6.249 22.627 0l64-64c6.249-6.248 6.249-16.379 0-22.627l-8-8c-20.35-20.351-50.837-23.06-73.817-9.817l-16.928-16.928c-11.57-11.57-26.952-17.942-43.313-17.942-33.776 0-61.255 27.479-61.255 61.255v226.745c-8.837 0-16 7.164-16 16s7.163 16 16 16v32c0 43.889 19.742 83.247 50.806 109.681l-22.338 23.229c-9.803 10.193-2.445 27.09 11.53 27.09 4.199 0 8.394-1.644 11.534-4.91l26.218-27.263c19.844 10.326 42.376 16.173 66.25 16.173h192c23.874 0 46.406-5.847 66.25-16.173l26.218 27.263c6.106 6.35 16.234 6.585 22.623.442 6.369-6.125 6.566-16.254.441-22.623l-22.338-23.229c31.064-26.433 50.806-65.791 50.806-109.68v-32c8.837 0 16-7.164 16-16s-7.163-16-16-16zm-310.89-223.738-40.845 40.845c-8.246-11.427-7.23-27.515 3.048-37.794 10.378-10.377 26.461-11.259 37.797-3.051zm278.89 287.738c0 61.757-50.243 112-112 112h-192c-61.757 0-112-50.243-112-112v-32h416z"/></g></svg>',
        'construction' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-buildings" viewBox="0 0 16 16"><path d="M14.763.075A.5.5 0 0 1 15 .5v15a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5V14h-1v1.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V10a.5.5 0 0 1 .342-.474L6 7.64V4.5a.5.5 0 0 1 .276-.447l8-4a.5.5 0 0 1 .487.022M6 8.694 1 10.36V15h5zM7 15h2v-1.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5V15h2V1.309l-7 3.5z"/><path d="M2 11h1v1H2zm2 0h1v1H4zm-2 2h1v1H2zm2 0h1v1H4zm4-4h1v1H8zm2 0h1v1h-1zm-2 2h1v1H8zm2 0h1v1h-1zm2-2h1v1h-1zm0 2h1v1h-1zM8 7h1v1H8zm2 0h1v1h-1zm2 0h1v1h-1zM8 5h1v1H8zm2 0h1v1h-1zm2 0h1v1h-1zm0-2h1v1h-1z"/></svg>',
        'lot' => '<svg width="20" height="20" fill="currentcolor" viewBox="0 0 16 16" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;"><path d="M6.667,10.333l6.333,0c0.92,0 1.667,0.746 1.667,1.667l0,2c0,0.92 -0.746,1.667 -1.667,1.667l-10,0c-0.92,0 -1.667,-0.746 -1.667,-1.667l0,-10c0,-0.92 0.746,-1.667 1.667,-1.667l2,0c0.92,0 1.667,0.746 1.667,1.667l0,6.333Zm-0.724,4l-2.276,0c-0.184,0 -0.333,-0.149 -0.333,-0.333c0,-0.184 0.149,-0.333 0.333,-0.333l2.333,0l0,-1.333l-1.667,0c-0.184,0 -0.333,-0.149 -0.333,-0.333c0,-0.184 0.149,-0.333 0.333,-0.333l1.667,0l0,-1.333l-2.333,0c-0.184,0 -0.333,-0.149 -0.333,-0.333c0,-0.184 0.149,-0.333 0.333,-0.333l2.333,0l0,-1.333l-1.667,0c-0.184,0 -0.333,-0.149 -0.333,-0.333c0,-0.184 0.149,-0.333 0.333,-0.333l1.667,0l0,-1.333l-2.333,0c-0.184,0 -0.333,-0.149 -0.333,-0.333c0,-0.184 0.149,-0.333 0.333,-0.333l2.333,0l0,-1.333l-1.667,0c-0.184,0 -0.333,-0.149 -0.333,-0.333c0,-0.184 0.149,-0.333 0.333,-0.333l1.61,0c-0.137,-0.388 -0.508,-0.667 -0.943,-0.667l-2,0c-0.552,0 -1,0.448 -1,1l0,10c0,0.552 0.448,1 1,1l2,0c0.435,0 0.806,-0.278 0.943,-0.667Zm0.724,-3.333l0,3c0,0.375 -0.124,0.721 -0.333,1l6.667,0c0.552,0 1,-0.448 1,-1l0,-2c0,-0.552 -0.448,-1 -1,-1l-6.333,0Zm4.667,0.667l1.333,0c0.368,0 0.667,0.298 0.667,0.667l0,1.333c0,0.368 -0.298,0.667 -0.667,0.667l-1.333,0c-0.368,0 -0.667,-0.298 -0.667,-0.667l0,-1.333c0,-0.368 0.298,-0.667 0.667,-0.667Zm0,0.667l0,1.333l1.333,0l0,-1.333l-1.333,0Z" style="fill-rule:nonzero;"/></svg>',
        'parking' => '<svg width="20" height="20" viewBox="0 0 16 16" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linecap:round;stroke-linejoin:round;"><path d="M14.678,10.331c-0.229,-0.304 -1.08,-0.513 -1.44,-1.061c-0.36,-0.547 -0.655,-1.732 -1.571,-2.187c-0.916,-0.455 -2.668,-0.583 -3.668,-0.583c-1,0 -2.75,0.125 -3.668,0.582c-0.918,0.457 -1.211,1.641 -1.571,2.188c-0.36,0.546 -1.211,0.758 -1.44,1.062c-0.229,0.304 -0.39,2.226 -0.292,3.169c0.098,0.943 0.281,1.5 0.281,1.5l2.688,0c0.44,0 0.583,-0.165 1.483,-0.25c0.987,-0.094 1.956,-0.125 2.519,-0.125c0.562,0 1.562,0.031 2.549,0.125c0.9,0.085 1.048,0.25 1.483,0.25l2.656,0c0,0 0.183,-0.557 0.281,-1.5c0.098,-0.943 -0.064,-2.865 -0.292,-3.169Zm-2.178,4.669l1.75,0l0,0.5l-1.75,0l0,-0.5Zm-10.75,0l1.75,0l0,0.5l-1.75,0l0,-0.5Z" style="fill:none;fill-rule:nonzero;stroke:currentColor;stroke-width:.8px;"/><path d="M11.39,12.661c-0.185,-0.213 -0.787,-0.392 -1.583,-0.511c-0.797,-0.119 -1.088,-0.15 -1.8,-0.15c-0.713,0 -1.037,0.051 -1.8,0.15c-0.764,0.099 -1.337,0.275 -1.583,0.511c-0.369,0.357 0.172,0.759 0.596,0.807c0.411,0.047 1.233,0.03 2.791,0.03c1.557,0 2.38,0.017 2.791,-0.03c0.424,-0.052 0.926,-0.425 0.589,-0.807Zm2.097,-2.066c-0.004,-0.051 -0.046,-0.092 -0.097,-0.094c-0.369,-0.013 -0.744,0.013 -1.408,0.209c-0.339,0.099 -0.658,0.258 -0.94,0.471c-0.071,0.056 -0.046,0.206 0.043,0.222c0.548,0.064 1.099,0.097 1.651,0.097c0.331,0 0.672,-0.094 0.736,-0.389c0.032,-0.17 0.038,-0.344 0.015,-0.516Zm-10.973,-0c0.004,-0.051 0.046,-0.092 0.097,-0.094c0.369,-0.013 0.744,0.013 1.408,0.209c0.339,0.099 0.658,0.258 0.94,0.471c0.071,0.056 0.046,0.206 -0.043,0.222c-0.548,0.064 -1.099,0.097 -1.651,0.097c-0.331,0 -0.672,-0.094 -0.736,-0.389c-0.032,-0.17 -0.038,-0.344 -0.015,-0.516Z" style="fill-rule:nonzero;"/><path d="M13.5,9l0.5,0m-12,0l0.5,0m-0.062,0.594c0,0 1.448,-0.375 5.562,-0.375c4.114,0 5.562,0.375 5.562,0.375" style="fill:none;fill-rule:nonzero;stroke:currentColor;stroke-width:.8px;"/></svg>',
        'house' => '<svg height="16" width="16" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve"><g><g><g><path style="fill:currentColor;" d="M483.132,423.762c10.894,0,19.609-9.804,19.609-20.698V20.698C502.74,8.715,492.936,0,482.043,0 H29.957C17.974,0,9.26,9.804,9.26,20.698v382.366c0,11.983,9.804,20.698,20.698,20.698H60.46v46.843H29.957 c-11.983,0-20.698,8.715-20.698,20.698C9.26,503.285,19.064,512,29.957,512h51.2h130.723h90.417h129.634h51.2 c10.894,0,19.609-8.715,19.609-20.698c0-11.983-9.804-20.698-20.698-20.698H452.63v-46.843H483.132z M50.655,41.396h411.779 v340.97h-30.502H322.996v-86.06c0-11.983-9.804-20.698-20.698-20.698h-90.417c-11.983,0-20.698,9.804-20.698,20.698v86.06H81.157 H50.655V41.396z M101.855,423.762h89.328v46.843h-89.328V423.762z M231.489,470.604v-153.6H281.6v153.6H231.489z M411.234,470.604h-88.238v-46.843h88.238V470.604z"/><path style="fill:currentColor;" d="M139.983,177.566c11.983,0,20.698-8.715,20.698-19.609V118.74 c0-11.983-8.715-20.698-20.698-20.698c-11.983,0-20.698,9.804-20.698,20.698v38.128 C119.285,168.851,129.089,177.566,139.983,177.566z"/><path style="fill:currentColor;" d="M256.545,177.566c10.894,0,20.698-8.715,20.698-19.609V118.74 c0-11.983-8.715-20.698-20.698-20.698s-20.698,9.804-20.698,20.698v38.128C235.847,168.851,245.651,177.566,256.545,177.566z"/><path style="fill:currentColor;" d="M373.106,177.566c10.894,0,20.698-8.715,20.698-19.609V118.74 c0-11.983-8.715-20.698-20.698-20.698c-11.983,0-20.698,9.804-20.698,20.698v38.128 C352.408,168.851,362.213,177.566,373.106,177.566z"/></g></g></g></svg>',
        'garden' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M9 5.25C7.03323 5.25 5.25 7.15209 5.25 9.75C5.25 12.0121 6.60204 13.7467 8.25001 14.1573V10.9014L6.33398 9.62405L7.16603 8.37597L8.792 9.45995L9.87597 7.83398L11.124 8.66603L9.75001 10.7271V14.1573C11.398 13.7467 12.75 12.0121 12.75 9.75C12.75 7.15209 10.9668 5.25 9 5.25ZM3.75 9.75C3.75 12.6785 5.62993 15.2704 8.25001 15.6906V19.5H3V21H21V19.5H18.75V18L18 17.25H12L11.25 18V19.5H9.75001V15.6906C12.3701 15.2704 14.25 12.6785 14.25 9.75C14.25 6.54892 12.0038 3.75 9 3.75C5.99621 3.75 3.75 6.54892 3.75 9.75ZM12.75 19.5H17.25V18.75H12.75V19.5Z" fill="currentColor"/></svg>',
        'sale' => '<svg fill="currentColor" height="16" width="16" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 406.48 406.48" xml:space="preserve"><g><g><path d="M100.672,287.798c-6.25,0-11.334-5.084-11.334-11.334c0-6.25,5.085-11.334,11.334-11.334h2.868 c4.668,0,8.466,3.798,8.466,8.466c0,4.142,3.358,7.5,7.5,7.5c4.142,0,7.5-3.358,7.5-7.5c0-12.939-10.527-23.466-23.466-23.466 h-2.868c-14.521,0-26.334,11.813-26.334,26.334s11.813,26.334,26.334,26.334c6.25,0,11.334,5.084,11.334,11.334 c0,6.25-5.084,11.333-11.334,11.333h-2.868c-4.668,0-8.466-3.797-8.466-8.465c0-4.142-3.358-7.5-7.5-7.5 c-4.142,0-7.5,3.358-7.5,7.5c0,12.939,10.527,23.465,23.466,23.465h2.868c14.521,0,26.334-11.813,26.334-26.333 S115.193,287.798,100.672,287.798z"/></g></g><g><g><path d="M260.22,314.988c-4.142,0-7.5,3.358-7.5,7.5v2.979h-22.667v-67.836c0-4.142-3.358-7.5-7.5-7.5c-4.142,0-7.5,3.358-7.5,7.5 v75.335c0,4.142,3.358,7.5,7.5,7.5h37.667c4.142,0,7.5-3.358,7.5-7.5v-10.479C267.72,318.345,264.362,314.988,260.22,314.988z"/></g></g><g><g><path d="M324.642,277.684c4.142,0,7.5-3.358,7.5-7.5v-12.554c0-4.142-3.358-7.5-7.5-7.5h-37.668c-4.142,0-7.5,3.358-7.5,7.5 v75.335c0,4.142,3.358,7.5,7.5,7.5h37.668c4.142,0,7.5-3.358,7.5-7.5v-10.479c0-4.142-3.358-7.5-7.5-7.5 c-4.142,0-7.5,3.358-7.5,7.5v2.979h-22.668v-22.667h13.749c4.142,0,7.5-3.358,7.5-7.5c0-4.142-3.358-7.5-7.5-7.5h-13.749v-22.668 h22.668v5.054C317.142,274.327,320.5,277.684,324.642,277.684z"/></g></g><g><g><path d="M169.03,250.618c-14.386,0-26.09,11.704-26.09,26.09v55.771c0,4.142,3.358,7.5,7.5,7.5c4.142,0,7.5-3.358,7.5-7.5v-17.607 h22.18v17.607c0,4.142,3.358,7.5,7.5,7.5c4.142,0,7.5-3.358,7.5-7.5v-55.771C195.12,262.322,183.416,250.618,169.03,250.618z M180.12,299.871h-22.18v-23.163c0-6.115,4.975-11.09,11.09-11.09s11.09,4.975,11.09,11.09V299.871z"/></g></g><g><g><path d="M360.811,194.053h-33.864L229.368,65.978c4.985-5.933,7.995-13.579,7.995-21.917c0-18.816-15.308-34.124-34.124-34.124 c-18.816,0-34.124,15.308-34.124,34.124c0,8.34,3.012,15.987,7.999,21.921L79.531,194.053H45.669 C20.487,194.053,0,214.54,0,239.722v111.153c0,25.182,20.487,45.669,45.669,45.669h315.142c25.182,0,45.669-20.487,45.669-45.669 V239.722C406.48,214.54,385.993,194.053,360.811,194.053z M203.24,24.938c10.545,0,19.124,8.579,19.124,19.124 c0,10.545-8.579,19.124-19.124,19.124c-10.545,0-19.124-8.579-19.124-19.124C184.116,33.517,192.695,24.938,203.24,24.938z M189.042,75.079c4.327,1.989,9.133,3.106,14.198,3.106c5.067,0,9.875-1.119,14.203-3.108l90.646,118.977h-209.7L189.042,75.079z M391.48,350.874c0,16.911-13.758,30.669-30.669,30.669H45.669C28.758,381.543,15,367.785,15,350.874V239.722 c0-16.911,13.758-30.669,30.669-30.669h315.142c16.911,0,30.669,13.758,30.669,30.669V350.874z"/></g></g></svg>',
        'rent' => '<svg fill="currentColor" height="18" width="18" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve"><g><g><path d="M211.772,239.087c3.797-2.022,7.023-4.548,9.651-7.578c4.702-5.376,7.074-11.776,7.074-18.987 c0-4.872-1.126-9.327-3.302-13.184c-2.15-3.789-5.103-6.946-8.798-9.438c-3.448-2.321-7.501-4.096-11.998-5.265 c-4.275-1.109-9.003-1.673-13.978-1.673h-27.725v94.865h24.149V245.24l26.402,32.589h30.575L211.772,239.087z M203.375,216.9 c-0.725,1.229-1.749,2.21-3.149,3.063c-1.553,0.947-3.499,1.698-5.803,2.21c-2.372,0.512-4.898,0.785-7.578,0.836v-18.133h3.447 c2.202,0,4.378,0.222,6.4,0.648c1.775,0.384,3.354,0.973,4.651,1.749c0.947,0.546,1.698,1.289,2.253,2.202 c0.503,0.828,0.751,1.937,0.751,3.302C204.348,214.494,204.023,215.825,203.375,216.9z"/></g></g><g><g><polygon points="270.899,255.787 270.899,240.486 303.795,240.486 303.795,218.453 270.899,218.453 270.899,205.005  305.271,205.005 305.271,182.963 246.741,182.963 246.741,277.828 305.647,277.828 305.647,255.787"/></g></g><g><g><polygon points="370.466,182.963 370.466,233.805 334.217,182.963 313.668,182.963 313.668,277.828 337.323,277.828  337.323,226.987 373.572,277.828 394.121,277.828 394.121,182.963"/></g></g><g><g><polygon points="401.041,182.963 401.041,205.005 425.199,205.005 425.199,277.828 449.348,277.828 449.348,205.005  473.498,205.005 473.498,182.963"/></g></g><g><g><path d="M512,64V38.4H64V0H38.4v38.4H0V64h38.4v422.4H12.8c-7.074,0-12.8,5.726-12.8,12.8c0,7.074,5.726,12.8,12.8,12.8h76.8 c7.074,0,12.8-5.726,12.8-12.8c0-7.074-5.726-12.8-12.8-12.8H64V64h89.591v38.4H128c-14.14,0-25.6,11.46-25.6,25.6v204.8 c0,14.14,11.46,25.6,25.6,25.6h358.4c14.14,0,25.6-11.46,25.6-25.6V128c0-14.14-11.46-25.6-25.6-25.6h-25.6V64H512z M179.191,64 H435.2v38.4H179.191V64z M486.4,128v204.8H128V128H486.4z"/></g></g></svg>',
    ];

    return $icons[$type] ?? '';
}

/**
 * Render property metadata item
 * 
 * @param string $type Type of metadata (bedroom, bathroom, construction, lot, parking)
 * @param mixed $value The metadata value
 * @param array $args Additional arguments (unit, class, etc.)
 * @return string HTML <li> element
 */
function stories_render_metadata_item($type, $value, $args = []) {
    // Defaults
    $defaults = [
        'unit' => '',
        'class' => '',
        'format' => true, // Whether to apply format_numeric()
    ];
    $args = wp_parse_args($args, $defaults);

    // Validate value
    if (empty($value) || $value == 0) {
        return '';
    }

    // Format value if needed
    if ($args['format'] && function_exists('format_numeric')) {
        $value = format_numeric($value);
    }

    // Get icon
    $icon = stories_get_metadata_icon($type);

    // Build class attribute
    $class = "class=\"{$args['class']}\"";

    // Build unit suffix
    $unit = !empty($args['unit']) ? " {$args['unit']}" : '';

    return "<li {$class}>{$icon}{$value}{$unit}</li>";
}

/**
 * Display property metadata with singular/plural support
 * 
 * Renders all available metadata for a property:
 * - Bedrooms
 * - Bathrooms
 * - Construction size
 * - Lot size
 * - Parking spaces
 * - Property type
 * - Property ID
 * 
 * @param int $post_id Post ID (defaults to current post)
 * @param array $args Additional options (show_id, show_type, show_construction_label, show_lot_label, etc.)
 */
function stories_display_property_metadata($post_id = null, $args = []) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    // Default options
    $defaults = [
        'show_id' => false,
        'show_type' => false,
        'show_construction_label' => false, // Show "m² de construcción" instead of just "m²"
        'show_lot_label' => false,          // Show "m² de terreno" instead of just "m²"
        'show_plural' => false,              // Show "recámara/recámaras", "baño/baños", etc.
    ];
    $args = wp_parse_args($args, $defaults);

    $metadata = [
        'bedrooms' => [
            'key' => 'eb_bedrooms',
            'type' => 'bedroom',
            'class' => 'bedroom',
            'unit' => '',
            'format' => false,
            'singular' => 'recámara',
            'plural' => 'recámaras',
        ],
        'bathrooms' => [
            'key' => 'eb_bathrooms',
            'type' => 'bathroom',
            'class' => '',
            'unit' => '',
            'format' => false,
            'singular' => 'baño',
            'plural' => 'baños',
        ],
        'construction' => [
            'key' => 'eb_construction_size',
            'type' => 'construction',
            'class' => '',
            'unit' => $args['show_construction_label'] ? 'm² de construcción' : 'm²',
            'format' => true,
        ],
        'lot' => [
            'key' => 'eb_lot_size',
            'type' => 'lot',
            'class' => 'lot',
            'unit' => $args['show_lot_label'] ? 'm² de terreno' : 'm²',
            'format' => true,
        ],
        'parking' => [
            'key' => 'eb_parking',
            'type' => 'parking',
            'class' => '',
            'unit' => '',
            'format' => false,
            'singular' => 'estacionamiento',
            'plural' => 'estacionamientos',
        ],
    ];

    $items = [];

    foreach ($metadata as $key => $meta) {
        $value = get_post_meta($post_id, $meta['key'], true);
        
        if (empty($value) || $value == 0) {
            continue;
        }

        // Format value if needed
        $display_value = $value;
        if ($meta['format'] && function_exists('format_numeric')) {
            $display_value = format_numeric($value);
        }

        // Add singular/plural suffix for bedroom, bathroom, parking
        if ($args['show_plural'] && isset($meta['singular'], $meta['plural'])) {
            $unit = ' ' . ($value < 2 ? $meta['singular'] : $meta['plural']);
        } else {
            $unit = !empty($meta['unit']) ? " {$meta['unit']}" : '';
        }

        // Get icon
        $icon = stories_get_metadata_icon($meta['type']);

        // Build class attribute
        $class = !empty($meta['class']) ? "class=\"{$meta['class']}\"" : '';

        $items[] = "<li {$class}>{$icon}{$display_value}{$unit}</li>";
    }

    if (empty($items)) {
        return;
    }

    echo '<div class="post--metadata">';
    echo '<ul class="metadata-list">';
    echo implode("\n", $items);
    echo '</ul>';
    echo '</div>';
}

/**
 * Get property metadata variables for single property template
 * 
 * @param int $post_id Property post ID
 * @return array Array with price, operation, location, gallery keys
 */
function stories_get_property_data($post_id = 0) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    // Try to get gallery from ACF first (new system)
    $gallery = [];
    
    if (function_exists('get_field')) {
        $acf_gallery = get_field('eb_gallery', $post_id);
        if (!empty($acf_gallery)) {
            // ACF gallery returns array of image objects/IDs
            foreach ($acf_gallery as $image) {
                if (is_array($image)) {
                    // If it's already an array with 'url', use it
                    if (isset($image['url'])) {
                        $gallery[] = $image;
                    } else {
                        // If it's an array with ID, get the URL
                        $image_id = $image['id'] ?? $image;
                        $image_url = wp_get_attachment_image_url($image_id, 'full');
                        if ($image_url) {
                            $gallery[] = ['url' => $image_url];
                        }
                    }
                } else {
                    // It's an image ID
                    $image_url = wp_get_attachment_image_url($image, 'full');
                    if ($image_url) {
                        $gallery[] = ['url' => $image_url];
                    }
                }
            }
        }
    }
    
    // Fallback to EasyBroker format if ACF gallery is empty
    if (empty($gallery)) {
        $eb_gallery = get_post_meta($post_id, 'eb_gallery', true);
        
        if (is_string($eb_gallery)) {
            $gallery = maybe_unserialize($eb_gallery);
        } elseif (is_array($eb_gallery)) {
            $gallery = $eb_gallery;
        }
    }

    return [
        'price'     => get_post_meta($post_id, 'eb_price', true),
        'operation' => get_post_meta($post_id, 'eb_operation', true),
        'location'  => get_post_meta($post_id, 'eb_location', true),
        'gallery'   => is_array($gallery) ? $gallery : [],
    ];
}

/**
 * Get all detailed metadata for property details section
 * 
 * @param int $post_id Property post ID
 * @return array Array with all metadata items (id, location, type, operation, price, bedrooms, bathrooms, parking, construction, lot)
 */
function stories_get_full_property_metadata($post_id = 0) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    return [
        'id'            => get_post_meta($post_id, 'eb_public_id', true),
        'location'      => get_post_meta($post_id, 'eb_location', true),
        'type'          => get_post_meta($post_id, 'eb_property_type', true),
        'operation'     => get_post_meta($post_id, 'eb_operation', true),
        'price'         => get_post_meta($post_id, 'eb_price', true),
        'bedrooms'      => get_post_meta($post_id, 'eb_bedrooms', true),
        'bathrooms'     => get_post_meta($post_id, 'eb_bathrooms', true),
        'parking'       => get_post_meta($post_id, 'eb_parking', true),
        'construction'  => get_post_meta($post_id, 'eb_construction_size', true),
        'lot'           => get_post_meta($post_id, 'eb_lot_size', true),
    ];
}

/**
 * Render full property metadata list for details section
 * 
 * @param int $post_id Property post ID
 * @return void Outputs HTML list items
 */
function stories_render_full_property_metadata($post_id = 0) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $metadata = stories_get_full_property_metadata($post_id);
    
    // ID
    echo '<li>';
    echo '<span>' . stories_get_metadata_icon('id') . '</span> ';
    echo 'ID: ' . esc_html($metadata['id']);
    echo '</li>';
    
    // Location
    echo '<li>';
    echo '<span>' . stories_get_metadata_icon('location') . '</span> ';
    echo esc_html($metadata['location']);
    echo '</li>';
    
    // Type
    if (!empty($metadata['type'])) {
        echo '<li>';
        echo '<span><svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20.5391 8.67606V15.5524C20.5512 15.8014 20.4327 16.0559 20.1845 16.196L13.0531 20.2197C12.4152 20.5797 11.6357 20.5807 10.9969 20.2223L3.82016 16.1968C3.5659 16.0542 3.44711 15.7917 3.46487 15.5374V8.69449C3.44687 8.44374 3.56156 8.18452 3.80996 8.0397L10.9664 3.86752C11.6207 3.48606 12.4299 3.4871 13.0832 3.87025L20.1945 8.04063C20.4357 8.18211 20.5503 8.43167 20.5391 8.67606Z" stroke="currentColor"/><path d="M3.82019 9.25312C3.3487 8.98865 3.34307 8.31197 3.81009 8.03969L10.9665 3.86751C11.6209 3.48605 12.43 3.48709 13.0834 3.87024L20.1946 8.04062C20.6596 8.31329 20.6539 8.98739 20.1845 9.25227L13.0531 13.276C12.4152 13.636 11.6357 13.637 10.9969 13.2786L3.82019 9.25312Z" stroke="currentColor"/></svg></span> ';
        echo 'Tipo: ' . esc_html($metadata['type']);
        echo '</li>';
    }
    
    // Operation
    if (!empty($metadata['operation'])) {
        echo '<li>';
        echo '<span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bag" viewBox="0 0 16 16"><path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1m3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1z"/></svg></span> ';
        echo ($metadata['operation'] === 'sale' ? 'En venta' : ($metadata['operation'] === 'rental' ? 'En renta' : esc_html($metadata['operation'])));
        echo '</li>';
    }
    
    // Price
    if (!empty($metadata['price'])) {
        echo '<li>';
        echo '<span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-coin" viewBox="0 0 16 16"><path d="M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518z"/><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/><path d="M8 13.5a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11m0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12"/></svg></span> ';
        echo 'Precio: ' . esc_html($metadata['price']);
        echo '</li>';
    }
    
    // Bedrooms
    if (!empty($metadata['bedrooms']) && $metadata['bedrooms'] != 0) {
        echo '<li class="bedroom">';
        echo '<span>' . stories_get_metadata_icon('bedroom') . '</span> ';
        echo esc_html($metadata['bedrooms']) . ' ' . ($metadata['bedrooms'] < 2 ? 'recámara' : 'recámaras');
        echo '</li>';
    }
    
    // Bathrooms
    if (!empty($metadata['bathrooms']) && $metadata['bathrooms'] != 0) {
        echo '<li>';
        echo '<span>' . stories_get_metadata_icon('bathroom') . '</span> ';
        echo esc_html($metadata['bathrooms']) . ' ' . ($metadata['bathrooms'] < 2 ? 'baño' : 'baños');
        echo '</li>';
    }
    
    // Parking
    if (!empty($metadata['parking']) && $metadata['parking'] != 0) {
        echo '<li class="parking">';
        echo '<span>' . stories_get_metadata_icon('parking') . '</span> ';
        echo esc_html($metadata['parking']) . ' ' . ($metadata['parking'] < 2 ? 'estacionamiento' : 'estacionamientos');
        echo '</li>';
    }
    
    // Construction size
    if (!empty($metadata['construction']) && $metadata['construction'] != 0) {
        echo '<li>';
        echo '<span>' . stories_get_metadata_icon('construction') . '</span> ';
        echo format_numeric($metadata['construction']) . ' m² de construcción';
        echo '</li>';
    }
    
    // Lot size
    if (!empty($metadata['lot']) && $metadata['lot'] != 0) {
        echo '<li class="lot">';
        echo '<span>' . stories_get_metadata_icon('lot') . '</span> ';
        echo format_numeric($metadata['lot']) . ' m² de terreno';
        echo '</li>';
    }
}

/****************************************************************************************************************
 * A C F   F I E L D S   F O R   P R O P E R T I E S
 ****************************************************************************************************************/

/**
 * ACF Fields Registration
 * 
 * Registers custom fields for the Property CPT using ACF (Advanced Custom Fields)
 * Allows manual property creation and editing without depending on EasyBroker sync
 * 
 * @package stories-V2
 * @since 1.0.0
 */

if (!function_exists('acf_add_local_field_group')) {
    return;
}

/**
 * Register ACF fields for Property CPT
 */
function stories_register_property_acf_fields() {
    acf_add_local_field_group([
        'key'                   => 'group_property_details',
        'title'                 => 'Detalles de la Propiedad',
        'fields'                => [
            [
                'key'           => 'field_property_id',
                'label'         => 'ID Público',
                'name'          => 'eb_public_id',
                'type'          => 'text',
                'instructions'  => 'Identificador único de la propiedad',
                'required'      => 0,
                'placeholder'   => 'P-12345',
            ],
            [
                'key'           => 'field_property_price',
                'label'         => 'Precio',
                'name'          => 'eb_price',
                'type'          => 'text',
                'instructions'  => 'Formato: $1,500,000 o 1,500,000',
                'required'      => 1,
                'placeholder'   => '$1,500,000',
            ],
            [
                'key'           => 'field_property_operation',
                'label'         => 'Tipo de Operación',
                'name'          => 'eb_operation',
                'type'          => 'select',
                'choices'       => [
                    'sale'      => 'En Venta',
                    'rental'    => 'En Renta',
                ],
                'required'      => 1,
            ],
            [
                'key'           => 'field_property_location',
                'label'         => 'Ubicación',
                'name'          => 'eb_location',
                'type'          => 'text',
                'instructions'  => 'Dirección completa de la propiedad',
                'required'      => 1,
                'placeholder'   => 'Calle Principal 123, Ciudad, Estado',
            ],
            [
                'key'           => 'field_property_type',
                'label'         => 'Tipo de Propiedad',
                'name'          => 'eb_property_type',
                'type'          => 'select',
                'choices'       => [
                    'house'     => 'Casa',
                    'apartment' => 'Departamento',
                    'land'      => 'Terreno',
                    'commercial' => 'Comercial',
                    'office'    => 'Oficina',
                    'other'     => 'Otro',
                ],
                'required'      => 1,
            ],
            [
                'key'           => 'field_property_bedrooms',
                'label'         => 'Recámaras',
                'name'          => 'eb_bedrooms',
                'type'          => 'number',
                'required'      => 0,
                'min'           => 0,
                'placeholder'   => '3',
            ],
            [
                'key'           => 'field_property_bathrooms',
                'label'         => 'Baños',
                'name'          => 'eb_bathrooms',
                'type'          => 'number',
                'required'      => 0,
                'min'           => 0,
                'placeholder'   => '2',
            ],
            [
                'key'           => 'field_property_parking',
                'label'         => 'Estacionamientos',
                'name'          => 'eb_parking',
                'type'          => 'number',
                'required'      => 0,
                'min'           => 0,
                'placeholder'   => '2',
            ],
            [
                'key'           => 'field_property_construction_size',
                'label'         => 'Tamaño de Construcción (m²)',
                'name'          => 'eb_construction_size',
                'type'          => 'number',
                'required'      => 0,
                'min'           => 0,
                'placeholder'   => '150',
            ],
            [
                'key'           => 'field_property_lot_size',
                'label'         => 'Tamaño de Terreno (m²)',
                'name'          => 'eb_lot_size',
                'type'          => 'number',
                'required'      => 0,
                'min'           => 0,
                'placeholder'   => '250',
            ],
            [
                'key'           => 'field_property_gallery',
                'label'         => 'Galería de Imágenes',
                'name'          => 'eb_gallery',
                'type'          => 'gallery',
                'instructions'  => 'Agrega imágenes a la galería de la propiedad',
                'return_format' => 'array',
            ],
        ],
        'location'              => [
            [
                [
                    'param'     => 'post_type',
                    'operator'  => '==',
                    'value'     => 'property',
                ],
            ],
        ],
        'menu_order'            => 0,
        'position'              => 'normal',
        'style'                 => 'default',
        'label_placement'       => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen'        => [],
    ]);
}

add_action('acf/init', 'stories_register_property_acf_fields');

/**
 * Convert ACF gallery array to EasyBroker format
 * 
 * Converts ACF's gallery field format to the serialized format used by EasyBroker
 * This ensures compatibility with existing template code and prevents duplicate gallery handling
 * 
 * @param int $post_id Post ID
 */
function stories_convert_acf_gallery_to_eb_format($post_id) {
    // Get ACF gallery field (array of image IDs or objects)
    $gallery = get_field('eb_gallery', $post_id);
    
    if (empty($gallery)) {
        return;
    }
    
    // Convert ACF gallery format to EasyBroker format (array of objects with 'url' key)
    $eb_gallery = [];
    foreach ($gallery as $image) {
        $image_url = '';
        
        if (is_array($image)) {
            // If it's already an array with 'url', use it
            if (isset($image['url'])) {
                $image_url = $image['url'];
            } else {
                // If it has 'id' key, get the URL
                $image_id = $image['id'] ?? $image;
                $image_url = wp_get_attachment_image_url($image_id, 'full');
            }
        } else {
            // It's an image ID - get the full URL
            $image_url = wp_get_attachment_image_url($image, 'full');
        }
        
        if ($image_url) {
            $eb_gallery[] = [
                'url' => $image_url,
            ];
        }
    }
    
    // Save in EasyBroker meta format for backwards compatibility
    if (!empty($eb_gallery)) {
        update_post_meta($post_id, 'eb_gallery', $eb_gallery);
    }
}

add_action('acf/save_post', 'stories_convert_acf_gallery_to_eb_format', 20);
