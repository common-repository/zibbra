<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>
<p>
	<label for="<?php echo $this->get_field_id("title"); ?>"><?php echo __("Title:", Zibbra_Plugin::LC_DOMAIN); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id("title"); ?>" name="<?php echo $this->get_field_name("title"); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
</p>