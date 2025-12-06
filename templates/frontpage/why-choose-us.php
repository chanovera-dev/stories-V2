<section id="why-choose-us" class="block">
    <div class="content padding-section">
        <div>
        <?php if ( have_rows('wcu_images') ) : ?>
            <?php $i = 1; ?>
            <?php while ( have_rows('wcu_images') ) : the_row(); ?>

                <?php $img = get_sub_field('wcu_image_file'); ?>

                <?php if ( $img ) : ?>
                    <img 
                        class="image image-<?= sprintf('%02d', $i); ?>" 
                        src="<?= esc_url($img['url']); ?>" 
                        alt="<?= esc_attr($img['alt']); ?>"
                    >
                <?php endif; ?>

                <?php $i++; ?>
            <?php endwhile; ?>
        <?php endif; ?>
        </div>
        <div>
            <?php if ( $title = get_field( 'wcu_title' ) ) : ?>
            <h2 class="title-section"><?= esc_html($title); ?></h2>
            <?php endif; ?>
            <?php if ( $subtitle = get_field( 'wcu_subtitle' ) ) : ?>
            <h3 class="subtitle-section"><?= esc_html($subtitle); ?></h3>
            <?php endif; ?>
            <?php if ( $paragraph = get_field( 'wcu_paragraph' ) ) : ?>
            <p class="paragraph"><?= esc_html($paragraph); ?></p>
            <?php endif; ?>

            <?php if ( have_rows('wcu_list') ) : ?>
                <ul>
                <?php while ( have_rows('wcu_list') ) : the_row(); ?>
                    <li>
                        <?php 
                        $icon = get_sub_field('wcu_list_icon');
                        $icon_id = 0;

                        if ( is_array($icon) && isset($icon['ID']) ) {
                            $icon_id = $icon['ID'];
                        } elseif ( is_numeric($icon) ) {
                            $icon_id = $icon;
                        }

                        if( $icon_id ) {
                            $svg_path = get_attached_file( $icon_id );
                            if ( file_exists( $svg_path ) ) {
                                echo file_get_contents( $svg_path );
                            }
                        }

                        if ( $paragraph = get_sub_field( 'wcu_list_paragraph' ) ) : ?>
                            <?= esc_html($paragraph); ?>
                        <?php endif; ?>
                    </li>
                <?php endwhile; ?>
                </ul>
            <?php endif; ?>
            <div class="cta">
            <?php if ($primary_button = get_field('cta_primary_button')): ?>
                <?php $number = get_field('cta_primary_button_number',); ?>
                <button class="btn primary" onclick="window.open('https://wa.me/<?php echo esc_attr($number); ?>','_blank','noopener,noreferrer')"><?= stories_get_metadata_icon('whatsapp') . esc_html($primary_button); ?></button>
            <?php endif; ?>
            <?php if ($secondary_button = get_field('cta_secondary_button')): ?>
                <?php $secondary_link = get_field('cta_secondary_button_link'); ?>
                <button class="btn go-to-properties" onclick="window.location.href='<?php echo esc_url($secondary_link); ?>'"><?= esc_html($secondary_button) . stories_get_metadata_icon('right-arrow'); ?></button>
            <?php endif; ?>
            </div>
        </div>
    </div>
</section>