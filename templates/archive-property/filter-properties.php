<aside class="properties--filter">
    <!-- Filters -->
    <form class="property-filter-form" id="property-filters">
        <!-- Search -->
        <div class="filter">
            <input type="text" id="filter-search" name="search" aria-label="<?php esc_attr_e('Palabras clave', 'stories'); ?>" placeholder="<?php esc_html_e('Palabras clave', 'stories'); ?>">
        </div>

        <!-- Operation -->
        <fieldset class="menu-flex">
            <legend class="screen-reader-text" style="position:absolute; width:1px; height:1px; padding:0; margin:-1px; overflow:hidden; clip:rect(0,0,0,0); border:0;"><?php esc_html_e('Tipo de Operación', 'stories'); ?></legend>
            <div class="filter-property"><input type="checkbox" id="op-sale" name="operation[]" value="sale"><label for="op-sale"><?= stories_get_metadata_icon('sale'); esc_html_e('Venta', 'stories'); ?></label></div>
            <div class="filter-property"><input type="checkbox" id="op-rent" name="operation[]" value="rental"><label for="op-rent"><?= stories_get_metadata_icon('rent'); esc_html_e('Renta', 'stories'); ?></label></div>
        </fieldset>

        <!-- Type -->
        <fieldset class="menu-flex">
            <legend class="screen-reader-text" style="position:absolute; width:1px; height:1px; padding:0; margin:-1px; overflow:hidden; clip:rect(0,0,0,0); border:0;"><?php esc_html_e('Tipo de Propiedad', 'stories'); ?></legend>
            <!-- <div class="filter-property"><input type="checkbox" id="type-casa" name="type[]" value="casa"><label for="type-casa"><?= stories_get_metadata_icon('house'); esc_html_e('Casa', 'stories'); ?></label></div> -->
            <div class="filter-property"><input type="checkbox" id="type-bedroom" name="type[]" value="bedroom"><label for="type-bedroom"><?= stories_get_metadata_icon('bedroom'); esc_html_e('Habitación', 'stories'); ?></label></div>
            <!-- <div class="filter-property"><input type="checkbox" id="type-apartment" name="type[]" value="apartment"><label for="type-apartment"><?= stories_get_metadata_icon('construction'); esc_html_e('Depto', 'stories'); ?></label></div> -->
            <div class="filter-property"><input type="checkbox" id="type-land" name="type[]" value="land"><label for="type-land"><?= stories_get_metadata_icon('garden'); esc_html_e('Terreno', 'stories'); ?></label></div>
        </fieldset>

        <ul class="filter-navigation menu">

            <!-- Type -->
            <!-- <li class="menu-item-has-children filter type">
                <button class="btn button-for-submenu" type="button">
                    <?php esc_html_e('Tipo', 'stories'); ?>
                    <svg width="13" height="13" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 
                            .708.708l-6 6a.5.5 0 0 1-.708 
                            0l-6-6a.5.5 0 0 1 0-.708z"></path>
                    </svg>
                </button>
                <ul class="sub-menu">
                     
                    <li><p class="checkbox-filter-properties"><input type="checkbox" name="type[]" value="casa"><label><?php esc_html_e('Casa', 'stories'); ?></label></p></li>
                    <li><p class="checkbox-filter-properties"><input type="checkbox" name="type[]" value="departamento"><label><?php esc_html_e('Departamento', 'stories'); ?></label></p></li>
                    <li><p class="checkbox-filter-properties"><input type="checkbox" name="type[]" value="terreno"><label><?php esc_html_e('Terreno', 'stories'); ?></label></p></li>
                </ul>
            </li> -->

            <!-- Location -->
            <li class="menu-item-has-children filter state">
                <button class="btn button-for-submenu" type="button" aria-expanded="false" aria-label="<?php esc_attr_e('Abrir submenú para ubicación', 'stories'); ?>">
                    <?php esc_html_e('Ubicación', 'stories'); ?>
                    <?= stories_get_metadata_icon('chevron-down'); ?>
                </button>
                <ul class="sub-menu">
                     
                    <?php foreach ($locations as $state => $cities): 
                        $state_id = 'state-' . sanitize_title($state);
                    ?>
                        <li class="menu-item-has-children">
                            <div class="btn wrapper-for-title">
                                <p class="checkbox-filter-properties"><input type="checkbox" id="<?php echo esc_attr($state_id); ?>" name="state[]" value="<?php echo esc_attr($state); ?>"><label for="<?php echo esc_attr($state_id); ?>"><?php echo esc_html($state); ?></label></p>
                                <button class="button-for-submenu" type="button" aria-expanded="false" aria-label="<?php printf(esc_attr__('Abrir submenú para %s', 'stories'), $state); ?>">
                                    <?= stories_get_metadata_icon('plus-circle'); ?>
                                </button>
                            </div>
                            <ul class="sub-menu">
                                 
                                <?php foreach ($cities as $city): 
                                    $city_id = 'city-' . sanitize_title($city);
                                ?>
                                    <li><p class="checkbox-filter-properties"><input type="checkbox" id="<?php echo esc_attr($city_id); ?>" name="city[]" value="<?php echo esc_attr($city); ?>"><label for="<?php echo esc_attr($city_id); ?>"><?php echo esc_html($city); ?></label></p></li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>

            <!-- Rooms -->
            <li class="menu-item-has-children filter rooms">
                <button class="btn button-for-submenu" type="button" aria-expanded="false" aria-label="<?php esc_attr_e('Abrir submenú para habitaciones', 'stories'); ?>">
                    <?php esc_html_e('Habitaciones', 'stories'); ?>
                    <?= stories_get_metadata_icon('chevron-down'); ?>
                </button>
                <ul class="sub-menu">
                     
                    <li>
                        <label for="bedrooms"><?php esc_html_e('Recámaras', 'stories'); ?></label>
                        <div class="number-input-wrapper">
                            <button type="button" class="btn-decrease" data-target="bedrooms" aria-label="decrease number of bedrooms"><?= stories_get_metadata_icon('minus'); ?></button>
                            <input type="number" name="bedrooms" id="bedrooms" min="0" placeholder="0">
                            <button type="button" class="btn-increase" data-target="bedrooms" aria-label="increase number of bedrooms"><?= stories_get_metadata_icon('plus'); ?></button>
                        </div>
                    </li>
                    <li>
                        <label for="bathrooms"><?php esc_html_e('Baños', 'stories'); ?></label>
                        <div class="number-input-wrapper">
                            <button type="button" class="btn-decrease" data-target="bathrooms" aria-label="decrease number of bathrooms"><?= stories_get_metadata_icon('minus'); ?></button>
                            <input type="number" name="bathrooms" id="bathrooms" min="0" placeholder="0">
                            <button type="button" class="btn-increase" data-target="bathrooms" aria-label="increase number of bathrooms"><?= stories_get_metadata_icon('plus'); ?></button>
                        </div>
                    </li>
                </ul>
            </li>

            <!-- Price -->
            <li class="menu-item-has-children filter price">
                <button class="btn button-for-submenu" type="button" aria-expanded="false" aria-label="<?php esc_attr_e('Abrir submenú para precio', 'stories'); ?>">
                    <?php esc_html_e('Precio', 'stories'); ?>
                    <?= stories_get_metadata_icon('chevron-down'); ?>
                </button>

                <ul class="sub-menu">
                     
                    <li> 
                        <div>
                            <label for="price_min"><?php esc_html_e('Mínimo', 'stories'); ?></label>
                            <input type="number" id="price_min" name="price_min" placeholder="<?php esc_html_e('Min $', 'stories'); ?>" min="0" value="<?php echo esc_attr(get_query_var('price_range')['min'] ?? 0); ?>">
                        </div>  
                        <div>
                            <label for="price_max"><?php esc_html_e('Máximo', 'stories'); ?></label>    
                            <input type="number" id="price_max" name="price_max" placeholder="<?php esc_html_e('Max $', 'stories'); ?>" min="0" value="<?php echo esc_attr(get_query_var('price_range')['max'] ?? 0); ?>">
                        </div>

                        <div class="price-range">
                            <label for="price_range"><?php esc_html_e('Rango estimado:', 'stories'); ?></label>
                            <input type="range" id="price_range" min="<?php echo esc_attr(get_query_var('price_range')['min'] ?? 0); ?>" max="<?php echo esc_attr(get_query_var('price_range')['max'] ?? 10000); ?>" step="100" value="<?php echo esc_attr(get_query_var('price_range')['min'] ?? 500); ?>">
                            <span class="range-value"><span id="price-range-value"><?= '$' . format_numeric(get_query_var('price_range')['min'] ?? 500); ?></span></span>
                        </div>
                    </li>
                </ul>
            </li>

            <!-- Size (Construction + Land) -->
            <li class="menu-item-has-children filter size">
                <button class="btn button-for-submenu" type="button" aria-expanded="false" aria-label="<?php esc_attr_e('Abrir submenú para medidas', 'stories'); ?>">
                    <?php esc_html_e('Medidas (m²)', 'stories'); ?>
                    <?= stories_get_metadata_icon('chevron-down'); ?>
                </button>

                <ul class="sub-menu">
                     

                    <!-- Construction -->
                    <li>
                        <label><?php esc_html_e('Construcción (m²)', 'stories'); ?></label>
                        <?php $construction_range = get_query_var('construction_range'); ?>
                        <div>
                            <label for="construction_min" class="screen-reader-text" style="position:absolute; width:1px; height:1px; padding:0; margin:-1px; overflow:hidden; clip:rect(0,0,0,0); border:0;"><?php esc_html_e('Min Construcción', 'stories'); ?></label>
                            <input type="number" id="construction_min" name="construction_min" placeholder="<?php esc_html_e('Min m²', 'stories'); ?>" min="0" value="<?php echo esc_attr($construction_range['min'] ?? 0); ?>">
                        </div>
                        <div>
                            <label for="construction_max" class="screen-reader-text" style="position:absolute; width:1px; height:1px; padding:0; margin:-1px; overflow:hidden; clip:rect(0,0,0,0); border:0;"><?php esc_html_e('Max Construcción', 'stories'); ?></label>
                            <input type="number" id="construction_max" name="construction_max" placeholder="<?php esc_html_e('Max m²', 'stories'); ?>" min="0" value="<?php echo esc_attr($construction_range['max'] ?? 0); ?>">
                        </div>
                        <div class="construction-range">
                            <label for="construction_range"><?php esc_html_e('Rango construcción:', 'stories'); ?></label>
                            <input type="range" id="construction_range" name="construction_range" min="<?php echo esc_attr($construction_range['min'] ?? 0); ?>" max="<?php echo esc_attr($construction_range['max'] ?? 1000); ?>" step="10" value="<?php echo esc_attr($construction_range['min'] ?? 100); ?>">
                            <span class="range-value"><span id="construction-range-value"><?php echo format_numeric($construction_range['min'] ?? 100); ?></span> m²</span>
                        </div>
                    </li>

                    <!-- Land -->
                    <li>
                        <label><?php esc_html_e('Terreno (m²)', 'stories'); ?></label>
                        <?php $land_range = get_query_var('land_range'); ?>
                        <div>
                            <label for="land_min" class="screen-reader-text" style="position:absolute; width:1px; height:1px; padding:0; margin:-1px; overflow:hidden; clip:rect(0,0,0,0); border:0;"><?php esc_html_e('Min Terreno', 'stories'); ?></label>
                            <input type="number" id="land_min" name="land_min" placeholder="<?php esc_html_e('Min m²', 'stories'); ?>" min="0" value="<?php echo esc_attr($land_range['min'] ?? 0); ?>">
                        </div>
                        <div>
                            <label for="land_max" class="screen-reader-text" style="position:absolute; width:1px; height:1px; padding:0; margin:-1px; overflow:hidden; clip:rect(0,0,0,0); border:0;"><?php esc_html_e('Max Terreno', 'stories'); ?></label>
                            <input type="number" id="land_max" name="land_max" placeholder="<?php esc_html_e('Max m²', 'stories'); ?>" min="0" value="<?php echo esc_attr($land_range['max'] ?? 0); ?>">
                        </div>
                        <div class="land-range">
                            <label for="land_range"><?php esc_html_e('Rango terreno:', 'stories'); ?></label>
                            <input type="range" id="land_range" name="land_range" min="<?php echo esc_attr($land_range['min'] ?? 0); ?>" max="<?php echo esc_attr($land_range['max'] ?? 2000); ?>" step="10" value="<?php echo esc_attr($land_range['min'] ?? 200); ?>">
                            <span class="range-value"><span id="land-range-value"><?php echo format_numeric($land_range['min'] ?? 200); ?></span> m²</span>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>

        <!-- Buttons -->
        <div class="filter-buttons">
            <button class="btn reset-button" type="button" id="reset-filters"><?php esc_html_e('Limpiar', 'stories'); ?></button>
            <button class="btn primary" type="submit"><?php esc_html_e('Filtrar', 'stories'); ?></button>
        </div>
    </form>
</aside>