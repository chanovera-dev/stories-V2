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

                if ( ! empty( $block['attrs']['ids'] ) && is_array( $block['attrs']['ids'] ) ) {
                    foreach ( $block['attrs']['ids'] as $id ) {
                        $ids[] = (int) $id;
                    }

                } else {
                    if ( ! empty( $block['innerBlocks'] ) ) {
                        foreach ( $block['innerBlocks'] as $ib ) {
                            if ( ! empty( $ib['blockName'] ) && $ib['blockName'] === 'core/image' ) {
                                if ( ! empty( $ib['attrs']['id'] ) ) {
                                    $ids[] = (int) $ib['attrs']['id'];
                                    continue;
                                }
                                if ( ! empty( $ib['innerHTML'] ) &&
                                    preg_match( '/<img[^>]+src=[\'"]([^\'"]+)[\'"]/', $ib['innerHTML'], $m )
                                ) {
                                    $aid = attachment_url_to_postid( $m[1] );
                                    if ( $aid ) $ids[] = (int) $aid;
                                }
                            } else {
                                $ids = array_merge( $ids, stories_collect_image_ids_from_blocks( array( $ib ) ) );
                            }
                        }
                    }

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
                $ids = array_merge(
                    $ids,
                    stories_collect_image_ids_from_blocks( $block['innerBlocks'] )
                );
            }
        }

        return $ids;
    }
}


/**
 * 🔥 EXTRA: Recorre cualquier valor de ACF y extrae IDs de imágenes
 */
function stories_collect_images_from_acf_value( $value ) {
    $ids = [];

    if ( empty( $value ) ) return $ids;

    // Es un ID
    if ( is_numeric( $value ) ) {
        return [ (int) $value ];
    }

    // Es URL → convertir a ID
    if ( is_string( $value ) && filter_var( $value, FILTER_VALIDATE_URL ) ) {
        $aid = attachment_url_to_postid( $value );
        return $aid ? [ $aid ] : [];
    }

    // Array → puede ser image field, gallery, repeater, flexible, etc.
    if ( is_array( $value ) ) {

        // ACF image field → array con ['ID']
        if ( isset( $value['ID'] ) ) {
            return [ (int) $value['ID'] ];
        }

        // ACF gallery field → array de arrays/IDs
        foreach ( $value as $item ) {
            $ids = array_merge( $ids, stories_collect_images_from_acf_value( $item ) );
        }
    }

    return $ids;
}


/**
 * FUNCIÓN PRINCIPAL
 */
if ( ! function_exists( 'stories_extract_gallery_images' ) ) {

    function stories_extract_gallery_images( $post_id ) {
        $content = get_post_field( 'post_content', $post_id );
        $image_ids = array();

        // 1) Bloques del editor
        if ( function_exists( 'parse_blocks' ) && ! empty( $content ) ) {
            $blocks = parse_blocks( $content );
            $image_ids = stories_collect_image_ids_from_blocks( $blocks );
        }

        // 2) Shortcode tradicional [gallery ids="1,2,3"]
        if ( empty( $image_ids ) && ! empty( $content ) ) {
            if ( preg_match_all( '/\[gallery[^\]]*ids=[\'"]?([^\'"\]]+)[\'"]?/', $content, $m ) ) {
                foreach ( $m[1] as $ids_str ) {
                    $parts = array_map( 'intval', explode( ',', $ids_str ) );
                    $image_ids = array_merge( $image_ids, $parts );
                    break;
                }
            }
        }

        // 3) get_post_galleries()
        if ( empty( $image_ids ) ) {
            $galleries = get_post_galleries( $post_id, false );
            if ( ! empty( $galleries ) ) {
                foreach ( $galleries as $gal ) {
                    foreach ( $gal as $src ) {
                        $aid = attachment_url_to_postid( $src );
                        if ( $aid ) $image_ids[] = (int) $aid;
                    }
                }
            }
        }

        /**
         * 🔥 4) NUEVO: Extraer imágenes desde todos los ACF del CPT property
         */
        if ( get_post_type( $post_id ) === 'property' && function_exists( 'get_fields' ) ) {
            $fields = get_fields( $post_id );

            if ( ! empty( $fields ) ) {
                foreach ( $fields as $field_value ) {
                    $acf_ids = stories_collect_images_from_acf_value( $field_value );
                    if ( ! empty( $acf_ids ) ) {
                        $image_ids = array_merge( $image_ids, $acf_ids );
                    }
                }
            }
        }

        // 5) Adjuntos del post
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

        // 6) Imagen destacada
        if ( empty( $image_ids ) && has_post_thumbnail( $post_id ) ) {
            $image_ids[] = (int) get_post_thumbnail_id( $post_id );
        }

        // Normalizar
        return array_values( array_unique( array_filter( $image_ids ) ) );
    }
}