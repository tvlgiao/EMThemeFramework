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

class EM_Themeframework_Block_Adminhtml_Area_Edit_Tab_Area extends Mage_Adminhtml_Block_Widget_Form
{

	/**
     * Class constructor
     *
     */
	protected function _construct()
	{
		parent::_construct();
		$this->setTemplate('em_themeframework/edit/form.phtml');
	}

	protected function _prepareForm()
	{
		$model = Mage::registry('themeframework_area');
		
		$form = new Varien_Data_Form();
		
		$scopeFS = $form->addFieldset('scope_fieldset', array('legend' => Mage::helper('themeframework')->__("Scope")));
		
		if ($model->getAreaId()) {
			$scopeFS->addField('area_id', 'hidden', array(
				'name' => 'area_id',
			));
		}
	
		// add Package Name element
		// $scopeFS->addField('package_name', 'select', array(
		//             'name'     => 'package_theme',
		//             'label'    => Mage::helper('widget')->__('Design Package/Theme'),
		//             'title'    => Mage::helper('widget')->__('Design Package/Theme'),
		//             'required' => true,
		//             'values'   => $this->getPackegeThemeOptionsArray()
		//         ));

		// add Store ID element
        if (!Mage::app()->isSingleStoreMode()) {
            $field =$scopeFS->addField('store_id', 'multiselect', array(
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
            $scopeFS->addField('store_id', 'hidden', array(
                'name'      => 'stores[]',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
        }

		// add Package Name element
		$el = new Varien_Data_Form_Element_Select(array(
            'name'     => 'package_theme',
            'label'    => Mage::helper('widget')->__('Design Package/Theme'),
            'title'    => Mage::helper('widget')->__('Design Package/Theme'),
            'required' => true,
            'values'   => $this->getPackegeThemeOptionsArray(),
			'onchange' => "EM_Framework.canvas.onThemeChange(this.value)"
        ));
		$el->setId('package_theme');
		$form->addElement($el);
	
		
		// Layout field
		$scopeFS->addField('layout', 'select', array(
            'label'     => Mage::helper('themeframework')->__('Layout'),
            'title'     => Mage::helper('themeframework')->__('Layout'),
            'name'      => 'layout',
            'required'  => true,
            'options'   => array(
                '1column' => Mage::helper('themeframework')->__('1 column'),
                '2columns-left' => Mage::helper('themeframework')->__('2 columns with left bar'),
				'2columns-right' => Mage::helper('themeframework')->__('2 columns with right bar'),
				'3columns' => Mage::helper('themeframework')->__('3 columns')
            ),
        ));
		
		// is_active field
		$scopeFS->addField('is_active', 'select', array(
            'label'     => Mage::helper('themeframework')->__('Status'),
            'title'     => Mage::helper('themeframework')->__('Status'),
            'name'      => 'is_active',
            'required'  => true,
            'options'   => array(
                '1' => Mage::helper('themeframework')->__('Enabled'),
                '0' => Mage::helper('themeframework')->__('Disabled'),
            ),
        ));
        if (!$model->getId()) {
            $model->setData('is_active', '1');
        }

		// hidden field: json_content
		$scopeFS->addField('json_content', 'hidden', array(
			'name' => 'json_content'
		));

		
		$form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

		
		ob_start();
		?>
		<script type="text/javascript">
			EM_Framework.THEME_BLOCKS = <?php echo json_encode($this->getSupportedContainerBlocks()); ?>;
			EM_Framework.CANVAS_CONTENT = <?php echo json_encode($model->getContentDecode()); ?>;
		</script>
		<?php
		$extraHtml = ob_get_contents();
		ob_end_clean();
		
		$this->assign('extraHtml', $extraHtml);


		return parent::_prepareForm();
	}

	public function getStoreCode()
    {
        return $this->getRequest()->getParam('store', '');
    }
	
	
	public function getPackegeThemeOptionsArray()
    {
        return Mage::getModel('core/design_source_design')
            ->setIsFullLabel(true)->getAllOptions(false);
    }

	public function getSupportedContainerBlocks() {
		
		$blocks = array();
		foreach ($this->getPackegeThemeOptionsArray() as $packageOption) {
			foreach ($packageOption['value'] as $themeOption) {
				$package_theme = $themeOption['value'];
				
				$blocks[$package_theme] = array(
					// core blocks that always appear in any themes
					'header' => "Header",
					'global_messages' => "Global Messages",
					'footer' => "Footer"
				);
				
				$ignoreBlocks = array(
					'after_body_start', // outer wrapper/container_24
					'top.container', // inside header block
					'top.menu', // inside header block
					'bottom.container', // inside footer block
					'cart_sidebar.extra_actions'
				);
				
				list($package, $theme) = explode('/', $package_theme);
				
				 $blocksChooser = Mage::app()->getFrontController()->getAction()->getLayout()
			            ->createBlock('widget/adminhtml_widget_instance_edit_chooser_block')
			            	->setArea('frontend')
			            	->setPackage($package)
			            	->setTheme($theme)
							->setLayoutHandle('default');

				foreach ($blocksChooser->getBlocks() as $k => $v) 
					if ($k != '' && !in_array($k, $ignoreBlocks) )
						$blocks[$package_theme][$k] = $v;
			}
		}
		
		return $blocks;

	}
}