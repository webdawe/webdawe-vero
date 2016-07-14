<?php
/**
 *
 * @category    Webdawe
 * @package     Webdawe_Vero
 * @author      Anil Paul
 * @copyright   Copyright (c) 2016 Webdawe
 * @license
 */
class Webdawe_Vero_Helper_Customer_Queue extends Webdawe_Vero_Helper_Abstract
{

    private $_customerAttributes = array();
    private $_customerAddressAttributes = array();

    const GROUP_FUNDRAISER = 'Fundraiser';

    /**
     * Retrieve Vero Customer Model According to the Object Passing
     * @param Mage_Customer_Model_Customer | Mage_Newsletter_Model_Subscriber | Mage_Sales_Model_Order $object
     * @return Webdawe_Vero_Model_Customer $veroCustomer
     */
    public function getVeroCustomerModel($object)
    {

        if ($object instanceof Mage_Customer_Model_Customer)
        {
            $email = $object->getEmail();
            $websiteId = $object->getWebsiteId();
            $customerId = $object->getId();

            $subscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($email);

        }
        else if ($object instanceof Mage_Newsletter_Model_Subscriber)
        {
            $email = $object->getSubscriberEmail();
            $websiteId =  Mage::getModel('core/store')->load($object->getStoreId())->getWebsiteId();
            $customerId = $object->getCustomerId();

            $subscriber = $object;
        }
        else if ($object instanceof Mage_Sales_Model_Order)
        {
            $email = $object->getCustomerEmail();
            $websiteId = $websiteId = Mage::getModel('core/store')->load($object->getStoreId())->getWebsiteId();
            $customerId = $object->getCustomerId();

            $subscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($email);
        }

        else if ($object instanceof Mage_Sales_Model_Quote)
        {
            $email = $object->getCustomerEmail();
            $websiteId = $websiteId = Mage::getModel('core/store')->load($object->getStoreId())->getWebsiteId();
            $customerId = $object->getCustomerId();

            $subscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($email);
        }

        //check whether a customer with the same email and store exists in the vero customer table, If so update the same customer entry, otherwise add new entry
        $veroCustomerCollection = Mage::getResourceModel('webdawe_vero/customer_collection')->addFieldToFilter('email', $email);

        if ($veroCustomerCollection->getSize())
        {
            $veroCustomer = $veroCustomerCollection->getFirstItem();
        }
        else
        {
            $veroCustomer =  Mage::getModel('webdawe_vero/customer');
        }

        $veroCustomer->setEmail($email);
        $veroCustomer->setWebsiteId($websiteId);
        $veroCustomer->setStoreId($object->getStoreId());

        if ($customerId)
        {
            $veroCustomer->setCustomerId($customerId);

        }

        //set Subscriber Information
        if ($subscriberId = $subscriber->getId())
        {
            $veroCustomer->setSubscriberId($subscriberId);

            if ($subscriber->getSubscriberStatus() == Mage_Newsletter_Model_Subscriber::STATUS_SUBSCRIBED)
            {
                $veroCustomer->setIsSubscriber(Webdawe_Vero_Model_Customer::YESNO_YES);
                $veroCustomer->setAction(Webdawe_Vero_Model_Customer::ACTION_SUBSCRIBE);
            }
            else
            {
                $veroCustomer->setIsSubscriber(Webdawe_Vero_Model_Customer::YESNO_NO);
                $veroCustomer->setAction(Webdawe_Vero_Model_Customer::ACTION_UNSUBSCRIBE);
            }
        }

        //determine and set  guest or not
        if (!$customerId && !$subscriberId)
        {
            $veroCustomer->setIsGuest(Webdawe_Vero_Model_Customer::YESNO_YES);
        }
        else
        {
            $veroCustomer->setIsGuest(Webdawe_Vero_Model_Customer::YESNO_NO);
        }

        if (empty($veroCustomer->getVeroId()))
        {
            //Customer
            if ($customerId)
            {
                $veroCustomer->setVeroId(Webdawe_Vero_Model_Customer::CUSTOMER_PREFIX . $customerId);
            }
            //Subscriber
            else if ($subscriberId)
            {
                $veroCustomer->setVeroId(Webdawe_Vero_Model_Customer::SUBSCRIBER_PREFIX . $subscriberId);
            }
            //Guest
            else if (!$customerId && !$subscriberId)
            {
                $veroCustomer->setVeroId(Webdawe_Vero_Model_Customer::GUEST_PREFIX . $object->getId());
            }
        }
        return  $veroCustomer;
    }
    /**
     * Add/ Update Customer to the Vero Customer
     * @param Mage_Customer_Model_Customer $customer
     */
    public function addCustomer(Mage_Customer_Model_Customer $customer)
    {

        $veroCustomer  = $this->getVeroCustomerModel($customer);

        //set import status and priority
        $veroCustomer->setIsImported(Webdawe_Vero_Model_Customer::YESNO_NO);
        $veroCustomer->setPriority(Webdawe_Vero_Model_Customer::PRIORITY_NORMAL);

        try
        {
            $veroCustomer->save();
        }
        catch (Exception $error)
        {
            Mage::log('Exception while saving the Vero Customer on customer save:' . $error->getMessage(), null, 'vero.log');
        }
    }

