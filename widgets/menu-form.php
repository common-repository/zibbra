<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>
<p>
	<label for="<?php echo $this->get_field_id("override"); ?>"><?php echo __("Override main menu?", Zibbra_Plugin::LC_DOMAIN); ?></label>
	<select class="widefat" id="<?php echo $this->get_field_id("override"); ?>" name="<?php echo $this->get_field_name("override"); ?>">
		<option value="Y"<?php echo $override=="Y" ? " selected=\"selected\"" : ""; ?>>Yes</option>
		<option value="N"<?php echo $override!="Y" ? " selected=\"selected\"" : ""; ?>>No</option>
	</select>
</p>
<p>
	<label for="<?php echo $this->get_field_id("show_home"); ?>"><?php echo __("Show Home?", Zibbra_Plugin::LC_DOMAIN); ?></label>
	<select class="widefat" id="<?php echo $this->get_field_id("show_home"); ?>" name="<?php echo $this->get_field_name("show_home"); ?>">
		<option value="Y"<?php echo $show_home=="Y" ? " selected=\"selected\"" : ""; ?>>Yes</option>
		<option value="N"<?php echo $show_home!="Y" ? " selected=\"selected\"" : ""; ?>>No</option>
	</select>
</p>
<p>
	<label for="<?php echo $this->get_field_id("depth"); ?>">Menu depth</label>
	<select class="widefat" id="<?php echo $this->get_field_id("depth"); ?>" name="<?php echo $this->get_field_name("depth"); ?>">
		<option value="1"<?php echo $depth=="1" ? " selected=\"selected\"" : ""; ?>>1</option>
		<option value="2"<?php echo $depth=="2" ? " selected=\"selected\"" : ""; ?>>2</option>
		<option value="3"<?php echo $depth=="3" ? " selected=\"selected\"" : ""; ?>>3</option>
	</select>
</p>
<p>
	<label for="<?php echo $this->get_field_id("location"); ?>">Menu location</label>
	<select class="widefat" id="<?php echo $this->get_field_id("location"); ?>" name="<?php echo $this->get_field_name("location"); ?>">
	<?php foreach($menu_locations as $loc=>$locid): ?>
		<option value="<?php echo $loc; ?>"<?php echo $location==$loc ? " selected=\"selected\"" : ""; ?>><?php echo $loc; ?></option>
	<?php endforeach; ?>
	</select>
</p>