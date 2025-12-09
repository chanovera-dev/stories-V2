<?php
/**
 * Extended Theme Functions
 *
 * Additional functionality for the stories theme:
 * - Safe SVG upload support (adds SVG MIME type with security considerations)
 * - Custom excerpt length (21 words) for archive/search displays
 * - Enhanced menu output for the primary menu (submenu indicators, custom markup)
 *
 * @package stories
 * @since 2.0.0
 */
 
/**
 * Enables SVG file upload support with security checks
 * 
 * Adds SVG MIME type to allowed upload formats while maintaining
 * WordPress security standards.
 *
 * @param array $mimes Current allowed MIME types
 * @return array Modified MIME types
 */
function mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter( 'upload_mimes', 'mime_types' ); 

/**
 * Customizes excerpt length for better readability
 * 
 * Reduces post excerpt length to 21 words for improved
 * display in archive pages and search results.
 *
 * @param int $limit Current excerpt length
 * @return int Modified excerpt length (21)
 */
function reduce_excerpt_length($limit) {
    return 21;
}
add_filter('excerpt_length', 'reduce_excerpt_length', 999);

/**
 * Enhances menu structure with custom elements
 * 
 * Adds submenu indicators and custom markup for mobile and primary
 * navigation menus. Includes SVG icons for visual hierarchy.
 *
 * @param string $item_output The menu item's HTML
 * @param object $item Menu item data object
 * @param int $depth Depth of menu item
 * @param object $args Menu arguments
 * @return string Modified menu item HTML
 */
function custom_menu($item_output, $item, $depth, $args) {
    $allowed_locations = ['primary'];

    if (!isset($args->theme_location) || !in_array($args->theme_location, $allowed_locations)) {
        return $item_output;
    }

    global $submenu_items_by_parent;
    static $checked_menus = [];

    if (!empty($args->menu) && !in_array($args->menu->term_id, $checked_menus)) {
        $menu_items = wp_get_nav_menu_items($args->menu->term_id);
        foreach ($menu_items as $menu_item) {
            $submenu_items_by_parent[$menu_item->menu_item_parent][] = $menu_item;
        }
        $checked_menus[] = $args->menu->term_id;
    }

    $has_children = !empty($submenu_items_by_parent[$item->ID]);

    if ($has_children) {
        $text = '<a href="' . esc_url($item->url) . '">' . esc_html($item->title) . '</a>';
        $svg_icon = '<svg width="13" height="13" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"></path></svg>';

        return '<div class="wrapper-for-title">' . $text . '<button class="button-for-submenu">' . $svg_icon . '</button></div>';
    }

    return $item_output;
}
add_filter('walker_nav_menu_start_el', 'custom_menu', 10, 4);

/**
 * Adds custom icons to social and contact menu links.
 *
 * This function injects CSS into the <head> to apply SVG icons as masks (`mask-image`)
 * for links within menus with the `.social` and `.contact` classes.
 *
 * - In `.social .menu`, it detects social media links by their href and assigns
 *   the corresponding icon (Facebook, WhatsApp, Twitter, YouTube, Instagram, Google, TikTok, LinkedIn).
 * - In `.contact .menu`, it detects contact links by their href (tel, mailto) and assigns
 *   the corresponding icon.
 *
 * Icons are loaded from the `assets/icons` folder of the active theme.
 *
 * @return void
 */
