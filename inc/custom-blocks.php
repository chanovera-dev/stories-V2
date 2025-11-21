<?php

/**
 * Convertir core/gallery a galería personalizada sin duplicaciones.
 * El JS será quien genere los clones para el loop infinito.
 */

add_filter('render_block', 'custom_render_gallery_block', 10, 3);

function custom_render_gallery_block($block_content, $block) {

    // Solo aplicar a core/gallery
    if ($block['blockName'] !== 'core/gallery') {
        return $block_content;
    }

    if (empty($block['innerBlocks'])) {
        return $block_content;
    }

    $images = [];

    // Extraer imágenes reales
    foreach ($block['innerBlocks'] as $inner) {
        if ($inner['blockName'] === 'core/image' && !empty($inner['attrs']['id'])) {

            $img_id = $inner['attrs']['id'];
            $src = wp_get_attachment_image_src($img_id, 'large')[0] ?? '';
            $alt = get_post_meta($img_id, '_wp_attachment_image_alt', true);

            if ($src) {
                $images[] = [
                    'src' => $src,
                    'alt' => $alt
                ];
            }
        }
    }

    if (empty($images)) {
        return $block_content;
    }

    $total_slides = count($images);
    $slide_width = 100 / $total_slides;

    ob_start();
    ?>

    <div class="post-gallery-wrapper">
        <div class="total-images post-tag glass-backdrop glass-bright"></div>
        <div class="post-gallery" style="display:flex;width:<?php echo $total_slides * 100; ?>%;">
            <?php foreach ($images as $i => $img): ?>
                <div class="post-gallery-slide<?php echo $i === 0 ? ' active' : ''; ?>"
                     style="width:<?php echo $slide_width; ?>%;">
                    <img src="<?php echo esc_url($img['src']); ?>"
                         alt="<?php echo esc_attr($img['alt']); ?>"
                         loading="lazy">
                </div>
            <?php endforeach; ?>
        </div>
        <div class="post-gallery-thumbs-container">
            <?php
                $prev_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"></path></svg>';
                $next_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-circle" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z"></path></svg>';
            ?>
            <button class="btn-pagination small-pagination" aria-label="Anterior"><?= $prev_icon; ?></button>
            <div class="post-gallery-thumbs"></div>
            <button class="btn-pagination small-pagination" aria-label="Siguiente"><?= $next_icon; ?></button>
        </div>
    </div>

    <?php
    return ob_get_clean();
}
