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
        <?php wp_breadcrumbs(); ?>
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
                <div class="is-layout-constrained">
                    <div class="post-gallery-wrapper">
                        <div class="total-images post-tag glass-backdrop glass-bright"></div>
                        <div class="post-gallery">
                            <?php foreach ( $gallery as $img ) :
                                $img_url = is_array($img) ? $img['url'] : $img; ?>
                                <div class="post-gallery-slide">
                                    <img src="<?php echo esc_url( $img_url ); ?>" alt="" loading="lazy">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="post-gallery-thumbs-container">
                            <button class="btn-pagination small-pagination" aria-label="Anterior"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"></path></svg></button>
                            <div class="post-gallery-thumbs"></div>
                            <button class="btn-pagination small-pagination" aria-label="Siguiente"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-circle" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z"></path></svg></button>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <?php endwhile; ?>
        
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