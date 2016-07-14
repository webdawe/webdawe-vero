<?php
/**
 *
 * @category    Webdawe
 * @package     Webdawe_Vero
 * @author      Anil Paul
 * @copyright   Copyright (c) 2016 Webdawe
 * @license
 */
class Webdawe_Vero_Model_Order extends Mage_Core_Model_Abstract
{
	const EVENT_OBJECT = 'order';
	
	/**
	 * Event prefix name.
	 *
	 * @var string
	 */
	const EVENT_PREFIX = 'webdawe_vero_order';

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

       $this->_init("webdawe_vero/order");

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

}
	 