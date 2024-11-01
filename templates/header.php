<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>

<?php if(get_option("zibbra_wrapped_theme","Y")=="N"): ?>

<?php get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		
<?php endif; ?>

<?php if(!empty($title)): ?>

	<?php if(get_option("zibbra_bootstrap_container_title","N")=="Y"): ?>

		<div class="container">

	<?php endif; ?>

	<header class="entry-header">
		<h1 class="entry-title"><?php echo $title; ?></h1>
	</header>

	<?php if(get_option("zibbra_bootstrap_container_title","N")=="Y"): ?>

		</div>

	<?php endif; ?>

<?php endif; ?>

<?php if(get_option("zibbra_bootstrap_container_content","N")=="Y"): ?>

	<div class="container">

<?php endif; ?>
