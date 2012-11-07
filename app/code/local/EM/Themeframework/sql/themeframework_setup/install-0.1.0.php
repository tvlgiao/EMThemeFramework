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

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

/**
 * Create table 'themeframework/area'
 */
if(!$installer->tableExists($installer->getTable('themeframework/area'))){
$table = $installer->getConnection()
    ->newTable($installer->getTable('themeframework/area'))
    ->addColumn('area_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Area ID')
    ->addColumn('package_theme', Varien_Db_Ddl_Table::TYPE_VARCHAR, 100, array(
        ), 'Package Theme')
	->addColumn('layout', Varien_Db_Ddl_Table::TYPE_VARCHAR, 100, array(
        ), 'Layout')
	->addColumn('content', Varien_Db_Ddl_Table::TYPE_TEXT, '2M', array(
        ), 'Area Content')
    ->addColumn('creation_time', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        ), 'Area Creation Time')
    ->addColumn('update_time', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        ), 'Area Modification Time')
    ->addColumn('is_active', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable'  => false,
        'default'   => '1',
        ), 'Is Area Active')
    ->setComment('EM ThemeFramework Area Table');
$installer->getConnection()->createTable($table);
}

/**
 * Create table 'cms/block_store'
 */
if(!$installer->tableExists($installer->getTable('themeframework/area_store'))){
$table = $installer->getConnection()
    ->newTable($installer->getTable('themeframework/area_store'))
    ->addColumn('area_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable'  => false,
        'primary'   => true,
        ), 'Area ID')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Store ID')
    ->addIndex($installer->getIdxName('themeframework/area_store', array('store_id')),
        array('store_id'))
    ->addForeignKey($installer->getFkName('themeframework/area_store', 'area_id', 'themeframework/area', 'area_id'),
        'area_id', $installer->getTable('themeframework/area'), 'area_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('themeframework/area_store', 'store_id', 'core/store', 'store_id'),
        'store_id', $installer->getTable('core/store'), 'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('EM ThemeFramework Area To Store Linkage Table');
$installer->getConnection()->createTable($table);
}

/**
 * Create table 'themeframework/page'
 */
if(!$installer->tableExists($installer->getTable('themeframework/page'))){
	$table = $installer->getConnection()
		->newTable($installer->getTable('themeframework/page'))
		->addColumn('page_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
			'identity'  => true,
			'nullable'  => false,
			'primary'   => true,
			), 'Area ID')
		->addColumn('title', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
			), 'Title')
		->addColumn('handle', Varien_Db_Ddl_Table::TYPE_VARCHAR, 100, array(
			), 'Handle')	
		->addColumn('custom_handle', Varien_Db_Ddl_Table::TYPE_VARCHAR, 100, array(
			), 'Custom Handle')	
		->addColumn('layout', Varien_Db_Ddl_Table::TYPE_VARCHAR, 100, array(
			), 'Layout')
		->addColumn('layout_update_xml', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
			), 'Layout Update Xml')
		->addColumn('status', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
			'nullable'  => false,
			'default'   => '1',
			), 'Status')
		->addColumn('sort', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
			'nullable'  => false,
			'default'   => '0',
			), 'Sort')	
		->setComment('EM ThemeFramework Page Table');
	$installer->getConnection()->createTable($table);
}

/**
 * Create table 'themeframework/page_store'
 */
