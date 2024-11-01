<?php
defined("ZIBBRA_BASE_DIR") or die("Restricted access");
/** @var ZProduct $product */
?>
<?php if($product->hasAddons()): ?>

	<div class="zibbra-product-addons">

        <div class="panel panel-default">

            <div class="list-group">

                <?php foreach($product->getAddons() as $addon): ?>

                    <a href="#" class="list-group-item addon" data-productid="<?php echo $addon->getProductid(); ?>">
                        <div class="image" style="background-image: url(<?php echo $addon->getFirstImage(); ?>);"></div>
                        <div class="info">
                            <h4 class="name"><?php echo $addon->getName(); ?></h4>
                            <div class="price">&euro;&nbsp;<?php echo number_format($addon->getPrice(),2,",",""); ?></div>
                            <input class="quantity" type="number" min="0" step="1" value="0" name="addons[<?php echo $addon->getProductid(); ?>]">
                        </div>
                        <div class="clearfix"></div>
                    </a>

                <?php endforeach; ?>

            </div>

        </div>

    </div>

<?php endif; ?>