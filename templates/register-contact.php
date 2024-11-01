<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>

<!-- Personal Information -->
<section class="zibbra-contact">
	<h4><?php echo __("Personal Information", Zibbra_Plugin::LC_DOMAIN); ?></h4>
	<div class="form-group">
		<label for="contact_title"><?php echo __("Title", Zibbra_Plugin::LC_DOMAIN); ?>: <span class="optional">(<?php echo __("Optional", Zibbra_Plugin::LC_DOMAIN); ?>)</span></label>
		<input type="text" id="contact_title" name="contact[title]" value="<?php echo isset($contact) ? $contact->getTitle() : ""; ?>" />
	</div>
	<div class="form-group">
		<label for="contact_firstname" class="required"><?php echo __("Firstname", Zibbra_Plugin::LC_DOMAIN); ?>:</label>
		<span class="info"><input id="contact_firstname" type="text" name="contact[firstname]" value="<?php echo isset($contact) ? $contact->getFirstname() : ""; ?>" /></span>
		<span class="info-text"><?php echo __("Please enter your firstname", Zibbra_Plugin::LC_DOMAIN); ?></span>
	</div>
	<div class="form-group">
		<label for="contact_lastname" class="required"><?php echo __("Lastname", Zibbra_Plugin::LC_DOMAIN); ?>:</label>
		<span class="info"><input id="contact_lastname" type="text" name="contact[lastname]" value="<?php echo isset($contact) ? $contact->getLastname() : ""; ?>" /></span>
		<span class="info-text"><?php echo __("Please enter your lastname", Zibbra_Plugin::LC_DOMAIN); ?></span>
	</div>
	<div class="form-group radioset">
		<label class="required"><?php echo __("Gender", Zibbra_Plugin::LC_DOMAIN); ?>:</label>
		<fieldset>
			<span class="row">
				<input type="radio" name="contact[gender]" value="m" id="contact_gender_male"<?php if((isset($contact) && $contact->getGender()=="m") || !isset($contact)): ?> checked="checked"<?php endif; ?> />
				<label for="contact_gender_male"><?php echo __("Male", Zibbra_Plugin::LC_DOMAIN); ?></label>
			</span>
			<span class="row">
				<input type="radio" name="contact[gender]" value="v" id="contact_gender_female"<?php if(isset($contact) && $contact->getGender()=="v"): ?> checked="checked"<?php endif; ?> />
				<label for="contact_gender_female"><?php echo __("Female", Zibbra_Plugin::LC_DOMAIN); ?></label>
			</span>
		</fieldset>
	</div>
	<div class="form-group">
		<label for="contact_mobile"><?php echo __("Mobile", Zibbra_Plugin::LC_DOMAIN); ?>: <span class="optional">(<?php echo __("Optional", Zibbra_Plugin::LC_DOMAIN); ?>)</span></label>
		<span class="info"><input type="text" id="contact_mobile" name="contact[mobile]" value="<?php echo isset($contact) ? $contact->getMobile() : ""; ?>" /></span>
		<span class="info-text"><?php echo __("Please enter your mobile number, format: +32 (123) 123456", Zibbra_Plugin::LC_DOMAIN); ?></span>
	</div>
	<div class="form-group">
		<label for="contact_phone"><?php echo __("Phone", Zibbra_Plugin::LC_DOMAIN); ?>: <span class="optional">(<?php echo __("Optional", Zibbra_Plugin::LC_DOMAIN); ?>)</span></label>
		<span class="info"><input type="text" id="contact_phone" name="contact[phone]" value="<?php echo isset($contact) ? $contact->getPhone() : ""; ?>" /></span>
		<span class="info-text"><?php echo __("Please enter your phone number, format: +32 (123) 123456", Zibbra_Plugin::LC_DOMAIN); ?></span>
	</div>
</section>
<!-- End account section information -->