if(!$installer->tableExists($installer->getTable('themeframework/page_store'))){ 
	$table = $installer->getConnection()
		->newTable($installer->getTable('themeframework/page_store'))
		->addColumn('page_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
			'nullable'  => false,
			'primary'   => true,
			), 'Area ID')
		->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
			'unsigned'  => true,
			'nullable'  => false,
			'primary'   => true,
			), 'Store ID')
		->addIndex($installer->getIdxName('themeframework/page_store', array('store_id')),
			array('store_id'))
		->addForeignKey($installer->getFkName('themeframework/page_store', 'page_id', 'themeframework/area', 'area_id'),
			'page_id', $installer->getTable('themeframework/page'), 'page_id',
			Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
		->addForeignKey($installer->getFkName('themeframework/page_store', 'store_id', 'core/store', 'store_id'),
			'store_id', $installer->getTable('core/store'), 'store_id',
			Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
		->setComment('EM ThemeFramework Page To Store Linkage Table');
	$installer->getConnection()->createTable($table);
}

/**
* Add Data 
*/
$model = Mage::getModel('themeframework/area');
	
$data = array(
	'package_theme'	=>	'default/default',
	'layout'	=>	'1column',	
	'content_decode'	=> unserialize(<<<EOB
a:9:{i:0;a:6:{s:10:"custom_css";s:16:"feedback-wrapper";s:10:"inner_html";s:0:"";s:10:"outer_html";s:0:"";s:13:"display_empty";b:0;s:4:"type";s:14:"container_free";s:5:"items";a:1:{i:0;s:8:"feedback";}}i:1;a:6:{s:10:"custom_css";s:0:"";s:10:"inner_html";s:0:"";s:10:"outer_html";s:0:"";s:13:"display_empty";b:0;s:4:"type";s:14:"container_free";s:5:"items";a:1:{i:0;s:6:"header";}}i:2;a:6:{s:10:"custom_css";s:21:"banner-header-wrapper";s:10:"inner_html";s:0:"";s:10:"outer_html";s:0:"";s:13:"display_empty";b:0;s:4:"type";s:14:"container_free";s:5:"items";a:1:{i:0;s:6:"area19";}}i:3;a:6:{s:10:"custom_css";s:9:"main-body";s:10:"inner_html";s:0:"";s:10:"outer_html";s:0:"";s:13:"display_empty";b:0;s:4:"type";s:12:"container_24";s:5:"items";a:8:{i:0;s:5:"clear";i:1;a:11:{s:6:"column";s:2:"13";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:20:"main-slideshow omega";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:14:"main_slideshow";}}i:2;a:11:{s:6:"column";s:2:"11";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:18:"home-right-product";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:2:{i:0;s:6:"area01";i:1;s:6:"area02";}}i:3;s:5:"clear";i:4;a:11:{s:6:"column";s:2:"24";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:0:"";s:10:"inner_html";s:39:"<div class="col-main">{{content}}</div>";s:13:"display_empty";b:0;s:5:"items";a:10:{i:0;s:11:"breadcrumbs";i:1;s:15:"global_messages";i:2;s:6:"area03";i:3;s:6:"area04";i:4;s:6:"area05";i:5;s:6:"area06";i:6;s:6:"area07";i:7;s:6:"area08";i:8;s:6:"area09";i:9;s:7:"content";}}i:5;s:5:"clear";i:6;a:11:{s:6:"column";s:2:"24";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:0:"";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:6:"area10";}}i:7;s:5:"clear";}}i:4;a:6:{s:10:"custom_css";s:0:"";s:10:"inner_html";s:0:"";s:10:"outer_html";s:38:"<div class="footer"> {{content}}</div>";s:13:"display_empty";b:0;s:4:"type";s:12:"container_24";s:5:"items";a:2:{i:0;a:11:{s:6:"column";s:2:"24";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:0:"";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:6:"area11";}}i:1;s:5:"clear";}}i:5;a:6:{s:10:"custom_css";s:8:"footer-1";s:10:"inner_html";s:0:"";s:10:"outer_html";s:42:"<div class="bkg-footer"> {{content}}</div>";s:13:"display_empty";b:0;s:4:"type";s:12:"container_24";s:5:"items";a:3:{i:0;a:11:{s:6:"column";s:2:"18";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:0:"";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:6:"area12";}}i:1;a:11:{s:6:"column";s:1:"6";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:0:"";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:6:"area13";}}i:2;s:5:"clear";}}i:6;a:6:{s:10:"custom_css";s:8:"footer-2";s:10:"inner_html";s:0:"";s:10:"outer_html";s:42:"<div class="bkg-footer"> {{content}}</div>";s:13:"display_empty";b:0;s:4:"type";s:12:"container_24";s:5:"items";a:3:{i:0;a:11:{s:6:"column";s:1:"6";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:0:"";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:6:"area14";}}i:1;a:11:{s:6:"column";s:2:"18";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:0:"";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:6:"area15";}}i:2;s:5:"clear";}}i:7;a:6:{s:10:"custom_css";s:0:"";s:10:"inner_html";s:0:"";s:10:"outer_html";s:42:"<div class="bkg-footer"> {{content}}</div>";s:13:"display_empty";b:0;s:4:"type";s:12:"container_24";s:5:"items";a:2:{i:0;a:11:{s:6:"column";s:2:"24";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:8:"footer-3";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:6:"area16";}}i:1;s:5:"clear";}}i:8;a:6:{s:10:"custom_css";s:8:"footer-4";s:10:"inner_html";s:0:"";s:10:"outer_html";s:42:"<div class="bkg-footer"> {{content}}</div>";s:13:"display_empty";b:0;s:4:"type";s:12:"container_24";s:5:"items";a:5:{i:0;a:11:{s:6:"column";s:2:"12";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:0:"";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:6:"area17";}}i:1;a:11:{s:6:"column";s:2:"12";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:0:"";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:6:"area18";}}i:2;s:5:"clear";i:3;a:11:{s:6:"column";s:2:"24";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:32:"container_body_end gird-ajaxcart";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:15:"before_body_end";}}i:4;s:5:"clear";}}}
EOB
),
	'is_active' => 1
);
$model->setData($data)->setStores(array(0))->save();

