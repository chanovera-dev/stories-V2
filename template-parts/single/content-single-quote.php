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
        <header class="block entry-header"></header>
        <section class="block">
            <div class="content">
                <?php
                    foreach ( [ 'content', 'tags-quote', 'author', 'single-post-pagination' ] as $part ) {
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