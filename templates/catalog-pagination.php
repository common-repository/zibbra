<?php

defined("ZIBBRA_BASE_DIR") or die("Restricted access");

$pagination = z_get_pagination();

$links = new StdClass();

$links->first = new StdClass();
$links->first->label = __("First", Zibbra_Plugin::LC_DOMAIN);
$links->first->link = $pagination->page > 1 ? get_catalog_link(array("page"=>1)) : "javascript:void(0);";
$links->first->disabled = $pagination->page == 1;
$links->first->classes = "btn btn-default".($links->first->disabled ? " disabled" : "");
$links->first->symbol = "&laquo;";

$links->previous = new StdClass();
$links->previous->label = __("Previous", Zibbra_Plugin::LC_DOMAIN);
$links->previous->link = $pagination->page > 1 ? get_catalog_link(array("page"=>($pagination->page - 1))) : "javascript:void(0);";
$links->previous->disabled = $pagination->page == 1;
$links->previous->classes = "btn btn-default".($links->previous->disabled ? " disabled" : "");
$links->previous->symbol = "&lsaquo;";

$links->next = new StdClass();
$links->next->label = __("Next", Zibbra_Plugin::LC_DOMAIN);
$links->next->link = $pagination->page < $pagination->pages ? get_catalog_link(array("page"=>($pagination->page + 1))) : "javascript:void(0);";
$links->next->disabled = $pagination->page == $pagination->pages;
$links->next->classes = "btn btn-default".($links->next->disabled ? " disabled" : "");
$links->next->symbol = "&rsaquo;";

$links->last = new StdClass();
$links->last->label = __("Last", Zibbra_Plugin::LC_DOMAIN);
$links->last->link = $pagination->page < $pagination->pages ? get_catalog_link(array("page"=>$pagination->pages)) : "javascript:void(0);";
$links->last->disabled = $pagination->page == $pagination->pages;
$links->last->classes = "btn btn-default".($links->last->disabled ? " disabled" : "");
$links->last->symbol = "&raquo;";

?>
<div class="clearfix"></div>

<?php if($pagination->active): ?>

	<?php if($pagination->pages > 1): ?>

		<div class="zibbra-catalog-pagination">
		
			<div class="btn-toolbar" role="toolbar" aria-label="<?php echo __("Pagination", Zibbra_Plugin::LC_DOMAIN); ?>">
			
				<div class="btn-group" role="group" aria-label="<?php echo __("Backward", Zibbra_Plugin::LC_DOMAIN); ?>">
				
					<a href="<?php echo $links->first->link; ?>" class="<?php echo $links->first->classes; ?>" aria-label="<?php echo $links->first->label; ?>">
						<span aria-hidden="true"><?php echo $links->first->symbol; ?></span>
					</a>
					
					<a href="<?php echo $links->previous->link; ?>" class="<?php echo $links->previous->classes; ?>" aria-label="<?php echo $links->previous->label; ?>">
						<span aria-hidden="true"><?php echo $links->previous->symbol; ?></span>
					</a>
					
				</div>
				
				<div class="btn-group" role="group" aria-label="<?php echo __("Pages", Zibbra_Plugin::LC_DOMAIN); ?>">
					<?php for($page=1; $page <= $pagination->pages; $page++): ?>
						<a href="<?php echo get_catalog_link(array("page"=>$page)); ?>" class="btn btn-default<?php if($pagination->page==$page): ?> active<?php endif; ?>" aria-label="<?php echo $page; ?>">
							<span><?php echo $page; ?></span>
						</a>
					<?php endfor; ?>
				</div>
				
				<div class="btn-group" role="group" aria-label="<?php echo __("Forward", Zibbra_Plugin::LC_DOMAIN); ?>">
				
					<a href="<?php echo $links->next->link; ?>" class="<?php echo $links->next->classes; ?>" aria-label="<?php echo $links->next->label; ?>">
						<span aria-hidden="true"><?php echo $links->next->symbol; ?></span>
					</a>
					
					<a href="<?php echo $links->last->link; ?>" class="<?php echo $links->last->classes; ?>" aria-label="<?php echo $links->last->label; ?>">
						<span aria-hidden="true"><?php echo $links->last->symbol; ?></span>
					</a>
				</div>
				
			</div>
		
		</div>
		
	<?php endif; ?>
	
	<script>
	Zibbra.Catalog.PAGE = "<?php echo $pagination->page; ?>";
	Zibbra.Catalog.PAGES = <?php echo $pagination->pages; ?>;
	Zibbra.Catalog.LIMIT = "<?php echo $pagination->limit; ?>";
	</script>
	
<?php endif; ?>