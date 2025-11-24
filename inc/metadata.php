<?php
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
        'bedroom' => '<svg width="19" height="19" viewBox="0 0 16 16" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linecap:round;stroke-linejoin:round;"><path d="M13,9.5l-10,0l0,-3.25c0.002,-0.685 0.565,-1.248 1.25,-1.25l7.5,0c0.685,0.002 1.248,0.565 1.25,1.25l0,3.25Zm-11.5,5.5l0,-3.5c0.003,-1.096 0.904,-1.997 2,-2l9,0c1.096,0.003 1.997,0.904 2,2l0,3.5" style="fill:none;fill-rule:nonzero;stroke:currentColor;stroke-width:.8px;"/><path d="M1.5,15l0,-0.25c0.001,-0.411 0.339,-0.749 0.75,-0.75l11.5,0c0.411,0.001 0.749,0.339 0.75,0.75l0,0.25m-11,-5.5l0,-0.5c0.002,-0.548 0.452,-0.998 1,-1l2.5,0c0.548,0.002 0.998,0.452 1,1l0,0.5m0,0l0,-0.5c0.002,-0.548 0.452,-0.998 1,-1l2.5,0c0.548,0.002 0.998,0.452 1,1l0,0.5" style="fill:none;fill-rule:nonzero;stroke:currentColor;stroke-width:.8px;"/></svg>',
        'bathroom' => '<svg fill="currentColor" width="16" height="16" viewBox="0 0 512 512" id="Layer_1" enable-background="new 0 0 512 512" xmlns="http://www.w3.org/2000/svg"><g><path d="m496 288c-38.154 0-437.487 0-448 0v-56h32c8.837 0 16-7.164 16-16v-40c0-8.836-7.163-16-16-16s-16 7.164-16 16v24h-16v-138.745c0-25.903 31.562-39.064 49.941-20.686l16.94 16.94c-13.424 23.401-10.164 53.835 9.805 73.805l8 8c6.247 6.248 16.379 6.249 22.627 0l64-64c6.249-6.248 6.249-16.379 0-22.627l-8-8c-20.35-20.351-50.837-23.06-73.817-9.817l-16.928-16.928c-11.57-11.57-26.952-17.942-43.313-17.942-33.776 0-61.255 27.479-61.255 61.255v226.745c-8.837 0-16 7.164-16 16s7.163 16 16 16v32c0 43.889 19.742 83.247 50.806 109.681l-22.338 23.229c-9.803 10.193-2.445 27.09 11.53 27.09 4.199 0 8.394-1.644 11.534-4.91l26.218-27.263c19.844 10.326 42.376 16.173 66.25 16.173h192c23.874 0 46.406-5.847 66.25-16.173l26.218 27.263c6.106 6.35 16.234 6.585 22.623.442 6.369-6.125 6.566-16.254.441-22.623l-22.338-23.229c31.064-26.433 50.806-65.791 50.806-109.68v-32c8.837 0 16-7.164 16-16s-7.163-16-16-16zm-310.89-223.738-40.845 40.845c-8.246-11.427-7.23-27.515 3.048-37.794 10.378-10.377 26.461-11.259 37.797-3.051zm278.89 287.738c0 61.757-50.243 112-112 112h-192c-61.757 0-112-50.243-112-112v-32h416z"/></g></svg>',
        'construction' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-buildings" viewBox="0 0 16 16"><path d="M14.763.075A.5.5 0 0 1 15 .5v15a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5V14h-1v1.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V10a.5.5 0 0 1 .342-.474L6 7.64V4.5a.5.5 0 0 1 .276-.447l8-4a.5.5 0 0 1 .487.022M6 8.694 1 10.36V15h5zM7 15h2v-1.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5V15h2V1.309l-7 3.5z"/><path d="M2 11h1v1H2zm2 0h1v1H4zm-2 2h1v1H2zm2 0h1v1H4zm4-4h1v1H8zm2 0h1v1h-1zm-2 2h1v1H8zm2 0h1v1h-1zm2-2h1v1h-1zm0 2h1v1h-1zM8 7h1v1H8zm2 0h1v1h-1zm2 0h1v1h-1zM8 5h1v1H8zm2 0h1v1h-1zm2 0h1v1h-1zm0-2h1v1h-1z"/></svg>',
        'lot' => '<svg width="20" height="20" fill="currentcolor" viewBox="0 0 16 16" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;"><path d="M6.667,10.333l6.333,0c0.92,0 1.667,0.746 1.667,1.667l0,2c0,0.92 -0.746,1.667 -1.667,1.667l-10,0c-0.92,0 -1.667,-0.746 -1.667,-1.667l0,-10c0,-0.92 0.746,-1.667 1.667,-1.667l2,0c0.92,0 1.667,0.746 1.667,1.667l0,6.333Zm-0.724,4l-2.276,0c-0.184,0 -0.333,-0.149 -0.333,-0.333c0,-0.184 0.149,-0.333 0.333,-0.333l2.333,0l0,-1.333l-1.667,0c-0.184,0 -0.333,-0.149 -0.333,-0.333c0,-0.184 0.149,-0.333 0.333,-0.333l1.667,0l0,-1.333l-2.333,0c-0.184,0 -0.333,-0.149 -0.333,-0.333c0,-0.184 0.149,-0.333 0.333,-0.333l2.333,0l0,-1.333l-1.667,0c-0.184,0 -0.333,-0.149 -0.333,-0.333c0,-0.184 0.149,-0.333 0.333,-0.333l1.667,0l0,-1.333l-2.333,0c-0.184,0 -0.333,-0.149 -0.333,-0.333c0,-0.184 0.149,-0.333 0.333,-0.333l2.333,0l0,-1.333l-1.667,0c-0.184,0 -0.333,-0.149 -0.333,-0.333c0,-0.184 0.149,-0.333 0.333,-0.333l1.61,0c-0.137,-0.388 -0.508,-0.667 -0.943,-0.667l-2,0c-0.552,0 -1,0.448 -1,1l0,10c0,0.552 0.448,1 1,1l2,0c0.435,0 0.806,-0.278 0.943,-0.667Zm0.724,-3.333l0,3c0,0.375 -0.124,0.721 -0.333,1l6.667,0c0.552,0 1,-0.448 1,-1l0,-2c0,-0.552 -0.448,-1 -1,-1l-6.333,0Zm4.667,0.667l1.333,0c0.368,0 0.667,0.298 0.667,0.667l0,1.333c0,0.368 -0.298,0.667 -0.667,0.667l-1.333,0c-0.368,0 -0.667,-0.298 -0.667,-0.667l0,-1.333c0,-0.368 0.298,-0.667 0.667,-0.667Zm0,0.667l0,1.333l1.333,0l0,-1.333l-1.333,0Z" style="fill-rule:nonzero;"/></svg>',
        'parking' => '<svg width="20" height="20" viewBox="0 0 16 16" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linecap:round;stroke-linejoin:round;"><path d="M14.678,10.331c-0.229,-0.304 -1.08,-0.513 -1.44,-1.061c-0.36,-0.547 -0.655,-1.732 -1.571,-2.187c-0.916,-0.455 -2.668,-0.583 -3.668,-0.583c-1,0 -2.75,0.125 -3.668,0.582c-0.918,0.457 -1.211,1.641 -1.571,2.188c-0.36,0.546 -1.211,0.758 -1.44,1.062c-0.229,0.304 -0.39,2.226 -0.292,3.169c0.098,0.943 0.281,1.5 0.281,1.5l2.688,0c0.44,0 0.583,-0.165 1.483,-0.25c0.987,-0.094 1.956,-0.125 2.519,-0.125c0.562,0 1.562,0.031 2.549,0.125c0.9,0.085 1.048,0.25 1.483,0.25l2.656,0c0,0 0.183,-0.557 0.281,-1.5c0.098,-0.943 -0.064,-2.865 -0.292,-3.169Zm-2.178,4.669l1.75,0l0,0.5l-1.75,0l0,-0.5Zm-10.75,0l1.75,0l0,0.5l-1.75,0l0,-0.5Z" style="fill:none;fill-rule:nonzero;stroke:currentColor;stroke-width:1px;"/><path d="M11.39,12.661c-0.185,-0.213 -0.787,-0.392 -1.583,-0.511c-0.797,-0.119 -1.088,-0.15 -1.8,-0.15c-0.713,0 -1.037,0.051 -1.8,0.15c-0.764,0.099 -1.337,0.275 -1.583,0.511c-0.369,0.357 0.172,0.759 0.596,0.807c0.411,0.047 1.233,0.03 2.791,0.03c1.557,0 2.38,0.017 2.791,-0.03c0.424,-0.052 0.926,-0.425 0.589,-0.807Zm2.097,-2.066c-0.004,-0.051 -0.046,-0.092 -0.097,-0.094c-0.369,-0.013 -0.744,0.013 -1.408,0.209c-0.339,0.099 -0.658,0.258 -0.94,0.471c-0.071,0.056 -0.046,0.206 0.043,0.222c0.548,0.064 1.099,0.097 1.651,0.097c0.331,0 0.672,-0.094 0.736,-0.389c0.032,-0.17 0.038,-0.344 0.015,-0.516Zm-10.973,-0c0.004,-0.051 0.046,-0.092 0.097,-0.094c0.369,-0.013 0.744,0.013 1.408,0.209c0.339,0.099 0.658,0.258 0.94,0.471c0.071,0.056 0.046,0.206 -0.043,0.222c-0.548,0.064 -1.099,0.097 -1.651,0.097c-0.331,0 -0.672,-0.094 -0.736,-0.389c-0.032,-0.17 -0.038,-0.344 -0.015,-0.516Z" style="fill-rule:nonzero;"/><path d="M13.5,9l0.5,0m-12,0l0.5,0m-0.062,0.594c0,0 1.448,-0.375 5.562,-0.375c4.114,0 5.562,0.375 5.562,0.375" style="fill:none;fill-rule:nonzero;stroke:currentColor;stroke-width:1px;"/></svg>',
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
 * Display property metadata
 * 
 * Renders all available metadata for a property:
 * - Bedrooms
 * - Bathrooms
 * - Construction size
 * - Lot size
 * - Parking spaces
 * 
 * @param int $post_id Post ID (defaults to current post)
 */
