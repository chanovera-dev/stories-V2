<?php
/**
 * Single Page Template
 *
 * This template is used to display individual WordPress pages.
 * It loads the header, runs the main loop to display page content
 * using the 'template-parts/content-page.php' template part,
 * and then loads the footer.
 *
 * This template is typically used for static pages like "About Us",
 * "Contact", or any page created in the WordPress admin.
 *
 * @package stories
 * @since 2.0.0
 */

get_header();

if ( have_posts() ) {

    while( have_posts() ) {

        the_post();
        get_template_part( 'template-parts/page/content', 'page' );
        
    }

}

get_footer();