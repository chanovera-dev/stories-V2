<?php
/**
 * Template part for displaying post content in single.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @since 2.0.0
 * @version 2.0.0
 */

?>
<div id="main" class="site-main" role="main">
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header class="block entry-header">
            <?php
                if ( has_post_thumbnail() ) {
                    echo get_the_post_thumbnail( null, 'full', [ 'class' => 'background-hero', 'alt'   => get_the_title(), 'loading' => 'lazy', 'data-speed' => '0.25' ] );
                    echo '<div class="backdrop"></div>';
                }
            ?>
            <div class="content">
                <div class="category post-tags">
                    <?php
                        $format_svg = '<svg width="16" height="16" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M2.5 1H12.5C13.3284 1 14 1.67157 14 2.5V12.5C14 13.3284 13.3284 14 12.5 14H2.5C1.67157 14 1 13.3284 1 12.5V2.5C1 1.67157 1.67157 1 2.5 1ZM2.5 2C2.22386 2 2 2.22386 2 2.5V8.3636L3.6818 6.6818C3.76809 6.59551 3.88572 6.54797 4.00774 6.55007C4.12975 6.55216 4.24568 6.60372 4.32895 6.69293L7.87355 10.4901L10.6818 7.6818C10.8575 7.50607 11.1425 7.50607 11.3182 7.6818L13 9.3636V2.5C13 2.22386 12.7761 2 12.5 2H2.5ZM2 12.5V9.6364L3.98887 7.64753L7.5311 11.4421L8.94113 13H2.5C2.22386 13 2 12.7761 2 12.5ZM12.5 13H10.155L8.48336 11.153L11 8.6364L13 10.6364V12.5C13 12.7761 12.7761 13 12.5 13ZM6.64922 5.5C6.64922 5.03013 7.03013 4.64922 7.5 4.64922C7.96987 4.64922 8.35078 5.03013 8.35078 5.5C8.35078 5.96987 7.96987 6.35078 7.5 6.35078C7.03013 6.35078 6.64922 5.96987 6.64922 5.5ZM7.5 3.74922C6.53307 3.74922 5.74922 4.53307 5.74922 5.5C5.74922 6.46693 6.53307 7.25078 7.5 7.25078C8.46693 7.25078 9.25078 6.46693 9.25078 5.5C9.25078 4.53307 8.46693 3.74922 7.5 3.74922Z" fill="currentColor"/></svg>';

                        echo '<a href="' . esc_url( get_post_format_link( 'image' ) ) . '" class="post-tag">'
                        . $format_svg . esc_html( __('Imagen', 'core') ) . '</a>';
                    ?>
                </div>
                <?php
                    the_title( '<h1 class="page-title">', '</h1>' );
                    $date_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar4-week" viewBox="0 0 16 16"><path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M2 2a1 1 0 0 0-1 1v1h14V3a1 1 0 0 0-1-1zm13 3H1v9a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1z"/><path d="M11 7.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm-3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm-2 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm-3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z"/></svg>';
                    echo '<div class="metadata"><div class="date">' . $date_icon . get_the_date() . '</div>';
                    if ( get_comments_number() > 0 ) :
                        echo '<div class="comments">';
                                if ( get_comments_number() == 1 ) {
                                    echo get_comments_number(); echo '<span></span>' . esc_html( 'Comentario', 'stories' );
                                } else {
                                    echo get_comments_number(); echo '<span></span>' . esc_html( 'Comentarios', 'stories' );
                                }
                        echo '</div>';
                    endif;
                ?>
            </div>
        </header>
        <section class="block">
            <div class="content">
                <?php
                    foreach ( [ 'content', 'tags', 'author', 'single-post-pagination' ] as $part ) {
                        get_template_part( 'templates/single/' . $part );
                    }
                ?>
            </div>
        </section>
        <?php 
            get_template_part( 'templates/single/related', 'posts' );
            if ( comments_open() ): 
        ?>
        <section class="block">
            <div class="content content-comments">
                <?php comments_template(); ?>
            </div>
        </section>
        <?php endif; ?>
    </article>
</div>