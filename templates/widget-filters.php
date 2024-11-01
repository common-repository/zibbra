<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>
<?php echo $args['before_widget']; ?>
<?php if(!empty($title)): ?>
	<?php echo $args['before_title'].$title.$args['after_title']; ?>
<?php endif; ?>
	<div class="zibbra-catalog-filters">
		<?php get_template_part("catalog", "filters"); ?>
	</div>
<?php echo $args['after_widget']; ?>