<?php

defined("ZIBBRA_BASE_DIR") or die("Restricted access");

?>

<form id="bpost_frontend_form" action="<?php echo $frontend_uri; ?>" method="post">
	<?php foreach($config as $key=>$value): ?>
		<?php if (is_array($value)): ?>
			<?php foreach($value as $subkey=>$subvalue): ?>
				<input type="hidden" name="<?php echo $key; ?>" value="<?php echo $subvalue; ?>" />
			<?php endforeach; ?>
		<?php else: ?>
			<input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>" />
		<?php endif; ?>
	<?php endforeach; ?>
</form>

<script type="text/javascript">

	jQuery(document).ready(function() {

		jQuery("#bpost_frontend_form").submit();

	}); // end ready

</script>