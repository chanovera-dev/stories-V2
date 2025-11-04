<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="post--body">
        <div class="post--body__content">
            <?php the_content(); ?>
        </div>
        <div class="post--body__footer">
            <div class="post--tags">
                <?php
                    $format = get_post_format() ?: 'standard';

                    $formats_labels = [
                        'standard' => __('Artículo', 'stories'),
                        'aside'    => __('Minientrada', 'stories'),
                        'gallery'  => __('Galería', 'stories'),
                        'link'     => __('Enlace', 'stories'),
                        'image'    => __('Imagen', 'stories'),
                        'quote'    => __('Cita', 'stories'),
                        'status'   => __('Estado', 'stories'),
                        'video'    => __('Video', 'stories'),
                        'audio'    => __('Audio', 'stories'),
                        'chat'     => __('Chat', 'stories'),
                    ];

                    $format_label = $formats_labels[$format] ?? ucfirst($format);

                    $format_link = ( 'standard' !== $format ) ? get_post_format_link( $format ) : get_permalink( get_option( 'page_for_posts' ) );

                    echo '<a href="' . esc_url( $format_link ) . '" class="post-format-label tag-type small-text"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box" viewBox="0 0 16 16"><path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5 8 5.961 14.154 3.5zM15 4.239l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464z"/></svg>' . esc_html( $format_label ) . '</a>';

                    $categories = get_the_category();
                    if ( ! empty( $categories ) ) {
                        foreach ( $categories as $category ) {
                            // Escapar el nombre y generar link seguro
                            $cat_name = esc_html( $category->name );
                            $cat_link = esc_url( get_category_link( $category->term_id ) );

                            echo "<a href='{$cat_link}' class='tag-type small-text'><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" fill=\"currentColor\" class=\"bi bi-bookmark\" viewBox=\"0 0 16 16\"><path d=\"M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.777.416L8 13.101l-5.223 2.815A.5.5 0 0 1 2 15.5zm2-1a1 1 0 0 0-1 1v12.566l4.723-2.482a.5.5 0 0 1 .554 0L13 14.566V2a1 1 0 0 0-1-1z\"/></svg>{$cat_name}</a> ";
                        }
                    }

                    $tags = get_the_tags();
                    if ( $tags ) {
                        foreach ( $tags as $tag ) {
                            echo '<a class="tag-type small-text" href="' . esc_url( get_tag_link( $tag->term_id ) ) . '"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-tag" viewBox="0 0 16 16"><path d="M6 4.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m-1 0a.5.5 0 1 0-1 0 .5.5 0 0 0 1 0"/><path d="M2 1h4.586a1 1 0 0 1 .707.293l7 7a1 1 0 0 1 0 1.414l-4.586 4.586a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 1 6.586V2a1 1 0 0 1 1-1m0 5.586 7 7L13.586 9l-7-7H2z"/></svg>' . esc_html( $tag->name ) . '</a>';
                        }
                    }
                ?>
            </div>
        </div>
    </div>
</article>