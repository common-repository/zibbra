<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>
<?php if(count($notifications)>0): ?>
	<?php echo $args['before_widget']; ?>
	<ul id="zibbra-notify">
		<?php foreach($notifications as $notification): ?>
			<li class="<?php echo $notification->getStatus(); ?>"><?php echo $notification->getMessage(); ?></li>
			<?php $notification->confirm(); ?>
		<?php endforeach; ?>
	</ul>
	<?php echo $args['after_widget']; ?>
<?php endif; ?>