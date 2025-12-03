<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> data-id="<?php echo get_the_ID(); ?>">
    <div class="post-body">
        <header class="post-body__header">
            <div class="category post--tags">
                <?php
                    $categories = get_the_category();
                    if ( ! empty( $categories ) ) {
                        foreach ( $categories as $category ) {
                            // Escapar el nombre y generar link seguro
                            $cat_name = esc_html( $category->name );
                            $cat_link = esc_url( get_category_link( $category->term_id ) );
                            $cat_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-journal-bookmark" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M6 8V1h1v6.117L8.743 6.07a.5.5 0 0 1 .514 0L11 7.117V1h1v7a.5.5 0 0 1-.757.429L9 7.083 6.757 8.43A.5.5 0 0 1 6 8"/><path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2"/><path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z"/></svg>';

                            echo "<a href='{$cat_link}' class='post-tag small glass-backdrop glass-bright'>{$cat_icon}{$cat_name}</a> ";
                        }
                    }
                ?>
            </div>
            <?php 
                if ( has_post_thumbnail() ) {
                    echo get_the_post_thumbnail( null, 'loop-thumbnail', [ 'alt' => get_the_title(), 'loading' => 'lazy' ] );
                }
            ?>
        </header>
        <div class="post-body__content">
            <a class="post--permalink" href="<?php the_permalink();?>">
                <?php the_title('<h3 class="post--title">', '</h3>'); ?>
            </a>
        </div>
    </div>
</article>