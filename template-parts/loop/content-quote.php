<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> data-id="<?= get_the_ID(); ?>">
    <div class="post-body">
        <div class="post-body__content">
            <div class="post--content">
                <?php the_content(); ?>
            </div>
            <div class="post--date">
                <?= stories_get_metadata_icon('date'); ?>
                <p><?= get_the_date( 'F j, Y' ); ?></p>
            </div>
        </div>
        <footer class="post-body__footer">
            <div class="tags post--tags">
                <?php
                    echo '<a href="' . esc_url( get_post_format_link( 'quote' ) ) . '" class="post-tag small">' . stories_get_metadata_icon('quote') . esc_html( __('Cita', 'stories') ) . '</a>';

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