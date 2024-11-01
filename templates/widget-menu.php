<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>
<?php if(!function_exists("widget_zibbra_menu_item")): ?>
	<?php function widget_zibbra_menu_item($item,$limit,$level=0) { ?>
		<?php if(($level<$limit) && (($level==0 && !$item->hasParent()) || $level>0)): ?>
			<li class="item-<?php echo $item->getCategoryid(); ?> <?php if($item->hasChildren()): ?>deeper parent<?php endif; ?> level-<?php echo $level; ?>">
				<a href="<?php echo site_url("/zibbra/catalog/".$item->getSlug()."/"); ?>">
					<span><?php echo $item->getName(); ?></span>
					<?php if($level==0 && $item->hasChildren()): ?>
						<div class="icon icon-arrow-down"></div>
					<?php endif; ?>
					<?php if($level>=1 && $item->hasChildren()): ?>
						<div class="icon icon-arrow-right"></div>
					<?php endif; ?>
				</a>
				<?php if($item->hasChildren()): ?>
					<ul class="nav">
						<?php foreach($item->getChildren() as $subitem): ?>
							<?php widget_zibbra_menu_item($subitem,$limit,$level+1); ?>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</li>
		<?php endif; ?>
	<?php } ?>
<?php endif; ?>
<div id="zibbra-menu" class="nav-menu">
	<ul class="nav">
		<?php if($show_home=="Y"): ?>
			<li class="page_item page-item-home level-0">
				<a href="<?php echo home_url(); ?>"><span>Home</span></a>
			</li>
		<?php endif; ?>
		<?php foreach($arrCategories as $oCategory): ?>
			<?php widget_zibbra_menu_item($oCategory,$depth); ?>
		<?php endforeach; ?>
	</ul>
</div>