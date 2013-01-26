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

class EM_Themeframework_Adminhtml_AreaController extends Mage_Adminhtml_Controller_Action
{
	protected function _initAction()
	{
		// load layout, set active menu and breadcrumbs
		$this->loadLayout()
			->_setActiveMenu('themeframework/area')
			->_addBreadcrumb(Mage::helper('themeframework')->__('EM Theme Framework'), Mage::helper('themeframework')->__('EM Theme Framework'))
			->_addBreadcrumb(Mage::helper('themeframework')->__('Layout Manager'), Mage::helper('themeframework')->__('Layout Manager'));
		return $this;
	}
	
	public function indexAction() {
		//$this->_redirect('*/*/edit/area_id/2/');
		//$this->_forward('edit');
		
		$this->_title($this->__('EM Theme Framework'))->_title($this->__('Layout Manager'));

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
		$id = $this->getRequest()->getParam('area_id');
		$model = Mage::getModel('themeframework/area');
		
		// 2. Initial checking
		if ($id) {
			$model->load($id);
			if (!$model->getId()) {
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('themeframework')->__('This area no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
		}
		
		$this->_title($model->getId() ? $this->__("Edit Area #") + $model->getId() : $this->__('New Area'));
		
		// 3. Set entered data if was error when we do save
		$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
		if (!empty($data))
			$model->setData($data);
		
		// 4. Register model to use later in blocks
        Mage::register('themeframework_area', $model);

		// 5. Build edit form
        $this->_initAction()
			 ->_addBreadcrumb($id ? Mage::helper('themeframework')->__('Edit Area') : Mage::helper('themeframework')->__('New Area'), $id ? Mage::helper('themeframework')->__('Edit Area') : Mage::helper('themeframework')->__('New Area'));

		$this->_setActiveMenu('themeframework/items');
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
		$this->getLayout()->getBlock('head')->setTitle(Mage::helper('themeframework')->__("Theme Framework Editor"));
		$this->getLayout()->getBlock('head')->addCss('em_themeframework/grid.css');
		$this->getLayout()->getBlock('head')->addCss('em_themeframework/ui-lightness/jquery-ui-1.8.20.custom.css');
		$this->getLayout()->getBlock('head')->addCss('em_themeframework/area.css');
		$this->getLayout()->getBlock('head')->addItem('skin_js', 'em_themeframework/json2.js');
		$this->getLayout()->getBlock('head')->addItem('skin_js', 'em_themeframework/jquery-1.7.2.min.js');
		$this->getLayout()->getBlock('head')->addItem('skin_js', 'em_themeframework/jquery-ui-1.8.20.custom.min.js');
		$this->getLayout()->getBlock('head')->addItem('skin_js', 'em_themeframework/area.js');
		
        $editBlock = $this->getLayout()->createBlock('themeframework/adminhtml_area_edit');
        $editBlock->setChild('form',
            $this->getLayout()->createBlock('themeframework/adminhtml_area_edit_tab_area')
        );

        $this->_addContent($editBlock);

        $this->renderLayout();
    }
    

    public function saveAction() {
		if($data = $this->getRequest()->getPost()) {
			$data['content_decode'] = json_decode($data['json_content'], true);
			
			$id = $this->getRequest()->getParam('area_id');
            $model = Mage::getModel('themeframework/area')->load($id);
            if (!$model->getId() && $id) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('cms')->__('This block no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }

			$model->setData($data);
			
			if ($this->getRequest()->getParam('saveasnew')) {
				$model->setId(null);
				$model->setStoreId(null);
			}

			try {
				$model->save();				
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('cms')->__('The block has been saved.'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);
				
				// check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('area_id' => $model->getId()));
                    return;
                }
                // go to grid
                $this->_redirect('*/*/');
                return;
			}
			
			catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				Mage::getSingleton('adminhtml/session')->setFormData($data);
				$this->_redirect('*/*/edit', array('area_id' => $this->getRequest()->getParam('area_id')));
                return;
			}
		}
		
		$this->_redirect('*/*/');
    }

	function deleteAction() {
		// check if we know what should be deleted
        if ($id = $this->getRequest()->getParam('area_id')) {
            try {
                // init model and delete
                $model = Mage::getModel('themeframework/area');
                $model->load($id);
                $model->delete();
                // display success message
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('themeframework')->__('The layout has been deleted.'));
                // go to grid
                $this->_redirect('*/*/');
                return;

            } catch (Exception $e) {
                // display error message
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                // go back to edit form
                $this->_redirect('*/*/edit', array('area_id' => $id));
                return;
            }
        }
        // display error message
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('themeframework')->__('Unable to find a layout to delete.'));
        // go to grid
        $this->_redirect('*/*/');
	}
	
	function previewBlockAction() {
		$this->getResponse()->setRedirect(Mage::getModel('core/url')->getDirectUrl('themeframework/area/previewBlock/key/'.Mage::getSingleton('adminhtml/url')->getSecretKey('cms_block', 'edit')));
	}
}