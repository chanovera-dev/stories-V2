<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> data-id="<?= get_the_ID(); ?>">
    <div class="post-body">
        <header class="post-body__header">
            <div class="category post--tags">
                <?= '<a href="' . esc_url( get_post_format_link( 'gallery' ) ) . '" class="post-tag small glass-backdrop glass-bright">' . stories_get_metadata_icon('gallery') . esc_html( __('Galería', 'stories') ) . '</a>'; ?>
            </div>
            <div class="gallery-wrapper">
                <div class="gallery">
                    <?php
                        // Asegurar que la función esté cargada
                        if ( function_exists( 'stories_extract_gallery_images' ) ) {

                            $ids = stories_extract_gallery_images( get_the_ID() );

                            if ( ! empty( $ids ) ) {
                                foreach ( $ids as $id ) {
                                    echo '<div class="slide">';
                                    echo wp_get_attachment_image( $id, 'post-header-thumbnail' );
                                    echo '</div>';
                                }
                            }

                        }
                    ?>
                </div>
                <div class="gallery-navigation">
                    <button class="gallery-prev btn-pagination small-pagination" aria-label="Foto anterior"><?= stories_get_metadata_icon('backward'); ?></button>
                    <div class="bullets"></div>
                    <button class="gallery-next btn-pagination small-pagination" aria-label="Foto siguiente"><?= stories_get_metadata_icon('forward'); ?></button>
                </div>
            </div>
            <?php $post_title = get_the_title(); ?>
            <a class="post--permalink btn-pagination small-pagination" href="<?php the_permalink(); ?>" aria-label="Ver la galería de <?= esc_attr( $post_title ); ?>">
                <?= stories_get_metadata_icon('permalink'); ?>
            </a>
        </header>
        <div class="post-body__content">
            <a class="post--permalink" href="<?php the_permalink();?>">
                <?php the_title('<h3 class="post--title">', '</h3>'); ?>
            </a>
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