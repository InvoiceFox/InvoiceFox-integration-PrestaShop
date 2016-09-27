<?php
require_once (dirname(__FILE__).'/helpers/helper.php');
require_once(dirname(__FILE__).'/classes/lib/invfoxapi.php');
require_once(dirname(__FILE__).'/classes/lib/strpcapi.php');
require_once $_SERVER['DOCUMENT_ROOT'].__PS_BASE_URI__.'classes/order/OrderState.php';
require_once $_SERVER['DOCUMENT_ROOT'].__PS_BASE_URI__.'classes/order/Order.php';
require_once $_SERVER['DOCUMENT_ROOT'].__PS_BASE_URI__.'classes/Customer.php';
class InvoiceFox extends Module
{
	public function __construct()
	{
		$this->name = 'invoicefox';
		$this->tab = 'others';
		$this->version = '0.1';
		$this->author = 'Juliston';
		$this->bootstrap = true;
		parent::__construct();
		$this->displayName = $this->l('Invoicefox Module');
		$this->description = $this->l('Invoicefox Module');
	}

	public function installTab($parent, $class_name, $name)
	{
		// Create new admin tab
		$tab = new Tab();
		$tab->id_parent = (int)Tab::getIdFromClassName($parent);
		$tab->name = array();
		foreach (Language::getLanguages(true) as $lang)
			$tab->name[$lang['id_lang']] = $name;
		$tab->class_name = $class_name;
		$tab->module = $this->name;
		$tab->active = 1;
		return $tab->add();
	}

	public function uninstallTab($class_name)
	{
		// Retrieve Tab ID
		$id_tab = (int)Tab::getIdFromClassName($class_name);

		// Load tab
		$tab = new Tab((int)$id_tab);

		// Delete it
		return $tab->delete();
	}


	public function install()
	{
		// Call install parent method
		if (!parent::install())
			return false;

		// Execute module install SQL statements
		$sql_file = dirname(__FILE__).'/install/install.sql';
		if (!$this->loadSQLFile($sql_file))
			return false;

		// Register hooks
		if (!$this->registerHook('actionOrderStatusUpdate'))
			return false;
		
		if (!$this->installTab('AdminOrders', 'AdminInvoicefox', 'Invoicefox'))
			return false;

		// All went well!
		return true;
	}

	public function uninstall()
	{
		// Call uninstall parent method
		if (!parent::uninstall())
			return false;

		// Execute module install SQL statements
		// $sql_file = dirname(__FILE__).'/install/uninstall.sql';
		// if (!$this->loadSQLFile($sql_file))
		//	return false;
		$this->uninstallTab('AdminInvoicefox');
		// All went well!
		return true;
	}

	public function loadSQLFile($sql_file)
	{
		// Get install SQL file content
		$sql_content = file_get_contents($sql_file);

		// Replace prefix and store SQL command in array
		$sql_content = str_replace('PREFIX_', _DB_PREFIX_, $sql_content);
		$sql_requests = preg_split("/;\s*[\r\n]+/", $sql_content);

		// Execute each SQL statement
		$result = true;
		foreach($sql_requests as $request)
			if (!empty($request))
				$result &= Db::getInstance()->execute(trim($request));

		// Return result
		return $result;
	}

	
	
	public function getHookController($hook_name)
	{
		// Include the controller file
		require_once(dirname(__FILE__).'/controllers/hook/'. $hook_name.'.php');

		// Build dynamically the controller name
		$controller_name = $this->name.$hook_name.'Controller';

		// Instantiate controller
		$controller = new $controller_name($this, __FILE__, $this->_path);

		// Return the controller
		return $controller;
	}

	public function hookActionOrderStatusUpdate($params)
	{
		$controller = $this->getHookController('actionOrderStatusUpdate');
		$controller->run($params);
	}

	public function hookFinalize($params)
	{
		$controller = $this->getHookController('actionOrderStatusUpdate');
		$controller->finalize($params);
	}
	
	public function hookPrint($params)
	{
		$controller = $this->getHookController('actionOrderStatusUpdate');
		$controller->print_invoice($params);
	}
	
	public function hookPrintupn($params)
	{
		$controller = $this->getHookController('actionOrderStatusUpdate');
		$controller->print_invoice($params);
	}

	public function hookFiscol($params)
	{
		$controller = $this->getHookController('actionOrderStatusUpdate');
		$controller->fiscol($params);
	}

	public function getContent()
	{
		$ajax_hook = Tools::getValue('ajax_hook');
		$action = Tools::getValue('action');
		if ($ajax_hook != '')
		{
			$ajax_method = 'hook'.ucfirst($ajax_hook);
			if (method_exists($this, $ajax_method))
				die($this->{$ajax_method}(array()));
		}
		elseif($action == 'finalize' || $action == 'print' || $action == 'printupn' || $action == 'fiscol')
		{
			$action = 'hook'.ucfirst($action);
			if (method_exists($this, $action)){
				$params=array('id_order'=>Tools::getValue('id_order'));
				if(Tools::getValue('hstyle')){
					$params['hstyle'] = Tools::getValue('hstyle');
				}
				die($this->{$action}($params));
			}
		}


		$controller = $this->getHookController('getContent');
		return $controller->run();
	}

	
}
