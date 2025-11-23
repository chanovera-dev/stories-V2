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
});

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