    /**
     * Set Customer to Delete from Vero
     * @param Mage_Customer_Model_Customer $customer
     */
    public function deleteCustomer(Mage_Customer_Model_Customer $customer)
    {
        $veroCustomer  = $this->getVeroCustomerModel($customer);

        //set import status and priority
        $veroCustomer->setIsImported(Webdawe_Vero_Model_Customer::YESNO_NO);
        $veroCustomer->setPriority(Webdawe_Vero_Model_Customer::PRIORITY_HIGH);

        $veroCustomer->setAction(Webdawe_Vero_Model_Customer::ACTION_DELETE);

        try
        {
            $veroCustomer->save();
        }
        catch (Exception $error)
        {
            Mage::log('Exception while saving the Vero Customer on customer save:' . $error->getMessage(), null, 'vero.log');
        }
    }
    /**
     * Add/ Update Subscriber to the Vero Customer
     * @param Mage_Newsletter_Model_Subscriber $subscriber
     */
    public function addSubscriber(Mage_Newsletter_Model_Subscriber $subscriber)
    {
        $veroCustomer  = $this->getVeroCustomerModel($subscriber);

        //set import status and priority
        $veroCustomer->setIsImported(Webdawe_Vero_Model_Customer::YESNO_NO);
        $veroCustomer->setPriority(Webdawe_Vero_Model_Customer::PRIORITY_NORMAL);

        try
        {
            $veroCustomer->save();
        }
        catch (Exception $error)
        {
            Mage::log('Exception while saving the Vero Customer on subscribe:' . $error->getMessage(), null, 'vero.log');
        }
    }

    public function addTransactionalCustomer(Mage_Sales_Model_Order $order)
    {
        if ($email = $order->getCustomerEmail())
        {
            $veroCustomer  = $this->getVeroCustomerModel($order);

            //set import status and priority
            $veroCustomer->setIsImported(Webdawe_Vero_Model_Customer::YESNO_NO);
            $veroCustomer->setPriority(Webdawe_Vero_Model_Customer::PRIORITY_HIGH);

            $veroCustomer->setAction(Webdawe_Vero_Model_Customer::ACTION_TRANSACTIONAL);

            try
            {
                $veroCustomer->save();
            }
            catch (Exception $error)
            {
                Mage::log('Exception while saving the Vero Customer on trasactional:' . $error->getMessage(), null, 'vero.log');
            }
        }

        //Add Fundraiser Customer to the Vero Customer if they have earned points
        if ($fundraiserId = $order->getFundraiserId())
        {
            $fundraiser = Mage::getModel('ontic_fundraiser/fundraiser')->load($fundraiserId);

            if ($customerId = $fundraiser->getCustomerId())
            {
                $customer = Mage::getModel('customer/customer')->load($customerId);
                $veroCustomer  = $this->getVeroCustomerModel($customer);
                //set import status and priority
                $veroCustomer->setIsImported(Webdawe_Vero_Model_Customer::YESNO_NO);
                $veroCustomer->setPriority(Webdawe_Vero_Model_Customer::PRIORITY_HIGH);

                $veroCustomer->setAction(Webdawe_Vero_Model_Customer::ACTION_REWARDS);

                try
                {
                    $veroCustomer->save();
                }
                catch (Exception $error)
                {
                    Mage::log('Exception while saving the Vero Customer on fundraiser points donation:' . $error->getMessage(), null, 'vero.log');
                }
            }
        }

        return $veroCustomer;
    }

    /***
     * Add Quote Customer to Queue
     * @param Mage_Sales_Model_Quote $quote
     * @return Webdawe_Vero_Model_Customer
     */
    public function addQuoteCustomer(Mage_Sales_Model_Quote $quote)
    {
        if ($email = $quote->getCustomerEmail())
        {
            $veroCustomer  = $this->getVeroCustomerModel($quote);

            //set import status and priority
            $veroCustomer->setIsImported(Webdawe_Vero_Model_Customer::YESNO_NO);
            $veroCustomer->setPriority(Webdawe_Vero_Model_Customer::PRIORITY_HIGH);

            $veroCustomer->setAction(Webdawe_Vero_Model_Customer::ACTION_TRANSACTIONAL);

            try
            {
                $veroCustomer->save();
            }
            catch (Exception $error)
            {
                Mage::log('Exception while saving the Vero Customer on Quote:' . $error->getMessage(), null, 'vero.log');
            }

            return $veroCustomer;
        }

    }

