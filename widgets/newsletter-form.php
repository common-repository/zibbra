<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
</p>
<p>
<label for="<?php echo $this->get_field_id( 'description' ); ?>"><?php _e( 'Description:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'description' ); ?>" name="<?php echo $this->get_field_name( 'description' ); ?>" type="text" value="<?php echo esc_attr( $description ); ?>">
</p>
<p>
<label for="<?php echo $this->get_field_id( 'input' ); ?>"><?php _e( 'Input text:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'input' ); ?>" name="<?php echo $this->get_field_name( 'input' ); ?>" type="text" value="<?php echo esc_attr( $input ); ?>">
</p>
<p>
<label for="<?php echo $this->get_field_id( 'button' ); ?>"><?php _e( 'Button text:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'button' ); ?>" name="<?php echo $this->get_field_name( 'button' ); ?>" type="text" value="<?php echo esc_attr( $button ); ?>">
</p>
<p>
<label for="<?php echo $this->get_field_id( 'icon' ); ?>"><?php _e( 'Icon class:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'icon' ); ?>" name="<?php echo $this->get_field_name( 'icon' ); ?>" type="text" value="<?php echo esc_attr( $icon ); ?>">
</p>