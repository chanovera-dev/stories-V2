<div class="content content-tags">
    <?php
        $tags = get_the_tags();
        if ( $tags ) {
            foreach ( $tags as $tag ) {
                echo '<a class="post-tag small" href="' . esc_url( get_tag_link( $tag->term_id ) ) . '">' . stories_get_metadata_icon('tag') . esc_html( $tag->name ) . '</a>';
            }
        }
    ?>
</div>