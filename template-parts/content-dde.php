<article class="post">
    <div class="post--body">
        <div class="post--body__content">
            <a href="<?php the_permalink();?>" class="post--permalink">
                <?php the_title('<h3 class="post--title">', '</h3>'); ?>
            </a>
            <div class="text">
                <?php the_excerpt(); ?>
            </div>
        </div>
    </div>
</article>