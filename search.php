<?php
/**
 * Search Results Template
 *
 * This template is used to display search results when a user performs a search
 * on the website. It includes:
 * - A header with the search query.
 * - A loop displaying matched posts using appropriate template parts.
 * - Pagination for navigation between multiple pages of results.
 * - A friendly message if no results are found.
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