    /**
     * Retrieve Customer Attributes
     * @return array
     */
    public function getCustomerAttributes()
    {
        /* @var $configHelper Webdawe_Vero_Helper_Config */
        $configHelper = Mage::helper('webdawe_vero/config');

        if (!count($this->_customerAttributes))
        {
            $this->_customerAttributes = $configHelper->getCustomerAttributes();
        }

        return $this->_customerAttributes;
    }

    /**
     * Retrieve Customer Address Attributes
     * @return array
     */
    public function getCustomerAddressAttributes()
    {
        /* @var $configHelper Webdawe_Vero_Helper_Config */
        $configHelper = Mage::helper('webdawe_vero/config');

        if (!count($this->_customerAddressAttributes))
        {
            $this->_customerAddressAttributes = $configHelper->getCustomerAddressAttributes();
        }

        return $this->_customerAddressAttributes;
    }

    /**
     * Manupulate Street Information
     * @param Mage_Customer_Model_Address | Mage_Sales_Model_Order_Address $address
     * @param string $type
     * @return array
     */
    private function _manipulateStreetAddress($address, $type)
    {
        $street = $address->getStreet();
        $streetAddress = array();

        if ($street[0])
        {
            $streetAddress[ $type . '_address_1'] = $street[0];
        }
        if (count($street) > 1)
        {
            if ($street[1] != $street[0])
            {
                $streetAddress[$type .'_address_2'] = $street[1];
            }
            else
            {
                $streetAddress[$type .'_address_2'] = '';
            }
        }
        $countryName = Mage::getModel('directory/country')->load($address->getCountryId())->getName();

        $streetAddress[$type .'_country'] = $countryName;

        return $streetAddress;
    }

    /**
     * Retrieve Customer Properties
     * @param Webdawe_Vero_Model_Customer $veroCustomer
     * @return array
     */
    private function _getCustomerProperties(Webdawe_Vero_Model_Customer $veroCustomer)
    {

        $customerAttributes = array();

        if (!$veroCustomer->getCustomerId())
        {
            return $customerAttributes;
            $this->_showDebug('No Vero Customer Id Found', $customerAttributes);
        }

        $customerId = $veroCustomer->getCustomerId();

        /* @var $helper Webdawe_Vero_Helper_Data */
        $helper = Mage::helper('webdawe_vero');

        /* @var $configHelper Webdawe_Vero_Helper_Config */
        $configHelper = Mage::helper('webdawe_vero/config');

        $customer = Mage::getModel('customer/customer')->load($customerId);

        if (!$customer->getId())
        {
            return $customerAttributes;
            $this->_showDebug('No Customer Id Found', $customerAttributes);
        }

        //load customer attributes
        $customerAttributes = $helper->getKeyValueArrayFromGivenKeys($this->getCustomerAttributes(), $customer->getData());

        //Add Customer Id
        $customerAttributes['customer_id'] = $customerId;

        //load billing address attributes
        $customerBillingAttributes = array();
        if ($billingAddressId = $customer->getDefaultBilling())
        {
            $billingAddress = Mage::getModel('customer/address')->load($billingAddressId);
            $billingAttributes = $billingAddress->getData();

            $addressAttributes = $this->getCustomerAddressAttributes();
            $streetAddress = array();

            if(in_array('street', $addressAttributes))
            {
                $streetAddress = $this->_manipulateStreetAddress( $billingAddress, 'billing');
                $key = array_search('street', $addressAttributes);
                unset($addressAttributes[$key]);
            }

            if(in_array('country_id', $addressAttributes))
            {

                $key = array_search('country_id', $addressAttributes);
                unset($addressAttributes[$key]);
            }

            $customerBillingAttributes = $helper->getKeyValueArrayFromGivenKeys($addressAttributes, $billingAttributes, 'billing');
            $customerBillingAttributes = array_merge($customerBillingAttributes, $streetAddress);
        }
        else
        {
            $customerBillingAttributes = $this->_getAddressFromOrder($veroCustomer, 'billing');
        }

        //load shipping address attributes
        $customerShippingAttributes = array();

        if($shippingAddressId = $customer->getDefaultShipping())
        {
            $shippingAddress = Mage::getModel('customer/address')->load($shippingAddressId);
            $shippingAttributes = $shippingAddress->getData();
            $addressAttributes = $this->getCustomerAddressAttributes();

            if(in_array('street', $addressAttributes))
            {
                $streetAddress = $this->_manipulateStreetAddress($shippingAddress, 'shipping');

                $key = array_search('street', $addressAttributes);
                unset($addressAttributes[$key]);
            }

            if(in_array('country_id',  $addressAttributes))
            {
                $key = array_search('country_id', $addressAttributes);
                unset($addressAttributes[$key]);
            }

            $customerShippingAttributes = $helper->getKeyValueArrayFromGivenKeys($addressAttributes, $shippingAttributes, 'shipping');
            $customerShippingAttributes = array_merge($customerShippingAttributes, $streetAddress);

        }
        else
        {
            $customerShippingAttributes = $this->_getAddressFromOrder($veroCustomer, 'shipping');
        }

        $rewardsProperties = $this->_getRewardsProperties($customer);
        $websiteName = Mage::getModel('core/website')->load($customer->getWebsiteId())->getName();
        $storeName = $websiteName . ' ' . Mage::getModel('core/store')->load($customer->getStoreId())->getName();

        //other important fields
        $customerAttributes['customer_group'] = Mage::getModel('customer/group')->load($customer->getGroupId())->getCustomerGroupCode();
        $customerAttributes['website_name'] =  $websiteName;
        $customerAttributes['store_name'] =$storeName;

        //retrieve Fundraiser Properties
        $fundraiserProperties = array();

        if ($customerAttributes['customer_group'] == self::GROUP_FUNDRAISER)
        {
            $fundraiserProperties = $this->getFundraiserProperties($customer);
        }

        $customerProperties = array_merge($customerAttributes, $customerBillingAttributes, $customerShippingAttributes, $rewardsProperties, $fundraiserProperties);

        $this->_showDebug('Customer Properties', $customerProperties);
        return $customerProperties;
    }

