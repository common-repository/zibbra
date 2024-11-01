<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>
<p>
	<label for="<?php echo $this->get_field_id("title"); ?>"><?php _e("Title:"); ?></label> 
	<input type="text" id="<?php echo $this->get_field_id("title"); ?>" name="<?php echo $this->get_field_name("title"); ?>" value="<?php echo esc_attr($title); ?>" class="widefat" />
</p>
<p>
	<label for="<?php echo $this->get_field_name("size"); ?>"><?php echo __("Thumbnail size:", Zibbra_Plugin::LC_DOMAIN); ?></label>
	<input type="number" min="50" max="250" step="5" id="<?php echo $this->get_field_id("size"); ?>" name="<?php echo $this->get_field_name("size"); ?>" value="<?php echo esc_attr($size); ?>" />
</p>