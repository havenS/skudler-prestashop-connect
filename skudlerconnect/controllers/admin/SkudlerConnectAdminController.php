<?php

require_once(dirname(dirname(dirname(__FILE__))).'/lib/skudler-api/src/SkudlerAPI.php');

class SkudlerConnectAdminController extends AdminController {

    protected $moduleName = 'skudlerconnect';

    public $bootstrap = true;

    public $errors;

    public $lang;

    protected $options;

    protected $api;
    protected $api_key;
    protected $api_token;
    protected $apiStatus;

    protected $sites;

    public function __construct()
    {
        $this->options   = $this->getOptions();

        if(!empty($_POST))
            $this->processForm($_POST);

        $this->api_key   = $this->options['SKUDLER_API_KEY'];
        $this->api_token = $this->options['SKUDLER_API_TOKEN'];

        $this->initApi();

        $this->callCheckCredential();

        parent::__construct();

        global $cookie;

        $this->lang = $cookie->id_lang;

    }

    /**
     * Set values for the inputs.
     */
    protected function getOptions()
    {
        return array(
            'SKUDLER_ENABLED' 	=> Configuration::get('SKUDLER_ENABLED', false),

            'SKUDLER_API_KEY' 	=> Configuration::get('SKUDLER_API_KEY', false),
            'SKUDLER_API_TOKEN' => Configuration::get('SKUDLER_API_TOKEN', false),

            'SKUDLER_SITE_ID' 	=> Configuration::get('SKUDLER_SITE_ID', false),

            'SKUDLER_REGISTER_STATUS'	 => Configuration::get('SKUDLER_REGISTER_STATUS', false),
            'SKUDLER_REGISTER_EVENT'	 => Configuration::get('SKUDLER_REGISTER_EVENT', false),

            'SKUDLER_LOGIN_STATUS'		 => Configuration::get('SKUDLER_LOGIN_STATUS', false),
            'SKUDLER_LOGIN_EVENT'		 => Configuration::get('SKUDLER_LOGIN_EVENT', false),

            'SKUDLER_CART_UPDATE_STATUS' => Configuration::get('SKUDLER_CART_UPDATE_STATUS', false),
            'SKUDLER_CART_UPDATE_EVENT'	 => Configuration::get('SKUDLER_CART_UPDATE_EVENT', false),

            'SKUDLER_NEW_ORDER_STATUS'	 => Configuration::get('SKUDLER_NEW_ORDER_STATUS', false),
            'SKUDLER_NEW_ORDER_EVENT'	 => Configuration::get('SKUDLER_NEW_ORDER_EVENT', false),
        );

    }

    public function renderList()
    {
        $this->addCss(_MODULE_DIR_.$this->moduleName.'/css/admin.css');
        $this->addJs(_MODULE_DIR_.$this->moduleName.'/js/admin.js');

        $this->context->smarty->assign(array(
            'errors'    => $this->errors,
            'options'   => $this->options,
            'apiStatus' => $this->apiStatus,
            'sites'     => $this->apiStatus ?  $this->api->getSites() : false,
            'events'    => $this->options['SKUDLER_SITE_ID'] ? $this->api->getEvents($this->options['SKUDLER_SITE_ID']) : false,
        ));

        return $this->context->smarty->fetch(dirname(dirname(dirname(__FILE__))).'/views/templates/admin/configure.tpl');
    }

    protected function initApi()
    {
        $this->api = new \Skudler\SkudlerAPI($this->api_key, $this->api_token);
    }

    protected function processForm($form)
    {
        foreach (array_keys($this->options) as $key){
            $value = Tools::getValue($key);
            if(!empty($value)){
                $this->options[$key] = $value;
                Configuration::updateValue($key, Tools::getValue($key));
            }
        }
    }

    protected function callCheckCredential()
    {
        $call = $this->api->getSites(false);

        $this->setApiStatus($call && $call->status == 'success');
    }

    protected function setApiStatus($status)
    {
        $this->apiStatus = $status;

        Configuration::set('SKUDLER_API_STATUS', $status);

    }

}