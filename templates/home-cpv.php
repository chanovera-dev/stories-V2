<?php
/**
 * Template name: Inicio conocer para vivir
 */
get_header(); ?>

<div id="main" class="site-main" role="main"> 
    <article class="article single-page" id="<?php the_ID(); ?>">
        <section class="block">
            <div class="is-layout-constrained">
                <?php the_content(); ?>
            </div>
        </section>
    </article>
</div>

<?php get_footer(); ?>