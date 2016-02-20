<?php
defined('C5_EXECUTE') or die(_("Access Denied."));

if (is_object($product)) {
    ?>

    <form class="store-product-block" id="store-form-add-to-cart-<?= $product->getProductID() ?>">

        <div class="row">
            <?php if ($showImage){ ?>
            <div class="col-md-6">
                <?php } else { ?>
                <div class="col-md-12">
                    <?php } ?>
                    <?php if ($showProductName) { ?>
                        <h1 class="store-product-name"><?= $product->getProductName() ?></h1>
                    <?php } ?>

                    <?php if ($showProductPrice) { ?>
                        <p class="store-product-price">
                        <?php
                        $salePrice = $product->getProductSalePrice();
                        if (isset($salePrice) && $salePrice != "") {
                            echo '<span class="sale-price">' . t("On Sale: ") . $product->getFormattedSalePrice() . '</span>';
                            echo '<span class="original-price">' . $product->getFormattedOriginalPrice() . '</span>';
                        } else {
                            echo $product->getFormattedPrice();
                        }
                        ?>
                        </p>
                    <?php } ?>

                    <?php if ($showProductDescription) { ?>
                        <div class="store-product-description">
                            <?= $product->getProductDesc() ?>
                        </div>
                    <?php } ?>

                    <?php if ($showDimensions) { ?>
                        <div class="store-product-dimensions">
                            <strong><?= t("Dimensions") ?>:</strong>
                            <?= $product->getDimensions() ?>
                            <?= Config::get('communitystore.sizeUnit'); ?>
                        </div>
                    <?php } ?>

                    <?php if ($showWeight) { ?>
                        <div class="store-product-weight">
                            <strong><?= t("Weight") ?>:</strong>
                            <?= $product->getProductWeight() ?>
                            <?= Config::get('communitystore.weightUnit'); ?>
                        </div>
                    <?php } ?>

                    <?php if ($showGroups) { ?>
                        <ul>
                            <?php
                            $productgroups = $product->getProductGroups();
                            foreach ($productgroups as $pg) { ?>
                                <li class="store-product-group"><?= $pg->gName; ?> </li>
                            <?php } ?>
                        </ul>
                    <?php } ?>

                    <?php if ($showIsFeatured) {
                        if ($product->isFeatured()) {
                            ?>
                            <span class="store-product-featured"><?= t("Featured Item") ?></span>
                        <?php }
                    } ?>

                    <div class="store-product-options" id="product-options-<?= $bID; ?>">
                        <?php if ($product->allowQuantity()) { ?>
                            <div class="store-product-modal-option-group form-group">
                                <label class="store-product-option-group-label"><?= t('Quantity') ?></label>
                                <input type="number" name="quantity" class="store-product-qty form-control" value="1" min="1"
                                       step="1" <?= ($product->allowBackOrders() ? '' : 'max="' . $product->getProductQty() . '"'); ?>>
                            </div>
                        <?php } else { ?>
                            <input type="hidden" name="quantity" class="product-qty" value="1">
                        <?php } ?>
                        <?php

                        foreach ($optionGroups as $optionGroup) {
                            $groupoptions = array();
                            foreach ($optionItems as $option) {
                                if ($option->getProductOptionGroupID() == $optionGroup->getID()) {
                                    $groupoptions[] = $option;
                                }
                            }
                            ?>
                            <?php if (!empty($groupoptions)) { ?>
                                <div class="store-product-option-group form-group">
                                    <label class="store-product-option-group-label"><?= $optionGroup->getName() ?></label>
                                    <select class="store-product-option form-control" name="pog<?= $optionGroup->getID() ?>">
                                        <?php
                                        foreach ($groupoptions as $option) { ?>
                                            <option value="<?= $option->getID() ?>"><?= $option->getName() ?></option>
                                            <?php
                                            // below is an example of a radio button, comment out the <select> and <option> tags to use instead
                                            //echo '<input type="radio" name="pog'.$optionGroup->getID().'" value="'. $option->getID(). '" />' . $option->getName() . '<br />'; ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            <?php }
                        } ?>
                    </div>

                    <?php if ($showCartButton) { ?>
                        <p class="store-product-button">
                            <input type="hidden" name="pID" value="<?= $product->getProductID() ?>">

                            <span><a href="#nolink" data-add-type="none" data-product-id="<?= $product->getProductID() ?>"
                                  class="store-btn-add-to-cart btn btn-primary <?= ($product->isSellable() ? '' : 'hidden'); ?> "><?= ($btnText ? h($btnText) : t("Add to Cart")) ?></a>
                            </span>
                            <span
                                class="store-out-of-stock-label <?= ($product->isSellable() ? 'hidden' : ''); ?>"><?= t("Out of Stock") ?></span>
                        </p>
                    <?php } ?>

                </div>

                <?php if ($showImage) { ?>
                    <div class="product-image col-md-6">
                        <?php
                        $imgObj = $product->getProductImageObj();
                        if (is_object($imgObj)) {
                            $thumb = Core::make('helper/image')->getThumbnail($imgObj, 600, 600, true);
                            ?>
                            <div class="store-product-primary-image">
                                <a href="<?= $imgObj->getRelativePath() ?>"
                                   title="<?= h($product->getProductName()); ?>" class="product-thumb">
                                    <img src="<?= $thumb->src ?>">
                                </a>
                            </div>
                        <?php } ?>

                        <?php
                        $images = $product->getProductImagesObjects();
                        if (count($images) > 0) {
                            echo '<div class="store-product-additional-images">';
                            foreach ($images as $secondaryimage) {
                                if (is_object($secondaryimage)) {
                                    $thumb = Core::make('helper/image')->getThumbnail($secondaryimage, 300, 300, true);
                                    ?>
                                    <a href="<?= $secondaryimage->getRelativePath() ?>"
                                       title="<?= h($product->getProductName()); ?>" class="product-thumb"><img
                                            src="<?= $thumb->src ?>"></a>

                                <?php }
                            }
                            echo '</div>';
                        }
                        ?>
                    </div>
                <?php } ?>
            </div>
            <div class="row">
                <?php if ($showProductDetails) { ?>
                    <div class="store-product-detailed-description col-md-12">
                        <h2><?= t("Product Details") ?></h2>
                        <?= $product->getProductDetail() ?>
                    </div>
                <?php } ?>
            </div>
    </form>

    <script type="text/javascript">
        $(function () {
            $('.product-thumb').magnificPopup({
                type: 'image',
                gallery: {enabled: true}
            });

            <?php if ($product->hasVariations() && !empty($variationLookup)) {?>

            <?php
            $varationData = array();
            foreach($variationLookup as $key=>$variation) {
                $product->setVariation($variation);

                $imgObj = $variation->getVariationImageObj();

                if ($imgObj) {
                    $thumb = Core::make('helper/image')->getThumbnail($imgObj,600,800,true);
                }

                $varationData[$key] = array(
                'price'=>$product->getFormattedOriginalPrice(),
                'saleprice'=>$product->getFormattedSalePrice(),
                'available'=>($variation->isSellable()),
                'imageThumb'=>$thumb ? $thumb->src : '',
                'image'=>$imgObj ? $imgObj->getRelativePath() : ''

                );
            } ?>

            $('#product-options-<?= $bID; ?> select, #product-options-<?= $bID; ?> input').change(function () {
                var variationdata = <?= json_encode($varationData); ?>;
                var ar = [];

                $('#product-options-<?= $bID; ?> select, #product-options-<?= $bID; ?> input:checked').each(function () {
                    ar.push($(this).val());
                })

                ar.sort();
                var pdb = $(this).closest('.store-product-detail-block');

                if (variationdata[ar.join('_')]['saleprice']) {
                    var pricing = '<span class="store-sale-price"><?= t("On Sale: "); ?>' + variationdata[ar.join('_')]['saleprice'] + '</span>' +
                        '<span class="store-original-price">' + variationdata[ar.join('_')]['price'] + '</span>';

                    pdb.find('.store-product-price').html(pricing);
                } else {
                    pdb.find('.store-product-price').html(variationdata[ar.join('_')]['price']);
                }

                if (variationdata[ar.join('_')]['available']) {
                    pdb.find('.store-out-of-stock-label').addClass('hidden');
                    pdb.find('.store-btn-add-to-cart').removeClass('hidden');
                } else {
                    pdb.find('.store-out-of-stock-label').removeClass('hidden');
                    pdb.find('.store-btn-add-to-cart').addClass('hidden');
                }

                if (variationdata[ar.join('_')]['imageThumb']) {
                    var image = pdb.find('.store-product-primary-image img');

                    if (image) {
                        image.attr('src', variationdata[ar.join('_')]['imageThumb']);
                        var link = image.parent();

                        if (link) {
                            link.attr('href', variationdata[ar.join('_')]['image'])
                        }
                    }
                }

            });
            <?php } ?>

        });
    </script>

<?php } else { ?>
    <div class="alert alert-info"><?= t("Product not available") ?></div>
<?php } ?>