<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>

<div class="wrap">

    <div id="welcome-panel" class="welcome-panel">
        <div class="welcome-panel-content">
            <div style="float:right; text-align:right;">
                <small><?php echo __("Plugin version", Zibbra_Plugin::LC_DOMAIN); ?>:&nbsp;<strong><?php echo Zibbra_Plugin::VERSION; ?></strong></small>
                <br />
                <small><?php echo __("Client Library version", Zibbra_Plugin::LC_DOMAIN); ?>:&nbsp;<strong><?php echo ZLibrary::VERSION; ?></strong></small>
            </div>
            <h2><?php echo __("Zibbra Plugin", Zibbra_Plugin::LC_DOMAIN); ?></h2>
            <p class="about-description"><?php echo __("This page allows you to configure the Zibbra E-Commerce plugin.", Zibbra_Plugin::LC_DOMAIN); ?></p>
            <div class="welcome-panel-column-container">
                <div class="welcome-panel-column">
                    <h3><?php echo __("Get Started", Zibbra_Plugin::LC_DOMAIN); ?></h3>
                    <ul>
                        <li><a href="https://zibbra.com/" class="welcome-icon dashicons-cart" target="_blank">Meet Zibbra!</a></li>
                        <li><a href="mailto:sales@zibbra.com" class="welcome-icon dashicons-email" target="_blank">support@zibbra.com</a></li>
                        <li><a href="tel:003235355555" class="welcome-icon dashicons-phone">+32 (3) 5355555</a></li>
                    </ul>
                </div>
                <div class="welcome-panel-column">
                    <h3><?php echo __("Need Help?", Zibbra_Plugin::LC_DOMAIN); ?></h3>
                    <ul>
                        <li><a href="https://manual.zibbra.com/" class="welcome-icon welcome-learn-more" target="_blank">User Manual</a></li>
                        <li><a href="http://support.zibbra.com/" class="welcome-icon dashicons-editor-help" target="_blank">Helpdesk</a></li>
                        <li><a href="mailto:support@zibbra.com" class="welcome-icon dashicons-email" target="_blank">support@zibbra.com</a></li>
                    </ul>
                </div>
                <div class="welcome-panel-column welcome-panel-last">
                    <h3><?php echo __("Developers", Zibbra_Plugin::LC_DOMAIN); ?></h3>
                    <ul>
                        <li><a href="https://developers.zibbra.com/category/wordpress-plugin/" class="welcome-icon welcome-widgets-menus" target="_blank">Wordpress Plugin</a></li>
                        <li><a href="https://developers.zibbra.com/category/api-client-library/" class="welcome-icon dashicons-networking" target="_blank">API Client Library</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <form method="post" action="options.php">
        <?php @settings_fields('zibbra_plugin-group'); ?>
        <?php @do_settings_fields('zibbra_plugin-group'); ?>

        <div id="dashboard-widgets-wrap">
            <div id="dashboard-widgets" class="metabox-holder columns-2">

                <div id="postbox-container-1" class="postbox-container">
                    <div class="meta-box-sortables ui-sortable">

                        <div id="zibbra_api" class="postbox">
                            <button type="button" class="handlediv button-link" aria-expanded="true">
                                <span class="screen-reader-text">Toggle panel: <?php echo __("API Settings", Zibbra_Plugin::LC_DOMAIN); ?></span>
                                <span class="toggle-indicator" aria-hidden="true"></span>
                            </button>
                            <h2 class="hndle ui-sortable-handle"><?php echo __("API Settings", Zibbra_Plugin::LC_DOMAIN); ?></h2>
                            <div class="inside">
                                <div class="main">

                                    <table class="form-table">
                                        <tr valign="top">
                                            <td scope="row"><label for="zibbra_api_client_id"><?php echo __("API Client ID", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
                                            <td><input type="text" name="zibbra_api_client_id" id="zibbra_api_client_id" value="<?php echo get_option('zibbra_api_client_id'); ?>" class="regular-text" /></td>
                                        </tr>
                                        <tr valign="top">
                                            <td scope="row"><label for="zibbra_api_client_secret"><?php echo __("API Client Secret", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
                                            <td><input type="text" name="zibbra_api_client_secret" id="zibbra_api_client_secret" value="<?php echo get_option('zibbra_api_client_secret'); ?>" class="regular-text" /></td>
                                        </tr>
                                        <tr valign="top">
                                            <td scope="row"><label for="zibbra_api_env"><?php echo __("Zibbra Environment", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
                                            <td>
                                                <select name="zibbra_api_env" id="zibbra_api_env">
                                                    <option value="PRODUCTION"<?php echo get_option('zibbra_api_env')=="PRODUCTION" ? " selected=\"selected\"" : ""; ?>><?php echo __("Production", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                    <option value="STAGING"<?php echo get_option('zibbra_api_env')=="STAGING" ? " selected=\"selected\"" : ""; ?>><?php echo __("Staging", Zibbra_Plugin::LC_DOMAIN); ?></option>
	                                                <?php if(getenv("DEVELOPMENT")): ?>
		                                                <option value="DEVELOPMENT"<?php echo get_option('zibbra_api_env')=="DEVELOPMENT" ? " selected=\"selected\"" : ""; ?>><?php echo __("Development", Zibbra_Plugin::LC_DOMAIN); ?></option>
		                                                <option value="AWS"<?php echo get_option('zibbra_api_env')=="AWS" ? " selected=\"selected\"" : ""; ?>><?php echo __("Amazon AWS", Zibbra_Plugin::LC_DOMAIN); ?></option>
	                                                <?php endif; ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr valign="top">
                                            <td scope="row"><label for="zibbra_debug"><?php echo __("Enable debugging?", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
                                            <td>
                                                <select name="zibbra_debug" id="zibbra_debug">
                                                    <option value="Y"<?php echo get_option("zibbra_debug")=="Y" ? " selected=\"selected\"" : ""; ?>><?php echo __("Yes", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                    <option value="N"<?php echo get_option("zibbra_debug")!="Y" ? " selected=\"selected\"" : ""; ?>><?php echo __("No", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr valign="top">
                                            <td scope="row"><label for="zibbra_api_client_token"><?php echo __("Your current token", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
                                            <td><?php echo get_option("zibbra_api_client_token"); ?></td>
                                        </tr>
                                        <tr valign="top">
                                            <td scope="row"><label for="zibbra_api_client_expiry"><?php echo __("Token expires on", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
                                            <td>
                                                <?php
                                                if(($timestamp = get_option("zibbra_api_client_expiry"))!==false) {

                                                    $datetime = new DateTime();
                                                    $datetime->setTimestamp($timestamp);
                                                    echo $datetime->format(get_option("date_format")." ".get_option("time_format"));

                                                } // end if
                                                ?>
                                            </td>
                                        </tr>
                                        <tr valign="top">
                                            <td scope="row"><label for="zibbra_api_client_token_reset"><?php echo __("Reset token?", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
                                            <td><input type="checkbox" id="zibbra_api_client_token_reset" name="zibbra_api_client_token_reset" value="Y" /></td>
                                        </tr>
                                    </table>

                                </div>
                            </div>
                        </div>

	                    <div id="zibbra_log" class="postbox">
		                    <button type="button" class="handlediv button-link" aria-expanded="true">
			                    <span class="screen-reader-text">Toggle panel: <?php echo __("Log Settings", Zibbra_Plugin::LC_DOMAIN); ?></span>
			                    <span class="toggle-indicator" aria-hidden="true"></span>
		                    </button>
		                    <h2 class="hndle ui-sortable-handle"><?php echo __("Log Settings", Zibbra_Plugin::LC_DOMAIN); ?></h2>
		                    <div class="inside">
			                    <div class="main">

				                    <table class="form-table">
					                    <tr valign="top">
						                    <td scope="row"><label for="zibbra_log_dir"><?php echo __("Log directory?", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
						                    <td><input type="text" name="zibbra_log_dir" id="zibbra_log_dir" value="<?php echo get_option('zibbra_log_dir'); ?>" class="regular-text" /></td>
					                    </tr>
				                    </table>

			                    </div>
		                    </div>
	                    </div>

                        <div id="zibbra_catalog" class="postbox">
                            <button type="button" class="handlediv button-link" aria-expanded="true">
                                <span class="screen-reader-text">Toggle panel: <?php echo __("Catalog Settings", Zibbra_Plugin::LC_DOMAIN); ?></span>
                                <span class="toggle-indicator" aria-hidden="true"></span>
                            </button>
                            <h2 class="hndle ui-sortable-handle"><?php echo __("Catalog Settings", Zibbra_Plugin::LC_DOMAIN); ?></h2>
                            <div class="inside">
                                <div class="main">

                                    <table class="form-table">
                                        <tr valign="top">
                                            <td scope="row"><label for="zibbra_catalog_products_per_page"><?php echo __("Products per page?", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
                                            <td><input type="number" min="5" max="50" id="zibbra_catalog_products_per_page" name="zibbra_catalog_products_per_page" value="<?php echo get_option("zibbra_catalog_products_per_page",10); ?>" /></td>
                                        </tr>
                                        <tr valign="top">
                                            <td scope="row"><label for="zibbra_catalog_default_sort_type"><?php echo __("Default sort type?", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
                                            <td>
                                                <select name="zibbra_catalog_default_sort_type" id="zibbra_catalog_default_sort_type">
                                                    <option value="times_sold"<?php echo get_option("zibbra_catalog_default_sort_type")=="times_sold" ? " selected=\"selected\"" : ""; ?>><?php echo __("Best buy", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                    <option value="name"<?php echo get_option("zibbra_catalog_default_sort_type")=="name" ? " selected=\"selected\"" : ""; ?>><?php echo __("Name", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                    <option value="price"<?php echo get_option("zibbra_catalog_default_sort_type")=="price" ? " selected=\"selected\"" : ""; ?>><?php echo __("Price", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                    <option value="timestamp_insert"<?php echo get_option("zibbra_catalog_default_sort_type")=="timestamp_insert" ? " selected=\"selected\"" : ""; ?>><?php echo __("Newest", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr valign="top">
                                            <td scope="row"><label for="zibbra_catalog_default_sort_dir"><?php echo __("Default sort direction?", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
                                            <td>
                                                <select name="zibbra_catalog_default_sort_dir" id="zibbra_catalog_default_sort_dir">
                                                    <option value="asc"<?php echo get_option("zibbra_catalog_default_sort_dir")=="asc" ? " selected=\"selected\"" : ""; ?>><?php echo __("Ascending", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                    <option value="desc"<?php echo get_option("zibbra_catalog_default_sort_dir")!="asc" ? " selected=\"selected\"" : ""; ?>><?php echo __("Descending", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr valign="top">
                                            <td scope="row"><label for="zibbra_catalog_grid_list"><?php echo __("Grid or List layout?", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
                                            <td>
                                                <select name="zibbra_catalog_grid_list" id="zibbra_catalog_grid_list">
                                                    <option value="grid"<?php echo get_option('zibbra_catalog_grid_list')!="list" ? " selected=\"selected\"" : ""; ?>><?php echo __("Grid", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                    <option value="list"<?php echo get_option('zibbra_catalog_grid_list')=="list" ? " selected=\"selected\"" : ""; ?>><?php echo __("List", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr valign="top">
                                            <td scope="row"><label for="zibbra_catalog_show_filters"><?php echo __("Show filters?", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
                                            <td>
                                                <select name="zibbra_catalog_show_filters" id="zibbra_catalog_show_filters">
                                                    <option value="Y"<?php echo get_option("zibbra_catalog_show_filters")=="Y" ? " selected=\"selected\"" : ""; ?>><?php echo __("Yes", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                    <option value="N"<?php echo get_option("zibbra_catalog_show_filters")!="Y" ? " selected=\"selected\"" : ""; ?>><?php echo __("No", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr valign="top">
                                            <td scope="row"><label for="zibbra_catalog_filters_collapsed"><?php echo __("Filters collapsed?", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
                                            <td>
                                                <select name="zibbra_catalog_filters_collapsed" id="zibbra_catalog_filters_collapsed">
                                                    <option value="Y"<?php echo get_option("zibbra_catalog_filters_collapsed")=="Y" ? " selected=\"selected\"" : ""; ?>><?php echo __("Yes", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                    <option value="N"<?php echo get_option("zibbra_catalog_filters_collapsed")!="Y" ? " selected=\"selected\"" : ""; ?>><?php echo __("No", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr valign="top">
                                            <td scope="row"><label for="zibbra_catalog_header_sidebar"><?php echo __("Header in sidebar?", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
                                            <td>
                                                <select name="zibbra_catalog_header_sidebar" id="zibbra_catalog_header_sidebar">
                                                    <option value="Y"<?php echo get_option("zibbra_catalog_header_sidebar")=="Y" ? " selected=\"selected\"" : ""; ?>><?php echo __("Yes", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                    <option value="N"<?php echo get_option("zibbra_catalog_header_sidebar")!="Y" ? " selected=\"selected\"" : ""; ?>><?php echo __("No", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr valign="top">
                                            <td scope="row"><label for="zibbra_catalog_toolbar_sidebar"><?php echo __("Toolbar in sidebar?", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
                                            <td>
                                                <select name="zibbra_catalog_toolbar_sidebar" id="zibbra_catalog_toolbar_sidebar">
                                                    <option value="Y"<?php echo get_option("zibbra_catalog_toolbar_sidebar")=="Y" ? " selected=\"selected\"" : ""; ?>><?php echo __("Yes", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                    <option value="N"<?php echo get_option("zibbra_catalog_toolbar_sidebar")!="Y" ? " selected=\"selected\"" : ""; ?>><?php echo __("No", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr valign="top">
                                            <td scope="row"><label for="zibbra_catalog_show_stock"><?php echo __("Show stock info?", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
                                            <td>
                                                <select name="zibbra_catalog_show_stock" id="zibbra_catalog_show_stock">
                                                    <option value="Y"<?php echo get_option("zibbra_catalog_show_stock")!="N" ? " selected=\"selected\"" : ""; ?>><?php echo __("Yes", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                    <option value="N"<?php echo get_option("zibbra_catalog_show_stock")=="N" ? " selected=\"selected\"" : ""; ?>><?php echo __("No", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr valign="top">
                                            <td scope="row"><label for="zibbra_catalog_show_stock_backorder"><?php echo __("Show stock when backorder allowed?", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
                                            <td>
                                                <select name="zibbra_catalog_show_stock_backorder" id="zibbra_catalog_show_stock_backorder">
                                                    <option value="Y"<?php echo get_option("zibbra_catalog_show_stock_backorder")!="N" ? " selected=\"selected\"" : ""; ?>><?php echo __("Yes", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                    <option value="N"<?php echo get_option("zibbra_catalog_show_stock_backorder")=="N" ? " selected=\"selected\"" : ""; ?>><?php echo __("No", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr valign="top">
                                            <td scope="row"><label for="zibbra_catalog_show_stock_quantity"><?php echo __("Show stock quantity?", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
                                            <td>
                                                <select name="zibbra_catalog_show_stock_quantity" id="zibbra_catalog_show_stock_quantity">
                                                    <option value="Y"<?php echo get_option("zibbra_catalog_show_stock_quantity")!="N" ? " selected=\"selected\"" : ""; ?>><?php echo __("Yes", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                    <option value="N"<?php echo get_option("zibbra_catalog_show_stock_quantity")=="N" ? " selected=\"selected\"" : ""; ?>><?php echo __("No", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr valign="top">
                                            <td scope="row"><label for="zibbra_catalog_category_thumbnail_size"><?php echo __("Thumbnail size?", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
                                            <td><input type="number" min="120" max="320" step="10" id="zibbra_catalog_category_thumbnail_size" name="zibbra_catalog_category_thumbnail_size" value="<?php echo get_option("zibbra_catalog_category_thumbnail_size",120); ?>" /></td>
                                        </tr>
                                        <tr valign="top">
                                            <td scope="row"><label for="zibbra_catalog_redirect_addtocart"><?php echo __("Button", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
                                            <td>
                                                <select name="zibbra_catalog_redirect_addtocart" id="zibbra_catalog_redirect_addtocart">
                                                    <option value="redirect"<?php echo get_option("zibbra_catalog_redirect_addtocart")=="redirect" ? " selected=\"selected\"" : ""; ?>><?php echo __("Link product page", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                    <option value="addtocart"<?php echo get_option("zibbra_catalog_redirect_addtocart")!="addtocart" ? " selected=\"selected\"" : ""; ?>><?php echo __("Add to cart", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                    </table>

                                </div>
                            </div>
                        </div>

                        <div id="zibbra_product" class="postbox">
                            <button type="button" class="handlediv button-link" aria-expanded="true">
                                <span class="screen-reader-text">Toggle panel: <?php echo __("Product Settings", Zibbra_Plugin::LC_DOMAIN); ?></span>
                                <span class="toggle-indicator" aria-hidden="true"></span>
                            </button>
                            <h2 class="hndle ui-sortable-handle"><?php echo __("Product Settings", Zibbra_Plugin::LC_DOMAIN); ?></h2>
                            <div class="inside">
                                <div class="main">

                                    <table class="form-table">
                                        <tr valign="top">
                                            <td scope="row"><label for="zibbra_product_split_properties"><?php echo __("Split properties in 2 columns?", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
                                            <td>
                                                <select name="zibbra_product_split_properties" id="zibbra_product_split_properties">
                                                    <option value="Y"<?php echo get_option("zibbra_product_split_properties")=="Y" ? " selected=\"selected\"" : ""; ?>><?php echo __("Yes", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                    <option value="N"<?php echo get_option("zibbra_product_split_properties")!="Y" ? " selected=\"selected\"" : ""; ?>><?php echo __("No", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr valign="top">
                                            <td scope="row"><label for="zibbra_product_title_top"><?php echo __("Title at top?", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
                                            <td>
                                                <select name="zibbra_product_title_top" id="zibbra_product_title_top">
                                                    <option value="Y"<?php echo get_option("zibbra_product_title_top")=="Y" ? " selected=\"selected\"" : ""; ?>><?php echo __("Yes", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                    <option value="N"<?php echo get_option("zibbra_product_title_top")!="Y" ? " selected=\"selected\"" : ""; ?>><?php echo __("No", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                </select>
                                            </td>
                                        </tr>
	                                    <tr valign="top">
		                                    <td scope="row"><label for="zibbra_product_quantities"><?php echo __("Quantity selector?", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
		                                    <td>
			                                    <select name="zibbra_product_quantities" id="zibbra_product_quantities">
				                                    <option value="Y"<?php echo get_option("zibbra_product_quantities")=="Y" ? " selected=\"selected\"" : ""; ?>><?php echo __("Yes", Zibbra_Plugin::LC_DOMAIN); ?></option>
				                                    <option value="N"<?php echo get_option("zibbra_product_quantities")!="Y" ? " selected=\"selected\"" : ""; ?>><?php echo __("No", Zibbra_Plugin::LC_DOMAIN); ?></option>
			                                    </select>
		                                    </td>
	                                    </tr>
                                    </table>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div id="postbox-container-2" class="postbox-container">
                    <div class="meta-box-sortables ui-sortable">

                        <div id="zibbra_cart" class="postbox">
                            <button type="button" class="handlediv button-link" aria-expanded="true">
                                <span class="screen-reader-text">Toggle panel: <?php echo __("Cart Settings", Zibbra_Plugin::LC_DOMAIN); ?></span>
                                <span class="toggle-indicator" aria-hidden="true"></span>
                            </button>
                            <h2 class="hndle ui-sortable-handle"><?php echo __("Cart Settings", Zibbra_Plugin::LC_DOMAIN); ?></h2>
                            <div class="inside">
                                <div class="main">

                                    <table class="form-table">
                                        <tr valign="top">
                                            <td scope="row"><label for="zibbra_cart_incl_vat"><?php echo __("Show prices VAT included?", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
                                            <td>
                                                <select name="zibbra_cart_incl_vat" id="zibbra_cart_incl_vat">
                                                    <option value="Y"<?php echo get_option("zibbra_cart_incl_vat")=="Y" ? " selected=\"selected\"" : ""; ?>><?php echo __("Yes", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                    <option value="N"<?php echo get_option("zibbra_cart_incl_vat")!="Y" ? " selected=\"selected\"" : ""; ?>><?php echo __("No", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                </select>
                                            </td>
                                        </tr>
	                                    <tr valign="top">
		                                    <td scope="row"><label for="zibbra_cart_link_products"><?php echo __("Link products?", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
		                                    <td>
			                                    <select name="zibbra_cart_link_products" id="zibbra_cart_link_products">
				                                    <option value="Y"<?php echo get_option("zibbra_cart_link_products")!="N" ? " selected=\"selected\"" : ""; ?>><?php echo __("Yes", Zibbra_Plugin::LC_DOMAIN); ?></option>
				                                    <option value="N"<?php echo get_option("zibbra_cart_link_products")=="N" ? " selected=\"selected\"" : ""; ?>><?php echo __("No", Zibbra_Plugin::LC_DOMAIN); ?></option>
			                                    </select>
		                                    </td>
	                                    </tr>
	                                    <tr valign="top">
		                                    <td scope="row"><label for="zibbra_cart_lock_quantity"><?php echo __("Lock the quantity?", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
		                                    <td>
			                                    <select name="zibbra_cart_lock_quantity" id="zibbra_cart_lock_quantity">
				                                    <option value="Y"<?php echo get_option("zibbra_cart_lock_quantity")=="Y" ? " selected=\"selected\"" : ""; ?>><?php echo __("Yes", Zibbra_Plugin::LC_DOMAIN); ?></option>
				                                    <option value="N"<?php echo get_option("zibbra_cart_lock_quantity")!="Y" ? " selected=\"selected\"" : ""; ?>><?php echo __("No", Zibbra_Plugin::LC_DOMAIN); ?></option>
			                                    </select>
		                                    </td>
	                                    </tr>
	                                    <tr valign="top">
		                                    <td scope="row"><label for="zibbra_cart_lock_addons"><?php echo __("Lock the addons?", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
		                                    <td>
			                                    <select name="zibbra_cart_lock_addons" id="zibbra_cart_lock_addons">
				                                    <option value="Y"<?php echo get_option("zibbra_cart_lock_addons")=="Y" ? " selected=\"selected\"" : ""; ?>><?php echo __("Yes", Zibbra_Plugin::LC_DOMAIN); ?></option>
				                                    <option value="N"<?php echo get_option("zibbra_cart_lock_addons")!="Y" ? " selected=\"selected\"" : ""; ?>><?php echo __("No", Zibbra_Plugin::LC_DOMAIN); ?></option>
			                                    </select>
		                                    </td>
	                                    </tr>
	                                    <tr valign="top">
		                                    <td scope="row"><label for="zibbra_cart_show_continue_shopping"><?php echo __("Show continue shopping?", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
		                                    <td>
			                                    <select name="zibbra_cart_show_continue_shopping" id="zibbra_cart_show_continue_shopping">
				                                    <option value="Y"<?php echo get_option("zibbra_cart_show_continue_shopping")!="N" ? " selected=\"selected\"" : ""; ?>><?php echo __("Yes", Zibbra_Plugin::LC_DOMAIN); ?></option>
				                                    <option value="N"<?php echo get_option("zibbra_cart_show_continue_shopping")=="N" ? " selected=\"selected\"" : ""; ?>><?php echo __("No", Zibbra_Plugin::LC_DOMAIN); ?></option>
			                                    </select>
		                                    </td>
	                                    </tr>
	                                    <tr valign="top">
		                                    <td scope="row"><label for="zibbra_cart_url_continue_shopping"><?php echo __("URL continue shopping?", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
		                                    <td><input type="text" name="zibbra_cart_url_continue_shopping" id="zibbra_cart_url_continue_shopping" value="<?php echo get_option('zibbra_cart_url_continue_shopping'); ?>" class="regular-text" /></td>
	                                    </tr>
                                    </table>

                                </div>
                            </div>
                        </div>

                        <div id="zibbra_checkout" class="postbox">
                            <button type="button" class="handlediv button-link" aria-expanded="true">
                                <span class="screen-reader-text">Toggle panel: <?php echo __("Checkout Settings", Zibbra_Plugin::LC_DOMAIN); ?></span>
                                <span class="toggle-indicator" aria-hidden="true"></span>
                            </button>
                            <h2 class="hndle ui-sortable-handle"><?php echo __("Checkout Settings", Zibbra_Plugin::LC_DOMAIN); ?></h2>
                            <div class="inside">
                                <div class="main">

                                    <table class="form-table">
                                        <tr valign="top">
                                            <td scope="row"><label for="zibbra_checkout_redirect"><?php echo __("Redirect after order?", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
                                            <td><input type="text" name="zibbra_checkout_redirect" id="zibbra_checkout_redirect" value="<?php echo get_option('zibbra_checkout_redirect'); ?>" class="regular-text" /></td>
                                        </tr>
	                                    <tr valign="top">
		                                    <td scope="row"><label for="zibbra_checkout_link_products"><?php echo __("Link products?", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
		                                    <td>
			                                    <select name="zibbra_checkout_link_products" id="zibbra_checkout_link_products">
				                                    <option value="Y"<?php echo get_option("zibbra_checkout_link_products")!="N" ? " selected=\"selected\"" : ""; ?>><?php echo __("Yes", Zibbra_Plugin::LC_DOMAIN); ?></option>
				                                    <option value="N"<?php echo get_option("zibbra_checkout_link_products")=="N" ? " selected=\"selected\"" : ""; ?>><?php echo __("No", Zibbra_Plugin::LC_DOMAIN); ?></option>
			                                    </select>
		                                    </td>
	                                    </tr>
                                        <tr valign="top">
                                            <td scope="row"><label for="zibbra_checkout_allow_comments"><?php echo __("Allow comments?", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
                                            <td>
                                                <select name="zibbra_checkout_allow_comments" id="zibbra_checkout_allow_comments">
                                                    <option value="Y"<?php echo get_option("zibbra_checkout_allow_comments")!="N" ? " selected=\"selected\"" : ""; ?>><?php echo __("Yes", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                    <option value="N"<?php echo get_option("zibbra_checkout_allow_comments")=="N" ? " selected=\"selected\"" : ""; ?>><?php echo __("No", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr valign="top">
                                            <td scope="row"><label for="zibbra_checkout_vouchers"><?php echo __("Enable vouchers?", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
                                            <td>
                                                <select name="zibbra_checkout_vouchers" id="zibbra_checkout_vouchers">
                                                    <option value="Y"<?php echo get_option("zibbra_checkout_vouchers")=="Y" ? " selected=\"selected\"" : ""; ?>><?php echo __("Yes", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                    <option value="N"<?php echo get_option("zibbra_checkout_vouchers")!="Y" ? " selected=\"selected\"" : ""; ?>><?php echo __("No", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                </select>
                                            </td>
                                        </tr>
	                                    <tr valign="top">
		                                    <td scope="row"><label for="zibbra_checkout_agree_terms"><?php echo __("Require agree terms?", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
		                                    <td>
			                                    <select name="zibbra_checkout_agree_terms" id="zibbra_checkout_agree_terms">
				                                    <option value="Y"<?php echo get_option("zibbra_checkout_agree_terms")=="Y" ? " selected=\"selected\"" : ""; ?>><?php echo __("Yes", Zibbra_Plugin::LC_DOMAIN); ?></option>
				                                    <option value="N"<?php echo get_option("zibbra_checkout_agree_terms")!="Y" ? " selected=\"selected\"" : ""; ?>><?php echo __("No", Zibbra_Plugin::LC_DOMAIN); ?></option>
			                                    </select>
		                                    </td>
	                                    </tr>
	                                    <tr valign="top">
		                                    <td scope="row"><label for="zibbra_checkout_url_terms"><?php echo __("URL to terms?", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
		                                    <td><input type="text" name="zibbra_checkout_url_terms" id="zibbra_checkout_url_terms" value="<?php echo get_option('zibbra_checkout_url_terms'); ?>" class="regular-text" /></td>
	                                    </tr>
                                    </table>

                                </div>
                            </div>
                        </div>

                        <div id="zibbra_register" class="postbox">
                            <button type="button" class="handlediv button-link" aria-expanded="true">
                                <span class="screen-reader-text">Toggle panel: <?php echo __("Registration Settings", Zibbra_Plugin::LC_DOMAIN); ?></span>
                                <span class="toggle-indicator" aria-hidden="true"></span>
                            </button>
                            <h2 class="hndle ui-sortable-handle"><?php echo __("Registration Settings", Zibbra_Plugin::LC_DOMAIN); ?></h2>
                            <div class="inside">
                                <div class="main">

                                    <table class="form-table">
                                        <tr valign="top">
                                            <td scope="row"><label for="zibbra_register_generate_password"><?php echo __("Generate password?", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
                                            <td>
                                                <select name="zibbra_register_generate_password" id="zibbra_register_generate_password">
                                                    <option value="Y"<?php echo get_option("zibbra_register_generate_password")=="Y" ? " selected=\"selected\"" : ""; ?>><?php echo __("Yes", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                    <option value="N"<?php echo get_option("zibbra_register_generate_password")!="Y" ? " selected=\"selected\"" : ""; ?>><?php echo __("No", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr valign="top">
                                            <td scope="row"><label for="zibbra_register_shipping_first"><?php echo __("Primary address is shipping?", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
                                            <td>
                                                <select name="zibbra_register_shipping_first" id="zibbra_register_shipping_first">
                                                    <option value="Y"<?php echo get_option("zibbra_register_shipping_first")=="Y" ? " selected=\"selected\"" : ""; ?>><?php echo __("Yes", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                    <option value="N"<?php echo get_option("zibbra_register_shipping_first")!="Y" ? " selected=\"selected\"" : ""; ?>><?php echo __("No", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                    </table>

                                </div>
                            </div>
                        </div>

                        <div id="zibbra_captcha" class="postbox">
                            <button type="button" class="handlediv button-link" aria-expanded="true">
                                <span class="screen-reader-text">Toggle panel: <?php echo __("Captcha Settings", Zibbra_Plugin::LC_DOMAIN); ?></span>
                                <span class="toggle-indicator" aria-hidden="true"></span>
                            </button>
                            <h2 class="hndle ui-sortable-handle"><?php echo __("Captcha Settings", Zibbra_Plugin::LC_DOMAIN); ?></h2>
                            <div class="inside">
                                <div class="main">

                                    <p><?php echo sprintf(__("Register your re-captcha account for free at %s", Zibbra_Plugin::LC_DOMAIN), '<a href="https://www.google.com/recaptcha/" target="_blank">google.com/recaptcha</a>'); ?></p>

                                    <table class="form-table">
                                        <tr valign="top">
                                            <td scope="row"><label for="zibbra_recaptcha_key"><?php echo __("Re-Captcha Site Key", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
                                            <td><input type="text" name="zibbra_recaptcha_key" id="zibbra_recaptcha_key" value="<?php echo get_option('zibbra_recaptcha_key'); ?>" class="regular-text" /></td>
                                        </tr>
                                        <tr valign="top">
                                            <td scope="row"><label for="zibbra_recaptcha_secret"><?php echo __("Re-Captcha Secret", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
                                            <td><input type="text" name="zibbra_recaptcha_secret" id="zibbra_recaptcha_secret" value="<?php echo get_option('zibbra_recaptcha_secret'); ?>" class="regular-text" /></td>
                                        </tr>
                                    </table>

                                </div>
                            </div>
                        </div>

                        <div id="zibbra_ga" class="postbox">
                            <button type="button" class="handlediv button-link" aria-expanded="true">
                                <span class="screen-reader-text">Toggle panel: <?php echo __("Google Analytics Settings", Zibbra_Plugin::LC_DOMAIN); ?></span>
                                <span class="toggle-indicator" aria-hidden="true"></span>
                            </button>
                            <h2 class="hndle ui-sortable-handle"><?php echo __("Google Analytics Settings", Zibbra_Plugin::LC_DOMAIN); ?></h2>
                            <div class="inside">
                                <div class="main">

                                    <table class="form-table">
                                        <tr valign="top">
                                            <td scope="row"><label for="zibbra_ga_tracking_id"><?php echo __("Tracking ID?", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
                                            <td><input type="text" name="zibbra_ga_tracking_id" id="zibbra_ga_tracking_id" value="<?php echo get_option('zibbra_ga_tracking_id'); ?>" class="regular-text" /></td>
                                        </tr>
                                        <tr valign="top">
                                            <td scope="row"><label for="zibbra_ga_enable_ecommerce"><?php echo __("Enable E-Commerce Tracking?", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
                                            <td>
                                                <select name="zibbra_ga_enable_ecommerce" id="zibbra_ga_enable_ecommerce">
                                                    <option value="Y"<?php echo get_option("zibbra_ga_enable_ecommerce")!="N" ? " selected=\"selected\"" : ""; ?>><?php echo __("Yes", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                    <option value="N"<?php echo get_option("zibbra_ga_enable_ecommerce")=="N" ? " selected=\"selected\"" : ""; ?>><?php echo __("No", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                    </table>

                                </div>
                            </div>
                        </div>

                        <div id="zibbra_fb" class="postbox">
                            <button type="button" class="handlediv button-link" aria-expanded="true">
                                <span class="screen-reader-text">Toggle panel: <?php echo __("Facebook Pixel Settings", Zibbra_Plugin::LC_DOMAIN); ?></span>
                                <span class="toggle-indicator" aria-hidden="true"></span>
                            </button>
                            <h2 class="hndle ui-sortable-handle"><?php echo __("Facebook Pixel Settings", Zibbra_Plugin::LC_DOMAIN); ?></h2>
                            <div class="inside">
                                <div class="main">

                                    <table class="form-table">
                                        <tr valign="top">
                                            <td scope="row"><label for="zibbra_fb_tracking_id"><?php echo __("Tracking ID?", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
                                            <td><input type="text" name="zibbra_fb_tracking_id" id="zibbra_fb_tracking_id" value="<?php echo get_option('zibbra_fb_tracking_id'); ?>" class="regular-text" /></td>
                                        </tr>
                                    </table>

                                </div>
                            </div>
                        </div>

                        <div id="zibbra_theme" class="postbox">
                            <button type="button" class="handlediv button-link" aria-expanded="true">
                                <span class="screen-reader-text">Toggle panel: <?php echo __("Theme Settings", Zibbra_Plugin::LC_DOMAIN); ?></span>
                                <span class="toggle-indicator" aria-hidden="true"></span>
                            </button>
                            <h2 class="hndle ui-sortable-handle"><?php echo __("Theme Settings", Zibbra_Plugin::LC_DOMAIN); ?></h2>
                            <div class="inside">
                                <div class="main">

                                    <table class="form-table">
                                        <tr valign="top">
                                            <td scope="row"><label for="zibbra_wrapped_theme"><?php echo __("Wrapped Theme?", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
                                            <td>
                                                <select name="zibbra_wrapped_theme" id="zibbra_wrapped_theme">
                                                    <option value="Y"<?php echo get_option("zibbra_wrapped_theme")!="N" ? " selected=\"selected\"" : ""; ?>><?php echo __("Yes", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                    <option value="N"<?php echo get_option("zibbra_wrapped_theme")=="N" ? " selected=\"selected\"" : ""; ?>><?php echo __("No", Zibbra_Plugin::LC_DOMAIN); ?></option>
                                                </select>
                                            </td>
                                        </tr>
	                                    <tr valign="top">
		                                    <td scope="row"><label for="zibbra_bootstrap_container_title"><?php echo __("Enclose page title in bootstrap container?", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
		                                    <td>
			                                    <select name="zibbra_bootstrap_container_title" id="zibbra_bootstrap_container_title">
				                                    <option value="Y"<?php echo get_option("zibbra_bootstrap_container_title")=="Y" ? " selected=\"selected\"" : ""; ?>><?php echo __("Yes", Zibbra_Plugin::LC_DOMAIN); ?></option>
				                                    <option value="N"<?php echo get_option("zibbra_bootstrap_container_title")!="Y" ? " selected=\"selected\"" : ""; ?>><?php echo __("No", Zibbra_Plugin::LC_DOMAIN); ?></option>
			                                    </select>
		                                    </td>
	                                    </tr>
	                                    <tr valign="top">
		                                    <td scope="row"><label for="zibbra_bootstrap_container_content"><?php echo __("Enclose content in bootstrap container?", Zibbra_Plugin::LC_DOMAIN); ?></label></td>
		                                    <td>
			                                    <select name="zibbra_bootstrap_container_content" id="zibbra_bootstrap_container_content">
				                                    <option value="Y"<?php echo get_option("zibbra_bootstrap_container_content")=="Y" ? " selected=\"selected\"" : ""; ?>><?php echo __("Yes", Zibbra_Plugin::LC_DOMAIN); ?></option>
				                                    <option value="N"<?php echo get_option("zibbra_bootstrap_container_content")!="Y" ? " selected=\"selected\"" : ""; ?>><?php echo __("No", Zibbra_Plugin::LC_DOMAIN); ?></option>
			                                    </select>
		                                    </td>
	                                    </tr>
                                    </table>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        <?php @submit_button(); ?>

    </form>

</div>