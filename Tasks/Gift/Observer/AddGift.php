<?php

namespace Tasks\Gift\Observer;

use Magento\Checkout\Model\Cart as CustomerCart;

use Magento\Checkout\Model\Cart as CartGetAll;

use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Api\CartRepositoryInterface;

//use Magento\Checkout\Model\Cart;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class AddGift implements ObserverInterface
{
    protected $productRepository;
    protected $cart;
    protected $formKey;
    protected $cartRepository;
    protected $testCart;
    protected $cartGetAll;

    public function __construct(ProductRepository       $productRepository,
                                CartInterface           $cart,
                                FormKey                 $formKey,
                                CartRepositoryInterface $cartRepository,
                                CustomerCart            $testCart,
                                CartGetAll              $cartGetAll,

    )
    {
        $this->productRepository = $productRepository;
        $this->cart = $cart;
        $this->formKey = $formKey;
        $this->cartRepository = $cartRepository;
        $this->testCart = $testCart;
        $this->cartGetAll = $cartGetAll;
    }

    public function execute(Observer $observer)
    {
//        $item = $observer->getEvent()->getData('quote_item');
//        $item = $item->getParentItem() ? $item->getParentItem() : $item;

        $product = $observer->getEvent()->getData('product');
        $cartItems = $this->cartGetAll->getQuote()->getAllVisibleItems();

        /*//check if product and his gift already added and not let add again
        $productIds = $product->getExtensionAttributes()->getConfigurableProductLinks();
        //check simple product id from configurable product
        if($productIds){
            foreach ($productIds as $productId){

                $myProduct = $this->productRepository->getById($productId);

                //check if $myProduct has 'my_gift' field
                $checkMyGiftFieldProduct = $myProduct->getMyGift();
                $getProductSku = $myProduct->getSku();

                if($checkMyGiftFieldProduct){
                    foreach ($cartItems as $checkItem){
                        $checkMyGiftFieldItem = $checkItem->getMyGift();
                        $getItemSku = $checkItem->getSku();
                        //check if cart items have 'my_gift' field
                        if($checkMyGiftFieldItem && $checkMyGiftFieldItem === $checkMyGiftFieldProduct && $getProductSku === $getItemSku){
                            throw new LocalizedException(__("Already in your cart. You can only have one gift product."));
                        }
                    }
                }
            }
        }
        //*/

        //if product MySkuPants was added manually before add product 'A', add discount to product MySkuPants
        $productMyGiftFieldValue = null;
        //get simple product from configurable product
        $productLinks = $product->getExtensionAttributes()->getConfigurableProductLinks();
        if($productLinks){
            foreach ($productLinks as $key => $value) {
                if (is_int($key)) {
                    $getProductById = $this->productRepository->getById($value);
                    $productMyGiftFieldValue = $getProductById->getMyGift();
                }
            }
        }
        //if product was added manually
        $added_manually = 0;

        foreach ($cartItems as $value) {
            $skuOfItems = $value->getSku();
            if ($skuOfItems === $productMyGiftFieldValue) {
                $price = $value->getPrice();
                $value->setCustomPrice($price - ($price * 0.9999));
                $value->setOriginalCustomPrice($price - ($price * 0.9999));
                $value->setPrice($price - ($price * 0.9999));
                $value->save();

                $added_manually = 1;
            }
        }
        //

        if(!$added_manually){
            foreach ($cartItems as $item) {
                if ($item->getProductType() == "configurable") {
                    $products = $item->getQtyOptions();
                    foreach ($products as $pr) {
                        if ($pr['product'] === null) {
                            if ($pr->getMyGift() === null && $item->getMyGift() === null) {
                                break;
                            }
                        }
                        $checkOnGift = $pr['product']->getMyGift();
                        if (!empty($checkOnGift)) {
                            $giftSkus = [];
                            $productIds = $product->getExtensionAttributes()->getConfigurableProductLinks();
                            if ($productIds != NULL) {
                                foreach ($productIds as $id) {
                                    $cartProduct = $this->productRepository->getById($id);
                                    if ($cartProduct->getMyGift()) {
                                        $giftSku = $cartProduct->getMyGift();
                                        $giftSkus[] = $giftSku;
                                    }
                                }
                                if (!empty($giftSkus)) {
                                    foreach ($giftSkus as $sku) {
                                        if ($sku === 'MySkuPants') {
                                            $product = $this->productRepository->get($sku);
                                            $newProductName = $product->getName() . " Gift";
                                            $product->setName($newProductName);
                                            //price for gift
                                            $price = $product->getPrice();
                                            $product->setCustomPrice($price - ($price * 0.9999));
                                            $product->setOriginalCustomPrice($price - ($price * 0.9999));
                                            $product->setPrice($price - ($price * 0.9999));
                                            //
                                            $params = array(
                                                'product' => $product->getId(),
                                                'qty' => 1,
                                                'gift' => 1
                                            );
                                            $quoteGiftProduct = $this->testCart->addProduct($product, $params);
                                            $quoteId = $quoteGiftProduct['quote']->getId();
                                            //was error
                                            if ($quoteId) {
                                                $activeQuote = $this->cartRepository->getActive($quoteId);
                                            }

                                        }
                                    }
                                }
                            }
                        }
                    }
                }

            }
        }

        //archive
        /*foreach ($cartItems as $item) {
            if ($item->getProductType() == "configurable") {
                $products = $item->getQtyOptions();
                foreach ($products as $pr) {
                    if ($pr['product'] === null) {
                        if ($pr->getMyGift() === null && $item->getMyGift() === null) {
                            break;
                        }
                    }
                    $checkOnGift = $pr['product']->getMyGift();
                    if (!empty($checkOnGift)) {
                        $giftSkus = [];
                        $productIds = $product->getExtensionAttributes()->getConfigurableProductLinks();
                        if ($productIds != NULL) {
                            foreach ($productIds as $id) {
                                $cartProduct = $this->productRepository->getById($id);
                                if ($cartProduct->getMyGift()) {
                                    $giftSku = $cartProduct->getMyGift();
                                    $giftSkus[] = $giftSku;
                                }
                            }
                            if (!empty($giftSkus)) {
                                foreach ($giftSkus as $sku) {
                                    if ($sku === 'MySkuPants') {
                                        $product = $this->productRepository->get($sku);
                                        $newProductName = $product->getName() . " Gift";
                                        $product->setName($newProductName);
                                        //price for gift
                                        $price = $product->getPrice();
                                        $product->setCustomPrice($price - ($price * 0.9999));
                                        $product->setOriginalCustomPrice($price - ($price * 0.9999));
                                        $product->setPrice($price - ($price * 0.9999));
                                        //
                                        $params = array(
                                            'product' => $product->getId(),
                                            'qty' => 1,
                                            'gift' => 1
                                        );
                                        $quoteGiftProduct = $this->testCart->addProduct($product, $params);
                                        $quoteId = $quoteGiftProduct['quote']->getId();
                                        //was error
                                        if ($quoteId) {
                                            $activeQuote = $this->cartRepository->getActive($quoteId);
                                        }

                                    }
                                }
                            }
                        }
                    }
                }
            }

        }*/
    }
}
