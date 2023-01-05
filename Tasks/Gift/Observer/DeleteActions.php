<?php

namespace Tasks\Gift\Observer;

use Magento\Catalog\Model\ProductRepository;
use Magento\Checkout\Model\Cart;

use Magento\Quote\Model\Quote;
use Magento\Quote\Model\QuoteRepository;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\Quote\Item;
use Magento\Quote\Api\CartRepositoryInterface;


class DeleteActions implements ObserverInterface
{
    protected $cart;
    protected $productRepository;
    protected $item;
    protected $cartRepository;
    protected $quoteRepository;

    public function __construct(Cart                    $cart,
                                ProductRepository       $productRepository,
                                Item                    $item,
                                CartRepositoryInterface $cartRepository,
                                QuoteRepository         $quoteRepository
    )
    {
        $this->cart = $cart;
        $this->productRepository = $productRepository;
        $this->item = $item;
        $this->cartRepository = $cartRepository;
        $this->quoteRepository = $quoteRepository;
    }

    public function execute(Observer $observer,)
    {
        $item = $observer->getEvent()->getData('item');

//        $items = $this->cart->getItems();
//        $items2 = $this->cart->getQuote()->getItemsCollection();
//        $items3 = $this->cart->getQuote()->getAllItems();
        $items4 = $this->cart->getQuote()->getAllVisibleItems();

        $cartPriceBeforeDelete = [];
        foreach ($items4 as $value) {
            $cartPriceBeforeDelete[$value->getSku()] = $value->getPrice();
        }


        $options = $item->getQtyOptions();
        if (!$options) {
            $itemSku = $item->getSku();
            foreach ($items4 as $item) {
                $qtyOptions = $item->getQtyOptions();
                if ($qtyOptions) {
                    foreach ($qtyOptions as $key => $value) {
                        $productCheck = $this->productRepository->getById($key);
                        if ($productCheck->getMyGift() === $itemSku) {
                            $price = $item->getPrice();
                            $newPrice = $price - ($price * 0.1);
                            $item->setCustomPrice($newPrice);
                            $item->setOriginalCustomPrice($newPrice);
                            $item->save();
                            $cartTotalPrice = $this->cart->getQuote();
                            if ($newPrice != $cartTotalPrice->getGrandTotal()) {
                                $cartTotalPrice->setSubtotalWithDiscount($newPrice)
                                    ->setBaseSubtotalWithDiscount($newPrice)
                                    ->setSubtotal($newPrice)
                                    ->setBaseSubtotal($newPrice)
                                    ->setGrandTotal($newPrice)
                                    ->setBaseGrandTotal($newPrice);
                                $cartTotalPrice->save();
                            }

                        }
                    }
                }
            }
        } else if ($options) {
            $newTotalCartPrice = null;

            foreach ($options as $option) {
                $productId = $option->getProductId();
                if ($productId) {
                    $product = $this->productRepository->getById($productId);
                    $productGiftSku = $product->getMyGift();
                    if ($productGiftSku) {
                        foreach ($items4 as $cartProduct) {

                            $productSku = $cartProduct->getSku();
                            $productOptions = $cartProduct->getProduct()->getTypeInstance(true)->getOrderOptions($cartProduct->getProduct());
                            foreach ($productOptions['info_buyRequest'] as $key => $productOption) {
                                if ($productSku == $productGiftSku && $key === "gift" && $productOption === 1) {
                                    $this->cart->removeItem($cartProduct->getId());
                                    $this->cart->getQuote()->setTotalsCollectedFlag(false);
                                    $this->cart->save();
                                } //return regular price for manual added product 'B'(MySkuPants) and became a gift -v
                                else if ($productSku == $productGiftSku) {
                                    $getProductById = $this->productRepository->get($productSku);
                                    $realPrice = $getProductById->getPrice();

                                    $cartPriceBeforeDelete[$productSku] = $realPrice;

                                    $cartProduct->setCustomPrice($realPrice);
                                    $cartProduct->setOriginalCustomPrice($realPrice);
                                    $cartProduct->setPrice($realPrice);
                                    $cartProduct->save();
                                }
                                //tested -^
                            }
                            /*if($cartProduct->getQty() > 1){
//                                $cartProduct->setQty(1.0000);
                                $cartProductQty = $cartProduct->getQty() - 1;
                                $cartProductPrice = $cartProduct->getPrice();
                                $newTotalPrice = $cartProductPrice * $cartProductQty;

                                $cartProduct->setQty($cartProductQty);
                                $cartProduct->setRowTotal($cartProductPrice);
                                $cartProduct->setBaseRowTotal($cartProductPrice);
                                $cartProduct->save();

                                $this->cart->getQuote()->setItemsQty(count($items4));
                                $this->cart->getQuote()->setSubtotalWithDiscount($newTotalPrice)
                                    ->setBaseSubtotalWithDiscount($newTotalPrice)
                                    ->setSubtotal($newTotalPrice)
                                    ->setBaseSubtotal($newTotalPrice)
                                    ->setGrandTotal($newTotalPrice)
                                    ->setBaseGrandTotal($newTotalPrice);;
                                $this->cart->save();
                            }*/
                        }
                        $cartTotalPrice = $this->cart->getQuote();
                        $updateCartTotalPrice = array_sum($cartPriceBeforeDelete);
                        //change cart total price after delete
                        if($updateCartTotalPrice != $cartTotalPrice->getGrandTotal()){
                            $cartTotalPrice->setSubtotalWithDiscount($updateCartTotalPrice)
                                ->setBaseSubtotalWithDiscount($updateCartTotalPrice)
                                ->setSubtotal($updateCartTotalPrice)
                                ->setBaseSubtotal($updateCartTotalPrice)
                                ->setGrandTotal($updateCartTotalPrice)
                                ->setBaseGrandTotal($updateCartTotalPrice);
                            $cartTotalPrice->save();
                        }
                    }
                }
            }
        }
    }
}
