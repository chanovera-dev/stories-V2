<?php

$content = get_the_content();
preg_match('/https?:\/\/[^\s"]+/', $content, $matches);
$url = $matches[0] ?? '';

$title = get_the_title(); // Título por defecto
$image = '';
$date = ''; // Nueva variable para fecha externa

if ($url) {
    // Inicializar cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)');
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);

    $html = curl_exec($ch);
    curl_close($ch);

    if ($html) {

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
            $doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
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

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="post-body">
        <header class="post-body__header">
            <div class="category post--tags">
                <?php
                    $format_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-link-45deg" viewBox="0 0 16 16"><path d="M4.715 6.542 3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1 1 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4 4 0 0 1-.128-1.287z"/><path d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 1 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 1 0-4.243-4.243z"/></svg>';

                    echo '<a href="' . esc_url( get_post_format_link( 'link' ) ) . '" class="post-tag small glass-backdrop glass-bright">'
                    . $format_svg . esc_html( __('Enlace', 'stories') ) . '</a>';
                ?>
            </div>
            <?php if ($image): ?>
                <img class="wp-post-image" src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>" />
            <?php endif; ?>
        </header>
        <div class="post-body__content">
            <a class="post--permalink" href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener noreferrer">
                <h3 class="post--title"><?php echo esc_html($title); ?></h3>
            </a>
            <div class="post--date">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar2-week" viewBox="0 0 16 16">
                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M2 2a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1z"/>
                    <path d="M2.5 4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H3a.5.5 0 0 1-.5-.5zM11 7.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm-3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm-5 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z"/>
                </svg>
                <p><?php echo esc_html($date); ?></p>
            </div>
        </div>
        <footer class="post-body__footer">
            <div class="tags post--tags">
                <?php
                    $tags = get_the_tags();
                    $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-tag" viewBox="0 0 16 16"><path d="M6 4.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m-1 0a.5.5 0 1 0-1 0 .5.5 0 0 0 1 0"/><path d="M2 1h4.586a1 1 0 0 1 .707.293l7 7a1 1 0 0 1 0 1.414l-4.586 4.586a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 1 6.586V2a1 1 0 0 1 1-1m0 5.586 7 7L13.586 9l-7-7H2z"/></svg>';
                    if ( $tags ) {
                        foreach ( $tags as $tag ) {
                            echo '<a class="post-tag small" href="' . esc_url( get_tag_link( $tag->term_id ) ) . '">'. $icon . esc_html( $tag->name ) . '</a>';
                        }
                    }
                ?>
            </div>
        </footer>
    </div>
</article>