<?php
/**
 * Homepage / Blog Index Template
 *
 * This template is used to display the homepage or blog index page.
 * Depending on WordPress settings, it can show:
 * - Latest posts (blog posts) on the homepage.
 * - Featured content or sections if customized.
 * - Pagination for navigating multiple pages of posts.
 *
 * It is typically the first template users see when visiting the site.
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