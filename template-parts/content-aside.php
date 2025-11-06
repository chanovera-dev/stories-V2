<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="post--body">
        <div class="post--body__content">
            <div class="text">
                <?php the_content(); ?>
            </div>
            <div class="date">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar2-week" viewBox="0 0 16 16">
                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M2 2a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1z"/>
                    <path d="M2.5 4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H3a.5.5 0 0 1-.5-.5zM11 7.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm-3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm-5 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z"/>
                </svg>
                <?php the_date( 'F j, Y', '<p>', '</p>' ); ?>
            </div>
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

                    // 🔸 SVGs personalizados para cada formato
                    $format_svgs = [
                        'standard' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-text" viewBox="0 0 16 16"><path d="M5 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5M3 2a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1z"/></svg>',
                        'aside'    => '<svg width="16" height="16" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M11.2895 2.75C11.4964 2.74979 11.6821 2.87701 11.7565 3.07003L14.9664 11.39C15.0657 11.6477 14.9375 11.9371 14.6798 12.0365C14.4222 12.1359 14.1328 12.0076 14.0334 11.75L12.9822 9.02537H9.61106L8.56672 11.749C8.46786 12.0068 8.1787 12.1357 7.92086 12.0369C7.66302 11.938 7.53414 11.6488 7.63301 11.391L10.8232 3.07099C10.8972 2.87782 11.0826 2.75021 11.2895 2.75ZM11.2915 4.64284L12.6543 8.17537H9.93698L11.2915 4.64284ZM2.89895 5.20703C1.25818 5.20703 0.00915527 6.68569 0.00915527 8.60972C0.00915527 10.6337 1.35818 12.0124 2.89895 12.0124C3.72141 12.0124 4.57438 11.6692 5.15427 11.0219V11.53C5.15427 11.7785 5.35574 11.98 5.60427 11.98C5.8528 11.98 6.05427 11.7785 6.05427 11.53V5.72C6.05427 5.47147 5.8528 5.27 5.60427 5.27C5.35574 5.27 5.15427 5.47147 5.15427 5.72V6.22317C4.60543 5.60095 3.79236 5.20703 2.89895 5.20703ZM5.15427 9.79823V7.30195C4.76393 6.58101 3.94144 6.05757 3.08675 6.05757C2.10885 6.05757 1.03503 6.96581 1.03503 8.60955C1.03503 10.1533 2.00885 11.1615 3.08675 11.1615C3.97011 11.1615 4.77195 10.4952 5.15427 9.79823Z" fill="currentColor"/></svg>',
                        'gallery'  => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-images" viewBox="0 0 16 16"><path d="M4.502 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/><path d="M14.002 3a1 1 0 0 1 1 1v7.5a.5.5 0 0 1-.5.5H13V4a1 1 0 0 1 1-1z"/><path d="M2 4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zM1 6a1 1 0 0 1 1-1h9a1 1 0 0 1 1 1v5.5l-2.5-2.5a.5.5 0 0 0-.708 0L6.5 12l-2-2L1 13V6z"/></svg>',
                        'image'    => '<svg width="16" height="16" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M2.5 1H12.5C13.3284 1 14 1.67157 14 2.5V12.5C14 13.3284 13.3284 14 12.5 14H2.5C1.67157 14 1 13.3284 1 12.5V2.5C1 1.67157 1.67157 1 2.5 1ZM2.5 2C2.22386 2 2 2.22386 2 2.5V8.3636L3.6818 6.6818C3.76809 6.59551 3.88572 6.54797 4.00774 6.55007C4.12975 6.55216 4.24568 6.60372 4.32895 6.69293L7.87355 10.4901L10.6818 7.6818C10.8575 7.50607 11.1425 7.50607 11.3182 7.6818L13 9.3636V2.5C13 2.22386 12.7761 2 12.5 2H2.5ZM2 12.5V9.6364L3.98887 7.64753L7.5311 11.4421L8.94113 13H2.5C2.22386 13 2 12.7761 2 12.5ZM12.5 13H10.155L8.48336 11.153L11 8.6364L13 10.6364V12.5C13 12.7761 12.7761 13 12.5 13ZM6.64922 5.5C6.64922 5.03013 7.03013 4.64922 7.5 4.64922C7.96987 4.64922 8.35078 5.03013 8.35078 5.5C8.35078 5.96987 7.96987 6.35078 7.5 6.35078C7.03013 6.35078 6.64922 5.96987 6.64922 5.5ZM7.5 3.74922C6.53307 3.74922 5.74922 4.53307 5.74922 5.5C5.74922 6.46693 6.53307 7.25078 7.5 7.25078C8.46693 7.25078 9.25078 6.46693 9.25078 5.5C9.25078 4.53307 8.46693 3.74922 7.5 3.74922Z" fill="currentColor"/></svg>',
                        'video'    => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-camera-video" viewBox="0 0 16 16"><path d="M0 5a2 2 0 0 1 2-2h7a2 2 0 0 1 2 2v1.528l2.243-1.122A1 1 0 0 1 15 6.29v3.42a1 1 0 0 1-.757.884L11 9.472V11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2z"/></svg>',
                        'quote'    => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-quote" viewBox="0 0 16 16"><path d="M2 1a1 1 0 0 0-1 1v11.586l2-2H14a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM1 0h13a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H4.414L1 15.414V1a1 1 0 0 1 1-1z"/><path d="M7.066 5.76a.5.5 0 0 1 .694.72c-.294.283-.76.894-.76 1.52 0 .484.329.75.62.75.333 0 .62-.269.62-.75a.5.5 0 0 1 1 0c0 .986-.757 1.75-1.62 1.75-.924 0-1.62-.75-1.62-1.75 0-.892.544-1.689 1.066-2.24z"/></svg>',
                        'audio'    => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-music-note-beamed" viewBox="0 0 16 16"><path d="M6 13c0 1.105-.672 2-1.5 2S3 14.105 3 13s.672-2 1.5-2S6 11.895 6 13z"/><path fill-rule="evenodd" d="M9 3v10h1V4h4V3H9z"/></svg>',
                        'link'     => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-link-45deg" viewBox="0 0 16 16"><path d="M4.715 6.542a3.5 3.5 0 0 1 4.95 0l.829.828a.5.5 0 0 0 .708-.708l-.829-.828a4.5 4.5 0 0 0-6.364 6.364l.829.828a.5.5 0 1 0 .708-.708l-.829-.828a3.5 3.5 0 0 1 0-4.95z"/></svg>',
                        'chat'     => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-dots" viewBox="0 0 16 16"><path d="M2 2a2 2 0 0 0-2 2v9.586l2-2H14a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H2z"/><path d="M3 7a1 1 0 1 1 2 0 1 1 0 0 1-2 0m4 0a1 1 0 1 1 2 0 1 1 0 0 1-2 0m4 0a1 1 0 1 1 2 0 1 1 0 0 1-2 0"/></svg>',
                        'status'   => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-broadcast" viewBox="0 0 16 16"><path d="M3.05 3.05a7 7 0 0 1 9.9 0 .5.5 0 1 1-.707.707 6 6 0 0 0-8.486 0 .5.5 0 1 1-.707-.707z"/><path d="M4.93 4.93a5 5 0 0 1 7.07 0 .5.5 0 1 1-.707.707 4 4 0 0 0-5.657 0 .5.5 0 1 1-.707-.707z"/><path d="M7.757 7.757a2 2 0 1 1 2.828 0 2 2 0 0 1-2.828 0z"/></svg>',
                    ];

                    $format_label = $formats_labels[$format] ?? ucfirst($format);
                    $format_svg   = $format_svgs[$format] ?? $format_svgs['standard'];
                    $format_link  = ( 'standard' !== $format ) ? get_post_format_link( $format ) : get_permalink( get_option('page_for_posts') );

                    echo '<a href="' . esc_url( $format_link ) . '" class="post-format-label tag-type small-text">'
                        . $format_svg . esc_html( $format_label ) . '</a>';

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