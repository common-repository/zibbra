<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>
<?php echo $args['before_widget']; ?>
<h3><?php echo __("Powered by", Zibbra_Plugin::LC_DOMAIN); ?></h3>
<div id="powered-by-zibbra">
	<p align="center">
		<a href="https://www.zibbra.com" target="_blank" title="<?php echo __("Powered by", Zibbra_Plugin::LC_DOMAIN); ?> Zibbra">
			<img src="<?php echo plugins_url("images/zibbra_logo.png",ZIBBRA_BASE_DIR."/images"); ?>" width="150" border="0" />
		</a>
	</p>
</div>
<?php echo $args['after_widget']; ?>