<?php
/**
 * Single Property Template
 *
 * Template for displaying a single property post (Custom Post Type: 'property').
 * This file includes the full property details such as metadata, gallery, location, and related listings.
 *
 * @package inmobiliaria
 * @since 1.0.0
 */

while ( have_posts() ) : the_post();
    $id           = get_post_meta( get_the_ID(), 'eb_public_id', true );
    $price        = get_post_meta( get_the_ID(), 'eb_price', true );
    $currency     = get_post_meta( get_the_ID(), 'eb_currency', true );
    $operation    = get_post_meta( get_the_ID(), 'eb_operation', true );
    $bedrooms     = get_post_meta( get_the_ID(), 'eb_bedrooms', true );
    $bathrooms    = get_post_meta( get_the_ID(), 'eb_bathrooms', true );
    $parking      = get_post_meta( get_the_ID(), 'eb_parking', true );
    $type         = get_post_meta( get_the_ID(), 'eb_property_type', true) ?: 'Sin tipo';
    $location     = get_post_meta( get_the_ID(), 'eb_location', true );
    $gallery      = get_post_meta( get_the_ID(), 'eb_gallery', true );
    $construction = get_post_meta( get_the_ID(), 'eb_construction_size', true );
    $lot          = get_post_meta( get_the_ID(), 'eb_lot_size', true );
    $location_js  = esc_js($location);

    if ( is_string( $gallery ) ) {
        $gallery = maybe_unserialize( $gallery );
    }

    get_header(); 
?>

