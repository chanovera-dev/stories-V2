<?php
/**
 * Extrae todos los IDs de imágenes presentes dentro de bloques y shortcodes
 * del contenido de un post (galerías, imágenes individuales, innerBlocks, etc.)
 *
 * MAIN:    stories_extract_gallery_images( $post_id )
 * RETURNS: array de IDs de imágenes
 */

if ( ! function_exists( 'stories_collect_image_ids_from_blocks' ) ) {

    function stories_collect_image_ids_from_blocks( $blocks ) {
        $ids = array();

        foreach ( $blocks as $block ) {

            // --- 1) core/gallery con attrs.ids ---
            if ( ! empty( $block['blockName'] ) && $block['blockName'] === 'core/gallery' ) {

                // Caso normal: gallery con "ids"
                if ( ! empty( $block['attrs']['ids'] ) && is_array( $block['attrs']['ids'] ) ) {
                    foreach ( $block['attrs']['ids'] as $id ) {
                        $ids[] = (int) $id;
                    }
                } else {
                    // Gallery sin IDs → revisar innerBlocks
                    if ( ! empty( $block['innerBlocks'] ) ) {
                        foreach ( $block['innerBlocks'] as $ib ) {
                            if ( ! empty( $ib['blockName'] ) && $ib['blockName'] === 'core/image' ) {
                                if ( ! empty( $ib['attrs']['id'] ) ) {
                                    $ids[] = (int) $ib['attrs']['id'];
                                    continue;
                                }
                                // Extrae src desde innerHTML
                                if ( ! empty( $ib['innerHTML'] ) &&
                                    preg_match( '/<img[^>]+src=[\'"]([^\'"]+)[\'"]/', $ib['innerHTML'], $m )
                                ) {
                                    $aid = attachment_url_to_postid( $m[1] );
                                    if ( $aid ) $ids[] = (int) $aid;
                                }
                            } else {
                                // Recursivo
                                $ids = array_merge( $ids, stories_collect_image_ids_from_blocks( array( $ib ) ) );
                            }
                        }
                    }

                    // Último recurso: imágenes en innerHTML del gallery
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

            // --- 2) core/image simple ---
            if ( ! empty( $block['blockName'] ) && $block['blockName'] === 'core/image' ) {
                if ( ! empty( $block['attrs']['id'] ) ) {
                    $ids[] = (int) $block['attrs']['id'];
                } elseif ( ! empty( $block['attrs']['url'] ) ) {
                    $aid = attachment_url_to_postid( $block['attrs']['url'] );
                    if ( $aid ) $ids[] = (int) $aid;
                } elseif (
                    ! empty( $block['innerHTML'] ) &&
                    preg_match( '/<img[^>]+src=[\'"]([^\'"]+)[\'"]/', $block['innerHTML'], $m )
                ) {
                    $aid = attachment_url_to_postid( $m[1] );
                    if ( $aid ) $ids[] = (int) $aid;
                }
            }

            // --- 3) Recursividad general ---
            if ( ! empty( $block['innerBlocks'] ) ) {
                $ids = array_merge( $ids, stories_collect_image_ids_from_blocks( $block['innerBlocks'] ) );
            }
        }

        return $ids;
    }
}

/**
 * FUNCIÓN PRINCIPAL
 * Devuelve array de image IDs de un post
 */
if ( ! function_exists( 'stories_extract_gallery_images' ) ) {

    function stories_extract_gallery_images( $post_id ) {
        $content = get_post_field( 'post_content', $post_id );
        $image_ids = array();

        // 1) Bloques
        if ( function_exists( 'parse_blocks' ) && ! empty( $content ) ) {
            $blocks = parse_blocks( $content );
            $image_ids = stories_collect_image_ids_from_blocks( $blocks );
        }

        // 2) Shortcode [gallery ids="..."]
        if ( empty( $image_ids ) && ! empty( $content ) ) {
            if ( preg_match_all( '/\[gallery[^\]]*ids=[\'"]?([^\'"\]]+)[\'"]?/', $content, $m ) ) {
                foreach ( $m[1] as $ids_str ) {
                    $parts = array_map( 'intval', array_filter( array_map( 'trim', explode( ',', $ids_str ) ) ) );
                    if ( ! empty( $parts ) ) {
                        $image_ids = array_merge( $image_ids, $parts );
                        break;
                    }
                }
            }
        }

        // 3) get_post_galleries
        if ( empty( $image_ids ) && function_exists( 'get_post_galleries' ) ) {
            $galleries = get_post_galleries( $post_id, false );
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

        // 4) Adjuntos del post (por si usaron “subidos a este post”)
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

        // 5) Fallback a imagen destacada
        if ( empty( $image_ids ) && has_post_thumbnail( $post_id ) ) {
            $image_ids[] = (int) get_post_thumbnail_id( $post_id );
        }

        // Normalizar
        return array_values( array_unique( array_filter( $image_ids ) ) );
    }
}
