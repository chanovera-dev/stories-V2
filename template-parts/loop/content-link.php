<?php

$content = get_the_content();
preg_match('/https?:\/\/[^\s"]+/', $content, $matches);
$url = $matches[0] ?? '';

$title = get_the_title(); // Título por defecto
$image = '';
$date = ''; // Nueva variable para fecha externa

if ( $url && wp_http_validate_url( $url ) ) {
    
    // Intentar obtener datos de caché
    $transient_key = 'stories_link_preview_' . md5( $url );
    $cached_data   = get_transient( $transient_key );

    if ( false !== $cached_data ) {
        $title = $cached_data['title'];
        $image = $cached_data['image'];
        $date  = $cached_data['date'];
    } else {
        // Realizar petición segura con API HTTP de WP
        $response = wp_remote_get( $url, array(
            'timeout'     => 5,
            'user-agent'  => 'Mozilla/5.0 (compatible; StoriesTheme/2.0; +' . home_url() . ')',
            'redirection' => 5,
        ) );

        if ( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {
            $html = wp_remote_retrieve_body( $response );

            if ( $html ) {

                /**
                 * ===================================
                 *  TÍTULO EXTERNO
                 * ===================================
                 */

                // og:title
                if (preg_match('/<meta property="og:title" content="([^"]+)"/i', $html, $og_title)) {
                    $title = $og_title[1];
                }
                // <title>
                elseif (preg_match('/<title>(.*?)<\/title>/i', $html, $fallback_title)) {
                    $title = strip_tags($fallback_title[1]);
                }

                // Limpieza opcional
                $title = preg_replace('/\s*-\s*La Voz de la Región$/i', '', $title);


                /**
                 * ===================================
                 *  IMAGEN EXTERNA
                 * ===================================
                 */

                // og:image
                if (preg_match('/<meta property="og:image" content="([^"]+)"/i', $html, $og_image)) {
                    $image = $og_image[1];
                } else {
                    // Buscar manualmente imágenes del contenido principal
                    libxml_use_internal_errors(true);
                    $doc = new DOMDocument();
                    // Suppress warnings for malformed HTML
                    $doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_NOWARNING | LIBXML_NOERROR);
                    $xpath = new DOMXPath($doc);

                    $nodes = $xpath->query("//div[contains(@class, 'post-body')]//img");

                    if ($nodes->length > 0) {
                        foreach ($nodes as $img) {
                            $image = $img->getAttribute('src') ?: $img->getAttribute('data-src');
                            if ($image) break;
                        }
                    }

                    libxml_clear_errors();
                }


                /**
                 * ===================================
                 *  FECHA EXTERNA
                 * ===================================
                 */

                // 1. JSON-LD datePublished
                if (preg_match('/"datePublished"\s*:\s*"([^"]+)"/i', $html, $json_date)) {
                    $date = $json_date[1];
                }

                // 2. <meta property="article:published_time">
                elseif (preg_match('/<meta[^>]+property=["\']article:published_time["\'][^>]+content=["\']([^"\']+)["\']/i', $html, $meta_date)) {
                    $date = $meta_date[1];
                }

                // 3. meta name="date"
                elseif (preg_match('/<meta[^>]+name=["\']date["\'][^>]+content=["\']([^"\']+)["\']/i', $html, $meta2_date)) {
                    $date = $meta2_date[1];
                }

                // 4. <time datetime="">
                elseif (preg_match('/<time[^>]+datetime=["\']([^"\']+)["\']/i', $html, $time_date)) {
                    $date = $time_date[1];
                }

                // 5. Fecha simple YYYY-MM-DD
                elseif (preg_match('/\b(20\d{2}-\d{2}-\d{2})\b/', $html, $simple_date)) {
                    $date = $simple_date[1];
                }

                // Formateo de fecha a "January 1, 2025"
                if ($date) {
                    $timestamp = strtotime($date);
                    if ($timestamp) {
                        $date = date_i18n( 'F j, Y', $timestamp );
                    }
                }

                // Guardar en caché por 24 horas
                set_transient( $transient_key, array(
                    'title' => $title,
                    'image' => $image,
                    'date'  => $date,
                ), DAY_IN_SECONDS );
            }
        }
    }
}

// Si no hay imagen externa, usar imagen destacada del post
if (empty($image)) {
    $image = get_the_post_thumbnail_url(get_the_ID(), 'full');
}

// Si no hay fecha externa, usar la fecha del post
if (empty($date)) {
    $date = get_the_date('F j, Y');
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> data-id="<?= get_the_ID(); ?>">
    <div class="post-body">
        <header class="post-body__header">
            <div class="category post--tags">
                <?= '<a href="' . esc_url( get_post_format_link( 'link' ) ) . '" class="post-tag small glass-backdrop glass-bright">' . stories_get_metadata_icon('link') . esc_html( __('Enlace', 'stories') ) . '</a>'; ?>
            </div>
            <?php if ($image): ?>
                <img class="wp-post-image" src="<?= esc_url($image); ?>" alt="<?= esc_attr($title); ?>" />
            <?php endif; ?>
        </header>
        <div class="post-body__content">
            <a class="post--permalink" href="<?= esc_url($url); ?>" target="_blank" rel="noopener noreferrer">
                <h3 class="post--title"><?= esc_html($title); ?></h3>
            </a>
            <div class="post--date">
                <?= stories_get_metadata_icon('date'); ?>
                <p><?= esc_html($date); ?></p>
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