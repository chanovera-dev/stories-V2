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