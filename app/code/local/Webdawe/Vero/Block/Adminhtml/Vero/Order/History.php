<?php
/**
 *
 * @category    Webdawe
 * @package     Webdawe_Vero
 * @author      Anil Paul
 * @copyright   Copyright (c) 2016 Webdawe
 * @license
 */
class Webdawe_Vero_Block_Adminhtml_Vero_Customer_History extends Mage_Adminhtml_Block_Widget_Grid_Container
{

	public function __construct()
	{
		$this->_controller = "adminhtml_vero_customer_history";
		$this->_blockGroup = "webdawe_vero";
		$this->_headerText = Mage::helper("webdawe_vero")->__("Vero Customer Import History");
		$this->_addButton('configuration', array(
			'id'		=> 'configuration',
			'label'		=> $this->__('Configuration'),
			'onclick'	=> "setLocation('{$this->getConfigurationUrl()}')",
			'class'		=> 'back',
		));
		parent::__construct();

		$this->_removeButton('add');
	}

	/**
	 * Retrieve the system configuration url.
	 *
	 * @return string
	 */
	public function getConfigurationUrl()
	{
		return $this->getUrl('adminhtml/system_config/edit', array('section' => 'webdawe_vero'));
	}
	
}