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
                    <?= '<a href="' . esc_url( get_post_format_link( 'image' ) ) . '" class="post-tag">' . stories_get_metadata_icon('image') . esc_html( __('Imagen', 'core') ) . '</a>'; ?>
                </div>
                <?php
                    the_title( '<h1 class="page-title">', '</h1>' );
                    echo '<div class="metadata"><div class="date">' . stories_get_metadata_icon('date') . get_the_date() . '</div>';
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