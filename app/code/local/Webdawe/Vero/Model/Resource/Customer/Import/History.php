<?php
/**
 *
 * @category    Webdawe
 * @package     Webdawe_Vero
 * @author      Anil Paul
 * @copyright   Copyright (c) 2016 Webdawe
 * @license
 */
class Webdawe_Vero_Model_Resource_Customer_Import_History extends Mage_Core_Model_Resource_Db_Abstract
{
		
	/**
	 * Initialize resource model.
	 * 
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('webdawe_vero/customer_import_history', 'history_id');
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
	 * Validate Vero Customer Import History
	 * @param Mage_Core_Model_Abstract $object
	 * @return array
	 */
	public function validate(Mage_Core_Model_Abstract $object)
	{
		$errors = array();

		if (!Zend_Validate::is($object->getMessage(), 'NotEmpty'))
		{
			$errors[] = Mage::helper('webdawe_vero')->__('Message cannot be empty.');
		}
		if (!Zend_Validate::is($object->getStatus(), 'NotEmpty'))
		{
			$errors[] = Mage::helper('webdawe_vero')->__('Status cannot be empty.');
		}

		return $errors;
	}
}
?>