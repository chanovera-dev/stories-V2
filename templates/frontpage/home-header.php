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
            <img class="tree" src="<?= esc_url( get_template_directory_uri() ); ?>/assets/img/tree-min.webp" alt="tree" width="600" height="984" loading="lazy">
            <img class="tree-leaves" src="<?= esc_url( get_template_directory_uri() ); ?>/assets/img/tree-leaves-min.webp" alt="tree" width="600" height="984" loading="lazy">
            <img class="tree-leaves-2" src="<?= esc_url( get_template_directory_uri() ); ?>/assets/img/tree-leaves-2-min.webp" alt="tree" width="600" height="984" loading="lazy">
        </div>
        <div class="container">
            <div class="slideshow--wrapper">
                <div class="slideshow">
                    <?php
                        $args = array(
                            'post_type'      => 'quote',  
                            'posts_per_page' => 7,        
                            'post_status'    => 'publish',
                            'orderby'        => 'date',   
                            'order'          => 'DESC',   
                        );

                        $quotes_query = new WP_Query($args);

                        if ($quotes_query->have_posts()) :
                            while ($quotes_query->have_posts()) :
                                $quotes_query->the_post(); ?>

                                <article id="post-<?php the_ID(); ?>" <?php post_class('quote-item'); ?>>
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
            <div class="slideshow-bullets-wrapper">
                <button class="slideshow-prev btn-pagination small-pagination" aria-label="siguiente diapositiva">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"></path></svg>
                </button>
                <ul class="slideshow-bullets"></ul>
                <button class="slideshow-next btn-pagination small-pagination" aria-label="anterior diapositiva">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-circle" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z"></path></svg>
                </button>
            </div>
        </div>
    </div>
</header>