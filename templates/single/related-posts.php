<section class="block posts--body container--related-posts">
    <?php
        $categories = wp_get_post_categories(get_the_ID());
        $tags = wp_get_post_tags(get_the_ID());
        $args = array(
            'post_type'      => 'post',
            'posts_per_page' => 8,
            'post__not_in'   => array(get_the_ID()),
            'orderby'        => 'rand',
            'tax_query'      => array(
                'relation' => 'OR',
                array(
                    'taxonomy' => 'category',
                    'field'    => 'term_id',
                    'terms'    => $categories,
                ),
                array(
                    'taxonomy' => 'post_tag',
                    'field'    => 'term_id',
                    'terms'    => wp_list_pluck($tags, 'term_id'),
                ),
            ),
        );

        $related_posts = new WP_Query($args);

        if ($related_posts->have_posts()) :
    ?>
    <div class="content related-posts--title">
        <h2><?php echo esc_html_e( 'Contenido relacionado', 'stories' ); ?></h2>
    </div>
    <div class="content container">
        <div class="related-posts--list slideshow">
            <?php 
                while ($related_posts->have_posts()) : $related_posts->the_post();
                    $post_format = get_post_format();
                    $part = 'archive';

                    if ( $post_format ) {
                        if ( locate_template( "template-parts/loop/content-{$post_format}.php" ) ) {
                            $part = $post_format;
                        }
                    }

                    get_template_part( 'template-parts/loop/content', $part );
                endwhile;
            ?>
        </div>
    </div>
    <div class="content slideshow-buttons">
        <button id="related-products--backward-button" class="backward-button slideshow-button btn-pagination small-pagination">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"></path></svg>
        </button>
        <button id="related-products--forward-button" class="forward-button slideshow-button btn-pagination small-pagination">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-circle" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z"></path></svg>
        </button>
    </div>
    <?php
        wp_reset_postdata();
        endif;
    ?>
</section>