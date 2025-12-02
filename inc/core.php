<?php
/**
 * Core Theme Setup and Asset Loading
 *
 * Handles theme initialization, feature registration, and asset management:
 * - Navigation menus registration
 * - WordPress core features support
 * - Asset loading with cache busting
 *
 * Sets up theme defaults and registers support for various WordPress features
 * 
 * @package stories
 * @since 2.0.0
 */

function setup_stories() {

    /**
     * Register menus
     */
    register_nav_menus(
        array(
            'primary' => __( 'Main Menu', 'stories' ),
            'social'  => __( 'Social Menu', 'stories' ),
            'footer-1'   => __( 'Footer 1', 'stories' ),
            'footer-2'   => __( 'Footer 2', 'stories' ),
            'footer-3'   => __( 'Footer 3', 'stories' ),
        )
    );

    /**
     * Add theme support
     */
    add_theme_support( 'title-tag' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'custom-logo',
        array(
            'height'      => 32,
            'width'       => 172,
            'flex-height' => true,
            'flex-width'  => true,
        )
    );

    add_theme_support( 'html5',
        apply_filters(
            'chanovera_html5_args',
            array(
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
                'widgets',
                'style',
                'script',
            )
        )
    );

    add_theme_support( 'post-formats',
        array(
            'aside',
            'image',
            'video',
            'quote',
            'link',
            'gallery',
            'audio',
            'chat',
        )
    );

    add_theme_support( 'customize-selective-refresh-widgets' );

    add_theme_support( 'post-thumbnails', [ 'post', 'page', 'boletin' ] );
    set_post_thumbnail_size( 350, 200, true );

    add_image_size( 'post-header-thumbnail', 400, 400, true );

    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'align-wide' );
    
}
add_action( 'after_setup_theme', 'setup_stories' );

/**
 * Get an asset version for cache busting.
 *
 * Returns the file modification timestamp for the given asset (relative to the
 * theme root) so it can be used as a version string when enqueueing styles or
 * scripts. If the file does not exist the current time is returned to force
 * a cache refresh.
 *
 * Example: wp_enqueue_style( 'theme-style', get_stylesheet_uri(), array(),
 *     get_asset_version( '/style.css' ) );
 *
 * @param string $file_path Path to the asset file relative to the theme root (e.g. '/style.css').
 * @return int Unix timestamp to use as a cache-busting version.
 */
function get_asset_version( $file_path ) {
    $full_path = get_template_directory() . $file_path;

    return file_exists( $full_path ) ? filemtime( $full_path ) : time();
}

/**
 * Enqueues header styles with cache busting
 * 
 * Loads global stylesheet and form-specific styles in the header
 * with automatic versioning based on file modification time.
 */
function load_parts_header() {
    
    wp_register_style( 'global', get_template_directory_uri() . '/style.css', array(), get_asset_version('/style.css'), 'all' ); 
    wp_enqueue_style( 'global' );
     
}
add_action( 'wp_enqueue_scripts', 'load_parts_header' );

/**
 * Enqueues theme assets conditionally.
 *
 * Loads:
 * - WP root styles for all users (assets/css/wp-root.css)
 * - Form styles (assets/css/forms.css)
 * - Global JavaScript (assets/js/global.js) deferred to the footer
 * - Animate-in JavaScript (assets/js/animate-in.js) deferred to the footer
 * - Admin styles for logged-in users (assets/css/wp-logged-in.css)
 */
function footer_components() {
    $directory = get_template_directory();
    $uri = get_template_directory_uri();
        $assets_path = '/assets';

    $enqueue_style = function ( $handle, $path, $media = 'all' ) use ( $uri ) {
        wp_enqueue_style($handle, $uri . $path, [], get_asset_version( $path ), $media);
    };
    $enqueue_script = function ( $handle, $path ) use ( $uri ) {
        wp_enqueue_script($handle, $uri . $path, [], get_asset_version( $path ), true);
    };

    file_exists( $directory . "$assets_path/css/wp-root.css" ) ? $enqueue_style( 'wp-root', "$assets_path/css/wp-root.css" ) : null;
    file_exists( $directory . "$assets_path/css/forms.css" ) ? $enqueue_style( 'custom-forms', "$assets_path/css/forms.css" ) : null;
    file_exists( $directory . "$assets_path/css/shapes.css" ) ? $enqueue_style( 'shapes', "$assets_path/css/shapes.css" ) : null;
    file_exists( $directory . "$assets_path/js/global.js" ) ? $enqueue_script( 'global-scripts', "$assets_path/js/global.js" ) : null;
     
    if ( is_user_logged_in() ) {
        file_exists( $directory . "$assets_path/css/wp-logged-in.css" ) ? $enqueue_style( 'wp-logged-in', "$assets_path/css/wp-logged-in.css" ) : null;
    }
}
add_action( 'wp_enqueue_scripts', 'footer_components' );

/**
 * Register widgets areas
 */
function widgets_areas() {

    register_sidebar(
        array(
            'name'          => __( 'Posts sidebar', 'stories' ),
            'id'            => 'sidebar-1',
            'before_widget' => '',
            'after_widget'  => '',
        )
    );

    register_sidebar(
        array(
            'name'          => __( 'Post sidebar', 'stories' ),
            'id'            => 'sidebar-2',
            'before_widget' => '',
            'after_widget'  => '',
        )
    );

    register_sidebar(
        array(
            'name'          => __( 'Page sidebar', 'stories' ),
            'id'            => 'sidebar-3',
            'before_widget' => '',
            'after_widget'  => '',
        )
    );

}
add_action( 'widgets_init', 'widgets_areas' );