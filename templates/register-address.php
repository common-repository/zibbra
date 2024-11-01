<?php

defined("ZIBBRA_BASE_DIR") or die("Restricted access");

global $z_query;

$address_type = $z_query->get("address_type");
$address = $z_query->get("address",null);
$countries = $z_query->get("countries");

?>

<div class="form-group">
	<label for="<?php echo $address_type; ?>_street" class="required"><?php echo __("Street", Zibbra_Plugin::LC_DOMAIN); ?>:</label>
	<span class="info"><input id="<?php echo $address_type; ?>_street" type="text" name="<?php echo $address_type; ?>[street]" value="<?php echo isset($address) ? $address->getStreet() : ""; ?>" /></span>
	<span class="info-text"><?php echo __("Please enter your street name", Zibbra_Plugin::LC_DOMAIN); ?></span>
</div>
<div class="form-group">
	<label for="<?php echo $address_type; ?>_streetnr" class="required"><?php echo __("Nr", Zibbra_Plugin::LC_DOMAIN); ?>:</label>
	<span class="info"><input id="<?php echo $address_type; ?>_streetnr" type="text" name="<?php echo $address_type; ?>[streetnr]" value="<?php echo isset($address) ? $address->getStreetnr() : ""; ?>" /></span>
	<span class="info-text"><?php echo __("Please enter your street number", Zibbra_Plugin::LC_DOMAIN); ?></span>
</div>
<div class="form-group">
	<label for="<?php echo $address_type; ?>_box"><?php echo __("Box", Zibbra_Plugin::LC_DOMAIN); ?>: <span class="optional">(<?php echo __("Optional", Zibbra_Plugin::LC_DOMAIN); ?>)</span></label>
	<input id="<?php echo $address_type; ?>_box" type="text" name="<?php echo $address_type; ?>[box]" value="<?php echo isset($address) ? $address->getBox() : ""; ?>" />
</div>
<div class="form-group">
	<label for="<?php echo $address_type; ?>_zipcode" class="required"><?php echo __("Zipcode", Zibbra_Plugin::LC_DOMAIN); ?>:</label>
	<span class="info"><input id="<?php echo $address_type; ?>_zipcode" type="text" name="<?php echo $address_type; ?>[zipcode]" value="<?php echo isset($address) ? $address->getZipcode() : ""; ?>" /></span>
	<span class="info-text"><?php echo __("Please enter your zipcode", Zibbra_Plugin::LC_DOMAIN); ?></span>
</div>
<div class="form-group">
	<label for="<?php echo $address_type; ?>_city" class="required"><?php echo __("City", Zibbra_Plugin::LC_DOMAIN); ?>:</label>
	<span class="info"><input id="<?php echo $address_type; ?>_city" type="text" name="<?php echo $address_type; ?>[city]" value="<?php echo isset($address) ? $address->getCity() : ""; ?>" /></span>
	<span class="info-text"><?php echo __("Please enter your city", Zibbra_Plugin::LC_DOMAIN); ?></span>
</div>
<div class="form-group">
	<label for="<?php echo $address_type; ?>_countrycode" class="required"><?php echo __("Country", Zibbra_Plugin::LC_DOMAIN); ?>:</label>
	<span class="info">
		<select id="<?php echo $address_type; ?>_countrycode" name="<?php echo $address_type; ?>[countrycode]">
			<?php foreach($countries as $oCountry): ?>
				<option value="<?php echo $oCountry->getCountrycode(); ?>"<?php if((isset($address) && $address->getCountrycode()==$oCountry->getCountrycode()) || (!isset($address) && $oCountry->isDefault())): ?> selected="selected"<?php endif; ?>><?php echo $oCountry->getName(); ?></option>
			<?php endforeach; ?>
		</select>
	</span>
	<span class="info-text"><?php echo __("Please enter your country", Zibbra_Plugin::LC_DOMAIN); ?></span>
</div>