$data = array(
	'package_theme'	=>	'default/default',
	'layout'	=>	'2columns-right',	
	'content_decode'	=> unserialize(<<<EOB
a:9:{i:0;a:6:{s:10:"custom_css";s:16:"feedback-wrapper";s:10:"inner_html";s:0:"";s:10:"outer_html";s:0:"";s:13:"display_empty";b:0;s:4:"type";s:14:"container_free";s:5:"items";a:1:{i:0;s:8:"feedback";}}i:1;a:6:{s:10:"custom_css";s:14:"header-wrapper";s:10:"inner_html";s:0:"";s:10:"outer_html";s:0:"";s:13:"display_empty";b:0;s:4:"type";s:14:"container_free";s:5:"items";a:1:{i:0;s:6:"header";}}i:2;a:6:{s:10:"custom_css";s:21:"banner-header-wrapper";s:10:"inner_html";s:0:"";s:10:"outer_html";s:0:"";s:13:"display_empty";b:0;s:4:"type";s:14:"container_free";s:5:"items";a:1:{i:0;s:6:"area19";}}i:3;a:6:{s:10:"custom_css";s:9:"main-body";s:10:"inner_html";s:0:"";s:10:"outer_html";s:0:"";s:13:"display_empty";b:0;s:4:"type";s:12:"container_24";s:5:"items";a:5:{i:0;a:11:{s:6:"column";s:2:"18";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:12:"main-content";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:13:{i:0;s:14:"main_slideshow";i:1;s:6:"area01";i:2;s:6:"area02";i:3;s:11:"breadcrumbs";i:4;s:15:"global_messages";i:5;s:6:"area03";i:6;s:6:"area04";i:7;s:6:"area05";i:8;s:6:"area06";i:9;s:6:"area07";i:10;s:6:"area08";i:11;s:6:"area09";i:12;s:7:"content";}}i:1;a:11:{s:6:"column";s:1:"6";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:9:"col-right";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:5:"right";}}i:2;s:5:"clear";i:3;a:11:{s:6:"column";s:2:"24";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:0:"";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:6:"area10";}}i:4;s:5:"clear";}}i:4;a:6:{s:10:"custom_css";s:0:"";s:10:"inner_html";s:0:"";s:10:"outer_html";s:38:"<div class="footer"> {{content}}</div>";s:13:"display_empty";b:0;s:4:"type";s:12:"container_24";s:5:"items";a:2:{i:0;a:11:{s:6:"column";s:2:"24";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:0:"";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:6:"area11";}}i:1;s:5:"clear";}}i:5;a:6:{s:10:"custom_css";s:8:"footer-1";s:10:"inner_html";s:0:"";s:10:"outer_html";s:42:"<div class="bkg-footer"> {{content}}</div>";s:13:"display_empty";b:0;s:4:"type";s:12:"container_24";s:5:"items";a:3:{i:0;a:11:{s:6:"column";s:2:"18";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:0:"";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:6:"area12";}}i:1;a:11:{s:6:"column";s:1:"6";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:0:"";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:6:"area13";}}i:2;s:5:"clear";}}i:6;a:6:{s:10:"custom_css";s:8:"footer-2";s:10:"inner_html";s:0:"";s:10:"outer_html";s:42:"<div class="bkg-footer"> {{content}}</div>";s:13:"display_empty";b:0;s:4:"type";s:12:"container_24";s:5:"items";a:3:{i:0;a:11:{s:6:"column";s:1:"6";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:0:"";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:6:"area14";}}i:1;a:11:{s:6:"column";s:2:"18";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:0:"";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:6:"area15";}}i:2;s:5:"clear";}}i:7;a:6:{s:10:"custom_css";s:0:"";s:10:"inner_html";s:0:"";s:10:"outer_html";s:0:"";s:13:"display_empty";b:0;s:4:"type";s:12:"container_24";s:5:"items";a:2:{i:0;a:11:{s:6:"column";s:2:"24";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:8:"footer-3";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:6:"area16";}}i:1;s:5:"clear";}}i:8;a:6:{s:10:"custom_css";s:8:"footer-4";s:10:"inner_html";s:0:"";s:10:"outer_html";s:42:"<div class="bkg-footer"> {{content}}</div>";s:13:"display_empty";b:0;s:4:"type";s:12:"container_24";s:5:"items";a:5:{i:0;a:11:{s:6:"column";s:2:"12";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:0:"";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:6:"area17";}}i:1;a:11:{s:6:"column";s:2:"12";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:0:"";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:6:"area18";}}i:2;s:5:"clear";i:3;a:11:{s:6:"column";s:2:"24";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:13:"gird-ajaxcart";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:15:"before_body_end";}}i:4;s:5:"clear";}}}
EOB
),
	'is_active' => 1
);
$model->setData($data)->setStores(array(0))->save();

