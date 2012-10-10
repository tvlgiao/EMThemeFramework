<?php
/**
 * EMThemes
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the framework to newer
 * versions in the future. If you wish to customize the framework for your
 * needs please refer to http://www.emthemes.com/ for more information.
 *
 * @category    EM
 * @package     EM_ThemeFramework
 * @copyright   Copyright (c) 2012 CodeSpot JSC. (http://www.emthemes.com/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author      Giao L. Trinh (giao.trinh@emthemes.com)
 */

class EM_Themeframework_Adminhtml_PageController extends Mage_Adminhtml_Controller_Action
{
	protected function _initAction()
	{
		// load layout, set active menu and breadcrumbs
		$this->loadLayout()
			->_setActiveMenu('themeframework/page')
			->_addBreadcrumb(Mage::helper('themeframework')->__('EM Theme Framework'), Mage::helper('themeframework')->__('EM Theme Framework'))
			->_addBreadcrumb(Mage::helper('themeframework')->__('Page Manager'), Mage::helper('themeframework')->__('Page Manager'));
		return $this;
	}
	
	public function indexAction() {
		$this->_title($this->__('EM Theme Framework'))->_title($this->__('Page Manager'));
		$this->_initAction()
			 ->renderLayout();
	}
	
	public function newAction()
	{
		// the same form is used to create and edit
		$this->_forward('edit');
	}
	
	
    public function editAction() {
	
		// 1. Get ID and create model
		$id = $this->getRequest()->getParam('page_id');
		$model = Mage::getModel('themeframework/page');
		
		// 2. Initial checking
		if ($id) {
			$model->load($id);
			if (!$model->getId()) {
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('themeframework')->__('This page no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
		}
		
		$this->_title($model->getId() ? $this->__("Edit Page #") + $model->getId() : $this->__('New Page'));
		
		// 3. Set entered data if was error when we do save
		$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
		if (!empty($data))
			$model->setData($data);
		
		// 4. Register model to use later in blocks
        Mage::register('themeframework_page', $model);

		// 5. Build edit form
        $this->_initAction()
			 ->_addBreadcrumb($id ? Mage::helper('themeframework')->__('Edit Page') : Mage::helper('themeframework')->__('New Area'), $id ? Mage::helper('themeframework')->__('Edit Page') : Mage::helper('themeframework')->__('New Page'));

		$this->_setActiveMenu('themeframework/items');
		$this->_addContent($this->getLayout()->createBlock('themeframework/adminhtml_page_edit'));
        $this->renderLayout();
    }
    

    public function saveAction() {
		if($data = $this->getRequest()->getPost()) {
			$id = $this->getRequest()->getParam('page_id');
            $model = Mage::getModel('themeframework/page')->load($id);
            if (!$model->getId() && $id) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('cms')->__('This page no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }

			$model->setData($data);
			try {
				$model->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('cms')->__('The page has been saved.'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);
				
				// check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('page_id' => $model->getId()));
                    return;
                }
                // go to grid
                $this->_redirect('*/*/');
                return;
			}
			
			catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				Mage::getSingleton('adminhtml/session')->setFormData($data);
				$this->_redirect('*/*/edit', array('page_id' => $this->getRequest()->getParam('page_id')));
                return;
			}
		}
		
		$this->_redirect('*/*/');
    }
	
	public function massStatusAction()
    {
        $pageIds = $this->getRequest()->getParam('page');
        if(!is_array($pageIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($pageIds as $pageId) {
                    $page = Mage::getSingleton('themeframework/page')
                        ->load($pageId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d page(s) were successfully updated', count($pageIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
	public function massDeleteAction() {
        $pageIds = $this->getRequest()->getParam('page');
        if(!is_array($pageIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($pageIds as $pageId) {
                    $page = Mage::getModel('themeframework/page')->load($pageId);
                    $page->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d page(s) were successfully deleted', count($pageIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

	public function deleteAction() {
		// check if we know what should be deleted
        if ($id = $this->getRequest()->getParam('page_id')) {
            try {
                // init model and delete
                $model = Mage::getModel('themeframework/page');
                $model->load($id);
                $model->delete();
                // display success message
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('themeframework')->__('The page has been deleted.'));
                // go to grid
                $this->_redirect('*/*/');
                return;

            } catch (Exception $e) {
                // display error message
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                // go back to edit form
                $this->_redirect('*/*/edit', array('page_id' => $id));
                return;
            }
        }
        // display error message
        Mage::getSingleton('admihtml/session')->addError(Mage::helper('themeframework')->__('Unable to find a page to delete.'));
        // go to grid
        $this->_redirect('*/*/');
	}
	
}