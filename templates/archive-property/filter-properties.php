<aside class="properties--filter">
    <!-- Filters -->
    <form class="property-filter-form" id="property-filters">
        <!-- Search -->
        <div class="filter">
            <input type="text" name="search" placeholder="<?php esc_html_e('Palabras clave', 'inmobiliaria'); ?>">
        </div>

        <!-- Operation -->
        <div class="menu-flex">
            <div class="filter-property"><input type="checkbox" name="operation[]" value="sale"><label><?= stories_get_metadata_icon('sale'); esc_html_e('Venta', 'inmobiliaria'); ?></label></div>
            <div class="filter-property"><input type="checkbox" name="operation[]" value="rental"><label><?= stories_get_metadata_icon('rent'); esc_html_e('Renta', 'inmobiliaria'); ?></label></div>
        </div>

        <!-- Type -->
        <div class="menu-flex">
            <div class="filter-property"><input type="checkbox" name="type[]" value="casa"><label><?= stories_get_metadata_icon('house'); esc_html_e('Casa', 'inmobiliaria'); ?></label></div>
            <div class="filter-property"><input type="checkbox" name="type[]" value="bedroom"><label><?= stories_get_metadata_icon('bedroom'); esc_html_e('Habitación', 'inmobiliaria'); ?></label></div>
            <!-- <div class="filter-property"><input type="checkbox" name="type[]" value="apartment"><label><?= stories_get_metadata_icon('construction'); esc_html_e('Depto', 'inmobiliaria'); ?></label></div> -->
            <div class="filter-property"><input type="checkbox" name="type[]" value="terreno"><label><?= stories_get_metadata_icon('garden'); esc_html_e('Terreno', 'inmobiliaria'); ?></label></div>
        </div>

        <ul class="filter-navigation menu">

            <!-- Type -->
            <!-- <li class="menu-item-has-children filter type">
                <button class="btn button-for-submenu" type="button">
                    <?php esc_html_e('Tipo', 'inmobiliaria'); ?>
                    <svg width="13" height="13" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 
                            .708.708l-6 6a.5.5 0 0 1-.708 
                            0l-6-6a.5.5 0 0 1 0-.708z"></path>
                    </svg>
                </button>
                <ul class="sub-menu">
                    <div class="backdrop"></div>
                    <li><p class="checkbox-filter-properties"><input type="checkbox" name="type[]" value="casa"><label><?php esc_html_e('Casa', 'inmobiliaria'); ?></label></p></li>
                    <li><p class="checkbox-filter-properties"><input type="checkbox" name="type[]" value="departamento"><label><?php esc_html_e('Departamento', 'inmobiliaria'); ?></label></p></li>
                    <li><p class="checkbox-filter-properties"><input type="checkbox" name="type[]" value="terreno"><label><?php esc_html_e('Terreno', 'inmobiliaria'); ?></label></p></li>
                </ul>
            </li> -->

            <!-- Location -->
            <li class="menu-item-has-children filter state">
                <button class="btn button-for-submenu" type="button">
                    <?php esc_html_e('Ubicación', 'inmobiliaria'); ?>
                    <svg width="13" height="13" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 
                            .708.708l-6 6a.5.5 0 0 1-.708 
                            0l-6-6a.5.5 0 0 1 0-.708z"></path>
                    </svg>
                </button>
                <ul class="sub-menu">
                    <div class="backdrop"></div>
                    <?php foreach ($locations as $state => $cities): ?>
                        <li class="menu-item-has-children">
                            <div class="btn wrapper-for-title">
                                <p class="checkbox-filter-properties"><input type="checkbox" name="state[]" value="<?php echo esc_attr($state); ?>"><label><?php echo esc_html($state); ?></label></p>
                                <button class="button-for-submenu" type="button">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                                    </svg>
                                </button>
                            </div>
                            <ul class="sub-menu">
                                <div class="backdrop"></div>
                                <?php foreach ($cities as $city): ?>
                                    <li><p class="checkbox-filter-properties"><input type="checkbox" name="city[]" value="<?php echo esc_attr($city); ?>"><label><?php echo esc_html($city); ?></label></p></li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>

            <!-- Rooms -->
            <li class="menu-item-has-children filter rooms">
                <button class="btn button-for-submenu" type="button">
                    <?php esc_html_e('Habitaciones', 'inmobiliaria'); ?>
                    <svg width="13" height="13" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M1.646 4.646a.5.5 0 0 1 
                            .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 
                            .708.708l-6 6a.5.5 0 0 1-.708 
                            0l-6-6a.5.5 0 0 1 0-.708z"></path>
                    </svg>
                </button>
                <ul class="sub-menu">
                    <div class="backdrop"></div>
                    <li>
                        <label for="bedrooms"><?php esc_html_e('Recámaras', 'inmobiliaria'); ?></label>
                        <div class="number-input-wrapper">
                            <button type="button" class="btn-decrease" data-target="bedrooms"><?= stories_get_metadata_icon('minus'); ?></button>
                            <input type="number" name="bedrooms" id="bedrooms" min="0" placeholder="0">
                            <button type="button" class="btn-increase" data-target="bedrooms"><?= stories_get_metadata_icon('plus'); ?></button>
                        </div>
                    </li>
                    <li>
                        <label for="bathrooms"><?php esc_html_e('Baños', 'inmobiliaria'); ?></label>
                        <div class="number-input-wrapper">
                            <button type="button" class="btn-decrease" data-target="bathrooms"><?= stories_get_metadata_icon('minus'); ?></button>
                            <input type="number" name="bathrooms" id="bathrooms" min="0" placeholder="0">
                            <button type="button" class="btn-increase" data-target="bathrooms"><?= stories_get_metadata_icon('plus'); ?></button>
                        </div>
                    </li>
                </ul>
            </li>

            <!-- Price -->
            <li class="menu-item-has-children filter price">
                <button class="btn button-for-submenu" type="button">
                    <?php esc_html_e('Precio', 'inmobiliaria'); ?>
                    <svg width="13" height="13" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"></path>
                    </svg>
                </button>

                <ul class="sub-menu">
                    <div class="backdrop"></div>
                    <li> 
                        <div>
                            <label><?php esc_html_e('Mínimo', 'inmobiliaria'); ?></label>
                            <input type="number" name="price_min" placeholder="<?php esc_html_e('Min $', 'inmobiliaria'); ?>" min="0" value="<?php echo esc_attr(get_query_var('price_range')['min'] ?? 0); ?>">
                        </div>  
                        <div>
                            <label><?php esc_html_e('Máximo', 'inmobiliaria'); ?></label>    
                            <input type="number" name="price_max" placeholder="<?php esc_html_e('Max $', 'inmobiliaria'); ?>" min="0" value="<?php echo esc_attr(get_query_var('price_range')['max'] ?? 0); ?>">
                        </div>

                        <div class="price-range">
                            <label for="price_range"><?php esc_html_e('Rango estimado:', 'inmobiliaria'); ?></label>
                            <input type="range" id="price_range" min="<?php echo esc_attr(get_query_var('price_range')['min'] ?? 0); ?>" max="<?php echo esc_attr(get_query_var('price_range')['max'] ?? 10000); ?>" step="100" value="<?php echo esc_attr(get_query_var('price_range')['min'] ?? 500); ?>">
                            <span class="range-value"><span id="price-range-value"><?= '$' . format_numeric(get_query_var('price_range')['min'] ?? 500); ?></span></span>
                        </div>
                    </li>
                </ul>
            </li>

            <!-- Size (Construction + Land) -->
            <li class="menu-item-has-children filter size">
                <button class="btn button-for-submenu" type="button">
                    <?php esc_html_e('Medidas (m²)', 'inmobiliaria'); ?>
                    <svg width="13" height="13" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 
                            .708.708l-6 6a.5.5 0 0 1-.708 
                            0l-6-6a.5.5 0 0 1 0-.708z"></path>
                    </svg>
                </button>

                <ul class="sub-menu">
                    <div class="backdrop"></div>

                    <!-- Construction -->
                    <li>
                        <label><?php esc_html_e('Construcción (m²)', 'inmobiliaria'); ?></label>
                        <?php $construction_range = get_query_var('construction_range'); ?>
                        <div><input type="number" name="construction_min" placeholder="<?php esc_html_e('Min m²', 'inmobiliaria'); ?>" min="0" value="<?php echo esc_attr($construction_range['min'] ?? 0); ?>"></div>
                        <div><input type="number" name="construction_max" placeholder="<?php esc_html_e('Max m²', 'inmobiliaria'); ?>" min="0" value="<?php echo esc_attr($construction_range['max'] ?? 0); ?>"></div>
                        <div class="construction-range">
                            <label for="construction_range"><?php esc_html_e('Rango construcción:', 'inmobiliaria'); ?></label>
                            <input type="range" id="construction_range" name="construction_range" min="<?php echo esc_attr($construction_range['min'] ?? 0); ?>" max="<?php echo esc_attr($construction_range['max'] ?? 1000); ?>" step="10" value="<?php echo esc_attr($construction_range['min'] ?? 100); ?>">
                            <span class="range-value"><span id="construction-range-value"><?php echo format_numeric($construction_range['min'] ?? 100); ?></span> m²</span>
                        </div>
                    </li>

                    <!-- Land -->
                    <li>
                        <label><?php esc_html_e('Terreno (m²)', 'inmobiliaria'); ?></label>
                        <?php $land_range = get_query_var('land_range'); ?>
                        <div><input type="number" name="land_min" placeholder="<?php esc_html_e('Min m²', 'inmobiliaria'); ?>" min="0" value="<?php echo esc_attr($land_range['min'] ?? 0); ?>"></div>
                        <div><input type="number" name="land_max" placeholder="<?php esc_html_e('Max m²', 'inmobiliaria'); ?>" min="0" value="<?php echo esc_attr($land_range['max'] ?? 0); ?>"></div>
                        <div class="land-range">
                            <label for="land_range"><?php esc_html_e('Rango terreno:', 'inmobiliaria'); ?></label>
                            <input type="range" id="land_range" name="land_range" min="<?php echo esc_attr($land_range['min'] ?? 0); ?>" max="<?php echo esc_attr($land_range['max'] ?? 2000); ?>" step="10" value="<?php echo esc_attr($land_range['min'] ?? 200); ?>">
                            <span class="range-value"><span id="land-range-value"><?php echo format_numeric($land_range['min'] ?? 200); ?></span> m²</span>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>

        <!-- Buttons -->
        <div class="filter-buttons">
            <button class="btn reset-button" type="button" id="reset-filters"><?php esc_html_e('Limpiar', 'inmobiliaria'); ?></button>
            <button class="btn primary" type="submit"><?php esc_html_e('Filtrar', 'inmobiliaria'); ?></button>
        </div>
    </form>
</aside>