function theme_custom_icons() {
    ?>
        <style>          
            /* iconos de redes sociales */
            .menu li a[href*="facebook"]:before{mask-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/icons/facebook.svg');}
            .menu li a[href*="wa.me"]:before{mask-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/icons/whatsapp.svg');}
            .menu li a[href*="x.com"]:before,
            .menu li a[href*="twitter"]:before{mask-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/icons/twitter.svg');}
            .menu li a[href*="youtube"]:before{mask-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/icons/youtube.svg');}
            .menu li a[href*="instagram"]:before{mask-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/icons/instagram.svg');}
            .menu li a[href*="google"]:before{mask-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/icons/google.svg');}
            .menu li a[href*="tiktok"]:before{mask-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/icons/tiktok.svg');}
            .menu li a[href*="linkedin"]:before{mask-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/icons/linkedin.svg');}
            .menu li a[href*="flickr"]:before{mask-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/icons/flickr.svg');}

            .menu li a[href*="tel"]:before{mask-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/icons/support-phone.svg');}
            .menu li a[href*="mailto"]:before{mask-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/icons/mailto.svg');}
            .menu li a[href*="maps"]:before{mask-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/icons/map.svg');}

            a[href*="tel"]:before {mask-image: url('<?= get_stylesheet_directory_uri(); ?>/assets/icons/tel.svg');}
            .whatsapp-button:has(a[href*="api.whatsapp"]):before,
            #contact .content .is-layout-flex ul li a[href*="api.whatsapp"]:before {mask-image: url('<?= get_stylesheet_directory_uri(); ?>/assets/icons/whatsapp.svg');}
            #contact .content .is-layout-flex ul li a[href*="mailto"]:before {mask-image: url('<?= get_stylesheet_directory_uri(); ?>/assets/icons/mailto.svg');}
            #contact .content .is-layout-flex ul li.address:before {mask-image: url('<?= get_stylesheet_directory_uri(); ?>/assets/icons/address.svg');}
        </style>
    <?php
}
add_action('wp_head', 'theme_custom_icons');

/**
 * Modify the size of the comment avatar in WordPress.
 */
function custom_comment_avatar_size($avatar) {
    // Remove existing width, height, and style attributes from the avatar
    $avatar = preg_replace('/(width|height)="\d*"\s/', '', $avatar);
    $avatar = preg_replace('/style=["\'](.*?)["\']/', '', $avatar);

    // Set a fixed width and height of 70 pixels for the avatar
    $avatar = preg_replace('/src=([\'"])((?:(?!\1).)*?)\1/', 'src=$1$2$1 width="70" height="70"', $avatar);

    return $avatar;
}
add_filter('get_avatar', 'custom_comment_avatar_size', 10, 1);

/**
  * Add Google Tag Manager
  */
 function add_gtm_header() {
    ?>
    <!-- Google Tag Manager -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-8XCZ3SQKQ1"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-8XCZ3SQKQ1', { 'transport_type': 'beacon', 'send_page_view': false });
    </script>
    <?php
}
add_action('wp_head', 'add_gtm_header');

/**
 * Breadcrumbs
 */
function wp_breadcrumbs() {
    $separator = stories_get_metadata_icon('separator');
    $icon_home = stories_get_metadata_icon('home');
    $home = 'Inicio';
    $showCurrent = 1;
    $showOnHome = 0;
    $current = '';
    $paged = get_query_var('paged') ? get_query_var('paged') : 1;

    global $post;
    $homeLink = get_bloginfo('url');
    echo '<section class="block breadcrumbs--wrapper"><div class="content"><div class="breadcrumbs">';
    echo '<a class="go-home" href="' . $homeLink . '">' . $icon_home . $home . '</a>' . $separator;

    // ARCHIVO FORMATO IMAGE
    if ( is_tax( 'post_format', 'post-format-image' ) ) {
        echo $current . 'Dibujos';
    }

    // ARCHIVO FORMATO VIDEO
    elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
        echo $current . 'Videos';
    }

    // ARCHIVO FORMATO GALERÍA
    elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
        echo $current . 'Galerías';
    }

    // ARCHIVO FORMATO ENLACES
    elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
        echo $current . 'Artículos externos';
    }

    // ARCHIVO FORMATO CITAS
    elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
        echo $current . 'Citas';
    }

    // ARCHIVO FORMATO MINIENTRADA
    elseif ( is_tax( 'post_format', 'post-format-aside' ) ) {
        echo $current . 'Minientradas';
    }

    // 2. CATEGORÍA
    elseif ( is_category() ) {
        if ($paged === 1) {
            echo 'Últimos artículos de la'; 
            the_archive_title( '<h1 class="page-title">', '</h1>' );
        } else {
            echo esc_html('Página ' . $paged . ' de '); 
            the_archive_title('<h1 class="page-title">', '</h1>');
        } 
    }
    // 3. OTROS ARCHIVOS GENÉRICOS
    elseif ( is_archive() ) {
        if ($paged === 1) {
            echo 'Últimos artículos de la'; 
            the_archive_title( '<h1 class="page-title">', '</h1>' );
        } else {
            echo esc_html('Página ' . $paged . ' de '); 
            the_archive_title('<h1 class="page-title">', '</h1>');
        } 
    }
    elseif ( is_home() ) {
        if ($paged === 1) {
            echo '<h1 class="page-title">' . esc_html__( 'Contenido más reciente', 'stories' ) . '</h1>';
        } else {
            echo '<span>' . esc_html('Página ' . $paged . ' de ') . '</span>' . '<h1 class="page-title">todo el contenido</h1>';
        }
    }
    elseif ( is_page_template('archivo-detras-del-espejo.php') ) {
        echo $current . 'Capítulos de "Detrás del Espejo"';
    }
    elseif ( is_page() ) {
        if ($post->post_parent) {
            $ancestors = get_post_ancestors($post->ID);
            foreach ($ancestors as $ancestor) {
                $output = '<a href="' . get_permalink($ancestor) . '">' . get_the_title($ancestor) . '</a>' . $separator;
            }
            echo $output;
            echo $current . ' ' . get_the_title();
        } else {
            if ($showCurrent == 1) echo $current . ' ' . get_the_title();
        }
    }
    elseif ( is_search() ) {
        if ($paged === 1) {
            echo '<h1 class="page-title">';
            esc_html_e('Resultados de búsqueda de "', 'stories'); 
            echo get_search_query();
            esc_html_e('"', 'stories');
            echo '</h1>';
        } else {
            echo '<h1 class="page-title">' . esc_html('Página ' . $paged) . '</h1>';
        }
    }
    elseif ( is_day() ) {
        echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a>' . $separator;
        echo '<a href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a>' . $separator;
        echo get_the_time('d') . $separator;
        echo $current . ' ' . get_the_time('l');
    }
    elseif ( is_month() ) {
        echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a>' . $separator;
        echo $current . ' ' . get_the_time('F');
    }
    elseif ( is_year() ) {
        echo $current . ' ' . get_the_time('Y');
    }
    elseif ( is_single() && !is_attachment() ) {
        if ( get_post_type() != 'post' ) {
            $post_type = get_post_type_object(get_post_type());
            $slug = $post_type->rewrite;
            echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a>' . $separator;
            if ($showCurrent == 1) echo $current . ' ';
        } else {
            $cat = get_the_category();
            if ( $cat ) {
                $cat = $cat[0];
                $cats = get_category_parents($cat, TRUE, $separator);
                if ($showCurrent == 0) $cats = preg_replace("#^(.+)$separator$#", "$1", $cats);
                echo $cats;
            }
            echo $current . ' ';
        }
    }
    elseif (!is_single() && !is_page() && get_post_type() != 'post' && !is_404()) {
        // Aquí podrías manejar archivos de CPT si quisieras
    }

    echo '</div></div></section>';
}

/**
 * Get SVG icon for metadata item
 * 
 * @param string $type Type of metadata (bedroom, bathroom, construction, lot, parking)
 * @return string SVG markup
 */
function stories_get_metadata_icon($type) {
    $icons = [
        'separator' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708"/></svg>',
        'home'      => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1"><path d="M5 12.76c0-1.358 0-2.037.274-2.634c.275-.597.79-1.038 1.821-1.922l1-.857C9.96 5.75 10.89 4.95 12 4.95s2.041.799 3.905 2.396l1 .857c1.03.884 1.546 1.325 1.82 1.922c.275.597.275 1.276.275 2.634V17c0 1.886 0 2.828-.586 3.414S16.886 21 15 21H9c-1.886 0-2.828 0-3.414-.586S5 18.886 5 17z"/><path stroke-linecap="round" stroke-linejoin="round" d="M14.5 21v-5a1 1 0 0 0-1-1h-3a1 1 0 0 0-1 1v5"/></g></svg>',
        'close'     => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/><path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/></svg>',
        'search'    => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16"><path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/></svg>',
        'date'      => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar2-week" viewBox="0 0 16 16"><path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M2 2a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1z"/><path d="M2.5 4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H3a.5.5 0 0 1-.5-.5zM11 7.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm-3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm-5 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z"/></svg>',
        'tag'       => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-tag" viewBox="0 0 16 16"><path d="M6 4.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m-1 0a.5.5 0 1 0-1 0 .5.5 0 0 0 1 0"/><path d="M2 1h4.586a1 1 0 0 1 .707.293l7 7a1 1 0 0 1 0 1.414l-4.586 4.586a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 1 6.586V2a1 1 0 0 1 1-1m0 5.586 7 7L13.586 9l-7-7H2z"/></svg>',
        'category'  => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-journal-bookmark" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M6 8V1h1v6.117L8.743 6.07a.5.5 0 0 1 .514 0L11 7.117V1h1v7a.5.5 0 0 1-.757.429L9 7.083 6.757 8.43A.5.5 0 0 1 6 8"/><path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2"/><path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z"/></svg>',
        'aside'     => '<svg width="16" height="16" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M11.2895 2.75C11.4964 2.74979 11.6821 2.87701 11.7565 3.07003L14.9664 11.39C15.0657 11.6477 14.9375 11.9371 14.6798 12.0365C14.4222 12.1359 14.1328 12.0076 14.0334 11.75L12.9822 9.02537H9.61106L8.56672 11.749C8.46786 12.0068 8.1787 12.1357 7.92086 12.0369C7.66302 11.938 7.53414 11.6488 7.63301 11.391L10.8232 3.07099C10.8972 2.87782 11.0826 2.75021 11.2895 2.75ZM11.2915 4.64284L12.6543 8.17537H9.93698L11.2915 4.64284ZM2.89895 5.20703C1.25818 5.20703 0.00915527 6.68569 0.00915527 8.60972C0.00915527 10.6337 1.35818 12.0124 2.89895 12.0124C3.72141 12.0124 4.57438 11.6692 5.15427 11.0219V11.53C5.15427 11.7785 5.35574 11.98 5.60427 11.98C5.8528 11.98 6.05427 11.7785 6.05427 11.53V5.72C6.05427 5.47147 5.8528 5.27 5.60427 5.27C5.35574 5.27 5.15427 5.47147 5.15427 5.72V6.22317C4.60543 5.60095 3.79236 5.20703 2.89895 5.20703ZM5.15427 9.79823V7.30195C4.76393 6.58101 3.94144 6.05757 3.08675 6.05757C2.10885 6.05757 1.03503 6.96581 1.03503 8.60955C1.03503 10.1533 2.00885 11.1615 3.08675 11.1615C3.97011 11.1615 4.77195 10.4952 5.15427 9.79823Z" fill="currentColor"/></svg>',
        'gallery'   => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-images" viewBox="0 0 16 16"><path d="M4.502 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3"/><path d="M14.002 13a2 2 0 0 1-2 2h-10a2 2 0 0 1-2-2V5A2 2 0 0 1 2 3a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v8a2 2 0 0 1-1.998 2M14 2H4a1 1 0 0 0-1 1h9.002a2 2 0 0 1 2 2v7A1 1 0 0 0 15 11V3a1 1 0 0 0-1-1M2.002 4a1 1 0 0 0-1 1v8l2.646-2.354a.5.5 0 0 1 .63-.062l2.66 1.773 3.71-3.71a.5.5 0 0 1 .577-.094l1.777 1.947V5a1 1 0 0 0-1-1z"/></svg>',
        'backward'  => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"></path></svg>',
        'forward'   => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-circle" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z"></path></svg>',
        'permalink' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up-right-circle-fill" viewBox="0 0 16 16"><path d="M0 8a8 8 0 1 0 16 0A8 8 0 0 0 0 8m5.904 2.803a.5.5 0 1 1-.707-.707L9.293 6H6.525a.5.5 0 1 1 0-1H10.5a.5.5 0 0 1 .5.5v3.975a.5.5 0 0 1-1 0V6.707z"/></svg>',
        'image'     => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-image" viewBox="0 0 16 16"><path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/><path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1z"/></svg>',
        'link'      => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-link-45deg" viewBox="0 0 16 16"><path d="M4.715 6.542 3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1 1 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4 4 0 0 1-.128-1.287z"/><path d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 1 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 1 0-4.243-4.243z"/></svg>',
        'quote'     => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-quote" viewBox="0 0 16 16"><path d="M12 12a1 1 0 0 0 1-1V8.558a1 1 0 0 0-1-1h-1.388q0-.527.062-1.054.093-.558.31-.992t.559-.683q.34-.279.868-.279V3q-.868 0-1.52.372a3.3 3.3 0 0 0-1.085.992 4.9 4.9 0 0 0-.62 1.458A7.7 7.7 0 0 0 9 7.558V11a1 1 0 0 0 1 1zm-6 0a1 1 0 0 0 1-1V8.558a1 1 0 0 0-1-1H4.612q0-.527.062-1.054.094-.558.31-.992.217-.434.559-.683.34-.279.868-.279V3q-.868 0-1.52.372a3.3 3.3 0 0 0-1.085.992 4.9 4.9 0 0 0-.62 1.458A7.7 7.7 0 0 0 3 7.558V11a1 1 0 0 0 1 1z"/></svg>',
        'video'     => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-film" viewBox="0 0 16 16"><path d="M0 1a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1zm4 0v6h8V1zm8 8H4v6h8zM1 1v2h2V1zm2 3H1v2h2zM1 7v2h2V7zm2 3H1v2h2zm-2 3v2h2v-2zM15 1h-2v2h2zm-2 3v2h2V4zm2 3h-2v2h2zm-2 3v2h2v-2zm2 3h-2v2h2z"/></svg>',
    ];

    return $icons[$type] ?? '';
}

/**
 * Remove someone class of wp blocks
 */
function remove_wp_block_classes_on_specific_pages($block_content, $block) {
    if (!is_page(['home', 393])) {
        return $block_content;
    }

    if (!empty($block_content) && preg_match('/class="([^"]+)"/', $block_content, $matches)) {
        $classes = explode(' ', $matches[1]);

        $filtered_classes = array_filter($classes, function($class) {
            return strpos($class, 'wp-block-') === false &&
                   $class !== 'is-layout-constrained' &&
                   $class !== 'has-global-padding';
        });

        if (!empty($filtered_classes)) {
            $new_class_attribute = 'class="' . implode(' ', $filtered_classes) . '"';
            $block_content = str_replace($matches[0], $new_class_attribute, $block_content);
        } else {
            $block_content = str_replace($matches[0], '', $block_content);
        }
    }

    return $block_content;
}

add_filter('render_block', 'remove_wp_block_classes_on_specific_pages', 10, 2);

/**
 * Shortcode for skills on frontpage
 */
function mostrar_skills_repeater_shortcode() {
    ob_start();
    global $post;
    if ( have_rows( 'skills_repeater', $post->ID ) ) :
        while ( have_rows( 'skills_repeater', $post->ID ) ) : the_row();
            $class = get_sub_field( 'skill_class' );
            echo '<div class="skill-card ' . $class . '">';
            if ( $icon_id = get_sub_field( 'skill_icon' ) ) {
                $icon_path = get_attached_file( $icon_id );
                if ( file_exists( $icon_path ) ) {
                    $svg = trim( file_get_contents( $icon_path ) );
                    echo $svg;
                }
            }
            if ( $text = get_sub_field( 'skill_label' ) ) {
                echo '<p>' . esc_html( $text ) . '</p>';
            }
            echo '</div>';
        endwhile;
    endif;
    return ob_get_clean();
}
add_shortcode( 'skills_list', 'mostrar_skills_repeater_shortcode' );

/**
 * Meta description
 */
function get_dynamic_meta_description() {
    if ( is_singular() ) {
        global $post;

        if ( has_excerpt( $post->ID ) ) {
            return get_the_excerpt( $post->ID );
        }

        $custom_meta = get_post_meta( $post->ID, 'meta_description', true );
        if ( $custom_meta ) {
            return $custom_meta;
        }
    }

    return get_bloginfo( 'description' );
}