<div class="date categories tags">
    <div class="date">
        <?= stories_get_metadata_icon('date') . get_the_date(); ?>
    </div>
    <div class="categories">
        <?php
            $categories = get_the_category();
            if ( ! empty( $categories ) ) {
                foreach ( $categories as $category ) {
                    // Escapar el nombre y generar link seguro
                    $cat_name = esc_html( $category->name );
                    $cat_link = esc_url( get_category_link( $category->term_id ) );
                    $cat_icon = stories_get_metadata_icon('category');

                    echo "<a href='{$cat_link}' class='tag-type small-text'>{$cat_icon}{$cat_name}</a> ";
                }
            }
        ?>
    </div>
    <div class="tags">
        <?php
            $tags = get_the_tags();
            if ( $tags ) {
                foreach ( $tags as $tag ) {
                    echo '<a class="tag-type small-text" href="' . esc_url( get_tag_link( $tag->term_id ) ) . '">' . stories_get_metadata_icon('tag') . esc_html( $tag->name ) . '</a>';
                }
            }
        ?>
    </div>
</div>