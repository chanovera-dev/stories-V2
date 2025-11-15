<article id="post-<?php the_ID(); ?>" <?php post_class('post-body'); ?>>
    <header class="post-body__header">
        <div class="category post--tags">
            <?php
                $format_svg = '<svg width="16" height="16" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M2.5 1H12.5C13.3284 1 14 1.67157 14 2.5V12.5C14 13.3284 13.3284 14 12.5 14H2.5C1.67157 14 1 13.3284 1 12.5V2.5C1 1.67157 1.67157 1 2.5 1ZM2.5 2C2.22386 2 2 2.22386 2 2.5V8.3636L3.6818 6.6818C3.76809 6.59551 3.88572 6.54797 4.00774 6.55007C4.12975 6.55216 4.24568 6.60372 4.32895 6.69293L7.87355 10.4901L10.6818 7.6818C10.8575 7.50607 11.1425 7.50607 11.3182 7.6818L13 9.3636V2.5C13 2.22386 12.7761 2 12.5 2H2.5ZM2 12.5V9.6364L3.98887 7.64753L7.5311 11.4421L8.94113 13H2.5C2.22386 13 2 12.7761 2 12.5ZM12.5 13H10.155L8.48336 11.153L11 8.6364L13 10.6364V12.5C13 12.7761 12.7761 13 12.5 13ZM6.64922 5.5C6.64922 5.03013 7.03013 4.64922 7.5 4.64922C7.96987 4.64922 8.35078 5.03013 8.35078 5.5C8.35078 5.96987 7.96987 6.35078 7.5 6.35078C7.03013 6.35078 6.64922 5.96987 6.64922 5.5ZM7.5 3.74922C6.53307 3.74922 5.74922 4.53307 5.74922 5.5C5.74922 6.46693 6.53307 7.25078 7.5 7.25078C8.46693 7.25078 9.25078 6.46693 9.25078 5.5C9.25078 4.53307 8.46693 3.74922 7.5 3.74922Z" fill="currentColor"/></svg>';

                echo '<a href="' . esc_url( get_post_format_link( 'gallery' ) ) . '" class="post-tag small glass-backdrop glass-bright">'
                . $format_svg . esc_html( __('Galería', 'stories') ) . '</a>';
            ?>
        </div>
        <div class="gallery-wrapper">
            <div class="gallery">
                <?php
                    $post_id = get_the_ID();

                    /**
                     * Recorrer bloques recursivamente y extraer IDs de imágenes.
                     * - core/gallery (attrs.ids)
                     * - core/gallery con innerBlocks core/image (attrs.id)
                     * - core/image (attrs.id)
                     * - extraer src de img en innerHTML y convertir a ID con attachment_url_to_postid()
                     */
                    function collect_image_ids_from_blocks( $blocks ) {
                        $ids = array();

                        foreach ( $blocks as $block ) {

                            // 1) core/gallery con attrs->ids
                            if ( ! empty( $block['blockName'] ) && $block['blockName'] === 'core/gallery' ) {
                                if ( ! empty( $block['attrs']['ids'] ) && is_array( $block['attrs']['ids'] ) ) {
                                    foreach ( $block['attrs']['ids'] as $id ) {
                                        $ids[] = (int) $id;
                                    }
                                } else {
                                    // gallery sin ids: buscar innerBlocks core/image o img en innerHTML
                                    if ( ! empty( $block['innerBlocks'] ) ) {
                                        foreach ( $block['innerBlocks'] as $ib ) {
                                            // core/image con attrs.id
                                            if ( ! empty( $ib['blockName'] ) && $ib['blockName'] === 'core/image' ) {
                                                if ( ! empty( $ib['attrs']['id'] ) ) {
                                                    $ids[] = (int) $ib['attrs']['id'];
                                                    continue;
                                                }
                                                // si no tiene id, intentar extraer src desde innerHTML
                                                if ( ! empty( $ib['innerHTML'] ) ) {
                                                    if ( preg_match( '/<img[^>]+src=[\'"]([^\'"]+)[\'"]/', $ib['innerHTML'], $m ) ) {
                                                        $aid = attachment_url_to_postid( $m[1] );
                                                        if ( $aid ) $ids[] = (int) $aid;
                                                    }
                                                }
                                            } else {
                                                // si innerBlock no es core/image, recursivamente procesar
                                                $ids = array_merge( $ids, collect_image_ids_from_blocks( array( $ib ) ) );
                                            }
                                        }
                                    }
                                    // tambien intentar extraer imgs directos del innerHTML del gallery
                                    if ( empty( $ids ) && ! empty( $block['innerHTML'] ) ) {
                                        if ( preg_match_all( '/<img[^>]+src=[\'"]([^\'"]+)[\'"]/', $block['innerHTML'], $matches ) ) {
                                            foreach ( $matches[1] as $src ) {
                                                $aid = attachment_url_to_postid( $src );
                                                if ( $aid ) $ids[] = (int) $aid;
                                            }
                                        }
                                    }
                                }
                            }

                            // 2) core/image independiente
                            if ( ! empty( $block['blockName'] ) && $block['blockName'] === 'core/image' ) {
                                if ( ! empty( $block['attrs']['id'] ) ) {
                                    $ids[] = (int) $block['attrs']['id'];
                                } elseif ( ! empty( $block['attrs']['url'] ) ) {
                                    $aid = attachment_url_to_postid( $block['attrs']['url'] );
                                    if ( $aid ) $ids[] = (int) $aid;
                                } elseif ( ! empty( $block['innerHTML'] ) ) {
                                    if ( preg_match( '/<img[^>]+src=[\'"]([^\'"]+)[\'"]/', $block['innerHTML'], $m ) ) {
                                        $aid = attachment_url_to_postid( $m[1] );
                                        if ( $aid ) $ids[] = (int) $aid;
                                    }
                                }
                            }

                            // 3) Si tiene innerBlocks, procesarlos recursivamente
                            if ( ! empty( $block['innerBlocks'] ) ) {
                                $ids = array_merge( $ids, collect_image_ids_from_blocks( $block['innerBlocks'] ) );
                            }
                        }

                        return $ids;
                    }

                    // --------------- Ejecutar extracción ----------------
                    $image_ids = array();

                    // Obtener contenido del post
                    $content = get_post_field( 'post_content', $post_id );

                    // 1) Intentar parsear bloques (Gutenberg)
                    if ( function_exists( 'parse_blocks' ) && ! empty( $content ) ) {
                        $blocks = parse_blocks( $content );
                        $image_ids = collect_image_ids_from_blocks( $blocks );
                    }

                    // 2) Si no encontró nada, intentar shortcodes [gallery ids="..."]
                    if ( empty( $image_ids ) && ! empty( $content ) ) {
                        if ( preg_match_all( '/\[gallery[^\]]*ids=[\'"]?([^\'"\]]+)[\'"]?/', $content, $m ) ) {
                            // pueden existir varios shortcodes; tomar el primero con IDs
                            foreach ( $m[1] as $ids_str ) {
                                $parts = array_map( 'intval', array_filter( array_map( 'trim', explode( ',', $ids_str ) ) ) );
                                if ( ! empty( $parts ) ) {
                                    $image_ids = array_merge( $image_ids, $parts );
                                    break;
                                }
                            }
                        }
                    }

                    // 3) También intentar funciones helper: get_post_galleries (devuelve URLs), convertir a ID
                    if ( empty( $image_ids ) && function_exists( 'get_post_galleries' ) ) {
                        $galleries = get_post_galleries( $post_id, false ); // false -> devolver array de arrays de src
                        if ( ! empty( $galleries ) ) {
                            foreach ( $galleries as $gal ) {
                                foreach ( $gal as $src ) {
                                    $aid = attachment_url_to_postid( $src );
                                    if ( $aid ) $image_ids[] = (int) $aid;
                                }
                                if ( ! empty( $image_ids ) ) break;
                            }
                        }
                    }

                    // 4) Como último recurso, obtener adjuntos subidos al post (post_parent)
                    if ( empty( $image_ids ) ) {
                        $attachments = get_posts( array(
                            'post_parent'    => $post_id,
                            'post_type'      => 'attachment',
                            'post_mime_type' => 'image',
                            'numberposts'    => -1,
                            'orderby'        => 'menu_order ID',
                            'order'          => 'ASC',
                        ) );
                        if ( $attachments ) {
                            foreach ( $attachments as $att ) {
                                $image_ids[] = (int) $att->ID;
                            }
                        }
                    }

                    // 5) Fallback a la imagen destacada
                    if ( empty( $image_ids ) && has_post_thumbnail( $post_id ) ) {
                        $image_ids[] = (int) get_post_thumbnail_id( $post_id );
                    }

                    // Normalizar: únicos y filtrar
                    $image_ids = array_values( array_filter( array_unique( $image_ids ) ) );

                    // Renderizar imágenes (si hay)
                    if ( ! empty( $image_ids ) ) {
                        foreach ( $image_ids as $id ) {
                            // Puedes cambiar 'large' por el size que prefieras
                            echo wp_get_attachment_image( $id, 'large' );
                        }
                    } else {
                        // Comentario HTML para debugging sin romper el markup
                        echo '<!-- gallery: no se encontraron imágenes en bloques, shortcode o adjuntos -->';
                    }
                ?>
            </div>
            <div class="gallery-navigation">
                <button class="gallery-prev btn-pagination small-pagination" aria-label="Foto anterior"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"></path></svg></button>
                <div class="gallery-bullets"></div>
                <button class="gallery-next btn-pagination small-pagination" aria-label="Foto siguiente"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-circle" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z"></path></svg></button>
            </div>
        </div>
        <a class="post--permalink btn-pagination small-pagination" href="<?php the_permalink(); ?>" aria-label="Ver la imagen">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up-right-circle" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.854 10.803a.5.5 0 1 1-.708-.707L9.243 6H6.475a.5.5 0 1 1 0-1h3.975a.5.5 0 0 1 .5.5v3.975a.5.5 0 1 1-1 0V6.707z"/>
            </svg>
        </a>
    </header>
    <div class="post-body__content">
        <div class="post--date">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar2-week" viewBox="0 0 16 16">
                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M2 2a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1z"/>
                <path d="M2.5 4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H3a.5.5 0 0 1-.5-.5zM11 7.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm-3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm-5 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z"/>
            </svg>
            <p><?php echo get_the_date( 'F j, Y' ); ?></p>
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
</article>