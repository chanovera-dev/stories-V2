<div id="main" class="site-main" role="main">
    <article class="article single-post" id="<?php the_ID(); ?>">
        <header class="block">
            <div class="content">
                <?php
                    if ( has_post_thumbnail() ) {
                        echo get_the_post_thumbnail( null, 'full', [ 'class' => 'background-hero', 'alt'   => get_the_title(), 'loading' => 'lazy', 'data-speed' => '0.25' ] );
                    }

                    the_title( '<h1 class="page-title">', '</h1>' );
                    echo '<div class="metadata"><div class="date">' . get_the_date() . '</div>';
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
            <?php
                foreach ( [ 'categories', 'content', 'tags', 'author', 'single-post-pagination' ] as $part ) {
                    get_template_part( 'templates/single/' . $part );
                }
            ?>
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