<div class="date"><?= stories_get_metadata_icon('date') . get_the_date(); ?></div>
<div class="content content-tags">
    <?=
        '<a href="' . esc_url( get_post_format_link( 'quote' ) ) . '" class="post-tag small">' . stories_get_metadata_icon('quote') . esc_html( __('Cita', 'stories') ) . '</a>';

        $tags = get_the_tags();
        if ( $tags ) {
            foreach ( $tags as $tag ) {
                echo '<a class="post-tag small" href="' . esc_url( get_tag_link( $tag->term_id ) ) . '">' . stories_get_metadata_icon('tag') . esc_html( $tag->name ) . '</a>';
            }
        }
    ?>
</div>