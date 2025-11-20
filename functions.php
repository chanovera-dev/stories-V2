<?php
/**
 * Theme Core Functions and Setup
 *
 * This file acts as the main "engine room" for the stories theme.
 * It handles:
 *  - Defining constants like the theme version.
 *  - Security measures to prevent direct access.
 *  - Loading optional theme components from the /inc directory (core, extended features, template helpers).
 *
 * Each included file is loaded conditionally to ensure the theme only loads
 * what exists, keeping the code modular and maintainable.
 *
 * @package stories
 * @since 2.0.0
 */

// Prevent direct access to this file for security reasons.
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Theme version constant (safe: only define if not already defined).
 */
$theme = wp_get_theme();
$version = $theme && method_exists( $theme, 'get' ) ? $theme->get( 'Version' ) : '2.0.0';

if ( ! defined( 'STORIES_VERSION' ) ) {
    define( 'STORIES_VERSION', (string) $version );
}

/**
 * Load optional theme components from the /inc directory.
 * Note: files are included only if they exist.
 */
$inc_files = array(
    'core'           => 'inc/core.php',
    'extended'       => 'inc/extended.php',
    'custom-widgets' => 'inc/custom-widgets.php',
    // 'custom-blocks'  => 'inc/custom-blocks.php', se ha de activar cuando se mejore el nuevo script de post-gallery
    'templates'      => 'inc/templates.php',
    'customizer'     => 'inc/customizer.php',
);

foreach ( $inc_files as $key => $relative_path ) {
    $path = __DIR__ . '/' . $relative_path;
    if ( file_exists( $path ) ) {
        require_once $path;
    }
}