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
        /*$giftSkus = [];
        $productIds = $product->getExtensionAttributes()->getConfigurableProductLinks();
        if ($productIds != NULL) {
            foreach ($productIds as $id) {
                $cartProduct = $this->productRepository->getById($id);
                if($cartProduct->getMyGift()){
                    $giftSku = $cartProduct->getMyGift();
                    $giftSkus[] = $giftSku;
                }
            }
            if(!empty($giftSkus)){
                foreach ($giftSkus as $sku) {
                    if ($sku === 'MySkuPants') {
                        $product = $this->productRepository->get($sku);
                        $price = $product->getPrice() * 0.0001;
                        $product->setPrice($price);
                        $params = array(
                            'product' => $product->getId(),
                            'qty' => 1,
                        );
                        $quoteGiftProduct = $this->testCart->addProduct($product, $params);
                        $quoteId = $quoteGiftProduct['quote']->getId();
                        $activeQuote = $this->cartRepository->getActive($quoteId);
                    }
                }
            }
        }*/
    }
}
