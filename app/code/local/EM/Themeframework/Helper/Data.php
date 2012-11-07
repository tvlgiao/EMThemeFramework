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

class EM_Themeframework_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getHandlesAvailable(){
		return array(
			'default'							=>	$this->__('Default'),
			'catalog_category_default'			=>	$this->__('Catalog Category Default'),
			'catalog_category_layered'			=>	$this->__('Catalog Category Layered'),
			'catalog_product_compare_index'		=>	$this->__('Catalog Product Compare Index'),
			'catalog_product_view'				=>	$this->__('Catalog Product View'),
			'catalog_seo_sitemap'				=>	$this->__('Catalog Seo Sitemap'),
			'catalog_seo_sitemap_category'		=>	$this->__('Catalog Seo Sitemap Category'),
			'catalog_seo_sitemap_category_tree'	=>	$this->__('Catalog Seo Sitemap Category Tree'),
			'catalog_seo_sitemap_product'		=>	$this->__('Catalog Seo Sitemap Product'),
			'catalogsearch_result_index'		=>	$this->__('Quick Search Form'),
			'catalogsearch_advanced_index'		=>	$this->__('Advanced Search Form'),
			'catalogsearch_advanced_result'		=>	$this->__('Advanced Search Result'),
			'catalogsearch_term_popular'		=>	$this->__('Popular Search Terms'),
			'customer_account'					=>	$this->__('Customer Account'),
			'customer_account_login'			=>	$this->__('Customer Account Login'),
			'customer_account_login'			=>	$this->__('Customer Account Login'),
			'customer_account_logoutsuccess'	=>	$this->__('Customer Account Logout Success'),
			'customer_account_create'			=>	$this->__('Customer Account Create'),
			'customer_account_forgotpassword'	=>	$this->__('Customer Account Forgot Password'),
			'customer_account_resetpassword'	=>	$this->__('Customer Account Reset Password'),
			'customer_account_confirmation'		=>	$this->__('Customer Account Confirmation'),
			'customer_account_edit'				=>	$this->__('Customer Account Edit'),
			'customer_account_index'			=>	$this->__('Customer Account Index'),
			'customer_address_form'				=>	$this->__('Customer Address Form'),
			'checkout_cart_index'				=>	$this->__('Checkout Cart Index'),
			'checkout_cart_configure'			=>	$this->__('Checkout Cart Configure'),
			'checkout_onepage_index'			=>	$this->__('Checkout Onepage Index'),
			'checkout_onepage_progress'			=>	$this->__('Checkout Onepage Progress'),
			'checkout_onepage_paymentmethod'	=>	$this->__('Checkout Onepage Payment Method'),
			'checkout_onepage_shippingmethod'	=>	$this->__('Checkout Onepage Shipping Method'),
			'checkout_onepage_additional'		=>	$this->__('Checkout Onepage Additional'),
			'checkout_onepage_review'			=>	$this->__('Checkout Onepage Review'),
			'checkout_onepage_success'			=>	$this->__('Checkout Onepage Success'),
			'checkout_onepage_failure'			=>	$this->__('Checkout Onepage Failure'),
			'newsletter_manage_index'			=>	$this->__('Newsletter Manage Index'),
			'contacts_index_index'				=>	$this->__('Contacts'),
			'sales_order_history'				=>	$this->__('Customer My Account Order History'),
			'sales_order_view'					=>	$this->__('Customer My Account Order View'),
			'sales_order_invoice'				=>	$this->__('Customer My Account Order Invoice View'),
			'sales_order_shipment'				=>	$this->__('Customer My Account Order Shipment View'),
			'sales_order_creditmemo'			=>	$this->__('Customer My Account Order Creditmemo View'),
			'sales_order_print'					=>	$this->__('Sales Order Print View'),
			'sales_order_printinvoice'			=>	$this->__('Sales Invoice Print View'),
			'sales_order_printshipment'			=>	$this->__('Sales Shipment Print View'),
			'sales_guest_form'					=>	$this->__('Returns'),
			'sales_guest_view'					=>	$this->__('Customer My Account Order View'),
			'sales_guest_invoice'				=>	$this->__('Customer My Account Order Invoice View'),
			'sales_guest_shipment'				=>	$this->__('sales_guest_shipment'),
			'sales_guest_creditmemo'			=>	$this->__('Customer My Account Order Creditmemo View'),
			'reviews'							=>	$this->__('Product reviews page'),
			'review_product_list'				=>	$this->__('Review Product List'),
			'review_product_view'				=>	$this->__('Review Product View'),
			'review_customer_index'				=>	$this->__('Customer My Account Product Reviews'),
			'tag_list_index'					=>	$this->__('Tags List'),
			'tag_product_list'					=>	$this->__('Tagged Products List'),
			'tag_customer_index'				=>	$this->__('Customer My Account My Tags List'),
			'tag_customer_view'					=>	$this->__('Customer My Account Tag View'),
			'wishlist_index_index'				=>	$this->__('Customer My Account My Wishlist'),
			'wishlist_index_share'				=>	$this->__('Customer My Account Wishlist Sharing Form'),
			'wishlist_index_configure'			=>	$this->__('Configure Wishlist Item'),
			'wishlist_shared_index'				=>	$this->__('Customer Shared Wishlist View'),
			'contacts_index_index'				=>	$this->__('Contact Us Form'),
			'sendfriend_product_send'			=>	$this->__('Catalog Product Email to a Friend'),
			'custom_handle'						=>	$this->__('Custom Handle')
		);
	}
}