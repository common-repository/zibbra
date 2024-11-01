<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>
<?php echo $args['before_widget']; ?>
<?php if(!empty($icon) || !empty($title)): ?>
	<a class="toggle">
		<?php if(!empty($icon)): ?>
			<span class="icon <?php echo $icon; ?>"></span>
		<?php endif; ?>
		<?php if(!empty($title)): ?>
			<?php if(is_customer_logged_in()): ?>
				<span class="title"><?php echo __("Your Account", Zibbra_Plugin::LC_DOMAIN); ?></span>
			<?php else: ?>
				<span class="title"><?php echo $title; ?></span>
			<?php endif; ?>
		<?php endif; ?>
	</a>
<?php endif; ?>
<div class="zibbra-login-widget">
	<?php if(is_customer_logged_in()): ?>
		<ul>
			<li><a href="<?php echo site_url("/zibbra/account/"); ?>"><?php echo __("Account page", Zibbra_Plugin::LC_DOMAIN); ?></a></li>
			<li><a href="<?php echo wp_logout_url(home_url()); ?>"><?php echo __("Log out", Zibbra_Plugin::LC_DOMAIN); ?></a></li>
		</ul>
	<?php else: ?>
		<div id="zibbra-login">
			<?php

			$params = array(
				"redirect"=>isset($_GET['return_to']) ? esc_url($_GET['return_to'], ['http', 'https']) : site_url("/zibbra/account/"),
				"form_id"=>"zibbra-widget-login-form",
				"label_username"=>__("E-mail", Zibbra_Plugin::LC_DOMAIN),
				"label_password"=>__("Password", Zibbra_Plugin::LC_DOMAIN),
				"label_remember"=>__("Remember me", Zibbra_Plugin::LC_DOMAIN),
				"label_log_in"=>__("Log In", Zibbra_Plugin::LC_DOMAIN),
				"remember"=>true
			);

			wp_login_form($params);

			?>
		</div>
	<?php endif; ?>
</div>
<?php echo $args['after_widget']; ?>