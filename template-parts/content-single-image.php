<div id="main" class="site-main" role="main">
    <article class="article single-post" id="<?php the_ID(); ?>">
        <header class="block">
            <div class="content">
                <?php
                    if ( has_post_thumbnail() ) {
                        echo get_the_post_thumbnail( null, 'full', [ 'class' => 'background-image', 'alt'   => get_the_title(), 'loading' => 'lazy' ] );
                    }
                ?>
            </div>
        </header>
        <section class="block image-metadata">
            <?php
                foreach ( [ 'date-categories-tags', 'author', 'single-post-pagination' ] as $part ) {
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