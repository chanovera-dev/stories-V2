<article class="post image">
    <header class="post--header glass-bright">
        <?php echo get_the_post_thumbnail( null, 'post-header-thumbnail', [ 'class' => 'post-thumbnail', 'alt'   => get_the_title(), 'loading' => 'lazy' ] ); ?>
        <div class="glass-reflex"></div>
    </header>
    <div class="post--body">
        <div class="post--tags">
            <?php
                $tags = get_the_tags();
                if ( $tags ) {
                    foreach ( $tags as $tag ) {
                        echo '<a class="tag-type small-text" href="' . esc_url( get_tag_link( $tag->term_id ) ) . '"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-tag" viewBox="0 0 16 16"><path d="M6 4.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m-1 0a.5.5 0 1 0-1 0 .5.5 0 0 0 1 0"/><path d="M2 1h4.586a1 1 0 0 1 .707.293l7 7a1 1 0 0 1 0 1.414l-4.586 4.586a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 1 6.586V2a1 1 0 0 1 1-1m0 5.586 7 7L13.586 9l-7-7H2z"/></svg>' . esc_html( $tag->name ) . '</a>';
                    }
                }
            ?>
        </div>
    </div>
</article>