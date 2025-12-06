<?php
/**
 * Theme Templates and Asset Loader
 *
 * Handles enqueueing of CSS and JS assets for different page templates and conditions.
 * 
 * This file provides helper functions to enqueue styles and scripts with automatic
 * versioning, and selectively loads assets for:
 * - Single posts and pages (including featured images, related posts, and comments)
 * - Post listing pages (home, archives, search)
 * - 404 error page
 * - Front page
 *
 * The goal is to optimize performance by loading only the necessary assets
 * for each page type, reducing unnecessary HTTP requests.
 *
 * @package stories
 * @since 2.0.0
 */

/**
 * Helper: Enqueue style file with automatic versioning
 *
 * @param string $handle
 * @param string $path
 * @param string $media
 * @return void
 */
function stories_enqueue_style( $handle, $path, $media = 'all' ) {
    $uri = get_template_directory_uri();
    wp_enqueue_style( $handle, $uri . $path, [], get_asset_version( $path ), $media );
}

/**
 * Helper: Enqueue script file with automatic versioning
 *
 * @param string $handle
 * @param string $path
 * @return void
 */
function stories_enqueue_script( $handle, $path ) {
    $uri = get_template_directory_uri();
    wp_enqueue_script( $handle, $uri . $path, [], get_asset_version( $path ), true );
}

/**
 * Returns a centralized registry of all theme asset paths.
 *
 * Provides a single source of truth for referencing CSS and JS files used
 * throughout the theme. This ensures consistency, reduces repetition across
 * enqueue functions, and simplifies maintenance when updating asset locations.
 *
 * The returned array is organized into 'css' and 'js' subarrays, where each key
 * corresponds to an asset handle and each value is its relative path from the
 * theme directory.
 *
 * Example:
 * $assets = stories_get_assets();
 * wp_enqueue_style( 'frontpage', get_template_directory_uri() . $assets['css']['frontpage'] );
 *
 * @since 2.0.0
 * @package stories
 * @return array Associative array of asset paths grouped by type ('css' and 'js').
 */
function stories_get_assets() {
    $assets_path = '/assets';

    return [
        'css' => [
            'breadcrumbs'         => "$assets_path/css/breadcrumbs.css",
            'posts'               => "$assets_path/css/posts.css",
            'pagination'          => "$assets_path/css/pagination.css",
            'page-thumbnail'      => "$assets_path/css/page-thumbnail.css",
            'page'                => "$assets_path/css/page.css",
            'single'              => "$assets_path/css/single.css",
            'comments'            => "$assets_path/css/comments.css",
            'error404'            => "$assets_path/css/error404.css",
            'slideshow-styles'    => "$assets_path/css/slideshow.css",
            'sidebar'             => "$assets_path/css/sidebar.css",
            'post-gallery-styles' => "$assets_path/css/post-gallery.css",

            // REAL ESTATE
            'single-property'         => "$assets_path/css/single-property.css",
            
            // Frontpage
            'frontpage'               => "$assets_path/css/frontpage/frontpage.css",
            'hero'                    => "$assets_path/css/frontpage/hero.css",
            'why-choose-us'           => "$assets_path/css/frontpage/wcu.css",
        ],
        'js' => [
            'slideshow-script'    => "$assets_path/js/slideshow.js",
            'loop-gallery'        => "$assets_path/js/loop-gallery.js",
            'post-gallery-script' => "$assets_path/js/post-gallery.js",
            'parallax-hero'       => "$assets_path/js/parallax-hero.js",
            'animate-in'          => "$assets_path/js/animate-in.js",
            'posts-scripts'       => "$assets_path/js/posts.js",
            'post-scripts'        => "$assets_path/js/post.js",
            'blur-typing'             => "$assets_path/js/blur-typing.js",

            // REAL ESTATE
            'frontpage'               => "$assets_path/js/frontpage.js",
            'filters'                 => "$assets_path/js/filters.js",
            'filter-listeners'        => "$assets_path/js/filter-listeners.js",
            'reset-properties-filter' => "$assets_path/js/reset-properties-filter.js",
            'ajax-properties'         => "$assets_path/js/ajax-properties.js",
            'ajax-search'             => "$assets_path/js/ajax-search-properties.js",
        ]
    ];
}

