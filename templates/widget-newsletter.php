<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>
<?php echo $args['before_widget']; ?>
<?php if(!empty($title)): ?>
	<?php echo $args['before_title'].$title.$args['after_title']; ?>
<?php endif; ?>
<div id="zibbra-newsletter">
	<p><?php echo $description; ?></p>
	<form method="post" id="zibbra-newsletter-form">
		<?php echo wp_nonce_field("subscribe",Zibbra_Plugin::FORM_ACTION); ?>
		<input type="hidden" name="action" value="zibbra_newsletter_subscribe" />
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr>
				<td><input type="text" id="zibbra-newsletter-email" name="email" value="<?php echo $input; ?>" /></td>
				<td>
					<?php if(!empty($icon)): ?>
						<button id="zibbra-newsletter-subscribe"><span class="icon <?php echo $icon; ?>"></span></button>
					<?php else:?>
						<input type="submit" name="submit" id="zibbra-newsletter-subscribe" value="<?php echo $button; ?>" />
					<?php endif; ?>
				</td>
			</tr>
		</table>
	</form>
</div>
<?php echo $args['after_widget']; ?>