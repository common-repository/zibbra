<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>
<p>
	<label for="<?php echo $this->get_field_id("title"); ?>"><?php echo __("Title:", Zibbra_Plugin::LC_DOMAIN); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id("title"); ?>" name="<?php echo $this->get_field_name("title"); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
</p>
<p>
	<label for="<?php echo $this->get_field_name("maxval"); ?>"><?php echo __("Maximum number of items:", Zibbra_Plugin::LC_DOMAIN); ?></label>
	<select id="<?php echo $this->get_field_id("maxval"); ?>" name="<?php echo $this->get_field_name("maxval"); ?>">
		<option <?php if(esc_attr( $maxval ) == 2) echo 'selected'; ?> value="2">2</option>
		<option <?php if(esc_attr( $maxval ) == 3) echo 'selected'; ?> value="3">3</option>
		<option <?php if(esc_attr( $maxval ) == 4) echo 'selected'; ?> value="4">4</option>
		<option <?php if(esc_attr( $maxval ) == 6) echo 'selected'; ?> value="6">6</option>
		<option <?php if(esc_attr( $maxval ) == 8) echo 'selected'; ?> value="8">8</option>
		<option <?php if(esc_attr( $maxval ) == 12) echo 'selected'; ?> value="12">12</option>
	</select>
</p>
<p>
	<label for="<?php echo $this->get_field_name("thumbsize"); ?>"><?php echo __("Thumbnail size?", Zibbra_Plugin::LC_DOMAIN); ?></label>
	<input type="number" min="100" max="300" step="10" id="<?php echo $this->get_field_id("thumbsize"); ?>" name="<?php echo $this->get_field_name("thumbsize"); ?>" value="<?php echo esc_attr($thumbsize); ?>" />
</p>
<p>
	<label for="<?php echo $this->get_field_name("sort_type"); ?>"><?php echo __("Sort type?", Zibbra_Plugin::LC_DOMAIN); ?></label>
	<select name="<?php echo $this->get_field_name("sort_type"); ?>" id="<?php echo $this->get_field_id("sort_type"); ?>">
		<?php foreach($sort_type_options as $key=>$label): ?>
			<option value="<?php echo $key; ?>"<?php echo esc_attr( $sort_type ) == $key ? " selected=\"selected\"" : ""; ?>><?php echo $label; ?></option>
		<?php endforeach; ?>
	</select>
</p>
<p>
	<label for="<?php echo $this->get_field_name("sort_dir"); ?>"><?php echo __("Sort direction?", Zibbra_Plugin::LC_DOMAIN); ?></label>
	<select name="<?php echo $this->get_field_name("sort_dir"); ?>" id="<?php echo $this->get_field_id("sort_dir"); ?>">
		<?php foreach($sort_dir_options as $key=>$label): ?>
			<option value="<?php echo $key; ?>"<?php echo esc_attr( $sort_dir ) == $key ? " selected=\"selected\"" : ""; ?>><?php echo $label; ?></option>
		<?php endforeach; ?>
	</select>
</p>
<p>
	<label for="<?php echo $this->get_field_name("categoryid"); ?>"><?php echo __("Category?", Zibbra_Plugin::LC_DOMAIN); ?></label>
	<select name="<?php echo $this->get_field_name("categoryid"); ?>" id="<?php echo $this->get_field_id("categoryid"); ?>">
		<?php foreach($categories as $category): /** @var ZCategory $category */ ?>
			<option value="<?php echo $category->getCategoryid(); ?>"<?php echo esc_attr( $categoryid ) == $category->getCategoryid() ? " selected=\"selected\"" : ""; ?>><?php echo $category->getName(); ?></option>
		<?php endforeach; ?>
	</select>
</p>
<p>
	<label for="<?php echo $this->get_field_id("description"); ?>"><?php echo __("Show description?", Zibbra_Plugin::LC_DOMAIN); ?>:</label>
	<?php foreach($show_description_options as $key=>$label): ?>
		<input class="widefat" id="<?php echo $this->get_field_id("description"); ?>" name="<?php echo $this->get_field_name("description"); ?>" type="radio" value="<?php echo $key; ?>"<?php if($description == $key): ?> checked<?php endif; ?>>&nbsp;<label><?php echo $label; ?></label>
	<?php endforeach; ?>
</p>