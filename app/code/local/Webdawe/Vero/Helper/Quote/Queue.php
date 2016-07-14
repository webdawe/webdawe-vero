<?php
/**
 *
 * @category    Webdawe
 * @package     Webdawe_Vero
 * @author      Anil Paul
 * @copyright   Copyright (c) 2016 Webdawe
 * @license
 */
class Webdawe_Vero_Helper_Quote_Queue extends Webdawe_Vero_Helper_Abstract
{

    private $_customerAttributes = array();
    private $_customerAddressAttributes = array();

    const GROUP_FUNDRAISER = 'Fundraiser';


    public function add(Mage_Sales_Model_Quote $quote)
    {

        if (!$quote->getCustomerEmail())
        {
            return;
        }

        //Add the Email to the Customer Queue First
        $veroCustomer = Mage::helper('webdawe_vero/customer_queue')->addQuoteCustomer($quote);


        if ($veroId = $veroCustomer->getVeroId())
        {
            $veroQuote = Mage::getModel('webdawe_vero/quote')->load($quote->getId(), 'quote_id');

            $veroQuote->setVeroId($veroId);
            $veroQuote->setQuoteId($quote->getId());
            $veroQuote->setEmail($quote->getCustomerEmail());

            $veroQuote->setIsImported(Webdawe_Vero_Model_Order::YESNO_NO);
            $veroQuote->setPriority(Webdawe_Vero_Model_Order::PRIORITY_NORMAL);


            try
            {
                $veroQuote->save();
            }
            catch (Exception $error)
            {
                Mage::log('Exception while saving the Quote to Vero Quote Queue' . $error->getMessage(), null, 'vero.log');
            }
        }

    }
}