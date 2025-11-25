<aside class="properties--filter">
    <!-- Filters -->
    <form class="property-filter-form" id="property-filters">
        <div class="large-button" onclick="togglePropertiesSidebar()">
            Cerrar
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"></path>
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"></path>
            </svg>
        </div>
        <!-- Search -->
        <!-- <div class="filter">
            <input type="text" name="search" placeholder="<?php esc_html_e('Palabras clave', 'inmobiliaria'); ?>">
        </div> -->

        <!-- Operation -->
        <ul class="menu-operation">
            <li><input type="checkbox" name="operation[]" value="sale"><label><svg height="18" width="18" version="1.1" id="_x32_" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512"  xml:space="preserve"><style type="text/css">.st0{fill:currentColor;}</style><g><path class="st0" d="M480.172,261.756c-0.708-1.853-1.059-3.804-1.059-5.756c0-1.952,0.351-3.904,1.059-5.756l18.696-49.082 c1.614-4.24,2.402-8.646,2.395-12.992c0.007-12.041-5.981-23.658-16.533-30.552L440.743,128.9 c-3.315-2.163-5.729-5.491-6.762-9.315l-13.727-50.716c-4.334-15.97-18.808-26.952-35.216-26.952c-0.615,0-1.23,0.014-1.852,0.046 l-52.31,2.614l0,0l-0.919,0.02c-3.698,0-7.277-1.257-10.162-3.572L278.89,8.078c-6.662-5.379-14.8-8.085-22.884-8.078 c-8.091-0.007-16.229,2.699-22.904,8.085l0,0l-40.898,32.939c-2.878,2.316-6.464,3.572-10.162,3.572l-0.834-0.02l-52.647-2.627 l0.33,0.02c-0.722-0.046-1.363-0.053-1.925-0.053c-16.407,0-30.882,10.982-35.215,26.952l-13.728,50.716 c-1.032,3.824-3.447,7.152-6.762,9.315l-43.987,28.719c-10.552,6.894-16.546,18.517-16.539,30.558c0,4.34,0.781,8.746,2.388,12.98 l18.709,49.088c0.708,1.852,1.059,3.804,1.059,5.756c0,1.945-0.351,3.903-1.059,5.756l-18.702,49.088 c-1.614,4.227-2.395,8.633-2.395,12.973c-0.006,12.04,5.981,23.665,16.539,30.571l43.987,28.719 c3.315,2.163,5.729,5.478,6.762,9.308l13.728,50.702c4.333,15.977,18.808,26.958,35.208,26.965c0.569-0.007,1.178-0.013,1.82-0.046 l1.687-0.086l50.789-2.534l0.099-0.007l0.715-0.014c3.678,0,7.251,1.257,10.135,3.58l40.905,32.952 c6.668,5.372,14.806,8.078,22.897,8.071c8.084,0.007,16.222-2.699,22.89-8.071l40.904-32.952c2.885-2.329,6.458-3.58,10.135-3.58 l0.788,0.02l0.926,0.046l51.304,2.56l0.351,0.02c0.542,0.02,1.118,0.032,1.733,0.032c16.4,0,30.882-10.975,35.216-26.958 l13.727-50.702c1.032-3.83,3.447-7.145,6.755-9.308l43.994-28.719c10.552-6.906,16.54-18.524,16.533-30.564 c0.007-4.34-0.781-8.746-2.395-12.986L480.172,261.756z M473.609,337.373l-43.98,28.718c-7.496,4.889-12.927,12.372-15.269,21.018 l-13.728,50.703c-1.906,7.079-8.343,11.947-15.594,11.947l-0.9-0.026l-52.37-2.613h0.027c-0.595-0.033-1.211-0.047-1.86-0.047 c-8.309-0.006-16.393,2.839-22.89,8.078l-40.905,32.953c-2.97,2.388-6.529,3.572-10.135,3.572c-3.612,0-7.171-1.184-10.142-3.572 l-40.905-32.953c-6.496-5.233-14.58-8.078-22.89-8.078c-0.543,0-1.164,0.014-1.832,0.054v-0.007l-52.463,2.62h0.014l-0.827,0.013 c-7.237,0.006-13.681-4.862-15.587-11.941L97.647,387.11c-2.342-8.647-7.774-16.129-15.276-21.024l-43.981-28.712 c-4.677-3.05-7.323-8.21-7.33-13.556c0-1.918,0.344-3.85,1.058-5.736l18.703-49.088c1.594-4.181,2.394-8.587,2.394-12.993 c0-4.406-0.8-8.812-2.394-13l-18.703-49.075c-0.714-1.899-1.058-3.831-1.058-5.75c0.007-5.345,2.646-10.492,7.323-13.542 l43.994-28.718c7.496-4.903,12.927-12.385,15.269-21.025l13.727-50.709c1.906-7.072,8.35-11.948,15.594-11.941l0.708,0.014 l0.225,0.013l52.337,2.613h0.046l1.76,0.04c8.316,0.007,16.4-2.838,22.91-8.064l40.905-32.953l-6.37-7.912l6.377,7.905 c2.971-2.395,6.53-3.572,10.142-3.572c3.606,0,7.165,1.184,10.135,3.572l40.912,32.953c6.51,5.246,14.601,8.071,22.904,8.071 c0.615,0,1.178-0.013,1.687-0.033l0.192-0.013l51.47-2.567l0.952-0.047l0.78-0.02c7.245-0.007,13.688,4.869,15.594,11.941 l13.728,50.709c2.342,8.64,7.773,16.122,15.269,21.025l43.987,28.718c4.678,3.05,7.324,8.197,7.324,13.536 c0,1.918-0.337,3.856-1.065,5.762l-18.69,49.075c-1.594,4.181-2.395,8.587-2.395,12.993c0,4.406,0.801,8.812,2.395,12.993 l18.696,49.088c0.722,1.892,1.059,3.824,1.059,5.742C480.939,329.163,478.293,334.316,473.609,337.373z"/><path class="st0" d="M222.757,227.381c-0.365-0.642-0.906-1.006-1.7-0.847l-14.29,2.811c-0.794,0.152-1.158,0.688-1.251,1.423 L193.7,310.824c-0.093,0.728,0.225,1.138,1.019,0.98l14.852-2.918c0.793-0.159,1.177-0.582,1.244-1.429l1.66-14.336l26.992-5.306 l7.072,12.616c0.384,0.761,0.906,1.006,1.7,0.853l14.74-2.904c0.793-0.152,0.926-0.655,0.568-1.29L222.757,227.381z M214.335,278.03l3.42-28.712l0.344-0.066l14.045,25.278L214.335,278.03z"/><path class="st0" d="M155.071,271.428l-4.187,0.232c-9.104,0.496-12.828-1.72-13.695-6.14c-0.959-4.876,1.859-9.083,8.667-10.419 c6.344-1.251,12.417-0.324,19.079,2.368c0.622,0.231,1.171,0.006,1.509-0.648l4.895-11.684c0.411-0.906,0.212-1.329-0.562-1.654 c-7.442-3.711-17.571-4.909-27.772-2.897c-16.671,3.274-25.477,14.786-22.692,28.963c2.679,13.609,12.854,19.252,28.93,18.332 l4.194-0.232c9.421-0.556,12.702,1.74,13.602,6.279c1.066,5.444-2.739,10.076-10.784,11.657 c-8.163,1.608-15.844-0.887-21.752-3.969c-0.648-0.344-1.33-0.212-1.674,0.456l-6.675,11.558c-0.443,0.787-0.053,1.541,0.49,1.905 c7.144,4.598,19.76,7.066,31.782,4.697c19.959-3.923,28.249-16.274,25.51-30.22C181.19,276.066,171.365,270.462,155.071,271.428z"/><path class="st0" d="M323.46,271.064l-34.018,6.688c-0.45,0.086-0.721-0.099-0.814-0.549l-11.888-60.44 c-0.132-0.688-0.668-1.052-1.35-0.913l-14.402,2.832c-0.682,0.132-1.052,0.675-0.92,1.362l14.72,74.837 c0.139,0.675,0.682,1.045,1.363,0.913l50.232-9.884c0.688-0.132,1.045-0.675,0.913-1.356l-2.474-12.59 C324.69,271.282,324.148,270.925,323.46,271.064z"/><path class="st0" d="M385.151,259.394l-33.23,6.53c-0.45,0.093-0.728-0.086-0.814-0.536l-3.142-15.996 c-0.093-0.45,0.092-0.722,0.542-0.814l27.673-5.438c0.675-0.132,1.045-0.675,0.906-1.356l-2.362-12.02 c-0.133-0.675-0.682-1.039-1.356-0.906l-27.667,5.438c-0.45,0.092-0.728-0.093-0.82-0.543l-3.01-15.309 c-0.086-0.463,0.099-0.728,0.549-0.82l33.231-6.536c0.675-0.133,1.038-0.675,0.906-1.356l-2.382-12.127 c-0.139-0.688-0.681-1.051-1.356-0.912l-49.446,9.719c-0.681,0.139-1.045,0.674-0.906,1.362l14.714,74.837 c0.132,0.682,0.675,1.052,1.356,0.913l49.446-9.719c0.675-0.139,1.038-0.681,0.906-1.363l-2.382-12.133 C386.368,259.626,385.825,259.255,385.151,259.394z"/></g></svg><?php esc_html_e('Venta', 'inmobiliaria'); ?></label></li>
            <li><input type="checkbox" name="operation[]" value="rental"><label><svg fill="currentColor" height="16" width="16" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve"><g><g><path d="M211.772,239.087c3.797-2.022,7.023-4.548,9.651-7.578c4.702-5.376,7.074-11.776,7.074-18.987 c0-4.872-1.126-9.327-3.302-13.184c-2.15-3.789-5.103-6.946-8.798-9.438c-3.448-2.321-7.501-4.096-11.998-5.265 c-4.275-1.109-9.003-1.673-13.978-1.673h-27.725v94.865h24.149V245.24l26.402,32.589h30.575L211.772,239.087z M203.375,216.9 c-0.725,1.229-1.749,2.21-3.149,3.063c-1.553,0.947-3.499,1.698-5.803,2.21c-2.372,0.512-4.898,0.785-7.578,0.836v-18.133h3.447 c2.202,0,4.378,0.222,6.4,0.648c1.775,0.384,3.354,0.973,4.651,1.749c0.947,0.546,1.698,1.289,2.253,2.202 c0.503,0.828,0.751,1.937,0.751,3.302C204.348,214.494,204.023,215.825,203.375,216.9z"/></g></g><g><g><polygon points="270.899,255.787 270.899,240.486 303.795,240.486 303.795,218.453 270.899,218.453 270.899,205.005  305.271,205.005 305.271,182.963 246.741,182.963 246.741,277.828 305.647,277.828 305.647,255.787"/></g></g><g><g><polygon points="370.466,182.963 370.466,233.805 334.217,182.963 313.668,182.963 313.668,277.828 337.323,277.828  337.323,226.987 373.572,277.828 394.121,277.828 394.121,182.963"/></g></g><g><g><polygon points="401.041,182.963 401.041,205.005 425.199,205.005 425.199,277.828 449.348,277.828 449.348,205.005  473.498,205.005 473.498,182.963"/></g></g><g><g><path d="M512,64V38.4H64V0H38.4v38.4H0V64h38.4v422.4H12.8c-7.074,0-12.8,5.726-12.8,12.8c0,7.074,5.726,12.8,12.8,12.8h76.8 c7.074,0,12.8-5.726,12.8-12.8c0-7.074-5.726-12.8-12.8-12.8H64V64h89.591v38.4H128c-14.14,0-25.6,11.46-25.6,25.6v204.8 c0,14.14,11.46,25.6,25.6,25.6h358.4c14.14,0,25.6-11.46,25.6-25.6V128c0-14.14-11.46-25.6-25.6-25.6h-25.6V64H512z M179.191,64 H435.2v38.4H179.191V64z M486.4,128v204.8H128V128H486.4z"/></g></g></svg><?php esc_html_e('Renta', 'inmobiliaria'); ?></label></li>
        </ul>

        <ul class="filter-navigation menu">
            

            <!-- Type -->
            <!-- <li class="menu-item-has-children filter type">
                <button class="btn yellow button-for-submenu" type="button">
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
            <!-- <li class="menu-item-has-children filter state">
                <button class="btn yellow button-for-submenu" type="button">
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
            </li> -->

            <!-- Rooms -->
            <!-- <li class="menu-item-has-children filter rooms">
                <button class="btn yellow button-for-submenu" type="button">
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
                    <li><label for="bedrooms"><?php esc_html_e('Recámaras', 'inmobiliaria'); ?></label><input type="number" name="bedrooms" id="bedrooms" min="0" placeholder="0"></li>
                    <li><label for="bathrooms"><?php esc_html_e('Baños', 'inmobiliaria'); ?></label><input type="number" name="bathrooms" id="bathrooms" min="0" placeholder="0"></li>
                </ul>
            </li> -->

            <!-- Price -->
            <li class="menu-item-has-children filter price">
                <!-- <button class="btn yellow button-for-submenu" type="button">
                    <?php esc_html_e('Precio', 'inmobiliaria'); ?>
                    <svg width="13" height="13" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"></path>
                    </svg>
                </button> -->

                <!-- <ul class="sub-menu">
                    <div class="backdrop"></div>
                    <li> 
                        <div>
                            <label><?php esc_html_e('Mínimo', 'inmobiliaria'); ?></label>
                            <input type="number" name="price_min" placeholder="<?php esc_html_e('Min $', 'inmobiliaria'); ?>" min="0">
                        </div>  
                        <div>
                            <label><?php esc_html_e('Máximo', 'inmobiliaria'); ?></label>    
                            <input type="number" name="price_max" placeholder="<?php esc_html_e('Max $', 'inmobiliaria'); ?>" min="0">
                        </div>

                        <div class="price-range">
                            <label for="price_range"><?php esc_html_e('Rango estimado:', 'inmobiliaria'); ?></label>
                            <input type="range" id="price_range" min="0" max="10000" step="100" value="500">
                            <span class="range-value"><span id="price-range-value">$500</span></span>
                        </div>
                    </li>
                </ul> -->
            </li>

            <!-- Size (Construction + Land) -->
            <li class="menu-item-has-children filter size">
                <!-- <button class="btn yellow button-for-submenu" type="button">
                    <?php esc_html_e('Medidas (m²)', 'inmobiliaria'); ?>
                    <svg width="13" height="13" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 
                            .708.708l-6 6a.5.5 0 0 1-.708 
                            0l-6-6a.5.5 0 0 1 0-.708z"></path>
                    </svg>
                </button> -->

                <ul class="sub-menu">
                    <div class="backdrop"></div>

                    <!-- Construction -->
                    <!-- <li>
                        <label><?php esc_html_e('Construcción (m²)', 'inmobiliaria'); ?></label>
                        <div><input type="number" name="construction_min" placeholder="<?php esc_html_e('Min m²', 'inmobiliaria'); ?>"></div>
                        <div><input type="number" name="construction_max" placeholder="<?php esc_html_e('Max m²', 'inmobiliaria'); ?>"></div>
                        <div class="construction-range">
                            <label for="construction_range"><?php esc_html_e('Rango construcción:', 'inmobiliaria'); ?></label>
                            <input type="range" id="construction_range" name="construction_range" min="0" max="1000" step="10" value="100">
                            <span class="range-value"><span id="construction-range-value">100</span> m²</span>
                        </div>
                    </li> -->

                    <!-- Land -->
                    <!-- <li>
                        <label><?php esc_html_e('Terreno (m²)', 'inmobiliaria'); ?></label>
                        <div><input type="number" name="land_min" placeholder="<?php esc_html_e('Min m²', 'inmobiliaria'); ?>" min="0"></div>
                        <div><input type="number" name="land_max" placeholder="<?php esc_html_e('Max m²', 'inmobiliaria'); ?>" min="0"></div>
                        <div class="land-range">
                            <label for="land_range"><?php esc_html_e('Rango terreno:', 'inmobiliaria'); ?></label>
                            <input type="range" id="land_range" name="land_range" min="0" max="2000" step="10" value="200">
                            <span class="range-value"><span id="land-range-value">200</span> m²</span>
                        </div>
                    </li> -->
                </ul>
            </li>
        </ul>

        <!-- Buttons -->
        <!-- <div class="filter-buttons">
            <button class="btn reset-button" type="button" id="reset-filters"><?php esc_html_e('Limpiar', 'inmobiliaria'); ?></button>
            <button class="btn primary" type="submit"><?php esc_html_e('Filtrar', 'inmobiliaria'); ?></button>
        </div> -->
    </form>
</aside>