/**
 * Enqueues styles and scripts for single posts and pages.
 *
 * Loads page-specific assets when viewing single posts or pages.
 * Includes optional styles for featured images, related posts,
 * and comments, as well as JS effects such as parallax and blur typing.
 * Related posts and comment styles are loaded conditionally.
 *
 * @since 2.0.0
 * @return void
 */
function page_template() {
    $assets_path = '/assets';

    if ( is_page() or is_single() ) {
        $a = stories_get_assets();

        stories_enqueue_style( 'page', $a['css']['page'] );
        stories_enqueue_style( 'breadcrumbs', $a['css']['breadcrumbs'] );

        $post_id = get_queried_object_id();
        if ( $post_id && has_post_thumbnail( $post_id ) ) {
            stories_enqueue_style( 'page-thumbnail', $a['css']['page-thumbnail'] );
            stories_enqueue_script( 'parallax-hero', $a['js']['parallax-hero'] );
        }

        $post = get_post( $post_id );
        if ( $post && ( has_block( 'core/gallery', $post ) || has_shortcode( $post->post_content, 'gallery' ) ) ) {
            stories_enqueue_style( 'post-gallery-styles', $a['css']['post-gallery-styles'] );
            stories_enqueue_script( 'post-gallery-script', $a['js']['post-gallery-script'] );
        }

        if ( is_single() ) {
            stories_enqueue_style( 'single', $a['css']['single'] );
            stories_enqueue_style( 'slideshow-styles', $a['css']['slideshow-styles'] );
            stories_enqueue_script( 'slideshow-script', $a['js']['slideshow-script'] );
            stories_enqueue_script( 'animate-in', $a['js']['animate-in'] );
            stories_enqueue_script( 'post-scripts', $a['js']['post-scripts'] );

            if ( comments_open() ) {
                stories_enqueue_style( 'custom-comments', $a['css']['comments'] );
            }
        }
    }
}
add_action( 'wp_enqueue_scripts', 'page_template' );

/**
 * Enqueues styles and scripts for post listings pages.
 *
 * Loads specific CSS and JS assets for the blog home, archives, 
 * and search results pages. Includes pagination styles only 
 * when pagination links are present.
 *
 * @since 2.0.0
 * @return void
 */
