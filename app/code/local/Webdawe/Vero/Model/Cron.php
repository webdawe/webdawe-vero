<?php
/**
 *
 * @category    Webdawe
 * @package     Webdawe_Vero
 * @author      Anil Paul
 * @copyright   Copyright (c) 2016 Webdawe
 * @license
 */
class Webdawe_Vero_Model_Cron
{
    /**
     * Import Customers.
     *
     * @return Webdawe_Vero_Model_Cron
     */
    public function customerSync()
    {

        if (Mage::helper('webdawe_vero/config')->getEnabled())
        {
            $helper =  Mage::helper('webdawe_vero/customer_queue');
            $helper->sync();
        }

        return $this;
    }
}