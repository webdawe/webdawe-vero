<?php
/**
 *
 * @category    Webdawe
 * @package     Webdawe_Vero
 * @author      Anil Paul
 * @copyright   Copyright (c) 2016 Webdawe
 * @license
 */
class Webdawe_Vero_Adminhtml_Vero_QuoteController extends Mage_Adminhtml_Controller_Action
{

	protected function _initAction()
	{
			$this->loadLayout()
				->_setActiveMenu("webdawe_vero/adminhtml_vero_quote")
				->_addBreadcrumb(Mage::helper("adminhtml")->__("Vero"),Mage::helper("adminhtml")->__("Quotes"));

			return $this;
	}
	public function indexAction()
	{
			$this->_title($this->__("Vero"));
			$this->_title($this->__("Quotes"));

			$this->_initAction();

			$this->renderLayout();


	}


	/**
	 * Live Sync Data
	 */
	public function syncAction()
	{
		/*$queueId = $this->getRequest()->getParam('queue_id');

		$result = Mage::helper('webdawe_vero/customer_queue')->sync($queueId);
		$success = ($result->status == Webdawe_Vero_Model_Api_Vero::SUCCESS_STATUS
			&& $result->message == Webdawe_Vero_Model_Api_Vero::SUCCESS_MESSAGE);

		if($success)
		{

			Mage::getSingleton('core/session')->addSuccess('Sucessfully synced to Vero!');
		}
		else
		{
			Mage::getSingleton('core/session')->addError('Something went wrong!');
		}

		$this->_redirectReferer();*/

	}

	/**
	 * Mass delete action.
	 *
	 * @return void
	 */
	public function massRemoveAction()
	{
		$ids = $this->getRequest()->getParam('queue_ids');

		if (!is_array($ids) || count($ids) < 1)
		{
			$this->_getSession()->addError($this->__('Please select vero customer to delete.'));
			$this->_redirect('*/*/');

			return;
		}

		$collection = Mage::getResourceModel('webdawe_vero/quote_collection');
		$collection->addFieldToFilter('queue_id', array('in' => $ids));

		Mage::dispatchEvent('webdawe_vero_order_prepare_mass_delete', array('quote_collection' => $collection));

		if (!$collection->count())
		{
			$this->_getSession()->addNotice($this->__('None of the quotes were deleted.'));
			$this->_redirect('*/*/');

			return;
		}

		try
		{
			foreach ($collection as $item)
			{
				$item->delete();
			}

			$this->_getSession()->addSuccess($this->__('Total of %d vero quote(s) have been deleted.', $collection->count()));
		}
		catch (Mage_Core_Exception $error)
		{
			$this->_getSession()->addError($error->getMessage());
		}
		catch (Exception $error)
		{
			$this->_getSession()->addException($error, $this->__('An error occurred while deleting vero quote.'));
		}

		$this->_redirect('*/*/');
	}

	/**
	 * Export order grid to CSV format
	 */
	public function exportCsvAction()
	{
		$fileName   = 'vero_quotes.csv';
		$grid       = $this->getLayout()->createBlock('webdawe_vero/adminhtml_vero_quote_grid');
		$this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
	}
	/**
	 *  Export order grid to Excel XML format
	 */
	public function exportExcelAction()
	{
		$fileName   = 'vero_quotes.xml';
		$grid       = $this->getLayout()->createBlock('webdawe_vero/adminhtml_vero_quote_grid');
		$this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
	}
}
