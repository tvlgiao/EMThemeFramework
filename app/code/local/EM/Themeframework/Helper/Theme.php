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

class EM_Themeframework_Helper_Theme {
	
	function display($root, $layout = '1column') {
		$isPreview = Mage::registry('is_preview');
		if ($isPreview) {
			$model = Mage::getModel('themeframework/area')->getCollection()
							->addFilter('area_id', array('eq' => Mage::app()->getRequest()->getParam('id')))
							->getFirstItem();
		} else {
			$collection = Mage::getModel('themeframework/area')->getCollection()
							->addStoreFilter(Mage::app()->getStore()->getId())
							->addFilter('layout', array('eq' => $layout))
							->addFilter('is_active', array('eq' => 1))
							->addOrder('store_id', 'DESC');
			$model = $collection->getFirstItem();
		}
		
		$content = unserialize($model->getContent());
		$html = '';
		foreach ($content as $div) {
			$containerHtml = '';
			
			// div.container_24
			if ($div['type'] == 'container_24') {
				$gridHtml = '';
				foreach ($div['items'] as $grid) {
					// div.clear
					if (is_string($grid) && $grid == 'clear') {
						$gridHtml .= '<div class="clear"></div>';
					// div.grid_*
					} elseif (is_array($grid)) {
						$class = array('grid_'.$grid['column']);
						if ($grid['push']) $class[] = 'push_'.$grid['push'];
						if ($grid['pull']) $class[] = 'pull_'.$grid['pull'];
						if ($grid['prefix']) $class[] = 'prefix_'.$grid['prefix'];
						if ($grid['suffix']) $class[] = 'suffix_'.$grid['suffix'];
						if ($grid['first']) $class[] = 'alpha';
						if ($grid['last']) $class[] = 'omega';
						if ($grid['custom_css']) $class[] = $grid['custom_css'];
						$class = implode(' ', $class);
						
						
						// blocks
						$blockHtml = '';
						$debugTitle = '';
						foreach ($grid['items'] as $blockName) {
							if (empty($firstBlockName)) $debugTitle = '<div class="dbg-title">'.$blockName.'</div>';
							$blockHtml .= trim($root->getChildHtml($blockName));
						}
							
						if ($blockHtml == '' && !$grid['display_empty'])
							continue;
							
						if ($grid['inner_html'])
							$blockHtml = str_replace('{{content}}', $blockHtml, $grid['inner_html']);
						
						if ($isPreview) {
							$gridHtml .= '<div class="'.$class.' debug-container">'.$blockHtml.'<div class="debug">'.$debugTitle.'</div></div>';	
						} else {
						$gridHtml .= '<div class="'.$class.'">'.$blockHtml.'</div>';	
						}
					}
				}
				
				if ($gridHtml == '' && !$div['display_empty'])
					continue;
				
				if ($div['inner_html'])
					$gridHtml = str_replace('{{content}}', $gridHtml, $div['inner_html']);
				
				$containerHtml .= '<div class="container_24 '.$div['custom_css'].'">';
				$containerHtml .= $gridHtml;	
				$containerHtml .= '</div>';
			}
			
			// free div
			else {
				$blockHtml = '';
				$debugTitle = '';
				foreach ($div['items'] as $blockName) {
					if (empty($firstBlockName)) $debugTitle = '<div class="dbg-title">'.$blockName.'</div>';
					$blockHtml .= trim($root->getChildHtml($blockName));
				}
				
				if ($blockHtml == '' && !$div['display_empty'])
					continue;
					
				if ($div['inner_html'])
					$blockHtml = str_replace('{{content}}', $blockHtml, $div['inner_html']);
				
				if ($isPreview) {
					$containerHtml .= '<div class="'.$div['custom_css'].' debug-container"><div class="debug">'.$debugTitle.'</div>';
				} else {
					$containerHtml .= '<div class="'.$div['custom_css'].'">';
				}
				$containerHtml .= $blockHtml;
				$containerHtml .= '</div>';
			}
			
			if ($div['outer_html'])
				$containerHtml = str_replace('{{content}}', $containerHtml, $div['outer_html']);
			
			$html .= $containerHtml;
		}
		
		return $html;
	}
	
