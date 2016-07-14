<?php
/**
 *
 * @category    Webdawe
 * @package     Webdawe_Vero
 * @author      Anil Paul
 * @copyright   Copyright (c) 2016 Webdawe
 * @license
 */
class Webdawe_Vero_Model_Observer 
{
    /**
     * Add Customer to Vero Queue
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function onCustomerSaveAfter(Varien_Event_Observer $observer)
    {

        if (Mage::helper('webdawe_vero/config')->getEnabled())
        {
            /* @var $customer Mage_Customer_Model_Customer */
            $customer = $observer->getCustomer();

            /* @var $helper Webdawe_Vero_Helper_Customer_Queue */
            $helper = Mage::helper('webdawe_vero/customer_queue');

            $helper->addCustomer($customer);
        }

        return $this;
    }

    /**
     * Add Subscriber to Vero Queue
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function onSubscriberSaveAfter(Varien_Event_Observer $observer)
    {

        if (Mage::helper('webdawe_vero/config')->getEnabled())
        {
            $subscriber = $observer->getEvent()->getSubscriber();

            $helper = Mage::helper('webdawe_vero/customer_queue');
            $helper->addSubscriber($subscriber);
        }

        return $this;
    }

    /**
     * Delete Customer from Vero
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function onCustomerDeleteAfter(Varien_Event_Observer $observer)
    {

        if (Mage::helper('webdawe_vero/config')->getEnabled())
        {
            $customer = $observer->getEvent()->getCustomer();

            $helper = Mage::helper('webdawe_vero/customer_queue');
            $helper->deleteCustomer($customer);
        }
        return $this;
    }
    /**
     * On Quote To Order
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function onQuoteToOrder(Varien_Event_Observer $observer)
    {
        if (Mage::helper('webdawe_vero/config')->getEnabled())
        {

        }

        return $this;
    }

    /**
     * On Quote Save After
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function onQuoteSaveAfter(Varien_Event_Observer $observer)
    {
        if (Mage::helper('webdawe_vero/config')->getEnabled())
        {

            $quote = $observer->getEvent()->getQuote();
            if ($quote->getCustomerEmail())
            {
                Mage::helper('webdawe_vero/quote_queue')->add($quote);
            }

        }

        return $this;
    }
    /**
     * Reward Points Indexer Update
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function onRewardPointsIndexerUpdate(Varien_Event_Observer $observer)
    {
        if (Mage::helper('webdawe_vero/config')->getEnabled()&& Mage::helper('webdawe_vero/config')->isRewardsEnabled())
        {
            $customer = $observer->getEvent()->getCustomer();
            if (!$customer)
            {
                return $this;
            }

            $helper = Mage::helper('webdawe_vero/customer_queue');

            $helper->setCustomerToReImport($customer->getId(), Webdawe_Vero_Model_Customer::ACTION_REWARDS);
        }

        return $this;
    }

    /**
     * Event callback that is invoked after an invoice has been paid.
     *
     * @param Varien_Event_Observer $observer
     * @return Webdawe_Vero_Model_Observer
     */
    public function onInvoicePayment(Varien_Event_Observer $observer)
    {
        if (Mage::helper('webdawe_vero/config')->getEnabled())
        {
            $event = $observer->getEvent();
            $invoice = $event->getInvoice();
            $order = $invoice->getOrder();
            $payment = $order->getPayment();

            $orderHelper = Mage::helper('webdawe_vero/order_queue');

            $request = Mage::app()->getRequest();
            $controllerName = $request->getControllerName();
            $actionName = $request->getActionName();

            if ($controllerName === 'sales_order_invoice' && $actionName === 'save')
            {

                $orderHelper->add($order);
            }
            else if (stripos($payment->getMethod(), 'paypal') === false)
            {
                $orderHelper->add($order);
            }
        }

        return $this;
    }

    /**
     * Event callback that is invoked after an invoice has been paid using PayPal.
     *
     * @param Varien_Event_Observer $observer
     * @return Webdawe_Vero_Model_Observer
     */
    public function onPaypalPayment(Varien_Event_Observer $observer)
    {
        if (Mage::helper('webdawe_vero/config')->getEnabled())
        {
            $event = $observer->getEvent();
            $invoice = $event->getInvoice();
            $order = $invoice->getOrder();
            $payment = $order->getPayment();

            $orderHelper = Mage::helper('webdawe_vero/order_queue');

            if (stripos($payment->getMethod(), 'paypal') !== false) {

                $orderHelper->add($order);
            }
        }

        return $this;
    }

}