function posts_styles() {
    if ( is_home() or is_archive() or is_search() ) {
        $a = stories_get_assets();

        global $wp_query;

        $has_gallery = false;

        foreach ( $wp_query->posts as $post ) {
            if ( has_block( 'core/gallery', $post ) || has_shortcode( $post->post_content, 'gallery' ) ) {
                $has_gallery = true;
                break;
            }
        }
        
        if ( $has_gallery ) {
            require_once get_template_directory() . '/templates/helpers/extract-gallery-images.php';
            stories_enqueue_script( 'loop-gallery', $a['js']['loop-gallery'] );
        }

        stories_enqueue_style( 'breadcrumbs', $a['css']['breadcrumbs'] );
        stories_enqueue_style( 'posts', $a['css']['posts'] );
        stories_enqueue_style( 'pagination', $a['css']['pagination'] );
        stories_enqueue_script( 'animate-in', $a['js']['animate-in'] );
        stories_enqueue_script( 'posts-scripts', $a['js']['posts-scripts'] );

        if ( is_active_sidebar( 'sidebar-1' ) ) {
            stories_enqueue_style( 'sidebar', $a['css']['sidebar'] );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'posts_styles' );

/**
 * Enqueues styles specifically for 404 error page
 * 
 * Loads custom CSS file only when viewing 404 page
 * to optimize performance and reduce unnecessary loading
 *
 * @since 2.0.0
 * @return void
 */
function page404_styles() {
    if ( is_404() ) {
        $a = stories_get_assets();
        stories_enqueue_style( 'error404', $a['css']['error404'] );
    }
}
add_action( 'wp_enqueue_scripts', 'page404_styles' );

/**
 * Enqueues specific styles and scripts for property-related templates.
 *
 * This function loads custom CSS and JavaScript files for:
 * - Property archive pages (archive-property.php), including filters,
 *   pagination, and AJAX-powered property loading.
 * - Single property pages (single-property.php), including gallery,
 *   related property slideshow, and parallax effects.
 *
 * It uses custom enqueue helpers (stories_enqueue_style/script)
 * and localizes the AJAX script with the admin-ajax URL.
 *
 * @since 1.0.0
 * @package stories
 */
function properties_templates() {
    if ( is_page_template( 'archive-property.php' ) ) {
        $a  = stories_get_assets();

        stories_enqueue_style( 'breadcrumbs', $a['css']['breadcrumbs'] );
        stories_enqueue_style( 'sidebar', $a['css']['sidebar'] );
        stories_enqueue_style( 'posts', $a['css']['posts'] );

        stories_enqueue_script( 'animate-in', $a['js']['animate-in'] );
        stories_enqueue_script( 'loop-gallery', $a['js']['loop-gallery'] );
        stories_enqueue_style( 'pagination', $a['css']['pagination'] );
        stories_enqueue_script( 'filters', $a['js']['filters'] );
        stories_enqueue_script( 'filter-listeners', $a['js']['filter-listeners'] );
        stories_enqueue_script( 'reset', $a['js']['reset-properties-filter'] );
        stories_enqueue_script( 'ajax-search-from-other-page', $a['js']['ajax-search'] );
        stories_enqueue_script( 'ajax-properties', $a['js']['ajax-properties'] );

        wp_localize_script('ajax-properties', 'ajax_object', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('filter_properties_nonce')
        ]);
    }

    if ( is_singular( 'property' ) ) {
        $a  = stories_get_assets();

        function unload_parts_header() {
            wp_dequeue_style( 'page' );
        }
        add_action( 'wp_enqueue_scripts', 'unload_parts_header', 100 );

        stories_enqueue_style( 'breadcrumbs', $a['css']['breadcrumbs'] );
        stories_enqueue_style( 'post-gallery-styles', $a['css']['post-gallery-styles'] );
        stories_enqueue_style( 'single-property', $a['css']['single-property'] );
        stories_enqueue_style( 'slideshow-styles', $a['css']['slideshow-styles'] );
            
        stories_enqueue_script( 'loop-gallery', $a['js']['loop-gallery'] );
        stories_enqueue_script( 'post-gallery-script', $a['js']['post-gallery-script'] );
        stories_enqueue_script( 'slideshow-script', $a['js']['slideshow-script'] );
    }
}
add_action( 'wp_enqueue_scripts', 'properties_templates' );

/**
 * Frontpage template styles
 * 
 * Loads custom CSS file only when viewing the front page
 * to optimize performance and reduce unnecessary loading
 *
 * @since 1.0.0
 * @return void
 */
function frontpage_template() {
    if ( is_page_template( 'templates/frontpage.php' ) ) {
        $a = stories_get_assets();

        wp_dequeue_style( 'page' );
        wp_dequeue_style( 'wp-block-library' );
        wp_dequeue_script( 'property-filter' );
        wp_dequeue_script( 'reset' );

        stories_enqueue_style( 'frontpage', $a['css']['frontpage'] );
        stories_enqueue_style( 'hero', $a['css']['hero'] );
        stories_enqueue_style( 'why-choose-us', $a['css']['why-choose-us'] );

        stories_enqueue_script( 'blur-typing', $a['js']['blur-typing'] );
        stories_enqueue_script( 'animate-in', $a['js']['animate-in'] );
        stories_enqueue_script( 'frontpage', $a['js']['frontpage'] );
        stories_enqueue_script( 'ajax-search-from-other-page', $a['js']['ajax-search'] );

        wp_enqueue_script('ajax-properties', get_template_directory_uri() . '/assets/js/ajax-properties.js', ['jquery'], null, true);
        wp_localize_script('ajax-properties', 'ajax_object', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('filter_properties_nonce')
        ]);
    }
}
add_action( 'wp_enqueue_scripts', 'frontpage_template' );