<main id="main" class="site-main" role="main">
    <article class="property" id="<?php the_ID(); ?>">
        <?php require_once locate_template('templates/breadcrumbs.php' ); ?>
        <header class="block property--heading">
            <div class="content">
                <div class="property-data--wrapper">
                    <?php the_title( '<h1 class="property-title">', '</h1>' ); ?>
                    <p class="property--operation btn btn-small tag"><?php echo $operation === 'sale' ? 'En venta' : ( $operation === 'rental' ? 'En renta' : '' ); ?></p>
                    <h2 class="property--price"><?php echo esc_html( $price ); ?></h2>

                    <div class="property--metadata">
                        <div class="property--map" id="property-map"></div>

                        <ul class="property---metadata--list">
                            <li>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-heading" viewBox="0 0 16 16">
                                    <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2z"/>
                                    <path d="M3 8.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5m0-5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5z"/>
                                </svg>
                                <?php echo 'ID: ' . $id; ?>
                            </li>
                            <li>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt" viewBox="0 0 16 16">
                                    <path d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A32 32 0 0 1 8 14.58a32 32 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10"/>
                                    <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4m0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                                </svg>
                                <?php echo $location; ?>
                            </li>
                            <?php
                                if (!empty($construction) && $construction != 0) {
                                    echo '<li><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-buildings" viewBox="0 0 16 16"><path d="M14.763.075A.5.5 0 0 1 15 .5v15a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5V14h-1v1.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V10a.5.5 0 0 1 .342-.474L6 7.64V4.5a.5.5 0 0 1 .276-.447l8-4a.5.5 0 0 1 .487.022M6 8.694 1 10.36V15h5zM7 15h2v-1.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5V15h2V1.309l-7 3.5z"/><path d="M2 11h1v1H2zm2 0h1v1H4zm-2 2h1v1H2zm2 0h1v1H4zm4-4h1v1H8zm2 0h1v1h-1zm-2 2h1v1H8zm2 0h1v1h-1zm2-2h1v1h-1zm0 2h1v1h-1zM8 7h1v1H8zm2 0h1v1h-1zm2 0h1v1h-1zM8 5h1v1H8zm2 0h1v1h-1zm2 0h1v1h-1zm0-2h1v1h-1z"/></svg>';
                                    echo format_numeric($construction); 
                                    echo " m² de construcción</li>";
                                }
                                if (!empty($lot) && $lot != 0) {
                                    echo '<li class="lot"><svg fill="currentColor" width="20px" height="20px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M10,14 L19.5,14 C20.8807119,14 22,15.1192881 22,16.5 L22,19.5 C22,20.8807119 20.8807119,22 19.5,22 L4.5,22 C3.11928813,22 2,20.8807119 2,19.5 L2,4.5 C2,3.11928813 3.11928813,2 4.5,2 L7.5,2 C8.88071187,2 10,3.11928813 10,4.5 L10,14 Z M8.91464715,20 L5.5,20 C5.22385763,20 5,19.7761424 5,19.5 C5,19.2238576 5.22385763,19 5.5,19 L9,19 L9,17 L6.5,17 C6.22385763,17 6,16.7761424 6,16.5 C6,16.2238576 6.22385763,16 6.5,16 L9,16 L9,14 L5.5,14 C5.22385763,14 5,13.7761424 5,13.5 C5,13.2238576 5.22385763,13 5.5,13 L9,13 L9,11 L6.5,11 C6.22385763,11 6,10.7761424 6,10.5 C6,10.2238576 6.22385763,10 6.5,10 L9,10 L9,8 L5.5,8 C5.22385763,8 5,7.77614237 5,7.5 C5,7.22385763 5.22385763,7 5.5,7 L9,7 L9,5 L6.5,5 C6.22385763,5 6,4.77614237 6,4.5 C6,4.22385763 6.22385763,4 6.5,4 L8.91464715,4 C8.70872894,3.41740381 8.15310941,3 7.5,3 L4.5,3 C3.67157288,3 3,3.67157288 3,4.5 L3,19.5 C3,20.3284271 3.67157288,21 4.5,21 L7.5,21 C8.15310941,21 8.70872894,20.5825962 8.91464715,20 Z M10,15 L10,19.5 C10,20.062803 9.81402759,20.5821697 9.50018309,21 L19.5,21 C20.3284271,21 21,20.3284271 21,19.5 L21,16.5 C21,15.6715729 20.3284271,15 19.5,15 L10,15 Z M17,16 L19,16 C19.5522847,16 20,16.4477153 20,17 L20,19 C20,19.5522847 19.5522847,20 19,20 L17,20 C16.4477153,20 16,19.5522847 16,19 L16,17 C16,16.4477153 16.4477153,16 17,16 Z M17,17 L17,19 L19,19 L19,17 L17,17 Z"/></svg>';
                                    echo format_numeric($lot); 
                                    echo ' m² de terreno</li>';
                                }
                            ?>
                        </ul>
                    </div>
                </div>

                <div class="property--big-image--wrapper">
                    <!-- GALERY -->
                    <?php if ( !empty($gallery) && is_array($gallery) ) : ?>
                        <div class="property-gallery">
                            <div class="property-gallery--main">
                                <?php
                                    $first_image = is_array($gallery[0]) ? $gallery[0]['url'] : $gallery[0];
                                ?>
                                <img src="<?php echo esc_url( $first_image ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="active">
                            </div>

                            <div class="property-gallery--thumbs-wrapper">
                                <button class="thumbs-btn prev btn-pagination small-pagination glass-backdrop" aria-label="Anterior"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"/></svg></button>
                                <div class="property-gallery--thumbs">
                                    <?php foreach ( $gallery as $img ) :
                                        $img_url = is_array($img) ? $img['url'] : $img; ?>
                                        <img src="<?php echo esc_url( $img_url ); ?>" alt="" class="thumb">
                                    <?php endforeach; ?>
                                </div>
                                <button class="thumbs-btn next btn-pagination small-pagination glass-backdrop" aria-label="Siguiente"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-circle" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z"/></svg></button>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </header>
        <section class="block property--details">
            <div class="content">
                <h2 class="title-section"><?php esc_html_e( 'Detalles de la propiedad', 'inmobiliaria' ); ?></h2>
            </div>
            <div class="content details">
                <div class="property--metadata">
                    <ul class="property--metadata--list">
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-heading" viewBox="0 0 16 16">
                                <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2z"/>
                                <path d="M3 8.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5m0-5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5z"/>
                            </svg>
                            <?php echo 'ID: ' . $id; ?>
                        </li>
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt" viewBox="0 0 16 16">
                                <path d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A32 32 0 0 1 8 14.58a32 32 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10"/>
                                <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4m0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                            </svg>
                            <?php echo $location; ?>
                        </li>
                        <li>
                            <?php
                                echo '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20.5391 8.67606V15.5524C20.5512 15.8014 20.4327 16.0559 20.1845 16.196L13.0531 20.2197C12.4152 20.5797 11.6357 20.5807 10.9969 20.2223L3.82016 16.1968C3.5659 16.0542 3.44711 15.7917 3.46487 15.5374V8.69449C3.44687 8.44374 3.56156 8.18452 3.80996 8.0397L10.9664 3.86752C11.6207 3.48606 12.4299 3.4871 13.0832 3.87025L20.1945 8.04063C20.4357 8.18211 20.5503 8.43167 20.5391 8.67606Z" stroke="currentColor"/><path d="M3.82019 9.25312C3.3487 8.98865 3.34307 8.31197 3.81009 8.03969L10.9665 3.86751C11.6209 3.48605 12.43 3.48709 13.0834 3.87024L20.1946 8.04062C20.6596 8.31329 20.6539 8.98739 20.1845 9.25227L13.0531 13.276C12.4152 13.636 11.6357 13.637 10.9969 13.2786L3.82019 9.25312Z" stroke="currentColor"/></svg>Tipo: ';
                                echo $type;
                            ?>
                        </li>
                        <li>
                            <?php 
                                echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bag" viewBox="0 0 16 16"><path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1m3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1z"/></svg>';
                                echo $operation === 'sale' ? 'En venta' : ( $operation === 'rental' ? 'En renta' : '' ); 
                            ?>
                        </li>
                        <li>
                            <?php 
                                echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-coin" viewBox="0 0 16 16"><path d="M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518z"/><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/><path d="M8 13.5a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11m0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12"/></svg>';
                                echo 'Precio: '; 
                                echo esc_html( $price );     
                            ?>
                        </li>
                        <?php
                            if (!empty($bedrooms) && $bedrooms != 0) {
                                echo '<li class="bedroom"><svg xmlns="http://www.w3.org/2000/svg" class="ionicon" fill="currentColor" width="19px" height="19px" viewBox="0 0 512 512"><path d="M384 240H96V136a40.12 40.12 0 0140-40h240a40.12 40.12 0 0140 40v104zM48 416V304a64.19 64.19 0 0164-64h288a64.19 64.19 0 0164 64v112" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="20"/><path d="M48 416v-8a24.07 24.07 0 0124-24h368a24.07 24.07 0 0124 24v8M112 240v-16a32.09 32.09 0 0132-32h80a32.09 32.09 0 0132 32v16M256 240v-16a32.09 32.09 0 0132-32h80a32.09 32.09 0 0132 32v16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="20"/></svg>';
                                echo $bedrooms;
                                echo ( $bedrooms < 2 ) ? ' recámara' : ' recámaras';
                                echo '</li>';
                            }
                            if (!empty($bathrooms) && $bathrooms != 0) {
                                echo '<li><svg fill="currentColor" width="16" height="16" viewBox="0 0 512 512" id="Layer_1" enable-background="new 0 0 512 512" xmlns="http://www.w3.org/2000/svg"><g><path d="m496 288c-38.154 0-437.487 0-448 0v-56h32c8.837 0 16-7.164 16-16v-40c0-8.836-7.163-16-16-16s-16 7.164-16 16v24h-16v-138.745c0-25.903 31.562-39.064 49.941-20.686l16.94 16.94c-13.424 23.401-10.164 53.835 9.805 73.805l8 8c6.247 6.248 16.379 6.249 22.627 0l64-64c6.249-6.248 6.249-16.379 0-22.627l-8-8c-20.35-20.351-50.837-23.06-73.817-9.817l-16.928-16.928c-11.57-11.57-26.952-17.942-43.313-17.942-33.776 0-61.255 27.479-61.255 61.255v226.745c-8.837 0-16 7.164-16 16s7.163 16 16 16v32c0 43.889 19.742 83.247 50.806 109.681l-22.338 23.229c-9.803 10.193-2.445 27.09 11.53 27.09 4.199 0 8.394-1.644 11.534-4.91l26.218-27.263c19.844 10.326 42.376 16.173 66.25 16.173h192c23.874 0 46.406-5.847 66.25-16.173l26.218 27.263c6.106 6.35 16.234 6.585 22.623.442 6.369-6.125 6.566-16.254.441-22.623l-22.338-23.229c31.064-26.433 50.806-65.791 50.806-109.68v-32c8.837 0 16-7.164 16-16s-7.163-16-16-16zm-310.89-223.738-40.845 40.845c-8.246-11.427-7.23-27.515 3.048-37.794 10.378-10.377 26.461-11.259 37.797-3.051zm278.89 287.738c0 61.757-50.243 112-112 112h-192c-61.757 0-112-50.243-112-112v-32h416z"/></g></svg>';
                                echo $bathrooms;
                                echo ( $bathrooms < 2 ) ? ' baño' : ' baños';
                                echo '</li>';
                            }
                            if (!empty($parking) && $parking != 0) {
                                echo '<li class="parking"><svg xmlns="http://www.w3.org/2000/svg" class="ionicon" fill="currentColor" width="21" height="21" viewBox="0 0 512 512"><path d="M469.71 234.6c-7.33-9.73-34.56-16.43-46.08-33.94s-20.95-55.43-50.27-70S288 112 256 112s-88 4-117.36 18.63-38.75 52.52-50.27 70-38.75 24.24-46.08 33.97S29.8 305.84 32.94 336s9 48 9 48h86c14.08 0 18.66-5.29 47.46-8 31.6-3 62.6-4 80.6-4s50 1 81.58 4c28.8 2.73 33.53 8 47.46 8h85s5.86-17.84 9-48-2.04-91.67-9.33-101.4zM400 384h56v16h-56zM56 384h56v16H56z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="20"/><path d="M364.47 309.16c-5.91-6.83-25.17-12.53-50.67-16.35S279 288 256.2 288s-33.17 1.64-57.61 4.81-42.79 8.81-50.66 16.35C136.12 320.6 153.42 333.44 167 335c13.16 1.5 39.47.95 89.31.95s76.15.55 89.31-.95c13.56-1.65 29.62-13.6 18.85-25.84zM431.57 243.05a3.23 3.23 0 00-3.1-3c-11.81-.42-23.8.42-45.07 6.69a93.88 93.88 0 00-30.08 15.06c-2.28 1.78-1.47 6.59 1.39 7.1a455.32 455.32 0 0052.82 3.1c10.59 0 21.52-3 23.55-12.44a52.41 52.41 0 00.49-16.51zM80.43 243.05a3.23 3.23 0 013.1-3c11.81-.42 23.8.42 45.07 6.69a93.88 93.88 0 0130.08 15.06c2.28 1.78 1.47 6.59-1.39 7.1a455.32 455.32 0 01-52.82 3.1c-10.59 0-21.52-3-23.55-12.44a52.41 52.41 0 01-.49-16.51z"/><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="20" d="M432 192h16M64 192h16M78 211s46.35-12 178-12 178 12 178 12"/></svg>';
                                echo $parking; 
                                echo ( $parking < 2 ) ? ' estacionamiento' : ' estacionamientos';
                                echo '</li>';
                            }
                            if (!empty($construction) && $construction != 0) {
                                echo '<li><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-buildings" viewBox="0 0 16 16"><path d="M14.763.075A.5.5 0 0 1 15 .5v15a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5V14h-1v1.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V10a.5.5 0 0 1 .342-.474L6 7.64V4.5a.5.5 0 0 1 .276-.447l8-4a.5.5 0 0 1 .487.022M6 8.694 1 10.36V15h5zM7 15h2v-1.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5V15h2V1.309l-7 3.5z"/><path d="M2 11h1v1H2zm2 0h1v1H4zm-2 2h1v1H2zm2 0h1v1H4zm4-4h1v1H8zm2 0h1v1h-1zm-2 2h1v1H8zm2 0h1v1h-1zm2-2h1v1h-1zm0 2h1v1h-1zM8 7h1v1H8zm2 0h1v1h-1zm2 0h1v1h-1zM8 5h1v1H8zm2 0h1v1h-1zm2 0h1v1h-1zm0-2h1v1h-1z"/></svg>';
                                echo format_numeric($construction); 
                                echo " m² de construcción</li>";
                            }
                            if (!empty($lot) && $lot != 0) {
                                echo '<li class="lot"><svg fill="currentColor" width="20px" height="20px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M10,14 L19.5,14 C20.8807119,14 22,15.1192881 22,16.5 L22,19.5 C22,20.8807119 20.8807119,22 19.5,22 L4.5,22 C3.11928813,22 2,20.8807119 2,19.5 L2,4.5 C2,3.11928813 3.11928813,2 4.5,2 L7.5,2 C8.88071187,2 10,3.11928813 10,4.5 L10,14 Z M8.91464715,20 L5.5,20 C5.22385763,20 5,19.7761424 5,19.5 C5,19.2238576 5.22385763,19 5.5,19 L9,19 L9,17 L6.5,17 C6.22385763,17 6,16.7761424 6,16.5 C6,16.2238576 6.22385763,16 6.5,16 L9,16 L9,14 L5.5,14 C5.22385763,14 5,13.7761424 5,13.5 C5,13.2238576 5.22385763,13 5.5,13 L9,13 L9,11 L6.5,11 C6.22385763,11 6,10.7761424 6,10.5 C6,10.2238576 6.22385763,10 6.5,10 L9,10 L9,8 L5.5,8 C5.22385763,8 5,7.77614237 5,7.5 C5,7.22385763 5.22385763,7 5.5,7 L9,7 L9,5 L6.5,5 C6.22385763,5 6,4.77614237 6,4.5 C6,4.22385763 6.22385763,4 6.5,4 L8.91464715,4 C8.70872894,3.41740381 8.15310941,3 7.5,3 L4.5,3 C3.67157288,3 3,3.67157288 3,4.5 L3,19.5 C3,20.3284271 3.67157288,21 4.5,21 L7.5,21 C8.15310941,21 8.70872894,20.5825962 8.91464715,20 Z M10,15 L10,19.5 C10,20.062803 9.81402759,20.5821697 9.50018309,21 L19.5,21 C20.3284271,21 21,20.3284271 21,19.5 L21,16.5 C21,15.6715729 20.3284271,15 19.5,15 L10,15 Z M17,16 L19,16 C19.5522847,16 20,16.4477153 20,17 L20,19 C20,19.5522847 19.5522847,20 19,20 L17,20 C16.4477153,20 16,19.5522847 16,19 L16,17 C16,16.4477153 16.4477153,16 17,16 Z M17,17 L17,19 L19,19 L19,17 L17,17 Z"/></svg>';
                                echo format_numeric($lot); 
                                echo ' m² de terreno</li>';
                            }
                        ?>
                    </ul>                
                </div>
                <div class="is-layout-constrained">
                    <?php the_content(); ?>
                </div>
            </div>
        </section>
        <?php endwhile; ?>
        <?php
            // --- Extraer ciudad y estado desde $location ---
            $city  = '';
            $state = '';

            if ($location) {
                $parts = array_map('trim', explode(',', $location));

                // Ejemplo: Calle, Ciudad, Estado, País
                if (count($parts) >= 2) {
                    $city  = $parts[count($parts) - 3] ?? '';
                    $state = $parts[count($parts) - 2] ?? '';
                }
            }

            // --- WP_Query de propiedades relacionadas ---
            $args = array(
                'post_type'      => 'property',
                'post_status'    => 'publish',
                'posts_per_page' => 8,
                'orderby'        => 'date',
                'order'          => 'DESC',
                'no_found_rows'  => true,
                'post__not_in'   => array( get_the_ID() ), // excluir la actual
                'meta_query'     => array(
                    'relation' => 'OR',
                    array(
                        'key'     => 'eb_location',
                        'value'   => $city,
                        'compare' => 'LIKE',
                    ),
                    array(
                        'key'     => 'eb_location',
                        'value'   => $state,
                        'compare' => 'LIKE',
                    ),
                ),
            );

            $query = new WP_Query($args);

            if ($query->have_posts()) {
                echo '<section class="block related properties"><div class="content"><h2 class="title-section">Propiedades <span>cercanas</span></h2><div class="slideshow-buttons">
                <button id="related-products--backward-button" class="backward-button slideshow-button btn-pagination small-pagination">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"/></svg>
                </button>
                <button id="related-products--forward-button" class="forward-button slideshow-button btn-pagination small-pagination">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-circle" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z"/></svg>
                </button>
            </div></div>';
                echo '<div class="content slideshow--related-properties--wrapper"><div class="slideshow--related-properties"><div class="related-properties--list">';
                while ($query->have_posts()) {
                    $query->the_post();
                    get_template_part('template-parts/content', 'property');
                }
                echo '</div></div></div></section>';
            } else {
                echo '<p>No se encontraron propiedades relacionadas en la zona.</p>';
            }

            wp_reset_postdata();
        ?>
        <section class="block contact">
            <?php echo get_the_post_thumbnail( null, 'single-property--full', [ 'class' => 'background-contact background-parallax', 'alt' => get_the_title(), 'loading' => 'lazy', 'data-speed' => '0.2' ] ); ?>
            <div class="content">
                <div>
                    <h2 class="title-section">¿Aún tienes preguntas?</h2>
                    <p>Deja que nuestros expertos con experiencia te ayuden a elegir la propiedad adecuada, de forma rápida y segura, garantizando tu satisfacción.</p>
                    <?php 
                        // if ( $shortcode = get_field( 'contact_shortcode' ) ):
                        //     echo do_shortcode( $shortcode );
                        // endif; 
                        echo do_shortcode( '[contact-form-7 id="928a2fd" title="Formulario de contacto 1"]' );
                    ?>
                </div>
                <div>
                    <span>Por qué elegirnos</span>
                    <h2 class="title-section">Vive la diferencia única de Outlet de Casas</h2>
                    <p>Con más de dos décadas de experiencia en el mercado Inmobiliario Nacional, Outlet de Casas combina la experiencia local con los estándares internacionales para ofrecer a los clientes de todo el mundo un proceso inmobiliario fluido, transparente y confiable.</p>
                    <div>
                        <div class="badge"><svg width="50" height="50" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--twemoji" preserveAspectRatio="xMidYMid meet"><path fill="#006847" d="M4 5a4 4 0 0 0-4 4v18a4 4 0 0 0 4 4h7V5H4z"></path><path fill="#CE1126" d="M32 5h-7v26h7a4 4 0 0 0 4-4V9a4 4 0 0 0-4-4z"></path><path fill="#EEE" d="M11 5h14v26H11z"></path><path fill="#A6D388" d="M23 18a5 5 0 1 1-10 0h-1a6 6 0 0 0 12 0h-1z"></path><path d="M13.543 20.269a.498.498 0 0 0-.673-.218a.5.5 0 0 0-.219.673c.09.177.189.35.296.516v.001l.004.006v.001l.008.011v.001l.003.006l.001.002l.004.005v.001l.003.005v.002l.003.005l.001.002l.003.005l.001.002l.003.004l.002.003l.002.003l.001.003l.003.005l.001.001l.002.003l.003.006l.001.002l.003.005l.001.002l.007.01v.001l.005.007v.001c.052.077.105.151.161.226a.497.497 0 0 0 .697.101a.499.499 0 0 0 .103-.699a5.96 5.96 0 0 1-.43-.71zm-.497-1.582a5.282 5.282 0 0 1-.046-.833a.5.5 0 0 0-.485-.514l-.036.001a.5.5 0 0 0-.478.485a8.061 8.061 0 0 0-.001.311v.09l.001.005v.028l.001.003l-.001.002v.003l.001.002v.029l.001.003v.011l.001.019l.001.009v.011c.009.16.025.319.046.477a.5.5 0 0 0 .562.425a.503.503 0 0 0 .433-.567zm5.294 4.3l-.006.001l-.124.008a5.087 5.087 0 0 1-.71-.021a.5.5 0 0 0-.1.995l.125.011l.004.001l.002-.001l.003.001h.002l.006.001h.006l.003.001h.001l.006-.001l.003.001h.005l.006.001l.005.001h.013l.006.001h.013c.152.01.305.015.459.012h.039l.007-.001l.006.001h.015l.005-.001h.019l.006-.001h.001l.005.001l.001-.001l.006.001h.001l.005-.001h.008l.006-.001h.006l.001.001l.006-.001h.007l.005-.001h.02l.005-.001h.004l.004-.001l.004.001h.005l.004-.001h.005c.042-.001.085-.005.127-.007a.494.494 0 0 0 .453-.526a.493.493 0 0 0-.514-.473zm-2.439-.448a5.07 5.07 0 0 1-.724-.411a.495.495 0 0 0-.303-.087a.5.5 0 0 0-.263.911l.001.001l.006.003l.006.006l.006.004l.006.005c.266.181.548.34.842.476a.498.498 0 0 0 .663-.243a.498.498 0 0 0-.24-.665zm5.03-.398a.498.498 0 0 0-.248.08a5.142 5.142 0 0 1-.734.387a.498.498 0 0 0 .213.96a.528.528 0 0 0 .173-.038l.134-.06h.002l.006-.004l.002-.001l.004-.002h.004l.004-.002l.002-.002l.006-.001l.002-.002l.003-.002l.005-.002l.003-.001l.005-.002l.002-.001l.005-.002l.002-.001c.003-.002.003-.003.005-.003l.002-.001l.006-.003h.002l.006-.003l.006-.003l.002-.001l.005-.003l.002-.001l.006-.003l.001-.001l.007-.001l.007-.004l.002-.001l.004-.003h.002l.006-.004l.008-.002l.006-.004h.001l.007-.004l.001-.001l.014-.006l.007-.003l.014-.008l.008-.003l.006-.003h.001l.014-.008v-.001l.006-.002h.001l.022-.012l.007-.002l.014-.008l.007-.004h.001c.001-.001.003-.002.007-.002c.002-.003.004-.004.006-.005l.008-.004l.006-.003v-.001l.014-.006h.001l.007-.004l.001-.001l.005-.003h.001l.007-.004l.006-.003h.002l.006-.004v-.001l.007-.003l.001-.001l.006-.003v-.001l.006-.002l.002-.001l.004-.003l.003-.001l.005-.003l.002-.001l.005-.003l.002-.001l.006-.002c-.001-.001 0-.001.001-.001l.006-.003l.002-.001l.004-.003l.002-.001l.006-.003l.002-.001c0-.002.002-.002.004-.003l.003-.001l.004-.002l.003-.002l.004-.002l.003-.002l.003-.002l.003-.003l.005-.001l.004-.001l.002-.002l.004-.002l.004-.003l.002-.001l.004-.002l.004-.003l.003-.002c.002.001.003 0 .005-.001l.002-.002l.004-.002l.003-.002l.003-.002l.004-.002l.004-.003l.002-.001c.002-.001.006-.001.008-.003c-.002-.001-.002-.001 0-.001l.004-.002l.007-.005l.002-.001l.007-.003v-.001l.012-.006l.008-.005a.095.095 0 0 1 .02-.011h.001c.063-.038.128-.077.19-.117a.5.5 0 0 0 .152-.69a.554.554 0 0 0-.457-.225zm2.571-4.618l-.024.001a.5.5 0 0 0-.479.498c0 .277-.025.557-.072.829a.5.5 0 0 0 .987.168v-.005l.001-.002l.001-.002l-.001-.002l.001-.002v-.002l.001-.002v-.003l.001-.002v-.009c.002-.001.002-.003.002-.004v-.006a6.23 6.23 0 0 0 .052-.391l-.001-.001l.001-.003v-.001l.001-.004v-.011l.002-.003v-.001l-.001-.003v-.002l.001-.003v-.004l.002-.001l-.002-.003v-.004c.002-.001.002-.002.002-.003v-.019l.002-.003v-.001a6.22 6.22 0 0 0 .021-.495a.5.5 0 0 0-.498-.499zm-.443 2.696a.5.5 0 0 0-.68.196a4.853 4.853 0 0 1-.463.692a.5.5 0 0 0 .783.622c.116-.146.227-.299.33-.455c.002-.007.007-.014.011-.02l.001-.001l.004-.006v-.001l.004-.007c.002-.002.004-.004.004-.006l.001.001l.004-.006v-.001a.01.01 0 0 0 .003-.007l.002-.001l.004-.006v-.001c.002-.001.002-.003.003-.005l.001-.001l.004-.006l.001-.002l.002-.005l.003-.002l.002-.005h.002l.002-.006l.001-.002l.004-.004l.001-.002c.001-.002.001-.004.003-.005l.001-.003l.004-.003c0-.001 0-.002.002-.003v-.004l.003-.002l.003-.005l.001-.002l.003-.006l.002-.002l.002-.004l.002-.003c0-.001 0-.002.002-.003c0-.001 0-.002.002-.003l.003-.005l.001-.003l.002-.003l.002-.003c.002-.001.002-.002.002-.003c.002-.001.002-.002.002-.004l.002-.003l.003-.004l.003-.004v-.003l.002-.003l.003-.004l.003-.003v-.003l.004-.005v-.003c.002-.001.003-.002.003-.004l.002-.002l.002-.003l.003-.005l.002-.003v-.002l.004-.005c0-.001 0-.002.002-.003l.001-.003l.003-.004l.002-.004l.002-.003c.001-.001 0-.003.002-.004c0-.001 0-.002.002-.002l.002-.005l.002-.002l.002-.006l.002-.001l.002-.004l.002-.002l.002-.005l.002-.002l.002-.006c.002-.001 0-.002 0-.002l.004-.005l.001-.002l.004-.005v-.001l.003-.006l.002-.002l.002-.006v-.001l.005-.005l.003-.006v-.001l.004-.007l.002-.001l.025-.047a.498.498 0 0 0-.199-.672z" fill="#5C913B"></path><ellipse fill="#55ACEE" cx="18" cy="21" rx="3" ry="1"></ellipse><ellipse fill="#FFCC4D" cx="18.5" cy="21" rx="1.5" ry="1"></ellipse><path fill="#5C913B" d="M19.117 21.274a.503.503 0 0 1-.44-.739c.16-.294.328-.561.502-.795a10 10 0 0 1-.43.009c-.276 0-.528-.225-.528-.501s.195-.5.472-.5l.082.001c.296 0 .6-.018.872-.043a.49.49 0 0 1 .41.156c.014-.011.028-.022.043-.031c.1-.066.193-.119.28-.161a.49.49 0 0 1 .211-.094l.036-.007c.188-.061.32-.069.373-.069a.498.498 0 0 1 .477.647c-.082.266-.265.326-.586.39a1.2 1.2 0 0 0-.238.128a.507.507 0 0 1-.599-.034a.499.499 0 0 1-.002.614c-.17.217-.337.475-.496.768a.498.498 0 0 1-.439.261zm-1.42-1.589a.497.497 0 0 1-.066-.004a6.98 6.98 0 0 1-1.056-.221a.5.5 0 0 1-.337-.622l.006-.02l-.012.023a.5.5 0 0 1-.681.192a4.126 4.126 0 0 1-.907-.681a.5.5 0 0 1 .707-.707c.197.197.428.37.688.515a.5.5 0 0 1 .229.597a.5.5 0 0 1 .59-.256c.276.082.579.145.9.188a.5.5 0 0 1-.061.996zm-2.452-2.339c-.426 0-.977-.165-1.311-.559c-.512-.604-.813-1.379-.767-1.973c.012-.159-.143-.287-.295-.327c-.087-.023-.24-.037-.312.118a.25.25 0 0 1-.454-.21c.156-.339.506-.49.892-.392c.358.093.701.415.667.85c-.036.462.226 1.109.65 1.61c.223.264.611.371.875.381c.215.011.324-.038.347-.059c-.056-.133-.797-.523-1.113-.689c-.269-.141-.349-.335-.369-.472c-.067-.455.4-.916.852-1.36c.159-.157.31-.305.392-.414c.093-.123.078-.205.06-.256c-.069-.187-.368-.372-.728-.452c-.333-.074-.558-.235-.668-.479c-.145-.321-.068-.741.234-1.285a.25.25 0 1 1 .437.243c-.285.512-.257.744-.215.837c.042.092.149.157.32.195c.423.094.932.345 1.088.767c.089.241.044.501-.128.73c-.104.139-.268.3-.441.471c-.258.254-.739.727-.708.931c.006.042.061.079.107.102c.751.394 1.25.679 1.352 1.028a.456.456 0 0 1-.042.359c-.097.169-.299.273-.585.299c-.043.004-.09.006-.137.006z"></path><ellipse fill="#FFCC4D" cx="19.5" cy="18" rx=".5" ry="1"></ellipse><path fill="#FFCC4D" d="M17.292 17.188c0 .288-.345.521-.771.521c-.425 0-.771-.233-.771-.521s.345-.521.771-.521c.425 0 .771.233.771.521zm-1.187-4.627c.05.212-.227.46-.619.553c-.392.093-.75-.004-.801-.216c-.05-.213.227-.461.618-.554c.393-.092.752.004.802.217z"></path><path fill="#C1694F" d="M22.533 17.955c.09.07.243-.729.22-.978c0-.017-.029-.546.083-.924c.069-.128.073-1.083-.033-1.334c.084-.007.185-.034.197-.136c-.388.143-.479-.817-.852-1.369c-.362-.553-.811-.875-1.28-1.211a.979.979 0 0 1 .162-.27c-.575.288-1.471-.755-2.795-.677c-.297.029-.438.047-.514.229c-.247.02-.498.076-.498.401c0 .078.071.22.229.221c.216.063.392.014.539.316l.039.312s-.193-.247-.299-.286l.065-.133c-.1-.058-.277-.011-.277-.011s-.385-.18-.694-.132l-.06-.25c-.054.154-.175.146-.192.291c-.034-.104-.079-.233-.111-.337c-.109.148-.077.308-.116.462c-.042.036-.128.037-.15-.062c-.011-.122-.026-.133-.026-.278c-.078.137-.172.204-.203.439l-.083-.26c.003.307-.261.49-.511.707c-.071.13.011.131.017.198l.132.066l.237-.017c.039.049.007.053.11.084c.276.077.62-.254.89.267c-.124.104-.249.347-.209.393c.05 0-.1.07.102.006c-.21.204-.352.473-.352.489c-.024.058.084-.008.062.097l.05-.006c-.479.518-.016 1.075-.067 1.374c.08.129.09-.003.19-.016c.084.368.326.591.474.882l-.312.003c.007.138.132.269.231.39l-.209.066a1.128 1.128 0 0 0-.352.274c-.069.168.333.208.527.238l-.007.203c.303.029.653-.061.653-.078l.076-.059l.171.094c.057 0 .146-.228.105-.403c.11.131.214.342.324.474l.103-.014c.094.149.223.297.317.446l.105.04c.061-.021.113-.028.146-.148l.048.084l.166-.114l.116-.023l.087.142c.051-.019.101-.13.104-.248c.052.103.066.095.104.122l.077-.162l.415.388l.314.018c.112.076.419.124.471.001c.252.108.549-.014.534-.134c.232.092.589.03.589.015c.043-.005.153-.113.049-.194c-.767-.534-1.904-1.418-2.343-1.631c0-.158-.119-.727-.247-.883l.104-.174c.299.279.407.252.566.296c.17.135.229.34.399.527l.152.028a2.583 2.583 0 0 0-.554-.873c.164.082.436.301.618.46c.12.201.155.361.265.613c.08.051.162.238.151.083c-.019-.291-.224-.752-.224-.785c.326.258.322.66.421.905c.083.124.125.29.161.251l-.096-.756l-.056-.277c.241.139.327.669.44 1.305c0 .101.041.212.035.277c.052.064.111.241.11.136c0 0 0-1.034-.071-1.271c-.018-.127.03-.333.03-.333c.088.429.182.894.271 1.322v.315l.132.133c.025-.26.055-.518.081-.776l-.099-.925v-.264c-.002-.093.085-.076.14.03c.013.227.013.404.022.63c.039.258.066.447.085.776c.011.266.023.904.079.893z"></path></svg></div>
                        <h3>Presencia Nacional</h3>
                        <p>Operamos en más de 25 estados, ofreciendo a compradores e inversionistas acceso a las mejores propiedades del país, con expertos especializados en cada región.</p>
                    </div>
                    <div>
                        <div class="badge"><svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-card-checklist" viewBox="0 0 16 16"><path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2z"/><path d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0M7 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0"/></svg></div>
                        <h3>Solo propiedades verificadas</h3>
                        <p>Todas las propiedades son rigurosamente verificadas para garantizar su autenticidad y cumplimiento legal, asegurando transacciones seguras y libres de fraudes.</p>
                    </div>
                    <div>
                        <div class="badge"><svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-translate" viewBox="0 0 16 16"><path d="M0 2a2 2 0 0 1 2-2h7a2 2 0 0 1 2 2v3h3a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-3H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zm7.138 9.995q.289.451.63.846c-.748.575-1.673 1.001-2.768 1.292.178.217.451.635.555.867 1.125-.359 2.08-.844 2.886-1.494.777.665 1.739 1.165 2.93 1.472.133-.254.414-.673.629-.89-1.125-.253-2.057-.694-2.82-1.284.681-.747 1.222-1.651 1.621-2.757H14V8h-3v1.047h.765c-.318.844-.74 1.546-1.272 2.13a6 6 0 0 1-.415-.492 2 2 0 0 1-.94.31"/></svg></div>
                        <h3>Soporte multilingüe</h3>
                        <p>Nuestros agentes hablan varios idiomas para hacer que tu experiencia inmobiliaria sea fluida y personalizada, sin importar de dónde seas.</p>
                    </div>
                </div>
            </div>
        </section>
    </article>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const mapContainer = document.getElementById('property-map');
    const propertyLocation = "<?php echo esc_js($location); ?>";

    if (!propertyLocation) return;

    // Extraer partes (ciudad y estado si están en la dirección)
    const parts = propertyLocation.split(',').map(p => p.trim());
    const city = parts.length >= 2 ? parts[parts.length - 2] : null;
    const state = parts.length >= 1 ? parts[parts.length - 1] : null;

    // Función para mostrar un mapa embebido con coordenadas
    const renderMap = (lat, lon, label = '') => {
        mapContainer.innerHTML = `
            <iframe
                width="100%"
                height="300"
                style="border:0"
                loading="lazy"
                allowfullscreen
                src="https://www.google.com/maps?q=${lat},${lon}&hl=es;z=12&output=embed"
                title="Mapa ${label}">
            </iframe>
        `;
    };

    // Función genérica para buscar con Nominatim
    const fetchCoords = async (query) => {
        const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`);
        const data = await response.json();
        return (data && data.length > 0) ? { lat: data[0].lat, lon: data[0].lon } : null;
    };

    // Secuencia de búsqueda: dirección → ciudad → estado → México
    (async () => {
        let coords = await fetchCoords(propertyLocation);

        if (!coords && city) {
            console.warn(`Ubicación no encontrada, intentando ciudad: ${city}`);
            coords = await fetchCoords(city);
        }

        if (!coords && state) {
            console.warn(`Ciudad no encontrada, intentando estado: ${state}`);
            coords = await fetchCoords(state);
        }

        if (!coords) {
            console.warn("Usando mapa general de México.");
            coords = { lat: 23.6345, lon: -102.5528 }; // Centro de México
        }

        renderMap(coords.lat, coords.lon, propertyLocation);
    })().catch(err => {
        console.error("Error cargando mapa:", err);
        mapContainer.innerHTML = "<p>Error al cargar el mapa.</p>";
    });
});
</script>
<?php get_footer(); ?>