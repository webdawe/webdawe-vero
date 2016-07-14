<?php
/**
 *
 * @category    Webdawe
 * @package     Webdawe_Vero
 * @author      Anil Paul
 * @copyright   Copyright (c) 2016 Webdawe
 * @license
 */
class Webdawe_Vero_Block_Adminhtml_Vero_Customer_Grid_Column_Renderer_Action extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	/**
	 * Render grid column.
	 * 
	 * @param Varien_Object $row
	 * @return string
	 */
	public function render(Varien_Object $row)
	{
		switch ($row->getAction())
		{
			case Webdawe_Vero_Model_Customer::ACTION_SUBSCRIBE :
				return '<em>' .$this->__('Subscribe'). '</em>';
				break;
			case Webdawe_Vero_Model_Customer::ACTION_UNSUBSCRIBE :
				return '<em>' .$this->__('Unsubscribe'). '</em>';
				break;
			case Webdawe_Vero_Model_Customer::ACTION_REWARDS :
				return '<em>' .$this->__('Update Rewards'). '</em>';
				break;
			case Webdawe_Vero_Model_Customer::ACTION_TRANSACTIONAL :
				return '<em>' .$this->__('Update Transactional'). '</em>';
				break;
			default:
				return '<em>' .$this->__(''). '</em>';
				break;
		}
	}
	
	/**
	 * Render grid column for export.
	 * 
	 * @param Varien_Object $row
	 * @return string
	 */
	public function renderExport(Varien_Object $row)
	{
		switch ($row->getAction())
		{
			case Webdawe_Vero_Model_Customer::ACTION_SUBSCRIBE :
				return '<em>' .$this->__('Subscribe'). '</em>';
				break;
			case Webdawe_Vero_Model_Customer::ACTION_UNSUBSCRIBE :
				return '<em>' .$this->__('Unsubscribe'). '</em>';
				break;
			case Webdawe_Vero_Model_Customer::ACTION_REWARDS :
				return '<em>' .$this->__('Update Rewards'). '</em>';
				break;
			case Webdawe_Vero_Model_Customer::ACTION_TRANSACTIONAL :
				return '<em>' .$this->__('Update Transactional'). '</em>';
				break;
			default:
				return '<em>' .$this->__(''). '</em>';
				break;
		}
	}
}
?>