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
}
?>
