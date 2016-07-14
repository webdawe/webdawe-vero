<?php
/**
 *
 * @category    Webdawe
 * @package     Webdawe_Vero
 * @author      Anil Paul
 * @copyright   Copyright (c) 2016 Webdawe
 * @license
 */
class Webdawe_Vero_Model_Resource_Customer_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
	/**
	 * Initialize resource collection.
	 * 
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('webdawe_vero/customer');
	}

}
?>