    /**
     * Retrieve Fundraiser Properties
     * @param Mage_Customer_Model_Customer $customer
     * @return array
     */
    public function getFundraiserProperties(Mage_Customer_Model_Customer $customer)
    {
        $fundraiserProperties = array();
        $fundraiserCollection = Mage::getResourceModel('ontic_fundraiser/fundraiser_collection')->addFieldToFilter('customer_id', $customer->getId());
        $fundraiserCollection->addGroupNameToSelect();


        $fundraiser = $fundraiserCollection->getFirstItem();

        if ($fundraiser->getFundraiserId())
        {
            $fundraiserProperties['fundraiser_id'] = $fundraiser->getFundraiserId();
            $fundraiserProperties['fundraiser_group'] = $fundraiser->getGroupName();
            $fundraiserProperties['fundraiser_name'] = $fundraiser->getBusinessName();
        }

        $this->_showDebug('Fundraiser Properties', $fundraiserProperties);

        return $fundraiserProperties;
    }

    /**
     * Retrieve Billing Address FROM order
     * @param Webdawe_Vero_Model_Customer $veroCustomer
     * #param string $type
     * @return array
     */
    private function _getAddressFromOrder(Webdawe_Vero_Model_Customer $veroCustomer, $type)
    {
        $customerAddressAttributes = array();

        /* @var $helper Webdawe_Vero_Helper_Data */
        $helper = Mage::helper('webdawe_vero');

        /* @var $configHelper Webdawe_Vero_Helper_Config */
        $configHelper = Mage::helper('webdawe_vero/config');

        //Retrieve last order Id
        $query = "SELECT entity_id  as order_id FROM sales_flat_order
                  WHERE customer_email='" . $veroCustomer->getEmail() . "'
                  ORDER BY created_at DESC limit 1";

        if($orderId = $this->getReadConnection()->fetchOne($query))
        {

            $order = Mage::getModel('sales/order')->load($orderId);

            if ($type == 'billing')
            {
               $address = $order->getBillingAddress();
            }
            else
            {
               $address = $order->getShippingAddress();
            }

            $attributes = $address->getData();

            $addressAttributes = $this->getCustomerAddressAttributes();
            $streetAddress = array();

            if(in_array('street',  $addressAttributes))
            {
               $streetAddress = $this->_manipulateStreetAddress($address, $type);
               $key = array_search('street', $addressAttributes);
               unset($addressAttributes[$key]);
            }

            if(in_array('country_id',  $addressAttributes))
            {

               $key = array_search('country_id', $addressAttributes);
               unset($addressAttributes[$key]);
            }

            $customerAddressAttributes = $helper->getKeyValueArrayFromGivenKeys($addressAttributes, $attributes, $type);
            $customerAddressAttributes = array_merge($customerAddressAttributes, $streetAddress);
        }
        $this->_showDebug('Address From Order', $customerAddressAttributes);
        return $customerAddressAttributes;
    }

