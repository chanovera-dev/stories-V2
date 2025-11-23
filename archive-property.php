<?php
/**
 * Property Archive Template
 *
 * Template for displaying the property archive page (Custom Post Type: 'property').
 * This file handles the property listing loop, filters, pagination, and AJAX-based dynamic loading.
 *
 * @package inmobiliaria
 * @since 1.0.0
 *
 * Template Name: Propiedades */
$locations = get_property_locations();
set_query_var('locations', $locations);

get_header(); ?>

<main id="main" class="site-main" role="main">

    <?php wp_breadcrumbs(); ?>

    <!-- Archive Posts Section -->
    <section class="block posts--body">
        <div class="content">
            <?php     
                get_template_part('templates/archive-property/filter', 'properties');
                echo '<div class="loop">';
                get_template_part('templates/archive-property/properties', 'list');
                echo '</div>';
            ?>
        </div>
    </section>

</main><!-- .site-main -->

<?php get_footer(); ?>