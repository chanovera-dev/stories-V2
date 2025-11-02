<?php
/**
 * Single Post Template
 *
 * This template is used to display individual blog posts.
 * It loads the header, runs the main WordPress loop to display
 * the post content using the 'template-parts/content-single.php' template part,
 * and then loads the footer.
 *
 * Typically used for standard blog posts, news articles, or custom post types
 * that do not have a dedicated template.
 *
 * @package stories
 * @since 2.0.0
 */
get_header();

if ( have_posts() ) {

    while ( have_posts() ) {
        the_post();
        // Detectar formato de post; si no hay formato usar 'single' como fallback.
        $post_format = get_post_format();
        $suffix = 'single'; // por defecto buscamos template-parts/content-single.php

        if ( $post_format ) {
            // construimos el sufijo que queremos: 'single-{format}'
            $maybe = 'single-' . $post_format;

            // comprobamos si existe template-parts/content-single-{format}.php
            if ( locate_template( "template-parts/content-{$maybe}.php" ) ) {
                $suffix = $maybe;
            }
        }

        // Esto cargará template-parts/content-single.php o
        // template-parts/content-single-{format}.php si existía.
        get_template_part( 'template-parts/content', $suffix );
    }

}

get_footer();