function stories_display_property_metadata($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $metadata = [
        'bedrooms' => [
            'key' => 'eb_bedrooms',
            'type' => 'bedroom',
            'class' => 'bedroom',
            'unit' => '',
            'format' => false,
        ],
        'bathrooms' => [
            'key' => 'eb_bathrooms',
            'type' => 'bathroom',
            'class' => '',
            'unit' => '',
            'format' => false,
        ],
        'construction' => [
            'key' => 'eb_construction_size',
            'type' => 'construction',
            'class' => '',
            'unit' => 'm²',
            'format' => true,
        ],
        'lot' => [
            'key' => 'eb_lot_size',
            'type' => 'lot',
            'class' => 'lot',
            'unit' => 'm²',
            'format' => true,
        ],
        'parking' => [
            'key' => 'eb_parking',
            'type' => 'parking',
            'class' => '',
            'unit' => '',
            'format' => false,
        ],
    ];

    $items = [];

    foreach ($metadata as $meta) {
        $value = get_post_meta($post_id, $meta['key'], true);
        
        $item = stories_render_metadata_item(
            $meta['type'],
            $value,
            [
                'unit' => $meta['unit'],
                'class' => $meta['class'],
                'format' => $meta['format'],
            ]
        );

        if ($item) {
            $items[] = $item;
        }
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
