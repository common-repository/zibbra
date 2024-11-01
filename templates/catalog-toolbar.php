<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>
<?php

$orderby = z_get_orderby();
$pagination = z_get_pagination();

?>
<div class="zibbra-catalog-toolbar">

	<?php if($pagination->active): ?>
	
		<div class="zibbra-catalog-limit">
		
			<?php if(!TOOLBAR_SIDEBAR): ?>
		
				<span><?php echo __("Results per page", Zibbra_Plugin::LC_DOMAIN); ?>:</span>
				
			<?php endif; ?>
		
			<div class="btn-group<?php if(TOOLBAR_SIDEBAR): ?> btn-group-justified<?php endif; ?>" data-toggle="buttons">
		
				<?php if(TOOLBAR_SIDEBAR): ?>
			
					<button type="button" class="btn btn-default dropdown-label">			
						<?php if($pagination->limit==-1): ?>
							<?php echo __("All Results", Zibbra_Plugin::LC_DOMAIN); ?>
						<?php else: ?>
							<span class="value"><?php echo $pagination->limit; ?></span>
							<span class="suffix">&nbsp;/&nbsp;<?php echo __("Page", Zibbra_Plugin::LC_DOMAIN); ?></span>
						<?php endif; ?>
					</button>
				
				<?php endif; ?>
			
				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">		
					<?php if(!TOOLBAR_SIDEBAR): ?>
						<?php if($pagination->limit==-1): ?>
							<?php echo __("All Results", Zibbra_Plugin::LC_DOMAIN); ?>
						<?php else: ?>
							<span class="value"><?php echo $pagination->limit; ?></span>
							<span class="suffix">&nbsp;/&nbsp;<?php echo __("Page", Zibbra_Plugin::LC_DOMAIN); ?></span>
						<?php endif; ?>
					<?php endif; ?>
					<span class="caret"></span>
					<span class="sr-only"><?php echo __("Toggle Dropdown", Zibbra_Plugin::LC_DOMAIN); ?></span>
				</button>
				
				<ul class="dropdown-menu">
			
					<?php foreach($limit_options as $limit): ?>
					
						<?php if($limit <= $pagination->total_rows): ?>
						
							<li>
								<a href="<?php echo get_catalog_link(array("limit"=>$limit)); ?>" data-attr-limit="<?php echo $limit; ?>">
									<span class="value"><?php echo $limit; ?></span>
									<span class="suffix">&nbsp;/&nbsp;<?php echo __("Page", Zibbra_Plugin::LC_DOMAIN); ?></span>
								</a>
							</li>
							
						<?php endif; ?>
				
					<?php endforeach; ?>
						
					<li>
						<a href="<?php echo get_catalog_link(array("limit"=>-1)); ?>"><?php echo __("All Results", Zibbra_Plugin::LC_DOMAIN); ?></a>
					</li>
				
				</ul>
				
			</div>
			
		</div>
		
	<?php endif; ?>
	
	<div class="zibbra-catalog-sort">
		
		<?php if(TOOLBAR_SIDEBAR): ?>
		
			<div class="btn-group<?php if(TOOLBAR_SIDEBAR): ?> btn-group-justified<?php endif; ?>" data-toggle="buttons">
			
				<button type="button" class="btn btn-default dropdown-label">			
					<?php
					
						$option = $sort_options[$orderby->type];
						$link = get_catalog_link(array(
							"sort_type"=>$option->type,
							"sort_dir"=>$option->dir=="asc" ? "desc" : "asc"
						));
						
					?>
					<a href="<?php echo $link; ?>">
						<span><?php echo $option->label; ?></span>
					</a>
					<span class="icon icon-sort-<?php echo $orderby->dir; ?>"></span>
				</button>
				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
					<span class="caret"></span>
					<span class="sr-only"><?php echo __("Toggle Dropdown", Zibbra_Plugin::LC_DOMAIN); ?></span>
				</button>
				
				<ul class="dropdown-menu">
				
					<?php foreach($sort_options as $option): ?>
			
						<?php
						
							$link = get_catalog_link(array(
								"sort_type"=>$option->type,
								"sort_dir"=>$option->active ? ($option->dir=="asc" ? "desc" : "asc") : $orderby->dir
							));
							
						?>
						
						<li>
							<a id="zibbra-catalog-sort-<?php echo $option->type; ?>" href="<?php echo $link; ?>" class="<?php echo implode(" ",$option->classes); ?>">
								<span><?php echo $option->label; ?></span>
							</a>
						</li>
					
					<?php endforeach; ?>
				
				</ul>
			
			</div>
		
		<?php else: ?>
	
			<span><?php echo __("Sort by", Zibbra_Plugin::LC_DOMAIN); ?>:</span>
		
			<div class="btn-group" role="group" aria-label="<?php echo __("Sort by", Zibbra_Plugin::LC_DOMAIN); ?>">
			
				<?php foreach($sort_options as $option): ?>
		
					<?php
					
						$link = get_catalog_link(array(
							"sort_type"=>$option->type,
							"sort_dir"=>$option->active ? ($option->dir=="asc" ? "desc" : "asc") : $orderby->dir
						));
						
						$option->classes[] = "btn";
						$option->classes[] = "btn-default";
						
					?>
					
					<a id="zibbra-catalog-sort-<?php echo $option->type; ?>" href="<?php echo $link; ?>" class="<?php echo implode(" ",$option->classes); ?>">
						<span><?php echo $option->label; ?></span>
						<?php if($option->active): ?>
							<span class="icon <?php echo $orderby->dir=="asc" ? "icon-sort-asc" : "icon-sort-desc"; ?>"></span>
						<?php endif; ?>
					</a>
					
				<?php endforeach; ?>
			
			</div>
		
		<?php endif; ?>
		
	</div>
	
</div>
						
<div class="icon icon-filter"></div>

<script>
Zibbra.Catalog.SORT_TYPE = "<?php echo $orderby->type; ?>";
Zibbra.Catalog.SORT_DIR = "<?php echo $orderby->dir; ?>";
</script>