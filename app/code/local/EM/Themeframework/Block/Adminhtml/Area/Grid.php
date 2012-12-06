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

class EM_Themeframework_Block_Adminhtml_Area_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('themeframeworkAreaGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('themeframework/area')->getCollection();
        /* @var $collection EM_Themeframework_Model_Resource_Area_Collection */
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $baseUrl = $this->getUrl();

        $this->addColumn('area_id', array(
            'header'    => Mage::helper('themeframework')->__('ID'),
            'align'     => 'left',
            'index'     => 'area_id',
			'width' =>50
        ));

		/*$this->addColumn('package_theme', array(
			'header'    => Mage::helper('themeframework')->__('Package/Theme'),
			'align'     => 'left',
			'index'     => 'package_theme',
		));*/
		
		$this->addColumn('layout', array(
			'header'    => Mage::helper('themeframework')->__('Layout'),
			'align'     => 'left',
			'index'     => 'layout',
		));


        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'        => Mage::helper('themeframework')->__('Store View'),
                'index'         => 'store_id',
                'type'          => 'store',
                'store_all'     => true,
                'store_view'    => true,
                'sortable'      => false,
                'filter_condition_callback'
                                => array($this, '_filterStoreCondition'),
            ));
        }

        $this->addColumn('is_active', array(
            'header'    => Mage::helper('themeframework')->__('Status'),
            'index'     => 'is_active',
            'type'      => 'options',
            'options'   => array(
                0 => Mage::helper('themeframework')->__('Disabled'),
                1 => Mage::helper('themeframework')->__('Enabled')
            ),
        ));

        $this->addColumn('creation_time', array(
            'header'    => Mage::helper('themeframework')->__('Date Created'),
            'index'     => 'creation_time',
            'type'      => 'datetime',
        ));

        $this->addColumn('update_time', array(
            'header'    => Mage::helper('themeframework')->__('Last Modified'),
            'index'     => 'update_time',
            'type'      => 'datetime',
        ));

        $this->addColumn('action', array(
            'header'    => Mage::helper('themeframework')->__('Preview'),
            'width'     => 100,
            'sortable'  => false,
            'filter'    => false,
            'renderer'  => 'themeframework/adminhtml_area_grid_renderer_action',
        ));

        return parent::_prepareColumns();
    }

    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }

        $this->getCollection()->addStoreFilter($value);
    }

    /**
     * Row click url
     *
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('area_id' => $row->getId()));
    }

}
