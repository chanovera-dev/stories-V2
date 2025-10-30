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
    <header id="main-header" role="banner" aria-label="<?php echo esc_attr__( 'Main header', 'stories' ); ?>"></header>