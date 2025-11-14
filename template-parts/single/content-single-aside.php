<div id="main" class="site-main" role="main">
    <article class="article single-post" id="<?php the_ID(); ?>">
        <section class="block">    
            <div class="is-layout-constrained">
                <?php the_content(); ?>
            </div>
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