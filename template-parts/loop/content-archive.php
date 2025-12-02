<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> data-id="<?= get_the_ID(); ?>">
    <div class="post-body">
        <header class="post-body__header">
            <div class="category post--tags">
                <?php
                    $categories = get_the_category();
                    if ( ! empty( $categories ) ) {
                        foreach ( $categories as $category ) {
                            // Escapar el nombre y generar link seguro
                            $cat_name = esc_html( $category->name );
                            $cat_link = esc_url( get_category_link( $category->term_id ) );
                            $cat_icon = stories_get_metadata_icon('category');

                            echo "<a href='{$cat_link}' class='post-tag small glass-backdrop glass-bright'>{$cat_icon}{$cat_name}</a> ";
                        }
                    }
                ?>
            </div>
            <?php 
                if ( has_post_thumbnail() ) {
                    echo get_the_post_thumbnail( null, 'loop-thumbnail', [ 'alt' => get_the_title(), 'loading' => 'lazy' ] );
                }
            ?>
        </header>
        <div class="post-body__content">
            <a class="post--permalink" href="<?php the_permalink();?>">
                <?php the_title('<h3 class="post--title">', '</h3>'); ?>
            </a>
            <div class="post--excerpt">
                <?= get_the_excerpt(); ?>
            </div>
            <div class="post--date">
                <?= stories_get_metadata_icon('date'); ?>
                <p><?= get_the_date( 'F j, Y' ); ?></p>
            </div>
        </div>
        <footer class="post-body__footer">
            <div class="tags post--tags">
                <?php
                    $tags = get_the_tags();
                    if ( $tags ) {
                        foreach ( $tags as $tag ) {
                            echo '<a class="post-tag small" href="' . esc_url( get_tag_link( $tag->term_id ) ) . '">'. stories_get_metadata_icon('tag') . esc_html( $tag->name ) . '</a>';
                        }
                    }
                ?>
            </div>
        </footer>
    </div>
</article>