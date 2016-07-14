<?php
/**
 *
 * @category    Webdawe
 * @package     Webdawe_Vero
 * @author      Anil Paul
 * @copyright   Copyright (c) 2016 Webdawe
 * @license
 */
class Webdawe_Vero_Block_Adminhtml_Vero_Customer_History_Grid_Column_Renderer_Status extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	/**
	 * Render grid column.
	 * 
	 * @param Varien_Object $row
	 * @return string
	 */
	public function render(Varien_Object $row)
	{
		if ($row->isSuccess())
		{
			return '<span class="grid-severity-notice"><span>'.$this->__('Success').'</span></span>';
		}
		if ($row->isError())
		{
			return '<span class="grid-severity-critical"><span>'.$this->__('Error').'</span></span>';
		}

		if ($row->isFetched())
		{
			return '<span class="grid-severity-minor"><span>'.$this->__('Fetched').'</span></span>';
		}

		if ($row->isProcessing())
		{
			return '<span class="grid-severity-minor"><span>'.$this->__('Processing').'</span></span>';
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
		return $row->getStatus();
	}
}
?>