    /**
     * Retrieve Rewards Properties of a Customer
     * @param Mage_Customer_Model_Customer $customer
     * @return array
     */
    private function _getRewardsProperties(Mage_Customer_Model_Customer $customer)
    {
        $rewardsProperties = array();

        if (Mage::helper('webdawe_vero/config')->isRewardsEnabled())
        {
            $rewardsCustomer = Mage::getModel('rewards/customer')->getRewardsCustomer($customer);

            if ($usablePoints = $rewardsCustomer->getUsablePoints())
            {
                $rewardsProperties['remaining_points'] = $usablePoints[1];
            }
        }
        $this->_showDebug('Reward Properties', $rewardsProperties);

        return $rewardsProperties;
    }

    /**
     * Retireve Transactional Data
     * @param Webdawe_Vero_Model_Customer $veroCustomer
     * @return array
     */
    private function _getTransactionalProperties(Webdawe_Vero_Model_Customer $veroCustomer)
    {
        $transactionalProperties = array();

        //retrieve aggregate
        $query = "SELECT SUM(grand_total) as total_spend, count(*) as number_of_orders
                  FROM sales_flat_order WHERE customer_email='" . $veroCustomer->getEmail() . "'
                  ";

        $result = $this->getReadConnection()->fetchAll($query);

        if (count($result))
        {

            $transactionalProperties = array_merge($transactionalProperties, $result[0]);

            //retrieve last order info
            $query = "SELECT created_at AS last_order_date,entity_id AS last_order_id FROM sales_flat_order
                      WHERE customer_email='" . $veroCustomer->getEmail() . "' ORDER BY created_at DESC LIMIT 1";

            $result = $this->getReadConnection()->fetchAll($query);

            if (count($result))
            {
                $transactionalProperties = array_merge($transactionalProperties, $result[0]);
            }
        }

        $this->_showDebug('Transcational Properties', $transactionalProperties);
        return $transactionalProperties;
    }


    /**
     * Retrieve Subscriber Properties
     * @param Webdawe_Vero_Model_Customer $veroCustomer
     * @return array
     */
    private function _getSubscriberProperties(Webdawe_Vero_Model_Customer $veroCustomer)
    {
        $subscriberProperties = array();

        if(!$veroCustomer->getSubscriberId())
        {
           return $subscriberProperties;
        }

        $subscriberId = $veroCustomer->getSubscriberId();

        /* @var $subscriber Mage_Newsletter_Model_Subscriber */
        $subscriber = Mage::getModel('newsletter/subscriber')->load($subscriberId);

        if($subscriber->getSubscriberStatus() == Mage_Newsletter_Model_Subscriber::STATUS_SUBSCRIBED)
        {
            $subscriberProperties['subscribed'] = Webdawe_Vero_Model_Customer::YESNO_YES;
        }
        else
        {
            $subscriberProperties['subscribed'] = Webdawe_Vero_Model_Customer::YESNO_NO;
        }

        $subscriberProperties['subscriber_id'] = $subscriberId;

        $this->_showDebug('Subscriber Properties', $subscriberProperties);

        return  $subscriberProperties;
    }

    /**
     * Update Import Status
     * @param Webdawe_Vero_Model_Customer $veroCustomer
     * @param bool $success
     */
    private function _updateImportStatus(Webdawe_Vero_Model_Customer $veroCustomer, $success)
    {
         $attempts =  $veroCustomer->getAttempts();
        //If Successfully imported set imported status to Yes.
        if ($success)
        {
            $veroCustomer->setIsImported(Webdawe_Vero_Model_Customer::YESNO_YES);
            $veroCustomer->setAttempts(1);
        }
        else
        {
            $attempts++;
            $veroCustomer->setAttempts($attempts);
        }

        try
        {
            $veroCustomer->save();
        }
        catch (Exception $error)
        {
            Mage::log("Error While updating Vero Customer:" . $error->getMessage(), null, 'vero.log');
            echo 'Error Customer' . $error->getMessage() . PHP_EOL;
        }

    }

