<?php
/**
 * Template name: Inicio conocer para vivir
 */
get_header(); ?>

<div class="clouds--wrapper">
    <div class="clouds">
        <div class="c1 one"></div>
        <div class="c1 two"></div>
        <div class="c1 three"></div>
        <div class="c1 four"></div>
        <div class="c2 one"></div>
        <div class="c2 two"></div>
        <div class="c2 three"></div>
        <div class="c2 four"></div>
    </div>
</div>
<div class="stars"></div>


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