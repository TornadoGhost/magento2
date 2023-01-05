<?php

namespace Tasks\Gift\Observer;

use Magento\Catalog\Model\ProductRepository;
use Magento\Checkout\Model\Cart;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;

class UpdateQty implements ObserverInterface
{
    protected $cart;
    protected $productRepository;

    public function __construct(Cart $cart, ProductRepository $productRepository)
    {
        $this->cart = $cart;
        $this->productRepository = $productRepository;
    }

    public function execute(Observer $observer)
    {
        $productHasGiftArray = [];
        $info = $observer->getEvent()->getData('info');
        $cartProducts = $this->cart->getQuote()->getAllVisibleItems();
        //check for product with gift
        foreach ($cartProducts as $cartProduct) {
            //get SKU of my_gift field if it doesn't move on
            $getMyGiftSku = $cartProduct->getMyGift();
            if (!$getMyGiftSku) {
                if ($cartProduct->getProductType() == "configurable") {
                    $qtyOptions = $cartProduct->getQtyOptions();
                    if ($qtyOptions) {
                        foreach ($qtyOptions as $key => $value) {
                            $product = $this->productRepository->getById($key);
                            if ($product->getMyGift()) {
                                //get data of my_gift field
                                $productHasGiftArray[$cartProduct->getSku()] = $product->getMyGift();
                            }
                        }
                    }
                }
            } else {
                //if $cartProduct has column 'my_gift' get it and write in $productHasGiftArray
                $productHasGiftArray[$cartProduct->getSku()] = $getMyGiftSku;
            }


        }

        //array of chosen update items in cart
        $ids = $info->getData();

        foreach ($ids as $key => $value) {
            //get update item by id
            $item = $this->cart->getQuote()->getItemById($key);
            if (!empty($productHasGiftArray)) {
                foreach ($productHasGiftArray as $giftSku) {
                    if ($giftSku == $item->getSku()) {
                        $name = $item->getName();
                        throw new LocalizedException(__("You can have just one '$name' as a gift."));
                    }
                }
            }
        }
    }
}
