<?php

defined("ZIBBRA_BASE_DIR") or die("Restricted access");

global $z_query;
/** @var ZProduct[] $products */
$products = $z_query->get("products", null);

define("THUMBNAIL_SIZE", get_option("zibbra_catalog_category_thumbnail_size","120"));

?>
<?php get_zibbra_header(); ?>

<div class="zibbra-search">

	<?php get_search_form(); ?>

	<div class="zibbra-search-products">

		<h2 class="search-title"><?php echo __("Products", Zibbra_Plugin::LC_DOMAIN); ?></h2>

		<div class="search-results">

			<?php if (count($products) === 0) : ?>
				<div class="alert alert-warning">
					<?php _e('Sorry, no results were found.', 'sage'); ?>
				</div>
			<?php endif; ?>

			<div class="row">

				<?php foreach($products as $product): ?>

					<div class="col-xs-12 col-md-3 col-lg-2">

						<div class="thumbnail" id="zibbra-product-<?php echo $product->getProductid(); ?>">
							<?php if($product->hasImages()): ?>
								<a href="<?php echo site_url("/zibbra/product/".$product->getSlug()."/"); ?>">
									<img src="<?php echo $product->getFirstImage()->getPath(THUMBNAIL_SIZE); ?>" alt="<?php echo strip_tags($product->getName()); ?>">
								</a>
							<?php endif; ?>
							<div class="caption text-center">
								<h3 class="name"><?php echo strip_tags($product->getName()); ?></h3>
								<a href="<?php echo site_url("/zibbra/product/".$product->getSlug()."/"); ?>" class="btn btn-primary" role="button"><?php echo __("View product", Zibbra_Plugin::LC_DOMAIN); ?></a>
							</div>
						</div>

					</div>

				<?php endforeach; ?>

			</div>

		</div>

	</div>

	<div class="zibbra-search-content">

		<h2 class="search-title"><?php echo __("Website content", Zibbra_Plugin::LC_DOMAIN); ?></h2>

		<div class="search-results">

			<?php if (!have_posts()) : ?>
				<div class="alert alert-warning">
					<?php _e('Sorry, no results were found.', 'sage'); ?>
				</div>
			<?php endif; ?>

			<?php while (have_posts()) : the_post(); ?>
				<?php get_template_part('templates/content', 'search'); ?>
			<?php endwhile; ?>

			<?php the_posts_navigation(); ?>

		</div>

	</div>

</div>

<?php get_zibbra_footer(); ?>