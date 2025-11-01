<?php
/**
 * Theme Customizer Settings for Stories
 *
 * This file adds a custom section to the WordPress Customizer
 * that allows administrators to set site-specific data such as
 * a short bio or description. All fields include sanitization
 * and translation support for better security and flexibility.
 */

function stories_customize_register($wp_customize) {

    // ====== SECTION: Site Data ======
    $wp_customize->add_section('stories_site_data', array(
        'title'       => __('Site Data', 'stories'),
        'description' => __('Define basic information about your website.', 'stories'),
        'priority'    => 11,
    ));

    // ====== SETTING: Short Bio ======
    $wp_customize->add_setting('stories_bio', array(
        'default'           => __('Relatos y Cartas is a space dedicated to creativity and expression through words. Here you will find stories, micro-stories, poems, and letters that seek to inspire, move, and connect with readers.', 'stories'),
        'sanitize_callback' => 'wp_kses_post', // Allows safe HTML
    ));

    // ====== CONTROL: Short Bio ======
    $wp_customize->add_control('stories_bio', array(
        'label'   => __('Short Bio', 'stories'),
        'section' => 'stories_site_data',
        'type'    => 'textarea',
    ));
}
add_action('customize_register', 'stories_customize_register');