<?php
/**
 *
 * @category    Webdawe
 * @package     Webdawe_Vero
 * @author      Anil Paul
 * @copyright   Copyright (c) 2016 Webdawe
 * @license
 */
class Webdawe_Vero_Block_Adminhtml_Vero_Customer_Grid_Column_Renderer_Subscribed extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	/**
	 * Render grid column.
	 * 
	 * @param Varien_Object $row
	 * @return string
	 */
	public function render(Varien_Object $row)
	{
		if ($row->getIsSubscriber())
		{
			return '<span class="grid-severity-notice"><span>'.$this->__('Yes').'</span></span>';
		}
		
		return '<span class="grid-severity-critical"><span>'.$this->__('No').'</span></span>';
	}
	
	/**
	 * Render grid column for export.
	 * 
	 * @param Varien_Object $row
	 * @return string
	 */
	public function renderExport(Varien_Object $row)
	{
		return $row->getIsSubscriber();
	}
}
?>