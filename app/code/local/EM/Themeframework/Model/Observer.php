<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<?php
class EM_Themeframework_Model_Observer extends Varien_Object
{
	protected $_pages = null;
	protected function initPages($handles){
		if(!$this->_pages){
			$pages = Mage::getSingleton('themeframework/page')->getCollection()->addFieldToFilter('status',1)
				->addFieldToFilter(array('handle','custom_handle'),array(
					array(
						'in'		=>	$handles
					),
					array(
						'in'		=>	$handles
					)
				))
				->addStoreFilter(Mage::app()->getStore()->getId());
			$pages->getSelect()->order('sort DESC');	
			$this->_pages = $pages;	
		}
		return $this->_pages;	
	}
	
	/* Update template */
    public function changeTemplateEvent($observer) {
		$handles = $observer->getEvent()->getLayout()->getUpdate()->getHandles();
		$pages = $this->initPages($handles);
		if(!$pages->count())
			return $this;
		
		$layout = '';
		
		foreach($pages as $page){
			if((in_array($page->getHandle(),$handles) || $page->getHandle() == 'custom_handle') && ($page->getLayout()))
				$layout = $page->getLayout();
		}
		$observer->getEvent()->getAction()->getLayout()->helper('page/layout')->applyTemplate($layout);
    }

	/* Update custom layout */
	public function changeLayoutEvent($observer){
		$handles = $observer->getEvent()->getLayout()->getUpdate()->getHandles();
		$pages = $this->initPages($handles);
		if(!$pages->count())
			return $this;
		$update = $observer->getEvent()->getLayout()->getUpdate();
		foreach($pages as $page){
			if(in_array($page->getHandle(),$handles) || $page->getHandle() == 'custom_handle'){
				$layoutUpdate = $page->getLayoutUpdateXml();
				if(!empty($layoutUpdate)){
					$update->addUpdate($layoutUpdate);
				}	
			}			
		}
		return $this;
	}	
	
	public function processAfterHtmlDispatch($observer) {
		static $addJS;
		
		$cookie = Mage::getSingleton('core/cookie');
		$key = $key = $cookie->get('EDIT_BLOCK_KEY');
		if (!$key || !$cookie->get('adminhtml')) return;
		
		$block = $observer->getEvent()->getData('block');
		$name = $block->getNameInLayout();
		
		// is static block
		if (is_a($block, 'Mage_Cms_Block_Block') || is_a($block, 'Mage_Cms_Block_Widget_Block')) {
			$block_id = $block->getBlockId();
			$model = Mage::getModel('cms/block')
				->setStoreId(Mage::app()->getStore()->getId())
				->load($block_id);
			if (!($id = $model->getId())) $id = $block_id;
			
			$title = $model->getTitle();
			$transport = $observer->getEvent()->getTransport();
			
			$html = '';
			if (!$addJS) {
				$addJS = true;
				$html .= "<script type=\"text/javascript\" src=\"".$block->getSkinUrl('js/em_themeframework/em_themeframework.js')."\"></script>";
				$html .= "<script type=\"text/javascript\">jQuery(function($) { 
					$('head').append('<link rel=\"stylesheet\" type=\"text/css\" href=\"".$block->getSkinUrl('css/em_themeframework.css')."\"></link>');
					$('body').append('<div id=\"em_themeframework_previewblock_actions\"><a href=\"".Mage::getUrl('themeframework/area/disablePreview')."\" class=\"turnoff\">".$block->__("Disable Block Preview")."</a></div>');
				});</script>"; 
			}
			
			$html .= trim($transport->getHtml());
			$transport->setHtml($html
				."<div class=\"em_themeframework_previewblock".(!$html ? ' empty' : '')."\" style=\"display:none\">"
				."<a target=\"_blank\" href=\"".Mage::helper('adminhtml')->getUrl("adminhtml/cms_block/edit", array('block_id' => $id, 'key' => $key))."\">$title</a>"
				."</div>");
		} 
		// is widget
		elseif (strlen($name) == 32 && preg_replace('/[^a-z0-9]/', '', $name) == $name) {
			$transport = $observer->getEvent()->getTransport();
			$html = trim($transport->getHtml());
			$transport->setHtml($html
				."<div class=\"em_themeframework_previewblock".(!$html ? ' empty' : '')."\" style=\"display:none\">"
				."Widget ".$block->getType()
				."<br/><span class=\"path\">".$block->getTemplateFile()."</span>"
				."</div>");

		}
	}
}
?>
