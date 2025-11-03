<header class="block">
    <div class="content">
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
        <div class="tree--wrapper">
            <img class="tree" src="<?= esc_url( get_template_directory_uri() ); ?>/assets/img/tree.webp" alt="tree" width="600" height="984" loading="lazy">
            <img class="tree-leaves" src="<?= esc_url( get_template_directory_uri() ); ?>/assets/img/tree-leaves.webp" alt="tree" width="600" height="984" loading="lazy">
            <img class="tree-leaves-2" src="<?= esc_url( get_template_directory_uri() ); ?>/assets/img/tree-leaves-2.webp" alt="tree" width="600" height="984" loading="lazy">
        </div>
        <div class="container">
            <div class="slideshow">
                <?php
                    $args = array(
                        'post_type'      => 'quote',      // Nombre del CPT
                        'posts_per_page' => 4,            // Límite de posts
                        'post_status'    => 'publish',    // Solo los publicados
                        'orderby'        => 'date',       // Orden por fecha
                        'order'          => 'DESC',       // Más recientes primero
                    );

                    $quotes_query = new WP_Query($args);

                    if ($quotes_query->have_posts()) :
                        while ($quotes_query->have_posts()) :
                            $quotes_query->the_post(); ?>

                            <article id="post-<?php the_ID(); ?>" <?php post_class('post quote-item'); ?>>
                                <div class="quote-content">
                                    <?php the_content(); ?>
                                </div>
                            </article>

                        <?php endwhile;
                        wp_reset_postdata();
                    else :
                        echo '<p>No se encontraron citas recientes.</p>';
                    endif;
                    ?>
            </div>
        </div>
    </div>
</header>