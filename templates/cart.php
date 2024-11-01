<?php

defined("ZIBBRA_BASE_DIR") or die("Restricted access");

$cart = z_get_cart();

define("VALUTA_SYMBOL", $cart->getValutaSymbol());
define("SHOW_INCL_VAT", get_option("zibbra_cart_incl_vat", "N") == "Y");
define("LINK_PRODUCTS", get_option("zibbra_cart_link_products", "Y") == "Y");
define("LOCK_QUANTITY", get_option("zibbra_cart_lock_quantity", "N") == "Y");
define("LOCK_ADDONS", get_option("zibbra_cart_lock_addons", "N") == "Y");
define("CONTINUE_SHOPPING", get_option("zibbra_cart_show_continue_shopping", "Y") == "Y");
define("CONTINUE_SHOPPING_URL", get_option("zibbra_cart_url_continue_shopping", "") === "" ? site_url("/") : get_option("zibbra_cart_url_continue_shopping"));

$shipping = false;
$payment = false;

?>
<?php get_zibbra_header(__("Your shopping cart", Zibbra_Plugin::LC_DOMAIN)); ?>

	<?php if(!$cart->isEmpty()): ?>
						
		<form method="post" name="zibbra-cart-form" id="zibbra-cart-form">
				
			<div class="zibbra-cart-table">

				<?php get_template_part("cart", "table"); ?>
			
			</div>
				
			<div class="zibbra-cart-sidebar">
				
				<div class="zibbra-cart-totals">
				
					<table cellpadding="0" cellspacing="4" border="0">			
						<thead>					
							<tr>						
								<th colspan="2"><?php echo __("Cart totals", Zibbra_Plugin::LC_DOMAIN); ?></th>						
							</tr>					
						</thead>			
						<tbody>					
							<tr>						
								<td><?php echo __("Total VAT excl.", Zibbra_Plugin::LC_DOMAIN); ?>:</td>
								<td class="right amount_excl"><?php echo VALUTA_SYMBOL; ?>&nbsp;<?php echo number_format($cart->getTotalExcl(),2,",",""); ?></td>
							</tr>
							<tr>						
								<td><?php echo __("Total VAT", Zibbra_Plugin::LC_DOMAIN); ?>:</td>
								<td class="right amount_vat"><?php echo VALUTA_SYMBOL; ?>&nbsp;<?php echo number_format($cart->getTotalVat(),2,",",""); ?></td>
							</tr>
							<tr>						
								<td><?php echo __("Total VAT incl.", Zibbra_Plugin::LC_DOMAIN); ?>:</td>
								<td class="right amount_incl"><?php echo VALUTA_SYMBOL; ?>&nbsp;<?php echo number_format($cart->getTotalAmount(false),2,",",""); ?></td>
							</tr>				
						</tbody>				
					</table>
							
				</div>
				
				<div class="zibbra-cart-buttons">

					<?php if(CONTINUE_SHOPPING): ?>

						<a class="zibbra-cart-continue btn btn-default" href="<?php echo CONTINUE_SHOPPING_URL; ?>">
							<span><?php echo __("Continue shopping", Zibbra_Plugin::LC_DOMAIN); ?></span>
						</a>

					<?php endif; ?>
							
					<a class="zibbra-cart-checkout btn btn-primary" href="<?php echo site_url("/zibbra/checkout/"); ?>">
						<span><?php echo __("Checkout", Zibbra_Plugin::LC_DOMAIN); ?></span>
						<span class="arrow-right">&nbsp;&raquo;</span>
					</a>
							
				</div>
			
			</div>
			
			<div class="clear"></div>
		
		</form>
	
	<?php else: ?>
	
		<p><?php echo __("Your shopping cart is empty", Zibbra_Plugin::LC_DOMAIN); ?></p>
		
	<?php endif; ?>

	<script>
		Zibbra._("UPDATING_CART","<?php echo __("Update shopping cart, please wait...", Zibbra_Plugin::LC_DOMAIN); ?>");
	</script>

<?php get_zibbra_footer(); ?>
