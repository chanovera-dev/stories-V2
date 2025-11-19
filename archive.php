<?php
/**
 * Archive Page Template
 *
 * This template displays archive-type pages, including categories, tags, authors,
 * and custom post type archives. It includes a header with the archive title,
 * a loop that lists posts using 'content-archive' template parts, and pagination.
 * If no posts are found, a friendly message is displayed.
 *
 * @package stories
 * @since 2.0.0
 */

get_header(); ?>

<main id="main" class="site-main" role="main">

    <?php wp_breadcrumbs(); ?>

    <!-- Archive Posts Section -->
    <section class="block posts--body">
        <div class="content">
            <?php     
                get_template_part( 'templates/archive/wp', 'loop' );

                if ( is_active_sidebar( 'sidebar-1' ) ) {
                    echo '
                    <aside class="sidebar posts-body_sidebar">';
                    dynamic_sidebar( 'sidebar-1' ); echo '
                    </aside>';
                }
            ?>
        </div>
    </section>

</main><!-- .site-main -->

<?php get_footer(); ?>