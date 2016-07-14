<?php
/**
 *
 * @category    Webdawe
 * @package     Webdawe_Vero
 * @author      Anil Paul
 * @copyright   Copyright (c) 2016 Webdawe
 * @license
 */
class Webdawe_Vero_Model_Customer_Import_History extends Mage_Core_Model_Abstract
{
    const EVENT_OBJECT = 'customer_impory_history';

    /**
     * Event prefix name.
     *
     * @var string
     */
    const EVENT_PREFIX = 'webdawe_vero_customer_import_history';

    /**
     * Fetched Records
     */
    const STATUS_FETCHED = 1;

    /**
     * Processing Records
     */
    const STATUS_PROCESSING = 3;

    /**
     * Import Success
     */
    const STATUS_SUCCESS = 2;

    /**
     * Import Error
     */
    const STATUS_ERROR = 0;

    /**
     * Success Percentage
     */
    const SUCCESS_PERCENTAGE = 80;

    /**
     * Stores the prefix added to event names.
     *
     * @var string
     */
    protected $_eventPrefix = self::EVENT_PREFIX;

    /**
     * Stores the event object key name.
     *
     * @var string
     */
    protected $_eventObject = self::EVENT_OBJECT;

    /**
     * Initialize model.
     *
     * @return void
     */
    protected function _construct()
    {

        $this->_init("webdawe_vero/customer_import_history");

    }

    /**
     * Whether Success
     * @return bool
     */
    public function isSuccess()
    {
        return $this->getStatus() == self::STATUS_SUCCESS;
    }

    /**
     * Whether Error
     * @return bool
     */
    public function isError()
    {
        return $this->getStatus() == self::STATUS_ERROR;
    }

    /**
     * Whether Fetched
     * @return bool
     */
    public function isFetched()
    {
        return $this->getStatus() == self::STATUS_FETCHED;
    }

    /**
     * Whether Processing
     * @return bool
     */
    public function isProcessing()
    {
        return $this->getStatus() == self::STATUS_PROCESSING;
    }

    /**
     * Retrieve All Status
     *
     * @return array
     */
    public function getAllStatus()
    {
        $options = new Varien_Object(array(
            self::STATUS_FETCHED	 => Mage::helper('webdawe_vero')->__('Fetched'),
            self::STATUS_PROCESSING => Mage::helper('webdawe_vero')->__('Processing'),
            self::STATUS_ERROR	 => Mage::helper('webdawe_vero')->__('Error'),
            self::STATUS_SUCCESS	 => Mage::helper('webdawe_vero')->__('Success'),
        ));

        return $options->getData();
    }
}