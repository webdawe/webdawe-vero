<?php
/**
 * @category    Webdawe
 * @package     Webdawe_Vero
 * @author      Anil Paul
 * @copyright   Copyright (c) 2016 Webdawe
 * @license
 */
class Webdawe_Vero_Block_Adminhtml_Vero_Order_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
		public function __construct()
		{
			parent::__construct();
			$this->setId("veroOrderGrid");
			$this->setDefaultSort("modified_at");
			$this->setDefaultDir("DESC");
			$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
			$collection = Mage::getModel("webdawe_vero/order")->getCollection();
			$this->setCollection($collection);

			return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{

			$this->addColumn("queue_id", array(
				"header" => Mage::helper("webdawe_vero")->__("Queue ID"),
				"index" => "queue_id",
				"type" => "text",
				"width" => "60px",
			));

			$this->addColumn("order_id", array(
				"header" => Mage::helper("webdawe_vero")->__("Order ID"),
				"index" => "order_id",
				"type" => "text",
				"width" => "60px",
			));

			$this->addColumn("quote_id", array(
				"header" => Mage::helper("webdawe_vero")->__("Quote ID"),
				"index" => "quote_id",
				"type" => "text",
				"width" => "60px",
			));

			$this->addColumn("vero_id", array(
				"header" => Mage::helper("webdawe_vero")->__("Vero ID"),
				"index" => "vero_id",
				"type" => "text",
				"width" => "60px",
			));

			$this->addColumn("email", array(
				"header" => Mage::helper("webdawe_vero")->__("Email"),
				"index" => "email",
				"type" => "text",
				"width" => "200px",
			));

			$this->addColumn("is_imported", array(
				"header" => Mage::helper("webdawe_vero")->__("Imported"),
				"index" => "is_imported",
				"type" => "options",
				"width" => "50px",
				'options'	=> Mage::getSingleton('webdawe_vero/order')->getYesOrNo(),
				'renderer'	=> 'Webdawe_Vero_Block_Adminhtml_Vero_Order_Grid_Column_Renderer_Imported',
			));

			$this->addColumn("priority", array(
				"header" => Mage::helper("webdawe_vero")->__("Priority"),
				"index" => "priority",
				"type" => "options",
				"width" => "50px",
				'options'	=> Mage::getSingleton('webdawe_vero/order')->getPriorityOptions(),
				'renderer'	=> 'Webdawe_Vero_Block_Adminhtml_Vero_Order_Grid_Column_Renderer_Priority',
			));

			$this->addColumn("attempts", array(
				"header" => Mage::helper("webdawe_vero")->__("Attempts"),
				"index" => "attempts",
				"type" => "number",
				"width" => "50px",
			));

			$this->addColumn("message", array(
				"header" => Mage::helper("webdawe_vero")->__("Last Status"),
				"index" => "message",
				"type" => "text",
				"width" => "100px",
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

			$this->addColumn('Sync', array(
				'header'	=> $this->__('Sync'),
				'align'		=> 'center',
				'width'		=> 10,
				'type'		=> 'action',
				'sortable'	=> false,
				'filter'	=> false,
				'getter'	=> 'getQueueId',
				'actions'   => array(
					array(
						'caption'	=> Mage::helper('webdawe_vero')->__('Sync'),
						'field'		=> 'queue_id',
						'url'		=> array(
										'base'		=> 'adminhtml/vero_customer/sync',

						)
					)
				)
			));

			$this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV')); 
			$this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

				return parent::_prepareColumns();
		}


		protected function _prepareMassaction()
		{
			$this->setMassactionIdField('queue_id');
			$this->getMassactionBlock()->setFormFieldName('queue_ids');
			$this->getMassactionBlock()->setUseSelectAll(true);
			$this->getMassactionBlock()->addItem('remove_vero_order', array(
					 'label'=> Mage::helper('webdawe_vero')->__('Remove Vero Order'),
					 'url'  => $this->getUrl('adminhtml/vero_order/massRemove'),
					 'confirm' => Mage::helper('webdawe_vero')->__('Are you sure?')
				));
			return $this;
		}
			

}