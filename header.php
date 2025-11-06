<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <?php /* Title tag fallback if the theme does not support title-tag */ ?>
    <?php if ( ! current_theme_supports( 'title-tag' ) ) : ?>
        <title><?php wp_title( '|', true, 'right' ); ?></title>
    <?php endif; ?>
    <?php /* Output meta description if available (escaped) */ ?>
    <?php $site_description = get_bloginfo( 'description', 'display' ); ?>
    <?php if ( $site_description ) : ?>
        <meta name="description" content="<?php echo esc_attr( $site_description ); ?>">
    <?php endif; ?>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <?php if ( function_exists( 'wp_body_open' ) ) { wp_body_open(); } ?>
    <header id="main-header" role="banner" aria-label="<?php echo esc_attr__( 'Main header', 'stories' ); ?>">
        <section class="block">
            <div class="glass-backdrop"></div>
            <div class="content">
                <div class="site-brand">
                    <?php
                        if ( ! has_custom_logo() ) {
                            /* Safe site title output */
                            printf(
                                '<a href="%s" aria-label="%s">%s</a>',
                                esc_url( home_url( '/' ) ),
                                esc_attr__( 'Home', 'stories' ),
                                esc_html( get_bloginfo( 'name' ) )
                            );
                        } else {
                            the_custom_logo();
                        }
                    ?>
                </div>
                <div class="navigation-searchform--wrapper">
                    <?php
                        $menu_html = wp_nav_menu( array(
                            'theme_location'  => 'primary',
                            'container'       => 'nav',
                            'container_class' => 'main-navigation',
                            'echo'            => false,
                            'fallback_cb'     => false,
                        ) );

                        if ( $menu_html ) {
                            // insertar el backdrop justo después de la apertura del <nav ...>
                            $backdrop = '<div class="glass-backdrop glass-bright" aria-hidden="true"></div>';
                            $menu_html = preg_replace(
                                '/(<nav\b[^>]*class=["\\\'][^"\\\']*main-navigation[^"\\\']*["\\\'][^>]*>)/i',
                                '$1' . $backdrop,
                                $menu_html,
                                1
                            );
                            echo $menu_html;
                        }
                    ?>
                    <form role="search" method="get" id="custom-searchform" class="" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <div class="section">
                            <label class="screen-reader-text" for="s"><?php esc_html__('Buscar', 'stories'); ?></label>
                            <input class="wp-block-search__input" type="text" value="" name="s" id="s" placeholder="<?php esc_html_e('Buscar', 'stories'); ?>">
                            <div class="buttons-container">
                                <button type="submit" id="searchsubmit" value="Search" aria-label="Activate the search">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                                    </svg>
                                </button>
                                <div class="close-custom-searchform" onclick="closeCustomSearchform()">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div>
                    <button id="search-mobile__button" class="search-mobile__button" onclick="openCustomSearchform()" aria-label="Open search">
                        <div class="icon--wrapper">
                            <div class="bar"></div>
                        </div>
                    </button>
                </div>
                <button id="menu-mobile__button" class="menu-mobile__button" onclick="toggleMenuMobile()">
                    <span class="bar"></span>
                </button>
            </div>
        </section>
    </header>