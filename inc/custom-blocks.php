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
            <button class="btn-pagination" aria-label="Anterior"><?= stories_get_metadata_icon('backward'); ?></button>
            <div class="post-gallery-thumbs"></div>
            <button class="btn-pagination" aria-label="Siguiente"><?= stories_get_metadata_icon('forward'); ?></button>
        </div>
    </div>

    <?php
    return ob_get_clean();
}
