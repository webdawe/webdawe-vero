<?php
/**
 *
 * @category    Webdawe
 * @package     Webdawe_Vero
 * @author      Anil Paul
 * @copyright   Copyright (c) 2016 Webdawe
 * @license
 */
class Webdawe_Vero_Block_Adminhtml_Vero_Customer_Grid_Column_Renderer_Tags extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	/**
	 * Render grid column.
	 * 
	 * @param Varien_Object $row
	 * @return string
	 */
	public function render(Varien_Object $row)
	{

		if ($row->getTags())
		{
			return '<em>'. implode('<br>', unserialize(strtolower($row->getTags()))).'</em>';
		}
		else
		{
			return '<em>'. $this->__('Not Set') .'</em>';
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