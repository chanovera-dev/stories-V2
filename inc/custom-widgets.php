<?php
/**
 * Stories custom widgets
 * 
 * @since 1.0.0
 * @version 2.0.0
 */

/**
 * Custom output for wp_block_search()
 */
function custom_wp_block_search($block_content, $block) {
    if ($block['blockName'] === 'core/search') {
        ob_start();
        ?>
        <form role="search" method="get" action="<?php echo home_url( '/' ); ?>" class="wp-block-search__button-outside wp-block-search__text-button wp-block-search">
            <div class="wp-block-search__inside-wrapper ">
                <input class="wp-block-search__input" id="wp-block-search__input-1" placeholder="<?php esc_html_e('Buscar', 'stories'); ?>" value="" type="search" name="s" required="">
                <button aria-label="<?php esc_html_e('Buscar', 'stories'); ?>" class="wp-block-search__button wp-element-button" type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115 l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                    </svg>
                </button>
            </div>
        </form>
        <?php
        return ob_get_clean();
    }
    return $block_content;
}
add_filter('render_block', 'custom_wp_block_search', 10, 2);

/**
 * Custom output for category list in sidebar widget
 */
function custom_category_list($output, $args) {
    $categories = get_categories($args);

    $output = '';
    foreach ($categories as $category) {
        $svg_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-journal-bookmark" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M6 8V1h1v6.117L8.743 6.07a.5.5 0 0 1 .514 0L11 7.117V1h1v7a.5.5 0 0 1-.757.429L9 7.083 6.757 8.43A.5.5 0 0 1 6 8"></path><path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2"></path><path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z"></path></svg>';

        $output .= '<li>';
        $output .= '<a href="' . esc_url(get_category_link($category->term_id)) . '">';
        $output .= $svg_icon . '<span>' . esc_html($category->name);
        $output .= '</span></a>';
        $output .= '</li>';
    }
    $output .= '';

    return $output;
}
add_filter('wp_list_categories', 'custom_category_list', 10, 2);

/**
 * custom output fot wp_archive_list()
 */
function custom_archives_link( $link_html, $url, $text, $format, $before, $after ) {
    $custom_link = '<li><a href="' . esc_url($url) . '">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar4-week" viewBox="0 0 16 16">
        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M2 2a1 1 0 0 0-1 1v1h14V3a1 1 0 0 0-1-1zm13 3H1v9a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1z"/>
        <path d="M11 7.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm-3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm-2 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm-3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z"/>
    </svg>
    <span>' . $text . '</span></a></li>';
    // Return the modified link HTML
    return $before . $custom_link . $after;
}
add_filter( 'get_archives_link', 'custom_archives_link', 10, 6 );

/**
 * Add a custom output for latest posts block
 */
function custom_modify_latest_posts_block($block_content, $block) {
    // Verificamos que el bloque sea 'core/latest-posts'
    if ($block['blockName'] !== 'core/latest-posts') {
        return $block_content;
    }

    // Obtener las publicaciones recientes excluyendo formato "minientrada", "image", "video", "gallery", "link", "quote"
    $args = [
        'posts_per_page' => 5,
        'post_status'    => 'publish',
        'tax_query'      => [
            [
                'taxonomy' => 'post_format',
                'field'    => 'slug',
                'terms'    => ['post-format-aside', 'post-format-image', 'post-format-video', 'post-format-gallery', 'post-format-link', 'post-format-quote'],
                'operator' => 'NOT IN'
            ]
        ]
    ];
    $recent_posts = get_posts($args);

    if (empty($recent_posts)) {
        return $block_content;
    }

    $output = '<ul class="wp-block-latest-posts__list wp-block-latest-posts">';

    foreach ($recent_posts as $post) {
        $post_id = $post->ID;
        $post_title = esc_html(get_the_title($post_id));
        $post_link = esc_url(get_permalink($post_id));
        $post_date = get_the_date('j \d\e F \d\e Y', $post_id);
        $post_thumbnail = get_the_post_thumbnail($post_id, 'thumbnail', ['class' => 'latest-post-thumbnail']);

        $output .= '<li><a class="latest-post__body" href="' . $post_link . '">';
        if ($post_thumbnail) {
            $output .= '<div class="latest-post-thumbnail-wrapper">' . $post_thumbnail . '</div>';
        }
        $output .= '<h4 class="wp-block-latest-posts__post-title">' . $post_title . '</h4>';
        $output .= '<div class="latest-post-date">' . $post_date . '</div>';
        $output .= '</li></a>';
    }

    $output .= '</ul>';

    return $output;
}
add_filter('render_block', 'custom_modify_latest_posts_block', 10, 2);