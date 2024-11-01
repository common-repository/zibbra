<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>
<p>
	<label for="<?php echo $this->get_field_id("title"); ?>"><?php echo __("Title:", Zibbra_Plugin::LC_DOMAIN); ?></label> 
	<input class="widefat" id="<?php echo $this->get_field_id("title"); ?>" name="<?php echo $this->get_field_name("title"); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
</p>
<p>
	<label for="<?php echo $this->get_field_name("maxval"); ?>"><?php echo __("Maximum number of items:", Zibbra_Plugin::LC_DOMAIN); ?></label>
	<select id="<?php echo $this->get_field_id("maxval"); ?>" name="<?php echo $this->get_field_name("maxval"); ?>">
		<option <?php if(esc_attr( $maxval ) == 1) echo 'selected'; ?> value="1">1</option>
		<option <?php if(esc_attr( $maxval ) == 2) echo 'selected'; ?> value="2">2</option>
		<option <?php if(esc_attr( $maxval ) == 3) echo 'selected'; ?> value="3">3</option>
		<option <?php if(esc_attr( $maxval ) == 4) echo 'selected'; ?> value="4">4</option>
		<option <?php if(esc_attr( $maxval ) == 5) echo 'selected'; ?> value="5">5</option>
	</select>
</p>
<p>
<label for="<?php echo $this->get_field_name("thumbsize"); ?>"><?php echo __("Thumbnail size?", Zibbra_Plugin::LC_DOMAIN); ?></label>
<input type="number" min="100" max="300" step="10" id="<?php echo $this->get_field_id("thumbsize"); ?>" name="<?php echo $this->get_field_name("thumbsize"); ?>" value="<?php echo esc_attr($thumbsize); ?>" />
</p>