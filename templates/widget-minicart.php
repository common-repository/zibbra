<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>
<?php echo $args['before_widget']; ?>
<div class="zibbra-minicart">
	<?php include("widget-minicart-ajax.php"); ?>
</div>
<script>
Zibbra.Minicart.registerWidget({
	title: "<?php echo !empty($title) ? $args['before_title'].$title.$args['after_title'] : ""; ?>",
	popup: "<?php echo $popup; ?>",
	links: "<?php echo $links; ?>",
	site_url: "<?php echo site_url(); ?>"
});
</script>
<?php echo $args['after_widget']; ?>