    /**
     * Retireve Guest Properties
     * @param Webdawe_Vero_Model_Customer $veroCustomer
     * @return array
     */
    private function _getGuestProperties(Webdawe_Vero_Model_Customer $veroCustomer)
    {
        $guestProperties = array();

        if (!$veroCustomer->getIsGuest())
        {
            return $guestProperties;
        }

        //check whether any order is being placed before by this email Id
       $query = "SELECT customer_firstname AS firstname,customer_lastname AS lastname,store_id, entity_id as last_order_id
                  FROM sales_flat_order WHERE customer_email='" . $veroCustomer->getEmail() . "'
                  ORDER BY created_at DESC limit 1";

        $result = $this->getReadConnection()->fetchAll($query);

        if (count($result))
        {
            $guestProperties  = $result[0];

            $orderShippingAttributes = $this->_getAddressFromOrder($veroCustomer, 'shipping');
            $orderBillingAttributes = $this->_getAddressFromOrder($veroCustomer, 'billing');

            $guestProperties = array_merge($guestProperties,$orderShippingAttributes, $orderBillingAttributes);

        }
        //check whether any quote is being generated by this email Id
        else
        {
            $query = "SELECT customer_firstname AS firstname,customer_lastname AS lastname,store_id
                      FROM sales_flat_quote WHERE customer_email='" . $veroCustomer->getEmail() . "'
                      ORDER BY created_at DESC limit 1";

            $result = $this->getReadConnection()->fetchAll($query);
            if (count($result))
            {
                $guestProperties  = $result[0];
            }
        }

        if (array_key_exists('store_id', $guestProperties))
        {
            $store = Mage::getModel('core/store')->load($guestProperties['store_id']);
            $website = Mage::getModel('core/website')->load($store->getWebsiteId());
            $guestProperties['store_name'] = $website->getName() . ' ' . $store->getName();
            $guestProperties['website_name'] = $website->getName();
            unset($guestProperties['store_id']);
        }

        $this->_showDebug('Guest Properties', $guestProperties);

        return $guestProperties;
    }

    /**
     * Set Customer To Re-import
     * @param int $customerId
     * @param int $action
     */
    public function setCustomerToReImport($customerId, $action = Webdawe_Vero_Model_Customer::ACTION_UNSUBSCRIBE)
    {
        try
        {

            $condition = array("customer_id = ?" => $customerId);
            if (!is_int($customerId))
            {
                $condition = array("email = ?" => $customerId);
            }
            $this->getReadConnection()->update(
                $this->getResource()->getTableName('webdawe_vero/customer'),
                array('is_imported' => Webdawe_Vero_Model_Customer::YESNO_NO ,
                      'action'  => $action
                    ),
                $condition
            );
        }
        catch (Exception $error)
        {
            Mage::logException($error);
        }
    }

    /**
     * Retrieve Customer Website Tags
     * @param Webdawe_Vero_Model_Customer $veroCustomer
     * @return array
     */
    public function getWebsiteTags(Webdawe_Vero_Model_Customer $veroCustomer)
    {
        $websiteTags = array();

        $websitePrefix = Mage::helper('webdawe_vero/config')->getWebsitePrefix();
        if ($customerId = $veroCustomer->getCustomerId())
        {
            $collection = Mage::getResourceModel('customer/customer_collection');
            $collection->addAttributeToSelect('website_id');
            $collection->addFieldToFilter('email', $veroCustomer->getEmail());
            $collection->getSelect()->join(
                array('website_table' => $this->getResource()->getTableName('core/website')),
                'e.website_id = website_table.website_id',
                array('website_name' => 'name')
            );

            foreach ($collection as $website)
            {
                $websiteCountry = str_replace($websitePrefix, '', $website->getWebsiteName());
                array_push($websiteTags, $websiteCountry);
            }
        }
        else
        {
            $website = Mage::getModel('core/website')->load($veroCustomer->getWebsiteId());

            if ($websiteName = $website->getName())
            {
                $websiteCountry = str_replace($websitePrefix, '', $websiteName);
                array_push($websiteTags, $websiteCountry);
            }
        }



        $this->_showDebug('Website Tags', $websiteTags);

        return $websiteTags;
    }

