<?php

namespace Tasks\Gift\Observer;

use Magento\Checkout\Model\Cart;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface;

class Amount implements ObserverInterface
{
    protected $cart;
    protected $messageManager;

    public function __construct(Cart $cart, ManagerInterface $messageManager)
    {
        $this->cart = $cart;
        $this->messageManager = $messageManager;
    }

    /**
     * @throws LocalizedException
     */
    public function execute(Observer $observer)
    {
        $cart = $this->cart->getQuote()->getAllVisibleItems();
        $product = $observer->getEvent()->getData('product');

        //you cant add same gift product if he already in cary
        if($cart){
            foreach ($cart as $item){
                /*if($item->getSku() === $product->getSku()){
                    $name = $product->getName();
                    throw new LocalizedException(__("$name already in your cart. No more then one gift."));
                }*/
                $itemOptions = $item->getProduct()->getTypeInstance(true)->getOrderOptions($item->getProduct());
                if($itemOptions){
                    foreach ($itemOptions['info_buyRequest'] as $key => $itemOption){
                        if($key === "gift" && $itemOption === 1 && $item->getSku() === $product->getSku()){
                            $name = $product->getName();
                            throw new LocalizedException(__("$name already in your cart. No more then one gift."));
                        }
                    }
                }
            }
        }
    }
}
