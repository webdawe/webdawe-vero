<?php
/**
 * @category    Webdawe
 * @package     Webdawe_Vero
 * @author      Anil Paul
 * @copyright   Copyright (c) 2016 Webdawe
 * @license
 */
class Webdawe_Vero_Block_Adminhtml_Vero_Customer_History_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
		public function __construct()
		{
			parent::__construct();
			$this->setId("veroCustomerHistoryGrid");
			$this->setDefaultSort("modified_at");
			$this->setDefaultDir("DESC");
			$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
			$collection = Mage::getModel("webdawe_vero/customer_import_history")->getCollection();
			$this->setCollection($collection);

			return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{

			$this->addColumn("history_id", array(
				"header" => Mage::helper("webdawe_vero")->__("History ID"),
				"index" => "history_id",
				"type" => "text",
				"width" => "60px",
			));

			$this->addColumn("message", array(
				"header" => Mage::helper("webdawe_vero")->__("Message"),
				"index" => "message",
				"type" => "text",
				"width" => "200px",
			));

			$this->addColumn("status", array(
				"header" => Mage::helper("webdawe_vero")->__("Status"),
				"index" => "status",
				"type" => "options",
				"width" => "50px",
				'options'	=> Mage::getSingleton('webdawe_vero/customer_import_history')->getAllStatus(),
				'renderer'	=> 'Webdawe_Vero_Block_Adminhtml_Vero_Customer_History_Grid_Column_Renderer_Status',
			));

			$this->addColumn("created_at", array(
				"header" => Mage::helper("webdawe_vero")->__("Created"),
				"index" => "created_at",
				"width" => "150px",
				"type" => "datetime"
			));

			$this->addColumn("modified_at", array(
				"header" => Mage::helper("webdawe_vero")->__("Modified"),
				"index" => "modified_at",
				"width" => "150px",
				"type" => "datetime"
			));

			$this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV'));
			$this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

			return parent::_prepareColumns();
		}


		protected function _prepareMassaction()
		{
			$this->setMassactionIdField('history_id');
			$this->getMassactionBlock()->setFormFieldName('history_ids');
			$this->getMassactionBlock()->setUseSelectAll(true);
			$this->getMassactionBlock()->addItem('remove_vero_customer_import_history', array(
					 'label'=> Mage::helper('webdawe_vero')->__('Remove Vero Customer Import History'),
					 'url'  => $this->getUrl('adminhtml/vero_customer_history/massRemove'),
					 'confirm' => Mage::helper('webdawe_vero')->__('Are you sure to remove the selected Customer Import History?')
				));
			return $this;
		}
			

}