    /**
     * Sync Tags to Vero
     * @param Webdawe_Vero_Model_Customer $veroCustomer
     * @param array $customerProperties
     * @return bool
     */
    public function syncTags(Webdawe_Vero_Model_Customer $veroCustomer, $customerProperties)
    {
        $addTags = array();

        //tag custoemr group
        if (array_key_exists('customer_group', $customerProperties))
        {
           array_push($addTags, $customerProperties['customer_group']);
        }
        else
        {
            array_push($addTags, 'Guest');
        }
        //tag website countries
        $addTags = array_merge($addTags, $this->getWebsiteTags($veroCustomer));

        $currentTags = array();

        //check whether any difference in the tags
        if ($veroCustomer->getTags())
        {
            $currentTags = unserialize($veroCustomer->getTags());
        }

        $tags = array_diff($addTags, $currentTags);

        //sync Tag only if there is any difference in Tags
        if (count($tags))
        {
            /* @var $veroClient Webdawe_Vero_Model_Api_Vero */
            $veroClient = $this->_getVeroClient();

            $result = $veroClient->tags($veroCustomer->getVeroId(), $addTags, $currentTags);

            $success = ($result->status == Webdawe_Vero_Model_Api_Vero::SUCCESS_STATUS
                && $result->message == Webdawe_Vero_Model_Api_Vero::SUCCESS_MESSAGE);

            if(!$success)
            {
                $logMessage = sprintf('Error While Syncing (tag)- Customer Email %s : %s', $veroCustomer->getEmail(), $result->message);
                Mage::log($logMessage, null, 'vero.log') ;

            }
            $veroCustomer->setTags(serialize($addTags));
            //update import status
            $veroCustomer->setMessage('');
            $this->_showDebug('Added Tags', $addTags);
        }
        else
        {
            $success = Webdawe_Vero_Model_Customer::YESNO_YES;
        }

        $this->_updateImportStatus($veroCustomer, $success);

        return $success;
    }
    /**
     * Insert / Update Impory History
     * @param int $status
     * @param string|array $message
     * @param string $historyId
     * @return int $historyId
     */
    private function updateImportHistory($status = null, $message = null, $historyId = null)
    {

        if($message == null && $status == null)
        {
            return $historyId;
        }

        /* @var $importHistory Webdawe_Vero_Model_Customer_Import_History */
        $importHistory = Mage::getModel('webdawe_vero/customer_import_history');

        if (!is_null($historyId))
        {
            $importHistory->load($historyId, 'history_id');
        }

        if (is_array($message))
        {
            $message = implode('<br>', $message);
        }

        if ($importHistory->getMessage())
        {
            $message =  $importHistory->getMessage() . '<br>' .  $message;
        }

        $importHistory->setMessage($message);
        $importHistory->setStatusChanged(false);

        if (!is_null($status))
        {
            $importHistory->setStatusChanged(true);
            $importHistory->setStatus($status);
        }

        try
        {
            $importHistory->save();
            $historyId = $importHistory->getId();
        }
        catch(Exception $error)
        {
            Mage::log('Error Saving Api History:' . $error->getMessage(), null, 'vero.log');
        }

        return $importHistory->getHistoryId();
    }

