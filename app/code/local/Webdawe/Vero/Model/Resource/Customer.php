<?php
/**
 *
 * @category    Webdawe
 * @package     Webdawe_Vero
 * @author      Anil Paul
 * @copyright   Copyright (c) 2016 Webdawe
 * @license
 */
class Webdawe_Vero_Model_Resource_Customer extends Mage_Core_Model_Resource_Db_Abstract
{
		
	/**
	 * Initialize resource model.
	 * 
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('webdawe_vero/customer', 'queue_id');
	}
	

	/**
	 * Perform actions before saving.
	 * 
	 * @param Mage_Core_Model_Abstract $object
	 * @return Mage_Core_Model_Mysql4_Abstract
	 * @throws Mage_Core_Exception
	 */
	protected function _beforeSave(Mage_Core_Model_Abstract $object)
	{

		$errors = $this->validate($object);
		
		if (!empty($errors))
		{
			Mage::throwException(implode("\n", $errors));
		}

		//Assign Date
		$date = Mage::getSingleton('core/date')->gmtDate('Y-m-d H:i:s');

		if (!$object->getCreatedAt())
		{
			$object->setCreatedAt($date);
		}

		$object->setModifiedAt($date);

		return parent::_beforeSave($object);
	}

	/**
	 * Validate Vero Customer
	 * @param Mage_Core_Model_Abstract $object
	 * @return array
	 */
	public function validate(Mage_Core_Model_Abstract $object)
	{
		$errors = array();

		if (!Zend_Validate::is($object->getEmail(), 'NotEmpty'))
		{
			$errors[] = Mage::helper('webdawe_vero')->__('Email cannot be empty.');
		}
		if (!Zend_Validate::is($object->getWebsiteId(), 'NotEmpty'))
		{
			$errors[] = Mage::helper('webdawe_vero')->__('Website Id cannot be empty.');
		}
		if (!Zend_Validate::is($object->getStoreId(), 'NotEmpty'))
		{
			$errors[] = Mage::helper('webdawe_vero')->__('Store Id cannot be empty.');
		}

		return $errors;
	}
}
?>