$data = array(
	'package_theme'	=>	'default/default',
	'layout'	=>	'2columns-left',	
	'content_decode'	=> unserialize(<<<EOB
a:9:{i:0;a:6:{s:10:"custom_css";s:16:"feedback-wrapper";s:10:"inner_html";s:0:"";s:10:"outer_html";s:0:"";s:13:"display_empty";b:0;s:4:"type";s:14:"container_free";s:5:"items";a:1:{i:0;s:8:"feedback";}}i:1;a:6:{s:10:"custom_css";s:14:"header-wrapper";s:10:"inner_html";s:0:"";s:10:"outer_html";s:0:"";s:13:"display_empty";b:0;s:4:"type";s:14:"container_free";s:5:"items";a:1:{i:0;s:6:"header";}}i:2;a:6:{s:10:"custom_css";s:21:"banner-header-wrapper";s:10:"inner_html";s:0:"";s:10:"outer_html";s:0:"";s:13:"display_empty";b:0;s:4:"type";s:14:"container_free";s:5:"items";a:1:{i:0;s:6:"area19";}}i:3;a:6:{s:10:"custom_css";s:9:"main-body";s:10:"inner_html";s:0:"";s:10:"outer_html";s:0:"";s:13:"display_empty";b:0;s:4:"type";s:12:"container_24";s:5:"items";a:5:{i:0;a:11:{s:6:"column";s:1:"6";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:8:"col-left";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:4:"left";}}i:1;a:11:{s:6:"column";s:2:"18";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:12:"main-content";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:13:{i:0;s:14:"main_slideshow";i:1;s:6:"area02";i:2;s:6:"area01";i:3;s:11:"breadcrumbs";i:4;s:15:"global_messages";i:5;s:6:"area03";i:6;s:6:"area04";i:7;s:6:"area05";i:8;s:6:"area06";i:9;s:6:"area07";i:10;s:6:"area08";i:11;s:6:"area09";i:12;s:7:"content";}}i:2;s:5:"clear";i:3;a:11:{s:6:"column";s:2:"24";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:0:"";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:6:"area10";}}i:4;s:5:"clear";}}i:4;a:6:{s:10:"custom_css";s:0:"";s:10:"inner_html";s:0:"";s:10:"outer_html";s:38:"<div class="footer"> {{content}}</div>";s:13:"display_empty";b:0;s:4:"type";s:12:"container_24";s:5:"items";a:2:{i:0;a:11:{s:6:"column";s:2:"24";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:0:"";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:6:"area11";}}i:1;s:5:"clear";}}i:5;a:6:{s:10:"custom_css";s:8:"footer-1";s:10:"inner_html";s:0:"";s:10:"outer_html";s:42:"<div class="bkg-footer"> {{content}}</div>";s:13:"display_empty";b:0;s:4:"type";s:12:"container_24";s:5:"items";a:3:{i:0;a:11:{s:6:"column";s:2:"18";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:0:"";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:6:"area12";}}i:1;a:11:{s:6:"column";s:1:"6";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:0:"";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:6:"area13";}}i:2;s:5:"clear";}}i:6;a:6:{s:10:"custom_css";s:8:"footer-2";s:10:"inner_html";s:0:"";s:10:"outer_html";s:42:"<div class="bkg-footer"> {{content}}</div>";s:13:"display_empty";b:0;s:4:"type";s:12:"container_24";s:5:"items";a:3:{i:0;a:11:{s:6:"column";s:1:"6";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:0:"";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:6:"area14";}}i:1;a:11:{s:6:"column";s:2:"18";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:0:"";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:6:"area15";}}i:2;s:5:"clear";}}i:7;a:6:{s:10:"custom_css";s:0:"";s:10:"inner_html";s:0:"";s:10:"outer_html";s:42:"<div class="bkg-footer"> {{content}}</div>";s:13:"display_empty";b:0;s:4:"type";s:12:"container_24";s:5:"items";a:2:{i:0;a:11:{s:6:"column";s:2:"24";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:8:"footer-3";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:6:"area16";}}i:1;s:5:"clear";}}i:8;a:6:{s:10:"custom_css";s:8:"footer-4";s:10:"inner_html";s:0:"";s:10:"outer_html";s:42:"<div class="bkg-footer"> {{content}}</div>";s:13:"display_empty";b:0;s:4:"type";s:12:"container_24";s:5:"items";a:5:{i:0;a:11:{s:6:"column";s:2:"12";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:0:"";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:6:"area17";}}i:1;a:11:{s:6:"column";s:2:"12";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:5:"omega";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:6:"area18";}}i:2;s:5:"clear";i:3;a:11:{s:6:"column";s:2:"24";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:13:"gird-ajaxcart";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:15:"before_body_end";}}i:4;s:5:"clear";}}}
EOB
),
	'is_active' => 1
);
$model->setData($data)->setStores(array(0))->save();

