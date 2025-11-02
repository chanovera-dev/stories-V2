<div class="content content-author">
    <?php
    $author_id = get_post_field( 'post_author', get_the_ID() );

    echo get_avatar( $author_id, 70 );

    echo '<h3 class="author-name">' . esc_html( get_the_author_meta( 'display_name', $author_id ) ) . '</h3>';

    $description = get_the_author_meta( 'description', $author_id );
    if ( $description ) {
        echo '<span class="author-description">' . esc_html( $description ) . '</span>';
    }
    ?>
</div>