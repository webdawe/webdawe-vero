<?php
/**
 *
 * @category    Webdawe
 * @package     Webdawe_Vero
 * @author      Anil Paul
 * @copyright   Copyright (c) 2016 Webdawe
 * @license
 */
class Webdawe_Vero_Block_Adminhtml_Vero_Order_Grid_Column_Renderer_Priority extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	/**
	 * Render grid column.
	 * 
	 * @param Varien_Object $row
	 * @return string
	 */
	public function render(Varien_Object $row)
	{
		if ($row->isNormalPriority())
		{
			return '<span class="grid-severity-notice"><span>'.$this->__('Normal').'</span></span>';
		}
		if ($row->isMediumPriority())
		{
			return '<span class="grid-severity-major"><span>'.$this->__('Medium').'</span></span>';
		}
		if ($row->isHighPriority())
		{
			return '<span class="grid-severity-critical"><span>'.$this->__('High').'</span></span>';
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
		if ($row->isNormalPriority())
		{
			return $this->__('Normal');
		}
		if ($row->isMediumPriority())
		{
			return $this->__('Medium');
		}
		if ($row->isHighPriority())
		{
			return $this->__('High');
		}
	}
}
?>