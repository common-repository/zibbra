<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>
<p>
<label for="<?php echo $this->get_field_id("title"); ?>"><?php echo __("Title", Zibbra_Plugin::LC_DOMAIN); ?>:</label> 
<input class="widefat" id="<?php echo $this->get_field_id("title"); ?>" name="<?php echo $this->get_field_name("title"); ?>" type="text" value="<?php echo esc_attr($title); ?>">
</p>
<p>
<label for="<?php echo $this->get_field_id("popup"); ?>"><?php echo __("Popup", Zibbra_Plugin::LC_DOMAIN); ?>:</label> 
<input class="widefat" id="<?php echo $this->get_field_id("popup"); ?>" name="<?php echo $this->get_field_name("popup"); ?>" type="radio" value="Y"<?php if($popup=="Y"): ?> checked<?php endif; ?>>&nbsp;<label><?php echo __("Yes", Zibbra_Plugin::LC_DOMAIN); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id("popup"); ?>" name="<?php echo $this->get_field_name("popup"); ?>" type="radio" value="N"<?php if($popup=="N"): ?> checked<?php endif; ?>>&nbsp;<label><?php echo __("No", Zibbra_Plugin::LC_DOMAIN); ?></label>
</p>
<p>
<label for="<?php echo $this->get_field_id("links"); ?>"><?php echo __("Show links?", Zibbra_Plugin::LC_DOMAIN); ?>:</label> 
<input class="widefat" id="<?php echo $this->get_field_id("links"); ?>" name="<?php echo $this->get_field_name("links"); ?>" type="radio" value="Y"<?php if($links=="Y"): ?> checked<?php endif; ?>>&nbsp;<label><?php echo __("Yes", Zibbra_Plugin::LC_DOMAIN); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id("links"); ?>" name="<?php echo $this->get_field_name("links"); ?>" type="radio" value="N"<?php if($links=="N"): ?> checked<?php endif; ?>>&nbsp;<label><?php echo __("No", Zibbra_Plugin::LC_DOMAIN); ?></label>
</p>
<p>
	<input type="checkbox" <?php if(esc_attr( $click ) == "Y") echo 'checked'; ?> value="Y" name="<?php echo $this->get_field_name( 'click' ); ?>" id="<?php echo $this->get_field_id( 'click' ); ?>">
	<label for="<?php echo $this->get_field_id( 'click' ); ?>"><?php _e( 'Click instead of hover ' ); ?></label>
</p>