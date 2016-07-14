<?php
/**
 *
 * @category    Webdawe
 * @package     Webdawe_Vero
 * @author      Anil Paul
 * @copyright   Copyright (c) 2016 Webdawe
 * @license
 */
    class Webdawe_Vero_Helper_Order_Queue extends Webdawe_Vero_Helper_Abstract
{

    private $_orderAttributes = array();
    private $_orderItemCustomAttributes = array();
    private $_orderItemAttributes = array('product_id' ,'sku','name','qty_ordered','price','row_total','discount_amount');

    const EVENT_PLACED_ORDER = 'placed_order';
    const EVENT_PURCHASED_PRODUCT = 'purchased_order';

    /**
     * Add Order
     * @param Mage_Sales_Model_Order $order
     */
    public function add(Mage_Sales_Model_Order $order)
    {

        if (!$order->getCustomerEmail())
        {
            return;
        }

        //Add the Email to the Customer Queue First
        $veroCustomer = Mage::helper('webdawe_vero/customer_queue')->addTransactionalCustomer($order);

        if ($veroId = $veroCustomer->getVeroId())
        {
            $veroOrder = Mage::getModel('webdawe_vero/order')->load($order->getId(), 'order_id');

            //if already in the queue no need to add.
            if ($veroOrder->getId())
            {
                return;
            }

            $veroOrder->setVeroId($veroId);

            $veroOrder->setOrderId($order->getId());
            $veroOrder->setQuoteId($order->getQuoteId());
            $veroOrder->setEmail($order->getCustomerEmail());

            $veroOrder->setIsImported(Webdawe_Vero_Model_Order::YESNO_NO);
            $veroOrder->setPriority(Webdawe_Vero_Model_Order::PRIORITY_NORMAL);

            try
            {
                $veroOrder->save();
            }

            catch (Exception $error)
            {
                Mage::log('Exception while saving the Order to Vero Order Queue' . $error->getMessage(), null, 'vero.log');
            }
        }
    }

    /**
     * Retrieve Order Attributes
     * @return array
     */
    public function getOrderAttributes()
    {
        /* @var $configHelper Webdawe_Vero_Helper_Config */
        $configHelper = Mage::helper('webdawe_vero/config');

        if (!count($this->_orderAttributes))
        {
            $this->_orderAttributes = $configHelper->getOrderAttributes();
        }

        return $this->_orderAttributes;
    }

    /**
     * Retrieve Order Item Attributes
     * @return array
     */
    public function getOrderItemAttributes()
    {
        return $this->_orderItemAttributes;
    }

    /**
     * Retrieve Order Item Custom Attributes
     * @return array
     */
    public function getOrderItemCustomAttributes()
    {
        return $this->_orderItemCustomAttributes;
    }

    private function _getProductCustomProperties($productOptions)
    {
        $customOptions = array();

        /* @var $helper Webdawe_Vero_Helper_Data */
        $helper = Mage::helper('webdawe_vero');

        foreach($productOptions['options'] as $productOption)
        {

            foreach ($this->getOrderItemCustomAttributes() as $optionKey)
            {
                if ($productOption['label'] == $optionKey)
                {
                    $customOptions[$helper->getCamelCasedToUnderscoreText($optionKey)] = $productOption['value'] ;

                }
            }
        }

        return  $customOptions;
    }
    public function sync($queueId = '')
    {
        /* @var $configHelper Webdawe_Vero_Helper_Config */
        $configHelper = Mage::helper('webdawe_vero/config');

        /* @var $customerHelper Webdawe_Vero_Helper_Customer_Queue */
        $customerHelper = Mage::helper('webdawe_vero/customer_queue');

        /* @var $helper Webdawe_Vero_Helper_Data */
        $helper = Mage::helper('webdawe_vero');

        $messages = array();

        //calculate the import statistics
        $successRecords = 0;
        $errorRecords = 0;

        $veroOrderCollection = Mage::getResourceModel('webdawe_vero/order_collection');

        if (!$queueId)
        {

            $veroOrderCollection->addFieldToFilter('main_table.is_imported', Webdawe_Vero_Model_Customer::YESNO_NO);
            $veroOrderCollection->addFieldToFilter('main_table.attempts', array('lteq' => $configHelper->getMaximumAttempts()));
            $veroOrderCollection->addCustomerToSelect();
            $veroOrderCollection->getSelect()
                ->order('priority DESC,main_table.queue_id')
                ->limit($configHelper->getMaximumRecords());

            $messages = $this->setMessage($messages, $this->__('Fetched %s Records' , $veroOrderCollection->getSize()));

        }
        else
        {
            $veroOrderCollection->addCustomerToSelect();
            $veroOrderCollection->addFieldToFilter('main_table.queue_id', $queueId);
            $messages = $this->setMessage($messages, $this->__('Fetched %s Records Queue ID: %s' , $veroOrderCollection->getSize(), $queueId));
        }

        /* @var $veroClient Webdawe_Vero_Model_Api_Vero */
        $veroClient = $this->_getVeroClient();
        if ($noOfRecords = $veroOrderCollection->getSize())
        {
            foreach ($veroOrderCollection as $veroOrder)
            {

                echo $veroOrder->getOrderId() . PHP_EOL;
                $order = Mage::getModel('sales/order')->load($veroOrder->getOrderId());
                echo $veroOrder->getCustomerImported() . PHP_EOL;
                if ($order->getId())
                {
                    //If the order customer / email is not imported, import that first
                    if (!$veroOrder->getCustomerImported())
                    {
                        $result = $customerHelper->sync($veroOrder->getCustomerQueueId());

                        $success = ($result->status == Webdawe_Vero_Model_Api_Vero::SUCCESS_STATUS
                            && $result->message == Webdawe_Vero_Model_Api_Vero::SUCCESS_MESSAGE);
                        // If customer import is not success, continue to the next order
                        if(!$success)
                        {
                            continue;
                        }
                    }

                    $identify = array('id' => $veroOrder->getVeroId());
                    $orderProperties = $helper->getKeyValueArrayFromGivenKeys($this->getOrderAttributes(), $order->getData());

                    $result = $veroClient->track(self::EVENT_PLACED_ORDER, $identify, $orderProperties);

                    print_r($result);

                    $success = ($result->status == Webdawe_Vero_Model_Api_Vero::SUCCESS_STATUS
                        && $result->message == Webdawe_Vero_Model_Api_Vero::SUCCESS_MESSAGE);

                    foreach ($order->getAllVisibleItems() as $item)
                    {
                        $orderItemProperties = $helper->getKeyValueArrayFromGivenKeys($this->getOrderItemAttributes(), $item->getData());

                        $orderItemProperties = array_merge($orderItemProperties, $this->_getProductCustomProperties($item->getProductOptions()));

                        $result = $veroClient->track(self::EVENT_PURCHASED_PRODUCT, $identify, $orderItemProperties);

                        $success = ($result->status == Webdawe_Vero_Model_Api_Vero::SUCCESS_STATUS
                            && $result->message == Webdawe_Vero_Model_Api_Vero::SUCCESS_MESSAGE);

                        print_r($result);
                    }
                    exit;
                }

            }
        }
    }
}