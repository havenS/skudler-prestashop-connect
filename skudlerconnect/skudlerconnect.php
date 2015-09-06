<?php
/**
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2014 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

require_once dirname(__FILE__).'/lib/skudler-api/src/SkudlerAPI.php';

if (!defined('_PS_VERSION_'))
	exit;

class Skudlerconnect extends Module
{
	protected $api;

	public function __construct()
	{
		$this->name = 'skudlerconnect';
		$this->tab_name = 'Skudler Connect';
		$this->tabClassName = 'SkudlerConnectAdmin';
		$this->tab = 'advertising_marketing';
		$this->version = '1.0.0';
		$this->author = 'Skudler';
		$this->need_instance = 0;

		/**
		 * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
		 */
		$this->bootstrap = true;

		parent::__construct();

		$this->displayName = $this->l('Skulder Connect');
		$this->description = $this->l('Get back to your customers');

		$this->confirmUninstall = $this->l('Are you sure ?');
	}

	/**
	 * Don't forget to create update methods if needed:
	 * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
	 */
	public function install()
	{
		Configuration::updateValue('SKUDLER_ENABLED'			, false);
		Configuration::updateValue('SKUDLER_API_STATUS'			, false);
		Configuration::updateValue('SKUDLER_API_KEY'			, false);
		Configuration::updateValue('SKUDLER_API_TOKEN'			, false);
		Configuration::updateValue('SKUDLER_SITE_ID'			, false);
		Configuration::updateValue('SKUDLER_REGISTER_STATUS'	, false);
		Configuration::updateValue('SKUDLER_REGISTER_EVENT'		, false);
		Configuration::updateValue('SKUDLER_LOGIN_STATUS'		, false);
		Configuration::updateValue('SKUDLER_LOGIN_EVENT'		, false);
		Configuration::updateValue('SKUDLER_CART_UPDATE_STATUS'	, false);
		Configuration::updateValue('SKUDLER_CART_UPDATE_EVENT'	, false);
		Configuration::updateValue('SKUDLER_NEW_ORDER_STATUS'	, false);
		Configuration::updateValue('SKUDLER_NEW_ORDER_EVENT'	, false);

		return parent::install() &&
			$this->addTab() &&
			$this->registerHook('backOfficeHeader') &&
			$this->registerHook('actionAuthentication') &&
			$this->registerHook('actionCustomerAccountAdd') &&
			$this->registerHook('actionCartSave') &&
			$this->registerHook('actionValidateOrder');
	}

	public function uninstall()
	{
		Configuration::deleteByName('SKUDLER_ENABLED');
		Configuration::deleteByName('SKUDLER_API_STATUS');
		Configuration::deleteByName('SKUDLER_API_KEY');
		Configuration::deleteByName('SKUDLER_API_TOKEN');
		Configuration::deleteByName('SKUDLER_SITE_ID');
		Configuration::deleteByName('SKUDLER_REGISTER_STATUS');
		Configuration::deleteByName('SKUDLER_REGISTER_EVENT');
		Configuration::deleteByName('SKUDLER_LOGIN_STATUS');
		Configuration::deleteByName('SKUDLER_LOGIN_EVENT');
		Configuration::deleteByName('SKUDLER_CART_UPDATE_STATUS');
		Configuration::deleteByName('SKUDLER_CART_UPDATE_EVENT');
		Configuration::deleteByName('SKUDLER_NEW_ORDER_STATUS');
		Configuration::deleteByName('SKUDLER_NEW_ORDER_EVENT');

		return parent::uninstall();
	}

	public function addTab()
	{
		$tab                = new Tab();
		$tab->active        = 1;
		$tab->class_name    = $this->tabClassName;
		$tab->id_parent     = -1;
		$tab->module        = $this->name;
		$languages          = Language::getLanguages();

		foreach ($languages as $language)
			$tab->name[$language['id_lang']] = 'Skudler Connect';

		return $tab->add();
	}

	/**
	 * Load the configuration form
	 */
	public function getContent()
	{
		Tools::redirectAdmin($this->context->link->getAdminLink('SkudlerConnectAdmin'));
	}

	public function hookActionCustomerAccountAdd($params)
	{
		$this->initApi();

		if($this->checkEnabled('SKUDLER_REGISTER_STATUS')){
			$customerId = $params['cookie']->id_customer;

			$customer = new Customer($customerId);

			$formattedUser = array(
				'firstName' => $customer->firstname,
				'lastName'	=> $customer->lastname,
				'email'     => $customer->email,
			);

			@$this->api->addSubscription(Configuration::get('SKUDLER_REGISTER_EVENT'), $formattedUser);

		}
	}

	public function hookActionAuthentication($params)
	{
		$this->initApi();

		if($this->checkEnabled('SKUDLER_LOGIN_STATUS')){
			$customerId = $params['cookie']->id_customer;

			$customer = new Customer($customerId);

			$formattedUser = array(
				'firstName' => $customer->firstname,
				'lastName'	=> $customer->lastname,
				'email'     => $customer->email,
			);

			@$this->api->addSubscription(Configuration::get('SKUDLER_LOGIN_EVENT'), $formattedUser);

		}
	}

	public function hookActionCartSave($params)
	{
		$this->initApi();

		if($this->checkEnabled('SKUDLER_CART_UPDATE_STATUS')){
			$customerId = $params['cookie']->id_customer;

			$customer 	= new Customer($customerId);

			$formattedUser = array(
				'firstName' => $customer->firstname,
				'lastName'	=> $customer->lastname,
				'email'     => $customer->email,
				'products' 	=> $this->getFormattedProducts($params['cookie']->id_cart)
			);

			@$this->api->addSubscription(Configuration::get('SKUDLER_CART_UPDATE_EVENT'), $formattedUser);

		}
	}

	public function hookActionValidateOrder($params)
	{
		$this->initApi();

		if($this->checkEnabled('SKUDLER_NEW_ORDER_STATUS')){
			$customerId = $params['cookie']->id_customer;

			$customer = new Customer($customerId);

			$formattedUser = array(
				'firstName' => $customer->firstname,
				'lastName'	=> $customer->lastname,
				'email'     => $customer->email,
				'products' 	=> $this->getFormattedProducts($params['cookie']->id_cart)
			);

			@$this->api->addSubscription(Configuration::get('SKUDLER_NEW_ORDER_EVENT'), $formattedUser);

		}
	}





	protected function initApi()
	{
		$apiKey 	= Configuration::get('SKUDLER_API_KEY', false);
		$apiToken 	= Configuration::get('SKUDLER_API_TOKEN', false);
		$this->api = new \Skudler\SkudlerAPI($apiKey, $apiToken);
	}

	protected function checkEnabled($event)
	{
		$globalStatus 	= Configuration::get('SKUDLER_ENABLED', false);
		$eventStatus 	= Configuration::get($event, false);

		return $globalStatus && $eventStatus;
	}

	protected function getFormattedProducts($cartId)
	{
		$formattedProducts = array();

		$link		= new Link();
		$cart		= new CartCore($cartId);
		$products 	= $cart->getProducts();

		foreach($products as $product){
			$formattedProducts[] = array(
				'name' 			=> $product['name'],
				'description' 	=> $product['description_short'],
				'thumbnail' 	=> 'http://'.$link->getImageLink($product['link_rewrite'], $product['id_image'], 'home_default'),
				'quantity' 		=> $product['quantity'],
				'price' 		=> $product['price_wt'],
			);
		}

		return $formattedProducts;

	}

}
