<?php
/**
 *
 * @category    Webdawe
 * @package     Webdawe_Vero
 * @author      Anil Paul
 * @copyright   Copyright (c) 2016 Webdawe
 * @license
 */
class Webdawe_Vero_Model_Customer extends Mage_Core_Model_Abstract
{
	const EVENT_OBJECT = 'customer';
	
	/**
	 * Event prefix name.
	 *
	 * @var string
	 */
	const EVENT_PREFIX = 'webdawe_vero_customer';
	

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
	 * Subscribe Action
	 */
	const ACTION_SUBSCRIBE = 1;

	/**
	 * Unsubscribe Action
	 */
	const ACTION_UNSUBSCRIBE = 0;

	/**
	 * Resubscribe Action
	 */
	const ACTION_RESUBSCRIBE = 2;

	/**
	 * Delete Action
	 */
	const ACTION_DELETE = 3;

	/**
	 * Rewards Action
	 */
	const ACTION_REWARDS = 4;

	/**
	 * Transactional Action
	 */
	const ACTION_TRANSACTIONAL = 5;

	/**
	 * Priority Normal
	 */
	const PRIORITY_NORMAL = 1;

	/**
	 * Priority Medium
	 */
	const PRIORITY_MEDIUM = 2;

	/**
	 * Priority High
	 */
	const PRIORITY_HIGH = 3;

	/**
	 * YES/ NO - Yes
	 */
	const YESNO_YES = 1;
	/**
	 * YES/ NO - No
	 */
	const YESNO_NO = 0;

	/**
	 * Customer Prefix
	 */
	const CUSTOMER_PREFIX = 'C-';

	/**
	 * Subscriber Prefix
	 */
	const SUBSCRIBER_PREFIX = 'S-';
	/**
	 * Guest Prefix
	 */
	const GUEST_PREFIX = 'G-';

	/**
	 * Initialize model.
	 *
	 * @return void
	 */
	protected function _construct()
    {

       $this->_init("webdawe_vero/customer");

    }

	/**
	 * Whether the priority is Normal
	 *
	 * @return boolean
	 */
	public function isNormalPriority()
	{
		return $this->getPriority() == self::PRIORITY_NORMAL;
	}

	/**
	 * Whether the priority is High
	 *
	 * @return boolean
	 */
	public function isHighPriority()
	{
		return $this->getPriority() == self::PRIORITY_HIGH;
	}

	/**
	 * Whether the priority is Medium
	 *
	 * @return boolean
	 */
	public function isMediumPriority()
	{
		return $this->getPriority() == self::PRIORITY_MEDIUM;
	}

    /**
     * Whether the data set in the object is valid.
     *
     * @return array
     */
    public function validate()
    {
    	return $this->getResource()->validate($this);
    }

	/**
	 * Retrieve the Yes / No Options
	 *
	 * @return array
	 */
	public function getYesOrNo()
	{
		$yesOrNo = new Varien_Object(array(
			self::YESNO_YES	=> Mage::helper('webdawe_vero')->__('Yes'),
			self::YESNO_NO	=> Mage::helper('webdawe_vero')->__('No'),
		));

		return $yesOrNo->getData();
	}

	/**
	 * Retrieve Priority Options
	 *
	 * @return array
	 */
	public function getPriorityOptions()
	{
		$yesOrNo = new Varien_Object(array(
			self::PRIORITY_HIGH	=> Mage::helper('webdawe_vero')->__('High'),
			self::PRIORITY_MEDIUM	=> Mage::helper('webdawe_vero')->__('Medium'),
			self::PRIORITY_NORMAL	=> Mage::helper('webdawe_vero')->__('Normal'),
		));

		return $yesOrNo->getData();
	}

	/**
	 * Retrieve Action Options
	 *
	 * @return array
	 */
	public function getActionOptions()
	{
		$options = new Varien_Object(array(
			self::ACTION_SUBSCRIBE	 => Mage::helper('webdawe_vero')->__('Subscribe'),
			self::ACTION_UNSUBSCRIBE => Mage::helper('webdawe_vero')->__('Unsubscribe'),
			self::ACTION_TRANSACTIONAL	 => Mage::helper('webdawe_vero')->__('Update Transactional'),
			self::ACTION_REWARDS	 => Mage::helper('webdawe_vero')->__('Update Rewards'),
		));

		return $options->getData();
	}
}
	 