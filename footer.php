        <footer id="main-footer">
            <section class="block middle-footer">
                <div class="content">
                    <div class="about">
                        <h3 class="title-section">Sobre Relatos y Cartas</h3>
                        <p class="section">Relatos y Cartas es un espacio dedicado a la creatividad y la expresión a través de las palabras. Aquí encontrarás cuentos, microcuentos, poemas e historias que buscan inspirar, emocionar y conectar con los lectores.</p>
                        <?php
                            wp_nav_menu(
                                array(
                                    'container_id' => 'social', 
                                    'container_class' => 'social', 
                                    'theme_location' => 'social',
                                ) 
                            );
                        ?>
                    </div>
                    <div class="other-links">
                        <div class="group-links">
                            <?php
                                $menu_id_pages = get_nav_menu_locations()['pages'];
                                $menu_pages = wp_get_nav_menu_object($menu_id_pages);
                                $items_pages = wp_get_nav_menu_items($menu_id_pages);

                                if ( ! empty($items_pages) ) {
                                    echo '
                                    <h3 class="title-section">' . $menu_pages->name . '</h3>';
                                    wp_nav_menu(
                                        array(
                                            'container' => 'nav',
                                            'container_class' => 'pages',
                                            'theme_location' => 'pages',
                                        )
                                    );
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </section>
            <section class="block end-footer">
                <div class="content">
                    <p>© <?php bloginfo( 'name' ); echo ' ' . date("Y"); ?> • <?= __('Todos los Derechos Reservados', 'stories') ?></p>
                </div>
            </section>
        </footer>
        <?php wp_footer(); ?>
    </body>
</html>