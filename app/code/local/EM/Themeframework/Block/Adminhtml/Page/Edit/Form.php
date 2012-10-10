<?php
/* Giao: This file does not seem to be used */


class EM_Themeframework_Block_Adminhtml_Page_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		$model = Mage::registry('themeframework_page');
		
		$form = new Varien_Data_Form(array(
                                      'id' => 'edit_form',
                                      'action' => $this->getUrl('*/*/save', array('page_id' => $this->getRequest()->getParam('page_id'))),
                                      'method' => 'post',
        							  'enctype' => 'multipart/form-data'
                                   ));
		/* Page Information Fieldset */
		$infoFS = $form->addFieldset('info_fieldset', array('legend' => Mage::helper('themeframework')->__("Page Information")));
		
		if ($model->getPageId()) {
			$infoFS->addField('page_id', 'hidden', array(
				'name' => 'page_id',
			));
		}
	
		// Title field
		$infoFS->addField('title', 'text', array(
			'label'     => Mage::helper('themeframework')->__('Title'),
			'class'     => 'required-entry',
			'required'  => true,
			'name'      => 'title',
		));

		// Handle field
		$infoFS->addField('handle', 'select', array(
            'label'     => Mage::helper('themeframework')->__('Handle'),
            'title'     => Mage::helper('themeframework')->__('Handle'),
            'name'      => 'handle',
            'required'  => true,
            'options'   => Mage::helper('themeframework')->getHandlesAvailable()
        ));
		
		// Custom Handle field
		$infoFS->addField('custom_handle', 'text', array(
			'label'     => Mage::helper('themeframework')->__('Custom Handle'),
			'title'     => Mage::helper('themeframework')->__('Custom Handle'),
			'name'      => 'custom_handle',
		));
		
		// add Store ID element
        if (!Mage::app()->isSingleStoreMode()) {
            $field =$infoFS->addField('store_id', 'multiselect', array(
                'name'      => 'stores[]',
                'label'     => Mage::helper('cms')->__('Store View'),
                'title'     => Mage::helper('cms')->__('Store View'),
                'required'  => true,
                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            ));
            $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
            $field->setRenderer($renderer);
        }
        else {
            $infoFS->addField('store_id', 'hidden', array(
                'name'      => 'stores[]',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
        }

		// is_active field
		$infoFS->addField('status', 'select', array(
            'label'     => Mage::helper('themeframework')->__('Status'),
            'title'     => Mage::helper('themeframework')->__('Status'),
            'name'      => 'status',
            'required'  => true,
            'options'   => array(
                '1' => Mage::helper('themeframework')->__('Enabled'),
                '0' => Mage::helper('themeframework')->__('Disabled'),
            ),
        ));
        if (!$model->getId()) {
            $model->setData('status', '1');
        }
		
		// Custom Handle field
		$infoFS->addField('sort', 'text', array(
			'label'     => Mage::helper('themeframework')->__('Priority'),
			'title'     => Mage::helper('themeframework')->__('Priority'),
			'name'      => 'sort',
		));

		/* Design Fieldset */
		$designFS = $form->addFieldset('design_fieldset', array('legend' => Mage::helper('themeframework')->__("Design")));
		// Layout field
		$designFS->addField('layout', 'select', array(
            'label'     => Mage::helper('themeframework')->__('Layout'),
            'title'     => Mage::helper('themeframework')->__('Layout'),
            'name'      => 'layout',
            'options'   => array(
				''					=>	Mage::helper('themeframework')->__('No layout updates'),
                'one_column' 		=> 	Mage::helper('themeframework')->__('1 column'),
                'two_columns_left' 	=> 	Mage::helper('themeframework')->__('2 columns with left bar'),
				'two_columns_right' => 	Mage::helper('themeframework')->__('2 columns with right bar'),
				'three_columns' 	=> 	Mage::helper('themeframework')->__('3 columns')
            )
        ));
		
		// Layout Update Xml field
		$designFS->addField('layout_update_xml', 'textarea', array(
			'label'     => Mage::helper('themeframework')->__('Layout Update Xml'),
			'title'     => Mage::helper('themeframework')->__('Layout Update Xml'),
			'name'      => 'layout_update_xml',
			'style'     => 'width:50em;height:25em;'
		));
		
		$form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);
		return parent::_prepareForm();
	}
}