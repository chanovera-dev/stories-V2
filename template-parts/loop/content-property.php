<article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?> data-id="<?php echo get_the_ID(); ?>">
    <div class="post-body">
        <header class="post-body__header">
            <div class="category post--tags">
                <?php
                    $type = get_post_meta(get_the_ID(), 'eb_property_type', true) ?: 'Sin tipo';
                    $operation = get_post_meta( get_the_ID(), 'eb_operation', true );
                    
                    // Translate property type from English to Spanish
                    $type_translated = function_exists('translate_property_type') ? translate_property_type($type) : $type;

                    echo '<span class="post-tag small glass-backdrop glass-bright">' . esc_html($type_translated) . ' ';
                    echo $operation === 'sale' ? 'en venta' : ( $operation === 'rental' ? 'en renta' : '' );
                    echo '</span>';
                ?>
            </div>
            <div class="gallery-wrapper">
                <div class="gallery">
                    <?php $gallery = get_post_meta( get_the_ID(), 'eb_gallery', true ); ?>
                    <?php if ( !empty($gallery) && is_array($gallery) ) : ?>
                        <?php foreach ( $gallery as $img ) :
                            $img_url = is_array($img) ? $img['url'] : $img; ?>
                            <div class="slide">
                                <img src="<?php echo esc_url( $img_url ); ?>" alt="" class="attachment-post-header-thumbnail size-post-header-thumbnail" width="400" height="400" loading="lazy">
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="gallery-navigation">
                    <button class="gallery-prev btn-pagination small-pagination" aria-label="Foto anterior"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"></path></svg></button>
                    <div class="gallery-bullets"></div>
                    <button class="gallery-next btn-pagination small-pagination" aria-label="Foto siguiente"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-circle" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z"></path></svg></button>
                </div>
            </div>
            <?php $post_title = get_the_title(); ?>
            <a class="post--permalink btn-pagination small-pagination" href="<?php the_permalink(); ?>" aria-label="Ver la galería de <?php echo esc_attr( $post_title ); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up-right-circle" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.854 10.803a.5.5 0 1 1-.708-.707L9.243 6H6.475a.5.5 0 1 1 0-1h3.975a.5.5 0 0 1 .5.5v3.975a.5.5 0 1 1-1 0V6.707z"/>
                </svg>
            </a>
            <?php $location = get_post_meta( get_the_ID(), 'eb_location', true ); ?>
            <p class="location">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt" viewBox="0 0 16 16">
                    <path d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A32 32 0 0 1 8 14.58a32 32 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10"/>
                    <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4m0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                </svg>
                <?php echo $location; ?>
            </p>
        </header>
        <div class="post-body__content">
            <a class="post--permalink" href="<?php the_permalink();?>">
                <?php the_title('<h3 class="post--title">', '</h3>'); ?>
            </a>
            <div class="post--date">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar2-week" viewBox="0 0 16 16">
                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M2 2a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1z"/>
                    <path d="M2.5 4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H3a.5.5 0 0 1-.5-.5zM11 7.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm-3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm-5 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z"/>
                </svg>
                <p><?php echo get_the_date( 'F j, Y' ); ?></p>
            </div>
            <?php $price = get_post_meta( get_the_ID(), 'eb_price', true ); ?>
            <h3 class="price">
                <?php 
                    // Extract numeric price for formatting
                    $price_numeric = preg_replace('/[^\d\.,]/', '', $price);
                    
                    // Handle european format (1.234.567,89) or US format (1,234,567.89)
                    if (strpos($price_numeric, ',') !== false && strpos($price_numeric, '.') !== false) {
                        // If contains both, assume european: remove dots, replace comma with dot
                        $price_numeric = str_replace('.', '', $price_numeric);
                        $price_numeric = str_replace(',', '.', $price_numeric);
                    } else {
                        // Remove commas used as thousands separators
                        $price_numeric = str_replace(',', '', $price_numeric);
                    }
                    
                    $price_numeric = preg_replace('/[^\d\.]/', '', $price_numeric);
                    
                    if (!empty($price_numeric)) {
                        echo function_exists('format_price') ? esc_html(format_price($price_numeric)) : esc_html($price);
                    } else {
                        echo esc_html($price);
                    }
                ?>
            </h3>
        </div>
        <footer class="post-body__footer">
            <?php stories_display_property_metadata(); ?>
            <?php
                $content = get_the_content();

                // 1. Buscar WhatsApp
                if ( preg_match( '/https:\/\/wa\.me\/[^\s"]+/', $content, $matches ) ) {
                    $whatsapp_url = $matches[0];
                    ?>
                    <button class="btn primary go-contact"
                            onclick="window.open('<?php echo esc_url( $whatsapp_url ); ?>','_blank','noopener,noreferrer')">
                        <?= stories_get_metadata_icon('whatsapp') . esc_html__('Informes', 'stories'); ?>
                    </button>
                    <?php

                // 2. Si no hay WhatsApp, buscar tel:
                } elseif ( preg_match( '/tel:([0-9+\-\s]+)/i', $content, $tel_match ) ) {
                    $tel_url = $tel_match[0];          // tel:555555...
                    $tel_num = trim($tel_match[1]);    // 555555...
                    ?>
                    <button class="btn primary go-contact"
                            onclick="window.location.href='<?php echo esc_url( $tel_url ); ?>'">
                        <?= stories_get_metadata_icon('phone') . esc_html__('Informes', 'stories'); ?>
                    </button>
                    <?php
                }
            ?>
        </footer>
    </div>
</article>