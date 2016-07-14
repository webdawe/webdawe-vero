<?php
/**
 * @category    Webdawe
 * @package     Webdawe_Vero
 * @author      Anil Paul
 * @copyright   Copyright (c) 2016 Webdawe
 * @license
 */
class Webdawe_Vero_Block_Adminhtml_Vero_Customer_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
		public function __construct()
		{
			parent::__construct();
			$this->setId("veroCustomerGrid");
			$this->setDefaultSort("modified_at");
			$this->setDefaultDir("DESC");
			$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
			$collection = Mage::getModel("webdawe_vero/customer")->getCollection();
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

			$this->addColumn("vero_id", array(
				"header" => Mage::helper("webdawe_vero")->__("Vero ID"),
				"index" => "vero_id",
				"type" => "text",
				"width" => "60px",
			));

			$this->addColumn("customer_id", array(
			"header" => Mage::helper("webdawe_vero")->__("Customer ID"),
			"index" => "customer_id",
			"type" => "number",
			"width" => "50px",
			));
			$this->addColumn("subscriber_id", array(
				"header" => Mage::helper("webdawe_vero")->__("Subscriber ID"),
				"index" => "subscriber_id",
				"type" => "number",
				"width" => "50px",
			));

			$this->addColumn('website_id', array(
				'header'    => Mage::helper('customer')->__('Website'),
				'align'     => 'center',
				'width'     => '200px',
				'type'      => 'options',
				'options'   => Mage::getSingleton('adminhtml/system_store')->getWebsiteOptionHash(true),
				'index'     => 'website_id',
			));

			$this->addColumn('store_id', array(
				'header' => Mage::helper('webdawe_vero')->__('Store'),
				'index' => 'store_id',
				'type' => 'store',
				'width' => '200px',
				'store_view'=> true,
				'display_deleted' => false,
			));
			$this->addColumn("email", array(
				"header" => Mage::helper("webdawe_vero")->__("Email"),
				"index" => "email",
				"type" => "text",
				"width" => "200px",
			));

			$this->addColumn("is_subscribed", array(
				"header" => Mage::helper("webdawe_vero")->__("Subscribed"),
				"index" => "is_subscriber",
				"type" => "options",
				"width" => "50px",
				'options'	=> Mage::getSingleton('webdawe_vero/customer')->getYesOrNo(),
				'renderer'	=> 'Webdawe_Vero_Block_Adminhtml_Vero_Customer_Grid_Column_Renderer_Subscribed',
			));

			$this->addColumn("is_imported", array(
				"header" => Mage::helper("webdawe_vero")->__("Imported"),
				"index" => "is_imported",
				"type" => "options",
				"width" => "50px",
				'options'	=> Mage::getSingleton('webdawe_vero/customer')->getYesOrNo(),
				'renderer'	=> 'Webdawe_Vero_Block_Adminhtml_Vero_Customer_Grid_Column_Renderer_Imported',
			));

			$this->addColumn("is_guest", array(
				"header" => Mage::helper("webdawe_vero")->__("Guest"),
				"index" => "is_guest",
				"type" => "options",
				"width" => "50px",
				'options'	=> Mage::getSingleton('webdawe_vero/customer')->getYesOrNo(),
				'renderer'	=> 'Webdawe_Vero_Block_Adminhtml_Vero_Customer_Grid_Column_Renderer_Guest',
			));

			$this->addColumn("priority", array(
				"header" => Mage::helper("webdawe_vero")->__("Priority"),
				"index" => "priority",
				"type" => "options",
				"width" => "50px",
				'options'	=> Mage::getSingleton('webdawe_vero/customer')->getPriorityOptions(),
				'renderer'	=> 'Webdawe_Vero_Block_Adminhtml_Vero_Customer_Grid_Column_Renderer_Priority',
			));

			$this->addColumn("action", array(
				"header" => Mage::helper("webdawe_vero")->__("Last Purpose"),
				"index" => "action",
				"type" => "options",
				"width" => "170px",
				'options'	=> Mage::getSingleton('webdawe_vero/customer')->getActionOptions(),
				'renderer'	=> 'Webdawe_Vero_Block_Adminhtml_Vero_Customer_Grid_Column_Renderer_Action',
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
			$this->addColumn("tags", array(
				"header" => Mage::helper("webdawe_vero")->__("Tags"),
				"index" => "tags",
				"type" => "text",
				"width" => "150px",
				'renderer'	=> 'Webdawe_Vero_Block_Adminhtml_Vero_Customer_Grid_Column_Renderer_Tags',
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
			$this->getMassactionBlock()->addItem('remove_vero_customer', array(
					 'label'=> Mage::helper('webdawe_vero')->__('Remove Vero Customer'),
					 'url'  => $this->getUrl('adminhtml/vero_customer/massRemove'),
					 'confirm' => Mage::helper('webdawe_vero')->__('Are you sure?')
				));
			return $this;
		}
			

}