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
        get_template_part( 'template-parts/content', 'single' );
    }

}

get_footer();