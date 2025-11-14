<div class="is-layout-constrained">
    <?php 
        if ( has_post_thumbnail() ) {
            echo get_the_post_thumbnail( null, 'full', [ 'alt' => get_the_title(), 'loading' => 'lazy' ] );
        }

        the_content();

        wp_link_pages(
            array(
                'before' => '<div class="page-links">' . __( 'Páginas:', 'core' ),
                'after'  => '</div>',
            )
        );
    ?>
</div>