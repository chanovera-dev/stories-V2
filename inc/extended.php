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
            .menu li a[href*="twitter"]:before{mask-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/icons/twitter.svg');}
            .menu li a[href*="youtube"]:before{mask-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/icons/youtube.svg');}
            .menu li a[href*="instagram"]:before{mask-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/icons/instagram.svg');}
            .menu li a[href*="google"]:before{mask-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/icons/google.svg');}
            .menu li a[href*="tiktok"]:before{mask-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/icons/tiktok.svg');}
            .menu li a[href*="linkedin"]:before{mask-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/icons/linkedin.svg');}

            .menu li a[href*="tel"]:before{mask-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/icons/support-phone.svg');}
            .menu li a[href*="mailto"]:before{mask-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/icons/mailto.svg');}
            .menu li a[href*="maps"]:before{mask-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/icons/map.svg');}
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
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-7XNN23WGQT"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-7XNN23WGQT', { 'transport_type': 'beacon', 'send_page_view': false });
    </script>
    <?php
}
add_action('wp_head', 'add_gtm_header');

/**
 * Breadcrumbs
 */
function wp_breadcrumbs() {
    $separator = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708"/></svg>';
    $icon_home = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12.76c0-1.358 0-2.037.274-2.634c.275-.597.79-1.038 1.821-1.922l1-.857C9.96 5.75 10.89 4.95 12 4.95s2.041.799 3.905 2.396l1 .857c1.03.884 1.546 1.325 1.82 1.922c.275.597.275 1.276.275 2.634V17c0 1.886 0 2.828-.586 3.414S16.886 21 15 21H9c-1.886 0-2.828 0-3.414-.586S5 18.886 5 17z"/><path stroke-linecap="round" stroke-linejoin="round" d="M14.5 21v-5a1 1 0 0 0-1-1h-3a1 1 0 0 0-1 1v5"/></g></svg>';
    $home = 'Inicio';
    $showCurrent = 1;
    $showOnHome = 0;
    $current = '';
    $paged = get_query_var('paged') ? get_query_var('paged') : 1;

    global $post;
    $homeLink = get_bloginfo('url');
    echo '<section class="block breadcrumbs--wrapper"><div class="content"><div class="breadcrumbs">';
    echo '<a class="go-home" href="' . $homeLink . '">' . $icon_home . $home . '</a>' . $separator;

    if (is_category()) {
        if ($paged === 1) {
            echo 'Últimos artículos de la'; the_archive_title( '<h1 class="page-title">', '</h1>' );
        } else {
            echo esc_html('Página ' . $paged . ' de '); the_archive_title('<h1 class="page-title">', '</h1>');
        } 
    }
     elseif ( is_archive() ) {
        if ($paged === 1) {
            echo 'Últimos artículos de la'; the_archive_title( '<h1 class="page-title">', '</h1>' );
        } else {
            echo esc_html('Página ' . $paged . ' de '); the_archive_title('<h1 class="page-title">', '</h1>');
        } 
    } elseif (is_home()) {
        if ($paged === 1) {
            echo '<h1 class="page-title">' . esc_html_e( 'Últimos artículos', 'stories' ) . '</h1>';
        } else {
            echo esc_html('Página ' . $paged . ' de ') . '<h1 class="page-title">todos los artículos</h1>';
        }
    } elseif (is_page_template('archivo-detras-del-espejo.php')) {
        echo $current . 'Capítulos de "Detrás del Espejo"';
    } elseif (is_page()) {
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
    } elseif (is_search()) {
        if ($paged === 1) {
            echo '<h1 class="page-title">'; esc_html_e('Resultados de búsqueda de "', 'stories'); echo the_search_query(); esc_html_e('"', 'stories') . '</h1>';
        } else {
            echo '<h1 class="page-title">' . esc_html('Página ' . $paged) . '</h1>';
        }
    } elseif (is_day()) {
        echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a>' . $separator;
        echo '<a href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a>' . $separator;
        echo get_the_time('d') . $separator;
        echo $current . ' ' . get_the_time('l');
    } elseif (is_month()) {
        echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a>' . $separator;
        echo $current . ' ' . get_the_time('F');
    } elseif (is_year()) {
        echo $current . ' ' . get_the_time('Y');
    } elseif (is_single() && !is_attachment()) {
        if (get_post_type() != 'post') {
            $post_type = get_post_type_object(get_post_type());
            $slug = $post_type->rewrite;
            echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a>' . $separator;
            if ($showCurrent == 1) echo $current . ' ';
        } else
        {
            $cat = get_the_category();
            $cat = $cat[0];
            $cats = get_category_parents($cat, TRUE, $separator);
            if ($showCurrent == 0) $cats = preg_replace("#^(.+)$separator$#", "$1", $cats);
            echo $cats;
            echo $current . ' ';
        }
    } elseif (!is_single() && !is_page() && get_post_type() != 'post' && !is_404()) {}
    echo '</div></div></section>';
}