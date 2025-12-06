<?php
/**
 * Template name: Inicio
 *
 * @package stories
 * @since 2.0.0
 */
get_header(); ?>

<main id="main" class="site-main" role="main">
    <?php
        $directory = get_template_directory() . '/templates';

        $sections = [
            'frontpage/hero',
            'frontpage/about-us',
            'frontpage/why-choose-us',
            'frontpage/featured-properties',
            'frontpage/filter-properties',
            'frontpage/testimonies',
            'frontpage/call-to-action',
            'frontpage/blog',
            'frontpage/contact',
            'frontpage/faq',
            'frontpage/interactive-map',
        ];

        foreach ( $sections as $section => $condition ) {
            if ( is_int( $section ) ) {
                $section   = $condition;
                $condition = true;
            }

            if ( $condition && file_exists( "$directory/$section.php" ) ) {
                include "$directory/$section.php";
            }
        }
    ?>
</main>

<?php get_footer();?>