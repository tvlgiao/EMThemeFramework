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

class EM_Themeframework_Block_Adminhtml_Page_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('themeframeworkPageGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('themeframework/page')->getCollection();
        /* @var $collection EM_Themeframework_Model_Resource_Area_Collection */
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $baseUrl = $this->getUrl();

        $this->addColumn('page_id', array(
            'header'    => Mage::helper('themeframework')->__('ID'),
            'align'     => 'left',
            'index'     => 'page_id',
			'width' =>50
        ));
		
		$this->addColumn('title', array(
            'header'    => Mage::helper('themeframework')->__('title'),
            'align'     => 'left',
            'index'     => 'title'
        ));

		$this->addColumn('handle', array(
            'header'    => Mage::helper('themeframework')->__('Handle'),
            'align'     => 'left',
            'index'     => 'handle'
        ));
		
		$this->addColumn('layout', array(
			'header'    => Mage::helper('themeframework')->__('Layout'),
			'align'     => 'left',
			'index'     => 'layout',
			'type'      => 'options',
			'options'   => array(
				''					=>	Mage::helper('themeframework')->__('No layout updates'),
                'one_column' 		=> 	Mage::helper('themeframework')->__('1 column'),
                'two_columns_left' 	=> 	Mage::helper('themeframework')->__('2 columns with left bar'),
				'two_columns_right' => 	Mage::helper('themeframework')->__('2 columns with right bar'),
				'three_columns' 	=> 	Mage::helper('themeframework')->__('3 columns')
            )
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

        $this->addColumn('status', array(
            'header'    => Mage::helper('themeframework')->__('Status'),
            'index'     => 'status',
            'type'      => 'options',
            'options'   => array(
                0 => Mage::helper('themeframework')->__('Disabled'),
                1 => Mage::helper('themeframework')->__('Enabled')
            ),
        ));

        return parent::_prepareColumns();
    }
	
	protected function _prepareMassaction()
    {
        $this->setMassactionIdField('page_id');
        $this->getMassactionBlock()->setFormFieldName('page');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('themeframework')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('themeframework')->__('Are you sure?')
        ));

        //$statuses = Mage::getSingleton('blog/status')->getOptionArray();
        //$statuses = array(0=>'disable',1=>'Enable');
        //array_unshift($statuses, array('label'=>'', 'value'=>''));
        $statuses = array(
                        array('label'=>'Disabled', 'value'=>'0'),
                        array('label'=>'Enable', 'value'=>'1'),
        );  
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('themeframework')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('themeframework')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
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
        return $this->getUrl('*/*/edit', array('page_id' => $row->getId()));
    }

}