$data = array(
	'package_theme'	=>	'default/default',
	'layout'	=>	'3columns',	
	'content_decode'	=> unserialize(<<<EOB
a:9:{i:0;a:6:{s:10:"custom_css";s:16:"feedback-wrapper";s:10:"inner_html";s:0:"";s:10:"outer_html";s:0:"";s:13:"display_empty";b:0;s:4:"type";s:14:"container_free";s:5:"items";a:1:{i:0;s:8:"feedback";}}i:1;a:6:{s:10:"custom_css";s:0:"";s:10:"inner_html";s:0:"";s:10:"outer_html";s:0:"";s:13:"display_empty";b:0;s:4:"type";s:14:"container_free";s:5:"items";a:1:{i:0;s:6:"header";}}i:2;a:6:{s:10:"custom_css";s:21:"banner-header-wrapper";s:10:"inner_html";s:0:"";s:10:"outer_html";s:0:"";s:13:"display_empty";b:0;s:4:"type";s:14:"container_free";s:5:"items";a:1:{i:0;s:6:"area19";}}i:3;a:6:{s:10:"custom_css";s:9:"main-body";s:10:"inner_html";s:0:"";s:10:"outer_html";s:0:"";s:13:"display_empty";b:0;s:4:"type";s:12:"container_24";s:5:"items";a:6:{i:0;a:11:{s:6:"column";s:1:"6";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:8:"col-left";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:4:"left";}}i:1;a:11:{s:6:"column";s:2:"12";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:9:"gird-col3";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:13:{i:0;s:14:"main_slideshow";i:1;s:6:"area01";i:2;s:6:"area02";i:3;s:11:"breadcrumbs";i:4;s:15:"global_messages";i:5;s:6:"area03";i:6;s:6:"area04";i:7;s:6:"area05";i:8;s:6:"area06";i:9;s:6:"area07";i:10;s:6:"area08";i:11;s:6:"area09";i:12;s:7:"content";}}i:2;a:11:{s:6:"column";s:1:"6";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:9:"col-right";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:5:"right";}}i:3;s:5:"clear";i:4;a:11:{s:6:"column";s:2:"24";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:0:"";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:6:"area10";}}i:5;s:5:"clear";}}i:4;a:6:{s:10:"custom_css";s:0:"";s:10:"inner_html";s:0:"";s:10:"outer_html";s:38:"<div class="footer"> {{content}}</div>";s:13:"display_empty";b:0;s:4:"type";s:12:"container_24";s:5:"items";a:2:{i:0;a:11:{s:6:"column";s:2:"24";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:0:"";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:6:"area11";}}i:1;s:5:"clear";}}i:5;a:6:{s:10:"custom_css";s:8:"footer-1";s:10:"inner_html";s:0:"";s:10:"outer_html";s:42:"<div class="bkg-footer"> {{content}}</div>";s:13:"display_empty";b:0;s:4:"type";s:12:"container_24";s:5:"items";a:3:{i:0;a:11:{s:6:"column";s:2:"18";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:0:"";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:6:"area12";}}i:1;a:11:{s:6:"column";s:1:"6";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:0:"";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:6:"area13";}}i:2;s:5:"clear";}}i:6;a:6:{s:10:"custom_css";s:8:"footer-2";s:10:"inner_html";s:0:"";s:10:"outer_html";s:42:"<div class="bkg-footer"> {{content}}</div>";s:13:"display_empty";b:0;s:4:"type";s:12:"container_24";s:5:"items";a:3:{i:0;a:11:{s:6:"column";s:1:"6";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:0:"";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:6:"area14";}}i:1;a:11:{s:6:"column";s:2:"18";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:0:"";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:6:"area15";}}i:2;s:5:"clear";}}i:7;a:6:{s:10:"custom_css";s:8:"footer-3";s:10:"inner_html";s:0:"";s:10:"outer_html";s:42:"<div class="bkg-footer"> {{content}}</div>";s:13:"display_empty";b:0;s:4:"type";s:12:"container_24";s:5:"items";a:2:{i:0;a:11:{s:6:"column";s:2:"24";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:0:"";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:6:"area16";}}i:1;s:5:"clear";}}i:8;a:6:{s:10:"custom_css";s:8:"footer-4";s:10:"inner_html";s:0:"";s:10:"outer_html";s:42:"<div class="bkg-footer"> {{content}}</div>";s:13:"display_empty";b:0;s:4:"type";s:12:"container_24";s:5:"items";a:5:{i:0;a:11:{s:6:"column";s:2:"12";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:0:"";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:6:"area17";}}i:1;a:11:{s:6:"column";s:2:"12";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:0:"";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:6:"area18";}}i:2;s:5:"clear";i:3;a:11:{s:6:"column";s:2:"24";s:4:"push";s:0:"";s:4:"pull";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";s:5:"first";b:0;s:4:"last";b:0;s:10:"custom_css";s:13:"gird-ajaxcart";s:10:"inner_html";s:0:"";s:13:"display_empty";b:0;s:5:"items";a:1:{i:0;s:15:"before_body_end";}}i:4;s:5:"clear";}}}
EOB
),
	'is_active' => 1
);
$model->setData($data)->setStores(array(0))->save();
 /* Save data */
$installer->endSetup();