<?php

function theme_customizer($wp_customize) {
    // Datos personales
    $wp_customize->add_section('site__data', array(
        'title' => __('Datos del sitio'),
        'description' => __('Establece tus datos'), 
        'priority' => 11,
    ));
        // bio corta
        $wp_customize->add_setting('bio', array(
            'default' => __('Relatos y Cartas es un espacio dedicado a la creatividad y la expresión a través de las palabras. Aquí encontrarás cuentos, microcuentos, poemas e historias que buscan inspirar, emocionar y conectar con los lectores.'),
        ));
        $wp_customize->add_control('bio', array(
            'label' => 'Bio corta',
            'section' => 'site__data',
            'type' => 'textarea',
        ));  
}
add_action('customize_register', 'theme_customizer');