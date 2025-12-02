<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> data-id="<?= get_the_ID(); ?>">
    <div class="post-body">
        <header class="post-body__header">
            <div class="category post--tags">
                <?= '<a href="' . esc_url( get_post_format_link( 'video' ) ) . '" class="post-tag small glass-backdrop glass-bright">' . stories_get_metadata_icon('video') . esc_html( __('Video', 'core') ) . '</a>';
                ?>
            </div>
            <?php 
                // =========================================
                // OBTENER PRIMER VIDEO SIN ROMPER EL LOOP
                // =========================================

                $post_obj  = get_post();
                $content   = $post_obj->post_content;

                $first_video_html = ''; // ← almacena el video

                // 1) iframe
                if (!$first_video_html && preg_match('/<iframe.*?<\/iframe>/is', $content, $match)) {
                    $first_video_html = '<div class="post-video-wrapper">' . $match[0] . '</div>';
                }

                // 2) video HTML5
                if (!$first_video_html && preg_match('/<video.*?<\/video>/is', $content, $match)) {
                    $first_video_html = '<div class="post-video-wrapper">' . $match[0] . '</div>';
                }

                // 3) shortcode [video]
                if (!$first_video_html && has_shortcode($content, 'video')) {
                    $first_video_html = do_shortcode('[video]');
                }

                // 4) bloques de Gutenberg
                if (!$first_video_html) {
                    $blocks = parse_blocks($content);

                    foreach ($blocks as $block) {

                        if ($block['blockName'] === 'core/video' && !empty($block['attrs']['src'])) {
                            $first_video_html = '<video controls src="' . esc_url($block['attrs']['src']) . '"></video>';
                            break;
                        }

                        if ($block['blockName'] === 'core/embed' && !empty($block['attrs']['url'])) {
                            $first_video_html = wp_oembed_get($block['attrs']['url']);
                            break;
                        }
                    }
                }

                // --------------------------------------------
                // IMPRIMIR EL VIDEO (solo si existe)
                // --------------------------------------------
                if ($first_video_html) {
                    echo $first_video_html;
                }
            ?>
            <?php $post_title = get_the_title(); ?>
            <a class="post--permalink btn-pagination small-pagination" href="<?php the_permalink(); ?>" aria-label="Ver el video de <?= esc_attr( $post_title ); ?>">
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