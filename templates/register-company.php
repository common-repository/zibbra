<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>

<!-- Company Information -->
<section class="zibbra-company">
	<h4><?php echo __("Company Information", Zibbra_Plugin::LC_DOMAIN); ?></h4>
	<div class="form-group">
		<label for="company_name"><?php echo __("Company Name", Zibbra_Plugin::LC_DOMAIN); ?>: <span class="optional">(<?php echo __("Optional", Zibbra_Plugin::LC_DOMAIN); ?>)</span></label>
		<input type="text" id="company_name" name="company[name]" value="<?php echo isset($company) ? $company->getName() : ""; ?>" />
	</div>
	<div class="form-group">
		<label for="company_phone"><?php echo __("Phone", Zibbra_Plugin::LC_DOMAIN); ?>: <span class="optional">(<?php echo __("Optional", Zibbra_Plugin::LC_DOMAIN); ?>)</span></label>
		<span class="info"><input type="text" id="company_phone" name="company[phone]" value="<?php echo isset($company) ? $company->getPhone() : ""; ?>" /></span>
		<span class="info-text"><?php echo __("Please enter the company phone number, format: +32 (123) 123456", Zibbra_Plugin::LC_DOMAIN); ?></span>
	</div>
	<div class="form-group">
		<label for="company_fax"><?php echo __("Fax", Zibbra_Plugin::LC_DOMAIN); ?>: <span class="optional">(<?php echo __("Optional", Zibbra_Plugin::LC_DOMAIN); ?>)</span></label>
		<span class="info"><input type="text" id="company_fax" name="company[fax]" value="<?php echo isset($company) ? $company->getFax() : ""; ?>" /></span>
		<span class="info-text"><?php echo __("Please enter the company fax number, format: +32 (123) 123456", Zibbra_Plugin::LC_DOMAIN); ?></span>
	</div>
	<div class="form-group">
		<label for="company_email"><?php echo __("E-mail", Zibbra_Plugin::LC_DOMAIN); ?>: <span class="optional">(<?php echo __("Optional", Zibbra_Plugin::LC_DOMAIN); ?>)</span></label>
		<span class="info"><input type="email" id="company_email" name="company[email]" value="<?php echo isset($company) ? $company->getEmail() : ""; ?>" /></span>
		<span class="info-text"><?php echo __("Please enter the general company email adress", Zibbra_Plugin::LC_DOMAIN); ?></span>
	</div>
	<div class="form-group">
		<label for="company_website"><?php echo __("Website", Zibbra_Plugin::LC_DOMAIN); ?>: <span class="optional">(<?php echo __("Optional", Zibbra_Plugin::LC_DOMAIN); ?>)</span></label>
		<span class="info"><input type="text" id="company_website" name="company[website]" value="<?php echo isset($company) ? $company->getWebsite() : ""; ?>" /></span>
		<span class="info-text"><?php echo __("Please enter the company website adress, format: http://www.yourdomain.ext", Zibbra_Plugin::LC_DOMAIN); ?></span>
	</div>
	<div class="form-group">
		<label for="company_vat_nr"><?php echo __("VAT Number", Zibbra_Plugin::LC_DOMAIN); ?>: <span class="optional">(<?php echo __("Optional", Zibbra_Plugin::LC_DOMAIN); ?>)</span></label>
		<span class="info"><input type="text" id="company_vat_nr" name="company[vat_nr]" value="<?php echo isset($company) ? $company->getVatNr() : ""; ?>" /></span>
		<span class="info-text"><?php echo __("Please enter the VAT number for your company", Zibbra_Plugin::LC_DOMAIN); ?></span>
	</div>
</section>
<!-- End Company Information -->