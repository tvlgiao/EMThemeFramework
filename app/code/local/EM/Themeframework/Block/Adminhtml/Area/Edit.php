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

class EM_Themeframework_Block_Adminhtml_Area_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
	
	    $this->_objectId = 'area_id';
        $this->_controller = 'themeframework_adminhtml_area';
    
        parent::__construct();

        $this->setTemplate('em_themeframework/form/container.phtml');
        //$this->_objectId = Mage::registry('themeframework_data')->getId();
        $this->_blockGroup = 'themeframework';
        $this->_controller = 'adminhtml_area';

				
		// $this->setChild('duplicate_button',
		//         	$this->getLayout()->createBlock('adminhtml/widget_button')
		//             	->setData(array(
		//                 	'label'     => Mage::helper('themeframework')->__('Duplicate'),
		// 	                'onclick'   => 'setLocation(\'' . $this->getDuplicateUrl() . '\')',
		// 	                'class'  => 'add'
		//             	))
		//         );

		$this->_addButton('saveasnew', array(
	        'label'     => Mage::helper('themeframework')->__('Save as New'),
	        'onclick'   => "saveAsNew()",
	        'class'     => 'add',
	    ), -100);


        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save and Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);




		

        $this->_formScripts[] = "
            
			editForm.EM_oldSubmit = editForm.submit;
			editForm.submit = function(action) {
				$(editForm.formId).elements['json_content'].value = EM_Framework.canvas.serializeData();
				if (typeof action != 'undefined')
					this.EM_oldSubmit(action);
				else
					this.EM_oldSubmit();
			}


            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }

			function saveAsNew(){
                editForm.submit($('edit_form').action+'saveasnew/1/back/edit');
            }


        ";
	}

	public function getHeaderText()
	{
		if (Mage::registry('themeframework_area')->getId()) {
			return Mage::helper('themeframework')->__("Edit Layout #%s", $this->htmlEscape(Mage::registry('themeframework_area')->getId()));
		}
		else {
			return Mage::helper('themeframework')->__('New Layout');
		}
	}

	public function getDuplicateButtonHtml()
    {
        return $this->getChildHtml('duplicate_button');
    }

     /**
     * Get URL for cancel button
     *
     * @return string
     */
    public function getCancelUrl()
    {
        return $this->getUrl('*/adminhtml_area',array('store' => $this->getRequest()->getParam('store',0)));
    }

	public function getDuplicateUrl()
    {
        return $this->getUrl('*/*/duplicate', array('_current'=>true));
    }

   
}