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
                    <button class="gallery-prev btn-pagination small-pagination" aria-label="Foto anterior"><?= stories_get_metadata_icon('backward'); ?></button>
                    <div class="bullets"></div>
                    <button class="gallery-next btn-pagination small-pagination" aria-label="Foto siguiente"><?= stories_get_metadata_icon('forward'); ?></button>
                </div>
            </div>
            <?php $post_title = get_the_title(); ?>
            <a class="post--permalink btn-pagination small-pagination" href="<?php the_permalink(); ?>" aria-label="<?= get_the_ID(); ?> - Ver la galería de <?php echo esc_attr( $post_title ); ?>">
                <?= stories_get_metadata_icon('permalink'); ?>
            </a>
            <?php $location = get_post_meta( get_the_ID(), 'eb_location', true ); ?>
            <p class="location">
                <?= stories_get_metadata_icon('location'); ?>
                <?php echo $location; ?>
            </p>
        </header>
        <div class="post-body__content">
            <a class="post--permalink" href="<?php the_permalink();?>">
                <?php the_title('<h3 class="post--title">', '</h3>'); ?>
            </a>
            <div class="post--date">
                <?= stories_get_metadata_icon('date'); ?>
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

                // --- 1. Detectar métodos ---
                $methods = [];

                // 1. WhatsApp (todos)
                if ( preg_match_all( '/https:\/\/wa\.me\/[^\s"]+/', $content, $wa_matches ) ) {
                    foreach ( $wa_matches[0] as $wa_url ) {
                        $methods[] = [
                            'type' => 'whatsapp',
                            'url'  => $wa_url,
                            'icon' => stories_get_metadata_icon('whatsapp'),
                            'label'=> __('Informes', 'stories'),
                        ];
                    }
                }

                // 2. Teléfonos (todos)
                if ( preg_match_all( '/tel:([0-9+\-\s]+)/i', $content, $tel_matches ) ) {
                    foreach ( $tel_matches[0] as $i => $tel_url ) {
                        $methods[] = [
                            'type' => 'phone',
                            'url'  => $tel_url,
                            'icon' => stories_get_metadata_icon('phone'),
                            'label'=> __('Informes', 'stories'),
                        ];
                    }
                }

                // 3. Correos electrónicos (todos)
                if ( preg_match_all( '/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}/i', $content, $email_matches ) ) {
                    foreach ( $email_matches[0] as $email ) {
                        $methods[] = [
                            'type' => 'email',
                            'url'  => 'mailto:' . $email,
                            'icon' => stories_get_metadata_icon('email'),
                            'label'=> __('Informes', 'stories'),
                        ];
                    }
                }

                // --- 2. Determinar si se muestra o no el texto ---
                $show_text = count($methods) < 2;

                // --- 3. Imprimir botones ---
                foreach ( $methods as $m ) {
                ?>
                    <button class="btn primary go-contact"
                            onclick="window.open('<?php echo esc_url( $m['url'] ); ?>','_blank','noopener,noreferrer')">
                        <?= $m['icon']; ?>
                        <?php if ( $show_text ) echo esc_html( $m['label'] ); ?>
                    </button>
                <?php
                }
            ?>
        </footer>
    </div>
</article>