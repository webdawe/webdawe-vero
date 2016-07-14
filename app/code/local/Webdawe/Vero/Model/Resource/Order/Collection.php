<?php
/**
 *
 * @category    Webdawe
 * @package     Webdawe_Vero
 * @author      Anil Paul
 * @copyright   Copyright (c) 2016 Webdawe
 * @license
 */
class Webdawe_Vero_Model_Resource_Order_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
	/**
	 * Initialize resource collection.
	 * 
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('webdawe_vero/order');
	}

	public function addCustomerToSelect()
	{
		if (!$this->hasFlag('customer'))
		{
			$this->setFlag('customer');

			$this->getSelect()->join(
				array('customer_table' => $this->getTable('webdawe_vero/customer')),
				'main_table.vero_id = customer_table.vero_id',
				array('customer_queue_id'=> 'queue_id','customer_imported' => 'is_imported','message' => 'message')
			);
		}

		return $this;
	}
}
?>