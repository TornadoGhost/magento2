<?php

namespace Tasks\Gift\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Checkout\Model\Cart;

class AddDiscount implements ObserverInterface
{
    protected $cart;

    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    public function execute(Observer $observer)
    {
        $product = $observer->getEvent()->getData('product');
        $request = $observer->getEvent()->getData('request');
        $response = $observer->getEvent()->getData('response');
        $cart = $observer->getEvent()->getData('cart');
        $allItems = $items4 = $this->cart->getQuote()->getAllItems();

        $giftSku = null;

        /*foreach ($allItems as $item) {
            if ($item['product']->getMyGift()) {
                $giftSku = $item['product']->getMyGift();
            }
            if($item['product']->getSku() === $giftSku){
                $price = $item->getPrice();
                $item->setCustomPrice($price);
                $item->setOriginalCustomPrice($price);
                $item->setPrice($price);
                $item->save();
            }
        }*/

        /*$giftSku = null;
        $totalPrice = null;

        foreach ($allItems as $item) {
            if ($item['product']->getMyGift()) {
                $giftSku = $item['product']->getMyGift();
            }
            if($item['product']->getSku() === $giftSku){
                $price = $item->getPrice();
                $item->setCustomPrice($price - ($price * 0.9999));
                $item->setOriginalCustomPrice($price - ($price * 0.9999));
                $item->setPrice($price - ($price * 0.9999));
                $item->save();
            }
        }

        $cartItems = $cart->getQuote()->getAllVisibleItems();
        if($cartItems){
            foreach ($cartItems as $item){
                if($item->getPrice()){
                    $totalPrice += $item->getPrice();
                }
            }
        }

//        $productPrice = $product->getQuoteItemPrice();

        $cartTotalPrice = $this->cart->getQuote();
        if($cartTotalPrice){
            $cartTotalPrice->setSubtotalWithDiscount($totalPrice)
                ->setBaseSubtotalWithDiscount($totalPrice)
                ->setSubtotal($totalPrice)
                ->setBaseSubtotal($totalPrice)
                ->setGrandTotal($totalPrice)
                ->setBaseGrandTotal($totalPrice);
            $cartTotalPrice->save();
        }*/
    }
}
