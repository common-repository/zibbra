<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); 

global $z_query;

$customer = z_get_customer();
$order = z_get_order();
$confirm_url = $z_query->get("confirm_url");
$cancel_url = $z_query->get("cancel_url");

?>
<?php get_zibbra_header(__("Confirm your payment", Zibbra_Plugin::LC_DOMAIN)); ?>
	
	<div id="zibbra-payment">
		
		<form id="zibbra-payment-form" action="<?php echo $confirm_url; ?>" method="post">
		
			<?php echo wp_nonce_field("do_confirm", Zibbra_Plugin::FORM_ACTION); ?>
		
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<tbody>
					<tr>
						<td valign="top">
							
							<div id="zibbra-payment-account">
								<?php get_template_part("payment", "account"); ?>
							</div>
								
						</td>
						<td valign="top">
			
							<h3>
								<span><?php echo __("Review your order", Zibbra_Plugin::LC_DOMAIN); ?></span>
							</h3>
							
							<div id="zibbra-payment-order">
								<?php get_template_part("payment", "order"); ?>
							</div>
							
							<p>&nbsp;</p>
							
							<div id="zibbra-payment-confirm">
								<h3>
									<span><?php echo __("Confirm your payment", Zibbra_Plugin::LC_DOMAIN); ?></span>
								</h3>
								<input type="submit" name="submit" class="btn btn-primary" value="<?php echo __("Pay now", Zibbra_Plugin::LC_DOMAIN); ?>" />
								<a href="<?php echo $cancel_url; ?>" class="btn btn-default"><?php echo __("Cancel payment", Zibbra_Plugin::LC_DOMAIN); ?></a>
							</div>
							
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	
	</div>

<?php get_zibbra_footer(); ?>