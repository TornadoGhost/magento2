<?php

namespace Tasks\CustomAttributeAndSet\Model;

use Magento\Authorization\Model\UserContextInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\GroupInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\Model\AbstractExtensibleModel;
use Magento\Framework\Validator\Exception as ValidatorException;
use Magento\Payment\Model\Method\AbstractMethod;
use Magento\Quote\Api\CartManagementInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\PaymentInterface;
use Magento\Quote\Model\CustomerManagement;
use Magento\Quote\Model\Quote\Address\ToOrder as ToOrderConverter;
use Magento\Quote\Model\Quote\Address\ToOrderAddress as ToOrderAddressConverter;
use Magento\Quote\Model\Quote as QuoteEntity;
use Magento\Quote\Model\Quote\AddressFactory;
use Magento\Quote\Model\Quote\Item\ToOrderItem as ToOrderItemConverter;
use Magento\Quote\Model\Quote\Payment\ToOrderPayment as ToOrderPaymentConverter;
use Magento\Quote\Model\QuoteFactory;
use Magento\Quote\Model\QuoteIdMaskFactory;
use Magento\Quote\Model\ResourceModel\Quote\Item;
use Magento\Quote\Model\SubmitQuoteValidator;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderInterfaceFactory as OrderFactory;
use Magento\Sales\Api\OrderManagementInterface as OrderManagement;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class for managing quote
 *
 * @SuppressWarnings(PHPMD.CookieAndSessionMisuse)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class QuoteManagement implements CartManagementInterface
{
    /**
     * @var EventManager
     */
    protected $eventManager;

    /**
     * @var SubmitQuoteValidator
     */
    private $submitQuoteValidator;

    /**
     * @var OrderFactory
     */
    protected $orderFactory;

    /**
     * @var OrderManagement
     */
    protected $orderManagement;

    /**
     * @var CustomerManagement
     */
    protected $customerManagement;

    /**
     * @var ToOrderConverter
     */
    protected $quoteAddressToOrder;

    /**
     * @var ToOrderAddressConverter
     */
    protected $quoteAddressToOrderAddress;

    /**
     * @var ToOrderItemConverter
     */
    protected $quoteItemToOrderItem;

    /**
     * @var ToOrderPaymentConverter
     */
    protected $quotePaymentToOrderPayment;

    /**
     * @var UserContextInterface
     */
    protected $userContext;

    /**
     * @var CartRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var CustomerFactory
     */
    protected $customerModelFactory;

    /**
     * @var AddressFactory
     */
    protected $quoteAddressFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var CheckoutSession
     */
    protected $checkoutSession;

    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var AccountManagementInterface
     */
    protected $accountManagement;

    /**
     * @var QuoteFactory
     */
    protected $quoteFactory;

    /**
     * @var QuoteIdMaskFactory
     */
    private $quoteIdMaskFactory;

    /**
     * @var AddressRepositoryInterface
     */
    private $addressRepository;

    /**
     * @var array
     */
    private $addressesToSync = [];

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var RemoteAddress
     */
    private $remoteAddress;

    public function __construct(
        EventManager $eventManager,
        SubmitQuoteValidator $submitQuoteValidator,
        OrderFactory $orderFactory,
        OrderManagement $orderManagement,
        CustomerManagement $customerManagement,
        ToOrderConverter $quoteAddressToOrder,
        ToOrderAddressConverter $quoteAddressToOrderAddress,
        ToOrderItemConverter $quoteItemToOrderItem,
        ToOrderPaymentConverter $quotePaymentToOrderPayment,
        UserContextInterface $userContext,
        CartRepositoryInterface $quoteRepository,
        CustomerRepositoryInterface $customerRepository,
        CustomerFactory $customerModelFactory,
        AddressFactory $quoteAddressFactory,
        DataObjectHelper $dataObjectHelper,
        StoreManagerInterface $storeManager,
        CheckoutSession $checkoutSession,
        CustomerSession $customerSession,
        AccountManagementInterface $accountManagement,
        QuoteFactory $quoteFactory,
        QuoteIdMaskFactory $quoteIdMaskFactory = null,
        AddressRepositoryInterface $addressRepository = null,
        RequestInterface $request = null,
        RemoteAddress $remoteAddress = null
    ) {
        $this->eventManager = $eventManager;
        $this->submitQuoteValidator = $submitQuoteValidator;
        $this->orderFactory = $orderFactory;
        $this->orderManagement = $orderManagement;
        $this->customerManagement = $customerManagement;
        $this->quoteAddressToOrder = $quoteAddressToOrder;
        $this->quoteAddressToOrderAddress = $quoteAddressToOrderAddress;
        $this->quoteItemToOrderItem = $quoteItemToOrderItem;
        $this->quotePaymentToOrderPayment = $quotePaymentToOrderPayment;
        $this->userContext = $userContext;
        $this->quoteRepository = $quoteRepository;
        $this->customerRepository = $customerRepository;
        $this->customerModelFactory = $customerModelFactory;
        $this->quoteAddressFactory = $quoteAddressFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->storeManager = $storeManager;
        $this->checkoutSession = $checkoutSession;
        $this->accountManagement = $accountManagement;
        $this->customerSession = $customerSession;
        $this->quoteFactory = $quoteFactory;
        $this->quoteIdMaskFactory = $quoteIdMaskFactory ?: ObjectManager::getInstance()
            ->get(QuoteIdMaskFactory::class);
        $this->addressRepository = $addressRepository ?: ObjectManager::getInstance()
            ->get(AddressRepositoryInterface::class);
        $this->request = $request ?: ObjectManager::getInstance()
            ->get(RequestInterface::class);
        $this->remoteAddress = $remoteAddress ?: ObjectManager::getInstance()
            ->get(RemoteAddress::class);
    }

    public function createEmptyCart()
    {
        $storeId = $this->storeManager->getStore()->getStoreId();
        $quote = $this->createAnonymousCart($storeId);

        $quote->setBillingAddress($this->quoteAddressFactory->create());
        $quote->setShippingAddress($this->quoteAddressFactory->create());
        $quote->setCustomerIsGuest(1);

        try {
            $quote->getShippingAddress()->setCollectShippingRates(true);
            $this->quoteRepository->save($quote);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__("The quote can't be created."));
        }
        return $quote->getId();
    }
    public function createEmptyCartForCustomer($customerId)
    {
        $storeId = $this->storeManager->getStore()->getStoreId();
        $quote = $this->createCustomerCart($customerId, $storeId);

        $this->_prepareCustomerQuote($quote);

        try {
            $this->quoteRepository->save($quote);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__("The quote can't be created."));
        }
        return (int)$quote->getId();
    }
    public function assignCustomer($cartId, $customerId, $storeId)
    {
        $quote = $this->quoteRepository->getActive($cartId);
        $customer = $this->customerRepository->getById($customerId);
        $customerModel = $this->customerModelFactory->create();

        if (!in_array($storeId, $customerModel->load($customerId)->getSharedStoreIds())) {
            throw new StateException(
                __("The customer can't be assigned to the cart. The cart belongs to a different store.")
            );
        }
        if ($quote->getCustomerId()) {
            throw new StateException(
                __("The customer can't be assigned to the cart because the cart isn't anonymous.")
            );
        }
        try {
            $customerActiveQuote = $this->quoteRepository->getForCustomer($customerId);

            $quote->merge($customerActiveQuote);
            $customerActiveQuote->setIsActive(0);
            $this->quoteRepository->save($customerActiveQuote);

            // phpcs:ignore Magento2.CodeAnalysis.EmptyBlock
        } catch (NoSuchEntityException $e) {
        }

        $quote->setCustomer($customer);
        $quote->setCustomerIsGuest(0);
        $quote->setIsActive(1);

        $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'quote_id');
        if ($quoteIdMask->getId()) {
            $quoteIdMask->delete();
        }

        $this->quoteRepository->save($quote);

        return true;
    }
    protected function createAnonymousCart($storeId)
    {
        $quote = $this->quoteFactory->create();
        $quote->setStoreId($storeId);
        return $quote;
    }
    protected function createCustomerCart($customerId, $storeId)
    {
        try {
            $quote = $this->quoteRepository->getActiveForCustomer($customerId);
        } catch (NoSuchEntityException $e) {
            $customer = $this->customerRepository->getById($customerId);

            $quote = $this->quoteFactory->create();
            $quote->setStoreId($storeId);
            $quote->setCustomer($customer);
            $quote->setCustomerIsGuest(0);
        }
        return $quote;
    }
    public function placeOrder($cartId, PaymentInterface $paymentMethod = null)
    {
        $quote = $this->quoteRepository->getActive($cartId);
        $customer = $quote->getCustomer();
        $customerId = $customer ? $customer->getId() : null;

        if ($paymentMethod) {
            $paymentMethod->setChecks(
                [
                    AbstractMethod::CHECK_USE_CHECKOUT,
                    AbstractMethod::CHECK_USE_FOR_COUNTRY,
                    AbstractMethod::CHECK_USE_FOR_CURRENCY,
                    AbstractMethod::CHECK_ORDER_TOTAL_MIN_MAX,
                    AbstractMethod::CHECK_ZERO_TOTAL
                ]
            );
            $quote->getPayment()->setQuote($quote);

            $data = $paymentMethod->getData();
            $quote->getPayment()->importData($data);
        } else {
            $quote->collectTotals();
        }

        if ($quote->getCheckoutMethod() === self::METHOD_GUEST || !$customerId) {
            $quote->setCustomerId(null);
            $billingAddress = $quote->getBillingAddress();
            $quote->setCustomerEmail($billingAddress ? $billingAddress->getEmail() : null);
            if ($quote->getCustomerFirstname() === null
                && $quote->getCustomerLastname() === null
                && $billingAddress
            ) {
                $quote->setCustomerFirstname($billingAddress->getFirstname());
                $quote->setCustomerLastname($billingAddress->getLastname());
                if ($billingAddress->getMiddlename() === null) {
                    $quote->setCustomerMiddlename($billingAddress->getMiddlename());
                }
            }
            $quote->setCustomerIsGuest(true);
            $groupId = $customer ? $customer->getGroupId() : GroupInterface::NOT_LOGGED_IN_ID;
            $quote->setCustomerGroupId($groupId);
        }

        $remoteAddress = $this->remoteAddress->getRemoteAddress();
        if ($remoteAddress !== false) {
            $quote->setRemoteIp($remoteAddress);
            $quote->setXForwardedFor(
                $this->request->getServer('HTTP_X_FORWARDED_FOR')
            );
        }

        $this->eventManager->dispatch('checkout_submit_before', ['quote' => $quote]);

        $order = $this->submit($quote);

        if (null == $order) {
            throw new LocalizedException(
                __('A server error stopped your order from being placed. Please try to place your order again.')
            );
        }

        $this->checkoutSession->setLastQuoteId($quote->getId());
        $this->checkoutSession->setLastSuccessQuoteId($quote->getId());
        $this->checkoutSession->setLastOrderId($order->getId());
        $this->checkoutSession->setLastRealOrderId($order->getIncrementId());
        $this->checkoutSession->setLastOrderStatus($order->getStatus());

        $this->eventManager->dispatch('checkout_submit_all_after', ['order' => $order, 'quote' => $quote]);
        return $order->getId();
    }
    public function getCartForCustomer($customerId)
    {
        return $this->quoteRepository->getActiveForCustomer($customerId);
    }
    public function submit(QuoteEntity $quote, $orderData = [])
    {
        if (!$quote->getAllVisibleItems()) {
            $quote->setIsActive(false);
            return null;
        }

        return $this->submitQuote($quote, $orderData);
    }
    protected function resolveItems(QuoteEntity $quote)
    {
        $orderItems = [];
        foreach ($quote->getAllItems() as $quoteItem) {
            $itemId = $quoteItem->getId();

            if (!empty($orderItems[$itemId])) {
                continue;
            }

            $parentItemId = $quoteItem->getParentItemId();
            /** @var Item $parentItem */
            if ($parentItemId && !isset($orderItems[$parentItemId])) {
                $orderItems[$parentItemId] = $this->quoteItemToOrderItem->convert(
                    $quoteItem->getParentItem(),
                    ['parent_item' => null]
                );
            }
            $parentItem = isset($orderItems[$parentItemId]) ? $orderItems[$parentItemId] : null;
            $orderItems[$itemId] = $this->quoteItemToOrderItem->convert($quoteItem, ['parent_item' => $parentItem]);
        }
        return array_values($orderItems);
    }

    protected function submitQuote(QuoteEntity $quote, $orderData = [])
    {
        $order = $this->orderFactory->create();
        $this->submitQuoteValidator->validateQuote($quote);
        if (!$quote->getCustomerIsGuest()) {
            if ($quote->getCustomerId()) {
                $this->_prepareCustomerQuote($quote);
                $this->customerManagement->validateAddresses($quote);
            }
            $this->customerManagement->populateCustomerInfo($quote);
        }
        $addresses = [];
        $quote->reserveOrderId();
        if ($quote->isVirtual()) {
            $this->dataObjectHelper->mergeDataObjects(
                OrderInterface::class,
                $order,
                $this->quoteAddressToOrder->convert($quote->getBillingAddress(), $orderData)
            );
        } else {
            $this->dataObjectHelper->mergeDataObjects(
                OrderInterface::class,
                $order,
                $this->quoteAddressToOrder->convert($quote->getShippingAddress(), $orderData)
            );
            $shippingAddress = $this->quoteAddressToOrderAddress->convert(
                $quote->getShippingAddress(),
                [
                    'address_type' => 'shipping',
                    'email' => $quote->getCustomerEmail()
                ]
            );
            $shippingAddress->setData('quote_address_id', $quote->getShippingAddress()->getId());
            $addresses[] = $shippingAddress;
            $order->setShippingAddress($shippingAddress);
            $order->setShippingMethod($quote->getShippingAddress()->getShippingMethod());
        }
        $billingAddress = $this->quoteAddressToOrderAddress->convert(
            $quote->getBillingAddress(),
            [
                'address_type' => 'billing',
                'email' => $quote->getCustomerEmail()
            ]
        );
        $billingAddress->setData('quote_address_id', $quote->getBillingAddress()->getId());
        $addresses[] = $billingAddress;
        $order->setBillingAddress($billingAddress);
        $order->setAddresses($addresses);
        $order->setPayment($this->quotePaymentToOrderPayment->convert($quote->getPayment()));
        $order->setItems($this->resolveItems($quote));
        if ($quote->getCustomer()) {
            $order->setCustomerId($quote->getCustomer()->getId());
        }
        $order->setQuoteId($quote->getId());
        $order->setCustomerEmail($quote->getCustomerEmail());
        $order->setCustomerFirstname($quote->getCustomerFirstname());
        $order->setCustomerMiddlename($quote->getCustomerMiddlename());
        $order->setCustomerLastname($quote->getCustomerLastname());

        if ($quote->getOrigOrderId()) {
            $order->setEntityId($quote->getOrigOrderId());
        }

        if ($quote->getReservedOrderId()) {
            $order->setIncrementId($quote->getReservedOrderId());
        }

        $this->submitQuoteValidator->validateOrder($order);

        $this->eventManager->dispatch(
            'sales_model_service_quote_submit_before',
            [
                'order' => $order,
                'quote' => $quote
            ]
        );

        try {
            //save
            $myAttr = $quote->getItems();
            if ($myAttr[0]->getMyColumn()) {
                $myAttr = $myAttr[0]->getMyColumn();
            }
            foreach ($order->getItems() as $ord) {
                $ord->setMyColumn($myAttr);
            }
            //save
            $order = $this->orderManagement->place($order);


            $quote->setIsActive(false);
            $this->eventManager->dispatch(
                'sales_model_service_quote_submit_success',
                [
                    'order' => $order,
                    'quote' => $quote
                ]
            );
            //save
            $this->quoteRepository->save($quote);
        } catch (\Exception $e) {
            $this->rollbackAddresses($quote, $order, $e);
            throw $e;
        }
        return $order;
    }

    protected function _prepareCustomerQuote($quote)
    {
        $billing = $quote->getBillingAddress();
        $shipping = $quote->isVirtual() ? null : $quote->getShippingAddress();

        $customer = $this->customerRepository->getById($quote->getCustomerId());
        $hasDefaultBilling = (bool)$customer->getDefaultBilling();
        $hasDefaultShipping = (bool)$customer->getDefaultShipping();

        if ($shipping && !$shipping->getSameAsBilling()
            && (!$shipping->getCustomerId() || $shipping->getSaveInAddressBook())
        ) {
            if ($shipping->getQuoteId()) {
                $shippingAddress = $shipping->exportCustomerAddress();
            } else {
                $defaultShipping = $this->customerRepository->getById($customer->getId())->getDefaultShipping();
                if ($defaultShipping) {
                    try {
                        $shippingAddress = $this->addressRepository->getById($defaultShipping);
                        // phpcs:ignore Magento2.CodeAnalysis.EmptyBlock
                    } catch (LocalizedException $e) {
                        // no address
                    }
                }
            }
            if (isset($shippingAddress)) {
                if (!$hasDefaultShipping) {
                    //Make provided address as default shipping address
                    $shippingAddress->setIsDefaultShipping(true);
                    $hasDefaultShipping = true;
                    if (!$hasDefaultBilling && !$billing->getSaveInAddressBook()) {
                        $shippingAddress->setIsDefaultBilling(true);
                        $hasDefaultBilling = true;
                    }
                }
                //save here new customer address
                $shippingAddress->setCustomerId($quote->getCustomerId());
                $this->addressRepository->save($shippingAddress);
                $quote->addCustomerAddress($shippingAddress);
                $shipping->setCustomerAddressData($shippingAddress);
                $this->addressesToSync[] = $shippingAddress->getId();
                $shipping->setCustomerAddressId($shippingAddress->getId());
            }
        }

        if (!$billing->getCustomerId() || $billing->getSaveInAddressBook()) {
            if ($billing->getQuoteId()) {
                $billingAddress = $billing->exportCustomerAddress();
            } else {
                $defaultBilling = $this->customerRepository->getById($customer->getId())->getDefaultBilling();
                if ($defaultBilling) {
                    try {
                        $billingAddress = $this->addressRepository->getById($defaultBilling);
                        // phpcs:ignore Magento2.CodeAnalysis.EmptyBlock
                    } catch (LocalizedException $e) {
                        // no address
                    }
                }
            }
            if (isset($billingAddress)) {
                if (!$hasDefaultBilling) {
                    //Make provided address as default shipping address
                    if (!$hasDefaultShipping) {
                        //Make provided address as default shipping address
                        $billingAddress->setIsDefaultShipping(true);
                    }
                    $billingAddress->setIsDefaultBilling(true);
                }
                $billingAddress->setCustomerId($quote->getCustomerId());
                $this->addressRepository->save($billingAddress);
                $quote->addCustomerAddress($billingAddress);
                $billing->setCustomerAddressData($billingAddress);
                $this->addressesToSync[] = $billingAddress->getId();
                $billing->setCustomerAddressId($billingAddress->getId());
            }
        }
        if ($shipping && !$shipping->getCustomerId() && !$hasDefaultBilling) {
            $shipping->setIsDefaultBilling(true);
        }
    }
    private function rollbackAddresses(
        QuoteEntity $quote,
        OrderInterface $order,
        \Exception $e
    ): void {
        try {
            if (!empty($this->addressesToSync)) {
                foreach ($this->addressesToSync as $addressId) {
                    $this->addressRepository->deleteById($addressId);
                }
            }
            $this->eventManager->dispatch(
                'sales_model_service_quote_submit_failure',
                [
                    'order' => $order,
                    'quote' => $quote,
                    'exception' => $e,
                ]
            );
        } catch (\Exception $consecutiveException) {
            $message = sprintf(
                "An exception occurred on 'sales_model_service_quote_submit_failure' event: %s",
                $consecutiveException->getMessage()
            );
            // phpcs:ignore Magento2.Exceptions.DirectThrow
            throw new \Exception($message, 0, $e);
        }
    }
}