    /**
     * @param string $queueId
     * @return mixed
     */
    public function sync($queueId = '')
    {
        /* @var $configHelper Webdawe_Vero_Helper_Config */
        $configHelper = Mage::helper('webdawe_vero/config');

        $messages = array();

        //calculate the import statistics
        $successRecords = 0;
        $errorRecords = 0;

        $status   = Webdawe_Vero_Model_Customer_Import_History::STATUS_FETCHED;

        $veroCustomerCollection = Mage::getResourceModel('webdawe_vero/customer_collection');

        if (!$queueId)
        {

            $veroCustomerCollection->addFieldToFilter('is_imported', Webdawe_Vero_Model_Customer::YESNO_NO);
            $veroCustomerCollection->addFieldToFilter('attempts', array('lteq' => $configHelper->getMaximumAttempts()));
            $veroCustomerCollection->getSelect()
                ->order('priority DESC,queue_id')
                ->limit($configHelper->getMaximumRecords());

            $messages = $this->setMessage($messages, $this->__('Fetched %s Records' , $veroCustomerCollection->getSize()));

        }
        else
        {
            $veroCustomerCollection->addFieldToFilter('queue_id', $queueId);
            $messages = $this->setMessage($messages, $this->__('Fetched %s Records Queue ID: %s' , $veroCustomerCollection->getSize(), $queueId));
        }

        /* @var $veroClient Webdawe_Vero_Model_Api_Vero */
        $veroClient = $this->_getVeroClient();

        if ($noOfRecords = $veroCustomerCollection->getSize())
        {
            $historyId = $this->updateImportHistory($status, $messages);
            $historyId = $this->updateImportHistory(Webdawe_Vero_Model_Customer_Import_History::STATUS_PROCESSING, $messages);
            foreach ($veroCustomerCollection as $veroCustomer)
            {

                $properties = array();
                $updateTags = false;
                $this->_showDebug('Syncing Vero Customer', $veroCustomer->getData());

                if ($veroCustomer->getAction() == Webdawe_Vero_Model_Customer::ACTION_DELETE)
                {
                    $properties['subscribed']  =  Webdawe_Vero_Model_Customer::YESNO_NO;
                    $properties['deleted'] = Webdawe_Vero_Model_Customer::YESNO_YES;
                }
                //otherwise should  update all records
                else
                {
                    $updateTags = true;

                    //retrieve Customer Attributes
                    $customerProperties = $this->_getCustomerProperties($veroCustomer);

                    //retrieve Subscriber Fields
                    $subscriberProperties = $this->_getSubscriberProperties($veroCustomer);

                    //retrieve guest properties
                    $guestProperties = $this->_getGuestProperties($veroCustomer);

                    //Retrieve Transactional Data
                    $transactionalProperties = $this->_getTransactionalProperties($veroCustomer);

                    $properties  = array_merge($customerProperties, $subscriberProperties, $transactionalProperties, $guestProperties);

                    //since there is no valid endpoint for delete just change the subscriber status
                    if ($veroCustomer->getAction() == Webdawe_Vero_Model_Customer::ACTION_DELETE)
                    {
                        $properties['subscribed'] = Webdawe_Vero_Model_Customer::YESNO_NO;
                        $properties['deleted'] = Webdawe_Vero_Model_Customer::YESNO_YES;
                    }
                    else
                    {
                        $properties['deleted'] =  Webdawe_Vero_Model_Customer::YESNO_NO;
                    }
                }

                //when did the customer record updated on magento
                $properties['modified_date'] = $veroCustomer->getModifiedAt();

                //check whether the customer has an entry in Vero, if yes need to change the unique ID from email to the veroId
                if ($veroCustomer->getMessage() != Webdawe_Vero_Model_Api_Vero::SUCCESS_MESSAGE)
                {
                    $result = $veroClient->reidentify($veroCustomer->getEmail(), $veroCustomer->getVeroId());

                    $success = ($result->status == Webdawe_Vero_Model_Api_Vero::SUCCESS_STATUS
                        && $result->message == Webdawe_Vero_Model_Api_Vero::SUCCESS_MESSAGE);

                    if (!$success)
                    {
                        $logMessage = sprintf('Error While Syncing (reidentify)- Customer Email %s : %s', $veroCustomer->getEmail(), $result->message);
                        Mage::log($logMessage, null, 'vero.log') ;
                    }
                }

                $result = $veroClient->identify($veroCustomer->getVeroId(), $veroCustomer->getEmail(), $properties);

                $success = ($result->status == Webdawe_Vero_Model_Api_Vero::SUCCESS_STATUS
                    && $result->message == Webdawe_Vero_Model_Api_Vero::SUCCESS_MESSAGE);

                $veroCustomer->setMessage($result->message);


                if ($success)
                {
                    $this->_updateImportStatus($veroCustomer, $success);

                    if ($updateTags)
                    {
                        $success = $this->syncTags($veroCustomer, $properties);
                    }
                    if ($success)
                    {
                        $successRecords++;
                    }
                    else
                    {
                        $errorRecords++;
                    }
                }
                else
                {
                    $logMessage = sprintf('Error While Syncing (identify)- Customer Email %s : %s', $veroCustomer->getEmail(), $result->message);
                    Mage::log($logMessage, null, 'vero.log');
                }
            }

            $messages = array();
            $messages = $this->setMessage($messages, $this->__('Total %s records imported successfully', $successRecords));
            $messages = $this->setMessage($messages, $this->__('Total %s records failed', $errorRecords));
            $status   = Webdawe_Vero_Model_Customer_Import_History::STATUS_ERROR;

            if ($successRecords > 0)
            {
                $successfullPercentage = ($successRecords / $noOfRecords) * 100 ;
                if ($successfullPercentage >= Webdawe_Vero_Model_Customer_Import_History::SUCCESS_PERCENTAGE)
                {
                    $status = Webdawe_Vero_Model_Customer_Import_History::STATUS_SUCCESS;
                }
                else
                {
                    $messages = $this->setMessage($messages, $this->__('only %s%s records was successful', $successfullPercentage, '%'));
                }
            }

            $historyId = $this->updateImportHistory($status, $messages, $historyId);
        }
        else
        {
            $status   = Webdawe_Vero_Model_Customer_Import_History::STATUS_SUCCESS;
            $messages = $this->setMessage($messages, $this->__('No Records to import'));
            $historyId = $this->updateImportHistory($status, $messages);
        }

        $notifyEmails  = $configHelper->getNotifyEmails($status);
        $notifyMessage = $this->getMessages(1);

        $this->_sendNotification($notifyEmails, 'Vero Customer Import Status', $notifyMessage);

        //If Single Record Sync return $result from Vero
        if ($queueId)
        {
            return $result;
        }
    }
}