<section id="hero" class="block site-hero">
    <?php
        $video_url = get_field('hero_video');
        if ( ! $video_url ) {
            $video_url = get_template_directory_uri() . '/assets/video/hero.mp4';
        }
    ?>
    <video autoplay muted playsinline loop preload="metadata" role="presentation" id="video-on-hero" class="background-video-shortcode started" data-ratio="1.7777777777778" width="640" height="360">
        <source type="video/mp4" src="<?php echo esc_url( $video_url ); ?>">
    </video>
    <div class="content text-white text-shadow">
        <div>
            <?php if ( $title = get_field( 'hero_title' ) ): ?>
            <h1 class="hero-title"><?php echo esc_html( $title ); ?></h1>
            <?php endif;?>
            <?php if ( $subtitle = get_field( 'hero_subtitle' ) ) : ?>
            <div class="subtitle-wrapper">
                <h2 class="hero-subtitle"><?php echo esc_html( $subtitle ); ?></h2>
            </div>
            <?php endif;?>
            <form class="property-filter-form" id="property-filters" method="get" action="<?php echo site_url('/propiedades/'); ?>">
                <input type="text" name="search" placeholder="<?php esc_html_e('Estado, ciudad, compra, renta, etc.', 'stories'); ?>" value="<?php echo isset($_GET['search']) ? esc_attr($_GET['search']) : ''; ?>">
                <button type="submit"><?php esc_html_e('BUSCAR CASA', 'stories'); ?></button>
            </form>
            <div class="cta">
            <?php if ($primary_button = get_field('cta_primary_button')): ?>
                <?php $number = get_field('cta_primary_button_number',); ?>
                <button class="btn primary" onclick="window.open('https://wa.me/<?php echo esc_attr($number); ?>','_blank','noopener,noreferrer')"><?= stories_get_metadata_icon('whatsapp') . esc_html($primary_button); ?></button>
            <?php endif; ?>
            <?php if ($secondary_button = get_field('cta_secondary_button')): ?>
                <?php $secondary_link = get_field('cta_secondary_button_link'); ?>
                <button class="btn white-text go-to-properties" onclick="window.location.href='<?php echo esc_url($secondary_link); ?>'"><?= esc_html($secondary_button) . stories_get_metadata_icon('right-arrow'); ?></button>
            <?php endif; ?>
            </div>
        </div>
    </div>
</section>