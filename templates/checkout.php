<?php
/**
 * @var ZCustomer $customer
 * @var ZCart $cart
 */
defined("ZIBBRA_BASE_DIR") or die("Restricted access");

$cart = z_get_cart();
$customer = z_get_customer();
$order = z_get_order();

define("VALUTA_SYMBOL", $cart->getValutaSymbol());
define("LINK_PRODUCTS", get_option("zibbra_checkout_link_products","Y")=="Y");
define("ALLOW_COMMENTS", get_option("zibbra_checkout_allow_comments","N")=="Y");
define("ENABLE_VOUCHERS", get_option("zibbra_checkout_vouchers","N")=="Y");
define("AGREE_TERMS", get_option("zibbra_checkout_agree_terms","N")=="Y");
define("URL_TERMS", get_option("zibbra_checkout_url_terms",null));

?>
<?php get_zibbra_header(__("Checkout", Zibbra_Plugin::LC_DOMAIN)); ?>

	<div id="zibbra-checkout">

		<div id="zibbra-checkout-login-account">
			<div class="header"><span class="step">1</span><?php echo __("Account and address", Zibbra_Plugin::LC_DOMAIN); ?></div>
			<?php if(empty($customer)): ?>
				<div id="zibbra-checkout-login">
					<p><?php echo __("Customer login", Zibbra_Plugin::LC_DOMAIN); ?></p>
					<?php get_template_part("checkout", "login"); ?>					
				</div>
			<?php else: ?>
				<div id="zibbra-checkout-account">
					<?php get_template_part("checkout", "account"); ?>
				</div>
			<?php endif; ?>
		</div>
		
		<form id="zibbra-checkout-form" method="post">
			<?php echo wp_nonce_field("do_checkout",Zibbra_Plugin::FORM_ACTION); ?>
		
			<div id="zibbra-checkout-payment-shipping">
				<div class="header"><span class="step">2</span><?php echo __("Shipping method", Zibbra_Plugin::LC_DOMAIN); ?></div>
				<?php get_template_part("checkout", "shipping"); ?>
				<div class="header"><span class="step">3</span><?php echo __("Payment method", Zibbra_Plugin::LC_DOMAIN); ?></div>
				<?php get_template_part("checkout", "payment"); ?>
			</div>
			
			<div id="zibbra-checkout-order">
				<div class="header"><span class="step">4</span><?php echo __("Review your order", Zibbra_Plugin::LC_DOMAIN); ?></div>
				<div id="zibbra-checkout-cart">
					<?php get_template_part("checkout", "cart"); ?>
				</div>
				<?php if(ENABLE_VOUCHERS): ?>
					<div class="header"><span class="step">5</span><?php echo __("Discount voucher", Zibbra_Plugin::LC_DOMAIN); ?></div>
					<div id="zibbra-checkout-voucher" class="form-inline" nonce="<?php echo wp_create_nonce("do_apply_voucher"); ?>" style="margin:10px 0;">
						<?php if($cart->hasDiscount()): ?>
							<div class="form-group">
								<input type="text" name="voucher" id="voucher" class="form-control" value="<?php echo $cart->getDiscount()->getCode(); ?>" disabled="disabled" />
								<button class="btn btn-default"><?php echo __("Remove voucher", Zibbra_Plugin::LC_DOMAIN); ?></button>
							</div>
						<?php else: ?>
							<div class="form-group">
								<input type="text" name="voucher" id="voucher" class="form-control" placeholder="<?php echo __("Enter voucher code", Zibbra_Plugin::LC_DOMAIN); ?>" />
								<button class="btn btn-default"><?php echo __("Apply voucher", Zibbra_Plugin::LC_DOMAIN); ?></button>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
				<?php if(ALLOW_COMMENTS): ?>
					<?php if(ENABLE_VOUCHERS): ?>
						<div class="header"><span class="step">6</span><?php echo __("Optional comments", Zibbra_Plugin::LC_DOMAIN); ?></div>
					<?php else: ?>
						<div class="header"><span class="step">5</span><?php echo __("Optional comments", Zibbra_Plugin::LC_DOMAIN); ?></div>
					<?php endif; ?>
					<div id="zibbra-checkout-comments">
						<textarea name="comments" id="comments"><?php echo $checkout->comments; ?></textarea>
					</div>
				<?php endif; ?>
				<div class="header"><span class="step"><?php if(ENABLE_VOUCHERS && ALLOW_COMMENTS): ?>7<?php else: ?><?php if(ENABLE_VOUCHERS || ALLOW_COMMENTS): ?>6<?php else: ?>5<?php endif; ?><?php endif; ?></span><?php echo __("Confirm your order", Zibbra_Plugin::LC_DOMAIN); ?></div>
				<div id="zibbra-checkout-confirm">
					<?php if(AGREE_TERMS && !$order instanceof ZOrder): ?>
						<p>
							<label>
								<input type="checkbox" id="agree_terms" />
								<?php $url_terms = URL_TERMS; if(!empty($url_terms)): ?>
									<span>&nbsp;<?php echo __("I agree to the", Zibbra_Plugin::LC_DOMAIN); ?>&nbsp;<a href="<?php echo $url_terms; ?>" target="_blank"><?php echo __("general terms & conditions", Zibbra_Plugin::LC_DOMAIN); ?></a></span>
								<?php else: ?>
									<span>&nbsp;<?php echo __("I agree to the", Zibbra_Plugin::LC_DOMAIN); ?>&nbsp;<?php echo __("general terms & conditions", Zibbra_Plugin::LC_DOMAIN); ?></span>
								<?php endif; ?>
							</label>
						</p>
					<?php endif; ?>
					<p><?php echo __("Forgot an item?", Zibbra_Plugin::LC_DOMAIN); ?>&nbsp;<a href="<?php echo site_url("/zibbra/cart/"); ?>"><?php echo __("Edit your shopping cart", Zibbra_Plugin::LC_DOMAIN); ?></a></p>
					<input type="submit" name="submit" class="btn btn-primary" value="<?php echo $order instanceof ZOrder ? __("Update order", Zibbra_Plugin::LC_DOMAIN) : __("Place order", Zibbra_Plugin::LC_DOMAIN); ?>" disabled="disabled" class="disabled" />
					<?php if($order instanceof ZOrder): ?>
						<a href="<?php echo site_url("/zibbra/checkout/cancel/"); ?>" class="btn btn-default" onclick="return confirm('<?php echo __("Are you sure you want to cancel your order?", Zibbra_Plugin::LC_DOMAIN); ?>')"><?php echo __("Cancel order", Zibbra_Plugin::LC_DOMAIN); ?></a>
					<?php endif; ?>
				</div>
			</div>	
					
		</form>
	
	</div>
	
	<script>
	Zibbra._("LOADING","<?php echo __("Loading, please wait...", Zibbra_Plugin::LC_DOMAIN); ?>");
	Zibbra._("PROCESSING","<?php echo __("Processing your order, please wait...", Zibbra_Plugin::LC_DOMAIN); ?>");
	</script>

<?php get_zibbra_footer(); ?>