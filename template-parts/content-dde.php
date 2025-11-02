<article class="post dde">
    <div class="post--body">
        <div class="post--body__header">
            <a href="<?php the_permalink();?>" class="post--permalink">
                <?php the_title('<h3 class="post--title">', '</h3>'); ?>
            </a>
            <?php the_excerpt(); ?>
        </div>
    </div>
</article>