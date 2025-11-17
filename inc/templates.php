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
            'breadcrumbs'        => "$assets_path/css/breadcrumbs.css",
            'posts'              => "$assets_path/css/posts.css",
            'pagination'         => "$assets_path/css/pagination.css",
            'page-thumbnail'     => "$assets_path/css/page-thumbnail.css",
            'page'               => "$assets_path/css/page.css",
            'single'             => "$assets_path/css/single.css",
            'comments'           => "$assets_path/css/comments.css",
            'error404'           => "$assets_path/css/error404.css",
            'slideshow-styles'   => "$assets_path/css/slideshow.css",
        ],
        'js' => [
            'slideshow-script'   => "$assets_path/js/slideshow.js",
            'gallery'            => "$assets_path/js/gallery.js",
            'parallax-hero'      => "$assets_path/js/parallax-hero.js",
            'animate-in'         => "$assets_path/js/animate-in.js",
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

        if ( is_single() ) {
            stories_enqueue_style( 'single', $a['css']['single'] );
            stories_enqueue_style( 'slideshow-styles', $a['css']['slideshow-styles'] );
            stories_enqueue_script( 'slideshow-script', $a['js']['slideshow-script'] );
            stories_enqueue_script( 'animate-in', $a['js']['animate-in'] );
            require_once get_template_directory() . '/templates/helpers/extract-gallery-images.php';
            stories_enqueue_script( 'gallery', $a['js']['gallery'] );

            // global $wp_query;

            // $has_gallery = false;

            // foreach ( $wp_query->posts as $post ) {
            //     if ( has_block( 'core/gallery', $post ) || has_shortcode( $post->post_content, 'gallery' ) ) {
            //         $has_gallery = true;
            //         break;
            //     }
            // }
            
            // if ( $has_gallery ) {
            //     require_once get_template_directory() . '/templates/helpers/extract-gallery-images.php';
            //     stories_enqueue_script( 'gallery', $a['js']['gallery'] );
            // }

            // $related_posts = get_posts( [
            //     'post__not_in' => [ $post_id ],
            //     'posts_per_page' => 1,
            //     'category__in' => wp_get_post_categories( $post_id ),
            //     'tag__in' => wp_get_post_tags( $post_id, [ 'fields' => 'ids' ] ),
            // ] );

            // if ( ! empty( $related_posts ) ) {
            //     stories_enqueue_style( 'slideshow', $a['css']['slideshow'] );
            //     stories_enqueue_script( 'slideshow-script', $a['js']['slideshow-script'] );
            // }

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
            stories_enqueue_script( 'gallery', $a['js']['gallery'] );
        }

        stories_enqueue_style( 'breadcrumbs', $a['css']['breadcrumbs'] );
        stories_enqueue_style( 'posts', $a['css']['posts'] );
        stories_enqueue_style( 'pagination', $a['css']['pagination'] );
        stories_enqueue_script( 'animate-in', $a['js']['animate-in'] );
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