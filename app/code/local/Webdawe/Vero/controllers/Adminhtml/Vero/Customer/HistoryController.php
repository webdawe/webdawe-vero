<?php
/**
 *
 * @category    Webdawe
 * @package     Webdawe_Vero
 * @author      Anil Paul
 * @copyright   Copyright (c) 2016 Webdawe
 * @license
 */
class Webdawe_Vero_Adminhtml_Vero_Customer_HistoryController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu("webdawe_vero/adminhtml_vero_customer_customer_history")
            ->_addBreadcrumb(Mage::helper("adminhtml")->__("Vero"),Mage::helper("adminhtml")->__("Customer Import History"));

        return $this;
    }
    public function indexAction()
    {
        $this->_title($this->__("Vero"));
        $this->_title($this->__("Customer Import History"));

        $this->_initAction();

        $this->renderLayout();


    }

    /**
     * Mass delete action.
     *
     * @return void
     */
    public function massRemoveAction()
    {
        $ids = $this->getRequest()->getParam('history_ids');

        if (!is_array($ids) || count($ids) < 1)
        {
            $this->_getSession()->addError($this->__('Please select atleast one history to delete.'));
            $this->_redirect('*/*/');

            return;
        }

        $collection = Mage::getResourceModel('webdawe_vero/customer_import_history_collection');
        $collection->addFieldToFilter('history_id', array('in' => $ids));

        Mage::dispatchEvent('webdawe_vero_customer_prepare_mass_delete', array('customer_import_history_collection' => $collection));

        if (!$collection->count())
        {
            $this->_getSession()->addNotice($this->__('None of the customer import history were deleted.'));
            $this->_redirect('*/*/');

            return;
        }

        try
        {
            foreach ($collection as $item)
            {
                $item->delete();
            }

            $this->_getSession()->addSuccess($this->__('Total of %d customer import history have been deleted.', $collection->count()));
        }
        catch (Mage_Core_Exception $error)
        {
            $this->_getSession()->addError($error->getMessage());
        }
        catch (Exception $error)
        {
            $this->_getSession()->addException($error, $this->__('An error occurred while deleting customer import history.'));
        }

        $this->_redirect('*/*/');
    }

    /**
     * Export order grid to CSV format
     */
    public function exportCsvAction()
    {
        $fileName   = 'vero_customers_import_history.csv';
        $grid       = $this->getLayout()->createBlock('webdawe_vero/adminhtml_vero_customer_history_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }
    /**
     *  Export order grid to Excel XML format
     */
    public function exportExcelAction()
    {
        $fileName   = 'vero_customers_import_history.xml';
        $grid       = $this->getLayout()->createBlock('webdawe_vero/adminhtml_vero_customer_history_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }
}
