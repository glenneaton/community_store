<?php
defined('C5_EXECUTE') or die(_("Access Denied."));
use \Concrete\Package\CommunityStore\Src\CommunityStore\Product\Product as StoreProduct;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Utilities\Price as StorePrice;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Product\ProductOption\ProductOptionGroup as StoreProductOptionGroup;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Product\ProductOption\ProductOptionItem as StoreProductOptionItem;
?>
<div class="store-cart-modal clearfix" id="cart-modal">
    <a href="#nolink" class="store-modal-exit">x</a>
    <div class="store-cart-page-cart">
        <?php if (isset($actiondata) and !empty($actiondata)) { ?>
            <?php if($actiondata['action'] == 'add') { ?>
                <p class="alert alert-success"><strong><?= $actiondata['product']['pName']; ?></strong> <?= t('has been added to your cart');?></p>
            <?php } ?>

            <?php if( $actiondata['action'] =='update') { ?>
                <p class="alert alert-success"><?= t('Your cart has been updated');?></p>
            <?php } ?>

            <?php if($actiondata['action'] == 'clear') { ?>
                <p class="alert alert-warning"><?= t('Your cart has been cleared');?></p>
            <?php } ?>

            <?php if($actiondata['action'] == 'remove') { ?>
                <p class="alert alert-warning"><?= t('Item removed');?></p>
            <?php } ?>

            <?php if($actiondata['quantity'] != $actiondata['added']) { ?>
                <p class="alert alert-warning"><?= t('Due to stock levels your quantity has been limited');?></p>
            <?php } ?>

        <?php } ?>

        <h3><?= t("Shopping Cart")?></h3>

        <input id='cartURL' type='hidden' data-cart-url='<?=View::url("/cart/")?>'>
            <?php
            if($cart){ ?>
            <form method="post" action="<?= View::url('/cart/');?>" id="store-modal-cart">
            <table id="cart" class="table table-hover table-condensed" >
                <thead>
                <tr>
                    <th colspan="2" ><?= t('Product'); ?></th>
                    <th><?= t('Price'); ?></th>
                    <th><?= t('Quantity'); ?></th>
                    <th></th>

                </tr>
                </thead>
                <tbody>

                <?php
                $i=1;
                $allowUpdate = false;
                foreach ($cart as $k=>$cartItem){


                    $qty = $cartItem['product']['qty'];
                    $product = $cartItem['product']['object'];

                    if ($product->allowQuantity()) {
                        $allowUpdate = true;
                    }

                    if($i%2==0){$classes=" striped"; }else{ $classes=""; }
                    if(is_object($product)){
                        ?>

                        <tr class="store-cart-page-cart-list-item <?= $classes?>" data-instance-id="<?= $k?>" data-product-id="<?= $pID?>">
                            <td class="store-cart-list-thumb col-xs-2">
                                <a href="<?=URL::page(Page::getByID($product->getProductPageID()))?>">
                                    <?= $product->getProductImageThumb()?>
                                </a>
                            </td>
                            <td class="store-cart-list-product-name col-xs-5">
                                <a href="<?=URL::page(Page::getByID($product->getProductPageID()))?>">
                                    <?= $product->getProductName()?>
                                </a>

                                <?php if($cartItem['productAttributes']){?>
                                    <div class="store-cart-list-item-attributes">
                                        <?php foreach($cartItem['productAttributes'] as $groupID => $valID){
                                            $groupID = str_replace("pog","",$groupID);
                                            $optiongroup = StoreProductOptionGroup::getByID($groupID);
                                            $optionvalue = StoreProductOptionItem::getByID($valID);

                                            ?>
                                            <div class="store-cart-list-item-attribute">
                                                <span class="store-cart-list-item-attribute-label"><?= ($optiongroup ? $optiongroup->getName() : '')?>:</span>
                                                <span class="store-cart-list-item-attribute-value"><?= ($optionvalue ? $optionvalue->getName(): '')?></span>
                                            </div>
                                        <?php }  ?>
                                    </div>
                                <?php } ?>
                            </td>

                            <td class="store-cart-list-item-price col-xs-2">
                                <?=StorePrice::format($product->getActivePrice())?>
                            </td>

                            <td class="store-cart-list-product-qty col-xs-2">
                                <?php if ($product->allowQuantity()) { ?>
                                    <input type="hidden" name="instance[]" value="<?= $k?>">
                                    <input type="number" name="pQty[]" class="form-control" <?= ($product->allowBackOrders() || $product->isUnlimited() ? '' : 'max="'.$product->getProductQty() . '"');?> min="1" value="<?= $qty?>">
                                <?php } ?>
                            </td>
                            <td>
                                <a class="store-btn-cart-list-remove btn btn-danger" data-instance-id="<?= $k?>" data-modal="true"  href="#nolink"><i class="fa fa-remove"></i><?php ///echo t("Remove")?></a>
                            </td>

                        </tr>

                        <?php
                    }//if is_object
                    $i++;
                }//foreach ?>
                </tbody>

                <tfoot>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td colspan="2">
                        <?php if ($allowUpdate) { ?>
                            <p class="text-right"><a class="store-btn-cart-modal-update btn btn-default" data-modal="true" href="#nolink"><?= t("Update")?></a></p>
                        <?php } ?>

                    </td>
                </tr>
                </tfoot>
            </table>
            </form>



            <?php }//if cart
            ?>



        <?php if ($cart  && !empty($cart)) { ?>

        <?php if(!empty($discounts)) { ?>

            <div class="store-cart-page-discounts">
                <p><strong><?= (count($discounts) == 1 ? t('Discount Applied') : t('Discounts Applied'));?></strong></p>
                <ul>
                    <?php foreach($discounts as $discount) { ?>
                        <li><?= h($discount->getDisplay()); ?></li>
                    <?php } ?>
                </ul>
            </div>

        <?php }?>

        <p class="store-cart-page-cart-total text-right">
            <strong class="store-cart-grand-total-label"><?= t("Total")?>:</strong>
            <span class="store-cart-grand-total-value"><?=StorePrice::format($total)?></span>
        </p>
        <?php } else { ?>
        <p class="alert alert-info"><?= t('Your cart is empty'); ?></p>
        <?php } ?>


        <div class="store-cart-page-cart-links">
            <a class="store-btn-cart-modal-continue btn btn-default" href="#nolink"><?= t("Continue Shopping")?></a>
            <?php if ($cart  && !empty($cart)) { ?>
            <a class="store-btn-cart-modal-clear btn btn-default" href="#nolink"><?= t('Clear Cart')?></a>
            <a class="store-btn-cart-modal-checkout btn btn-primary pull-right" href="<?= View::url('/checkout')?>"><?= t('Checkout')?></a>
            <?php } ?>
        </div>

    </div>
</div>