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
            if ( have_posts() ) {
                 while ( have_posts() ) {
                    the_post();
                    $post_format = get_post_format();
                    $part = 'archive';

                    if ( $post_format ) {
                        if ( locate_template( "template-parts/loop/content-{$post_format}.php" ) ) {
                            $part = $post_format;
                        }
                    }

                    get_template_part( 'template-parts/loop/content', $part );
                }

                the_posts_pagination( array(
                    'mid_size'  => 2,
                    'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0"/></svg>',
                    'next_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708"/></svg>'
                ) );
            } else {
                /* translators: Message displayed when no posts are found in an archive. */
                echo '<p>' . esc_html__( 'No se han encontrado artículos', 'stories' ) . '</p>';
            }
            ?>
        </div>
    </section>

</main><!-- .site-main -->

<?php get_footer(); ?>