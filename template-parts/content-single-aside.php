<div id="main" class="site-main" role="main">
    <article class="article single-post" id="<?php the_ID(); ?>">
        <section class="block">    
            <div class="is-layout-constrained">
                <?php the_content(); ?>
            </div>
            <div class="content date categories tags">
                <div class="date"><?= get_the_date(); ?></div>
                <div class="categories">
                    <?php
                        $categories = get_the_category();
                        if ( ! empty( $categories ) ) {
                            foreach ( $categories as $category ) {
                                // Escapar el nombre y generar link seguro
                                $cat_name = esc_html( $category->name );
                                $cat_link = esc_url( get_category_link( $category->term_id ) );

                                echo "<a href='{$cat_link}' class='tag-type small-text'><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" fill=\"currentColor\" class=\"bi bi-bookmark\" viewBox=\"0 0 16 16\"><path d=\"M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.777.416L8 13.101l-5.223 2.815A.5.5 0 0 1 2 15.5zm2-1a1 1 0 0 0-1 1v12.566l4.723-2.482a.5.5 0 0 1 .554 0L13 14.566V2a1 1 0 0 0-1-1z\"/></svg>{$cat_name}</a> ";
                            }
                        }
                    ?>
                </div>
                <div class="tags">
                    <?php
                        $tags = get_the_tags();
                        if ( $tags ) {
                            foreach ( $tags as $tag ) {
                                echo '<a class="tag-type small-text" href="' . esc_url( get_tag_link( $tag->term_id ) ) . '"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-tag" viewBox="0 0 16 16"><path d="M6 4.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m-1 0a.5.5 0 1 0-1 0 .5.5 0 0 0 1 0"/><path d="M2 1h4.586a1 1 0 0 1 .707.293l7 7a1 1 0 0 1 0 1.414l-4.586 4.586a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 1 6.586V2a1 1 0 0 1 1-1m0 5.586 7 7L13.586 9l-7-7H2z"/></svg>' . esc_html( $tag->name ) . '</a>';
                            }
                        }
                    ?>
                </div>
            </div>
            <div class="content content-author">
                <?php
                    echo get_avatar( get_the_author_meta('email'), '70' ) . '
                    <h3 class="author-name">'; the_author(); echo '</h3>' . '
                    <span class="author-description">'; the_author_meta('description'); echo '</span>';
                ?>
            </div>
            <nav class="content post-navigation" aria-label="<?= esc_attr__( 'Navegación de publicaciones', 'stories' ); ?>">
                <div class="left">
                    <?php
                        $prev_post = get_previous_post();
                        if ($prev_post) :
                    ?>
            
                    <a href="<?= esc_url( get_permalink( $prev_post->ID ) ); ?>" class="previous-post-link">
                        <p class="pagination-indicator">
                             <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"/>
                            </svg>
                            <?= esc_html__('Anterior', 'stories'); ?>
                        </p>
                        <p class="title-post"><?= esc_html( $prev_post->post_title ); ?></p>
                    </a>
                    <?php endif; ?>
                </div>
                <div class="right">
                    <?php
                        $next_post = get_next_post();
                        if ($next_post) :
                    ?>
                    <a href="<?= esc_url( get_permalink( $next_post->ID ) ); ?>" class="next-post-link">
                        <p class="pagination-indicator">
                            <?= esc_html__('Siguiente', 'stories'); ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-circle" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z"/>
                            </svg>
                        </p>
                        <p class="title-post"><?= esc_html( $next_post->post_title ); ?></p>
                    </a>
                    <?php endif; ?>
                </div>
            </nav>
        </section>
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
                                if ( locate_template( "template-parts/content-{$post_format}.php" ) ) {
                                    $part = $post_format;
                                }
                            }

                            get_template_part( 'template-parts/content', $part );
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
        <?php if ( comments_open() ): ?>
        <section class="block">
            <div class="content content-comments">
                <?php comments_template(); ?>
            </div>
        </section>
        <?php endif; ?>
    </article>
</div>