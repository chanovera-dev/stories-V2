<nav class="content post-navigation" aria-label="<?= esc_attr__( 'Navegación de publicaciones', 'stories' ); ?>">
    <div class="left">
        <?php
            $next_post = get_next_post();
            if ($next_post) :
        ?>
        <a href="<?= esc_url( get_permalink( $next_post->ID ) ); ?>" class="next-post-link">
            <p class="pagination-indicator">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"/>
                </svg>
                <?= esc_html__('Siguiente', 'stories'); ?>
            </p>
            <p class="title-post"><?= esc_html( $next_post->post_title ); ?></p>
        </a>
        <?php endif; ?>
    </div>
    <div class="right">
        <?php
            $prev_post = get_previous_post();
            if ($prev_post) :
        ?>

        <a href="<?= esc_url( get_permalink( $prev_post->ID ) ); ?>" class="previous-post-link">
            <p class="pagination-indicator">
                <?= esc_html__('Anterior', 'stories'); ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-circle" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z"/>
                </svg>
            </p>
            <p class="title-post"><?= esc_html( $prev_post->post_title ); ?></p>
        </a>
        <?php endif; ?>
    </div>
</nav>