	function displayBootstrap($root, $layout = '1column') {
		$isPreview = Mage::registry('is_preview');
		if ($isPreview) {
			$model = Mage::getModel('themeframework/area')->getCollection()
							->addFilter('area_id', array('eq' => Mage::app()->getRequest()->getParam('id')))
							->getFirstItem();
		} else {
			$collection = Mage::getModel('themeframework/area')->getCollection()
							->addStoreFilter(Mage::app()->getStore()->getId())
							->addFilter('layout', array('eq' => $layout))
							->addFilter('is_active', array('eq' => 1))
							->addOrder('store_id', 'DESC');
			$model = $collection->getFirstItem();
		}
		
		$content = unserialize($model->getContent());
		$html = '';
		foreach ($content as $div) {
			$containerHtml = '';
			
			// div.container_24
			if ($div['type'] == 'container_24') {
				$spanHtml = '';
				$rowHtml = '';
				$col = 0;
				foreach ($div['items'] as $grid) {
					// div.clear
					if (is_string($grid) && $grid == 'clear') {
						if ($col > 0) {
							// finish a div.row
							$rowHtml .= '<div class="row'.($div['fluid'] ? '-fluid' : '').'">'.$spanHtml.'</div>';
							$spanHtml = '';
							$col = 0;
						}
					// div.grid_*
					} elseif (is_array($grid)) {
						
						// finish a div.row
						$col += floor($grid['column']/2);
						if ($col > 12) {
							$rowHtml .= '<div class="row'.($div['fluid'] ? '-fluid' : '').'">'.$spanHtml.'</div>';
							$spanHtml = '';
							$col = floor($grid['column']/2);
						}
						
						$class = array('span'.floor($grid['column']/2));
						# not available # if ($grid['push']) $class[] = 'push_'.$grid['push'];
						# not available # if ($grid['pull']) $class[] = 'pull_'.$grid['pull'];
						if ($grid['prefix']) $class[] = 'offset'.$grid['prefix'];
						# not available # if ($grid['suffix']) $class[] = 'suffix_'.$grid['suffix'];
						# not available # if ($grid['first']) $class[] = 'alpha';
						# not available # if ($grid['last']) $class[] = 'omega';
						if ($grid['custom_css']) $class[] = $grid['custom_css'];
						$class = implode(' ', $class);
						
						
						// blocks
						$blockHtml = '';
						$debugTitle = '';
						foreach ($grid['items'] as $blockName) {
							if (empty($firstBlockName)) $debugTitle = '<div class="dbg-title">'.$blockName.'</div>';
							$blockHtml .= trim($root->getChildHtml($blockName));
						}
							
						if ($blockHtml == '' && !$grid['display_empty'])
							continue;
							
						if ($grid['inner_html'])
							$blockHtml = str_replace('{{content}}', $blockHtml, $grid['inner_html']);
						
						if ($isPreview) {
							$spanHtml .= '<div class="'.$class.' debug-container">'.$blockHtml.'<div class="debug">'.$debugTitle.'</div></div>';	
						} else {
							$spanHtml .= '<div class="'.$class.'">'.$blockHtml.'</div>';	
						}
					}
				}
				
				if ($col > 0) {
					// finish a div.row
					$rowHtml .= '<div class="row'.($div['fluid'] ? '-fluid' : '').'">'.$spanHtml.'</div>';
					$spanHtml = '';
				}
				
				if ($rowHtml == '' && !$div['display_empty'])
					continue;
				
				if ($div['inner_html'])
					$rowHtml = str_replace('{{content}}', $rowHtml, $div['inner_html']);
				
				$containerHtml .= '<div class="container'.($div['fluid'] ? '-fluid ' : ' ').$div['custom_css'].'">';
				$containerHtml .= $rowHtml;	
				$containerHtml .= '</div>';
			}
			
			// free div
			else {
				$blockHtml = '';
				$debugTitle = '';
				foreach ($div['items'] as $blockName) {
					if (empty($firstBlockName)) $debugTitle = '<div class="dbg-title">'.$blockName.'</div>';
					$blockHtml .= trim($root->getChildHtml($blockName));
				}
				
				if ($blockHtml == '' && !$div['display_empty'])
					continue;
					
				if ($div['inner_html'])
					$blockHtml = str_replace('{{content}}', $blockHtml, $div['inner_html']);
				
				if ($isPreview) {
					$containerHtml .= '<div class="'.$div['custom_css'].' debug-container"><div class="debug">'.$debugTitle.'</div>';
				} else {
					$containerHtml .= '<div class="'.$div['custom_css'].'">';
				}
				$containerHtml .= $blockHtml;
				$containerHtml .= '</div>';
			}
			
			if ($div['outer_html'])
				$containerHtml = str_replace('{{content}}', $containerHtml, $div['outer_html']);
			
			$html .= $containerHtml;
		}
		
		return $html;
	}
}