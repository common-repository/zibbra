<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>
<p>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
</p>
<p>
	<label for="<?php echo $this->get_field_id( 'icon' ); ?>"><?php _e( 'Icon:' ); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'icon' ); ?>" name="<?php echo $this->get_field_name( 'icon' ); ?>" type="text" value="<?php echo esc_attr( $icon ); ?>">
</p>
<p>
	<input type="checkbox" <?php if(esc_attr( $popup ) == "Y") echo 'checked'; ?> value="Y" name="<?php echo $this->get_field_name( 'popup' ); ?>" id="<?php echo $this->get_field_id( 'popup' ); ?>">
	<label for="<?php echo $this->get_field_id( 'popup' ); ?>"><?php _e( 'Popup ' ); ?></label>
</p>
<p>
	<input type="checkbox" <?php if(esc_attr( $click ) == "Y") echo 'checked'; ?> value="Y" name="<?php echo $this->get_field_name( 'click' ); ?>" id="<?php echo $this->get_field_id( 'click' ); ?>">
	<label for="<?php echo $this->get_field_id( 'click' ); ?>"><?php _e( 'Click instead of hover ' ); ?></label>
</p>