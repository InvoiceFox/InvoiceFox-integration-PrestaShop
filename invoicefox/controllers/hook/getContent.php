<?php

class InvoicefoxGetContentController
{
	public function __construct($module, $file, $path)
	{
		$this->file = $file;
		$this->module = $module;
		$this->context = Context::getContext(); $this->_path = $path;
	}

	public function processConfiguration()
	{
		if (Tools::isSubmit('invoicefox_pc_form'))
		{
			      
			$invoicefox_api_key = trim(Tools::getValue('invoicefox_api_key'));
			$invoicefox_app_name = trim(Tools::getValue('invoicefox_app_name'));
			$invoicefox_api_domain = Tools::getValue('invoicefox_api_domain');
			$invoicefox_document_to_make = Tools::getValue('invoicefox_document_to_make');
			$invoicefox_proforma_days_valid = trim(Tools::getValue('invoicefox_proforma_days_valid'));
			$invoicefox_customer_general_payment_period = trim(Tools::getValue('invoicefox_customer_general_payment_period'));
			
			
			$invoicefox_add_post_content_in_item_descr = Tools::getValue('invoicefox_add_post_content_in_item_descr');
			$invoicefox_partial_sum_label = Tools::getValue('invoicefox_partial_sum_label');
			$invoicefox_round_calculated_taxrate_to = Tools::getValue('invoicefox_round_calculated_taxrate_to');
			$invoicefox_round_calculated_netprice_to = Tools::getValue('invoicefox_round_calculated_netprice_to');
			$invoicefox_from_warehouse_id = Tools::getValue('invoicefox_from_warehouse_id');
			$invoicefox_tax_rate_on_shipping = Tools::getValue('invoicefox_tax_rate_on_shipping');
			$invoicefox_use_shop_document_numbers = Tools::getValue('invoicefox_use_shop_document_numbers');
			$invoicefox_create_invfox_document_on_status = Tools::getValue('invoicefox_create_invfox_document_on_status');
			$invoicefox_close_invfox_document_on_status = Tools::getValue('invoicefox_close_invfox_document_on_status');

			$invoicefox_tax_id = Tools::getValue('invoicefox_tax_id');
			$invoicefox_tax_name = Tools::getValue('invoicefox_tax_name');
			$invoicefox_tax_location = Tools::getValue('invoicefox_tax_location');
			$invoicefox_tax_register = Tools::getValue('invoicefox_tax_register');
			$invoicefox_fiscalize = Tools::getValue('invoicefox_fiscalize');
			
			$ok='ok';
			if($invoicefox_api_key==''){
				$msg=$this->module->l('Please enter API Key');
				$ok='ko';
			}
			elseif($invoicefox_app_name==''){
				$msg=$this->module->l('Please enter API Name');
				$ok='ko';
			}
			elseif($invoicefox_proforma_days_valid==''){
				$msg=$this->module->l('Please enter Proforma days valid');
				$ok='ko';
			}
			elseif($invoicefox_customer_general_payment_period==''){
				$msg=$this->module->l('Please enter Customer general payment period');
				$ok='ko';
			}
			elseif($invoicefox_round_calculated_taxrate_to==''){
				$msg=$this->module->l('Please enter Round calculated taxrate to');
				$ok='ko';
			}
			elseif($invoicefox_round_calculated_netprice_to==''){
				$msg=$this->module->l('Please enter Round calculated netprice to');
				$ok='ko';
			}
			elseif($invoicefox_from_warehouse_id==''){
				$msg=$this->module->l('Please enter From warehouse id');
				$ok='ko';
			}
			elseif($invoicefox_tax_rate_on_shipping==''){
				$msg=$this->module->l('Please enter Tax rate on shipping');
				$ok='ko';
			}
			elseif($invoicefox_tax_id==''){
				$msg=$this->module->l('Please enter Tax id');
				$ok='ko';
			}
			elseif($invoicefox_tax_name==''){
				$msg=$this->module->l('Please enter Tax Name');
				$ok='ko';
			}
			elseif($invoicefox_tax_location==''){
				$msg=$this->module->l('Please enter Tax Location');
				$ok='ko';
			}
			elseif($invoicefox_tax_register==''){
				$msg=$this->module->l('Please enter Tax Register');
				$ok='ko';
			}
			elseif($invoicefox_fiscalize==''){
				$msg=$this->module->l('Please enter Tax Fiscalize');
				$ok='ko';
			}

			
	

			if($ok=='ok'){
				Configuration::updateValue('INVOICEFOX_API_KEY', $invoicefox_api_key);
				Configuration::updateValue('INVOICEFOX_APP_NAME', $invoicefox_app_name);
				Configuration::updateValue('INVOICEFOX_API_DOMAIN', $invoicefox_api_domain);
				Configuration::updateValue('INVOICEFOX_DOCUMENT_TO_MAKE', $invoicefox_document_to_make);
				Configuration::updateValue('INVOICEFOX_PROFORMA_DAYS_VALID', $invoicefox_proforma_days_valid);
				Configuration::updateValue('INVOICEFOX_CUSTOMER_GENERAL_PAYMENT_PERIOD', $invoicefox_customer_general_payment_period);

				Configuration::updateValue('INVOICEFOX_ADD_POST_CONTENT_IN_ITEM_DESCR', $invoicefox_add_post_content_in_item_descr);
				Configuration::updateValue('INVOICEFOX_PARTIAL_SUM_LABEL', $invoicefox_partial_sum_label);
				Configuration::updateValue('INVOICEFOX_ROUND_CALCULATED_TAXRATE_TO', $invoicefox_round_calculated_taxrate_to);
				Configuration::updateValue('INVOICEFOX_ROUND_CALCULATED_NETPRICE_TO', $invoicefox_round_calculated_netprice_to);
				Configuration::updateValue('INVOICEFOX_FROM_WAREHOUSE_ID', $invoicefox_from_warehouse_id);
				Configuration::updateValue('INVOICEFOX_TAX_RATE_ON_SHIPPING', $invoicefox_tax_rate_on_shipping);
				Configuration::updateValue('INVOICEFOX_USE_SHOP_DOCUMENT_NUMBERS', $invoicefox_use_shop_document_numbers);
				Configuration::updateValue('INVOICEFOX_CREATE_INVFOX_DOCUMENT_ON_STATUS', $invoicefox_create_invfox_document_on_status);
				Configuration::updateValue('INVOICEFOX_CLOSE_INVFOX_DOCUMENT_ON_STATUS', $invoicefox_close_invfox_document_on_status);

				Configuration::updateValue('INVOICEFOX_TAX_ID', $invoicefox_tax_id);
				Configuration::updateValue('INVOICEFOX_TAX_NAME', $invoicefox_tax_name);
				Configuration::updateValue('INVOICEFOX_TAX_LOCATION', $invoicefox_tax_location);
				Configuration::updateValue('INVOICEFOX_TAX_REGISTER', $invoicefox_tax_register);
				Configuration::updateValue('INVOICEFOX_FISCALIZE', $invoicefox_fiscalize);

				$this->context->smarty->assign('confirmation', $ok);
			}
			else{
				$this->context->smarty->assign('confirmation', $ok);
				$this->context->smarty->assign('msg',$msg);
			}
		}
	}

	public function renderForm()
	{
		$domains = array(
		  array(
			'id_option' => 'www.invoicefox.com',       // The value of the 'value' attribute of the <option> tag.
			'name' => 'www.invoicefox.com'    // The value of the text content of the  <option> tag.
		 ),
		  array(
			'id_option' => 'www.invoicefox.co.uk',
			'name' => 'www.invoicefox.co.uk'
		  ),
		  array(
			'id_option' => 'www.invoicefox.com.au',
			'name' => 'www.invoicefox.com.au'
		  ),
		  array(
			'id_option' => 'www.cebelca.biz',
			'name' => 'www.cebelca.biz'
		  ),
		  array(
			'id_option' => 'ww2.cebelca.biz',
			'name' => 'ww2.cebelca.biz'
		  ),
		  array(
			'id_option' => 'test.cebelca.biz',
			'name' => 'test.cebelca.biz'
		  ),
		  array(
			'id_option' => 'www.abelie.biz',
			'name' => 'www.abelie.biz'
		  ),
		
		);

		$documentmakes = array(
		  array(
			'id_option' => 'invoice', 
			'name' => 'invoice'  
		 ),
		  array(
			'id_option' => 'proforma',
			'name' => 'proforma'
		  ),
		  array(
			'id_option' => 'inventory',
			'name' => 'inventory'
		  ),
		
		);
		
		$invoicefox_add_post_content_in_item_descr_option= array(
		  array(
			'id_option' => 'true', 
			'name' => 'True'  
		 ),
		  array(
			'id_option' => 'false',
			'name' => 'False'
		  ),
		);

		$orderStates = new OrderStateCore();
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->module->l('Invoicefox Module configuration'),
					'icon' => 'icon-envelope'
				),
				'input' => array(
					array(
						'type' => 'text',
						'label' => $this->module->l('API Key:'),
						'name' => 'invoicefox_api_key',
						'required'=>true,
						'desc' => $this->module->l('Enter API Key of Invoicefox app.'),
					),
					array(
						'type' => 'text',
						'label' => $this->module->l('APP Name:'),
						'name' => 'invoicefox_app_name',
						'required'=>true,
					),
					array(
						'type' => 'select',
						'label' => $this->module->l('API domin:'),
						'name' => 'invoicefox_api_domain',
						'desc' => $this->module->l('API domin'),
						'options' => array( 'query' => $domains,'id' => 'id_option','name' => 'name'    ),
					),
					array(
						'type' => 'select',
						'label' => $this->module->l('Document to make:'),
						'name' => 'invoicefox_document_to_make',
						'desc' => $this->module->l('Enable grades on products.'),
						'options' => array( 'query' => $documentmakes,'id' => 'id_option','name' => 'name'    ),
					),
					array(
						'type' => 'text',
						'label' => $this->module->l('Proforma days valid:'),
						'name' => 'invoicefox_proforma_days_valid',
						'required'=>true,
						'desc' => $this->module->l('Proforma days valid.'),
						'default_value'=>2,
					),
					array(
						'type' => 'text',
						'label' => $this->module->l('Customer general payment period'),
						'name' => 'invoicefox_customer_general_payment_period',
						'required'=>true,
						'default_value'=>4,
					),
					array(
						'type' => 'switch',
						'label' => $this->module->l('Display Product Option in product title'),
						'name' => 'invoicefox_add_post_content_in_item_descr',
						'desc' => $this->module->l('Enable grades on products.'),
						'values' => array(
							array('id' => 'enable_invoicefox_add_post_content_in_item_descr_1', 'value' => 1, 'label' => $this->module->l('Enabled')),
							array('id' => 'enable_invoicefox_add_post_content_in_item_descr_0', 'value' => 0, 'label' => $this->module->l('Disabled'))
						),
					),
					array(
						'type' => 'text',
						'label' => $this->module->l('Partial sum label'),
						'name' => 'invoicefox_partial_sum_label',
						'desc' => $this->module->l('Partial sum label.'),
					),
					array(
						'type' => 'text',
						'label' => $this->module->l('Round calculated taxrate to:'),
						'name' => 'invoicefox_round_calculated_taxrate_to',
						'required'=>true,
					),
					array(
						'type' => 'text',
						'label' => $this->module->l('Round calculated netprice to:'),
						'name' => 'invoicefox_round_calculated_netprice_to',
						'required'=>true,
					),
					array(
						'type' => 'text',
						'label' => $this->module->l('From warehouse id:'),
						'name' => 'invoicefox_from_warehouse_id',
						'required'=>true,
					),
					array(
						'type' => 'text',
						'label' => $this->module->l('Tax rate on shipping:'),
						'name' => 'invoicefox_tax_rate_on_shipping',
						'required'=>true,
					),
					array(
						'type' => 'switch',
						'label' => $this->module->l('Use shop document numbers'),
						'name' => 'invoicefox_use_shop_document_numbers',
						'values' => array(
							array('id' => 'enable_invoicefox_use_shop_document_numbers_1', 'value' => 1, 'label' => $this->module->l('Enabled')),
							array('id' => 'enable_invoicefox_use_shop_document_numbers_0', 'value' => 0, 'label' => $this->module->l('Disabled'))
						),
					),
					array(
						'type' => 'select',
						'label' => $this->module->l('Create invfox document on status:'),
						'name' => 'invoicefox_create_invfox_document_on_status',
						'options' => array( 'query' => $orderStates->getOrderStates($this->context->cookie->id_lang),'id' => 'id_order_state','name' => 'name'    ),
					),
					array(
						'type' => 'select',
						'label' => $this->module->l('Create payment on status:'),
						'name' => 'invoicefox_close_invfox_document_on_status',
						'options' => array( 'query' => $orderStates->getOrderStates($this->context->cookie->id_lang),'id' => 'id_order_state','name' => 'name'    ),
					),
					array(
						'type' => 'text',
						'label' => $this->module->l('Tax id:'),
						'name' => 'invoicefox_tax_id',
						'required'=>true,
					),
					array(
						'type' => 'text',
						'label' => $this->module->l('Tax Name:'),
						'name' => 'invoicefox_tax_name',
						'required'=>true,
					),
					array(
						'type' => 'text',
						'label' => $this->module->l('Tax Location:'),
						'name' => 'invoicefox_tax_location',
						'required'=>true,
					),
					array(
						'type' => 'text',
						'label' => $this->module->l('Tax Register:'),
						'name' => 'invoicefox_tax_register',
						'required'=>true,
					),
					array(
						'type' => 'switch',
						'label' => $this->module->l('Fiscalize:'),
						'name' => 'invoicefox_fiscalize',
						'required'=>true,
						'values' => array(
							array('id' => 'enable_invoicefox_fiscalize_1', 'value' => 1, 'label' => $this->module->l('Enabled')),
							array('id' => 'enable_invoicefox_fiscalize_0', 'value' => 0, 'label' => $this->module->l('Disabled'))
						),
					),
				),
				'submit' => array('title' => $this->module->l('Save'))
			)
		);

		$helper = new HelperForm();
		$helper->table = 'invoicefox';
		$helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
		$helper->allow_employee_form_lang = (int)Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG');
		$helper->submit_action = 'invoicefox_pc_form';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->module->name.'&tab_module='.$this->module->tab.'&module_name='.$this->module->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => array(
				'invoicefox_api_key' => Tools::getValue('invoicefox_api_key', Configuration::get('INVOICEFOX_API_KEY')),
				'invoicefox_app_name' => Tools::getValue('invoicefox_app_name', Configuration::get('INVOICEFOX_APP_NAME')),
				'invoicefox_api_domain' => Tools::getValue('invoicefox_api_domain', Configuration::get('INVOICEFOX_API_DOMAIN')),
				'invoicefox_document_to_make' => Tools::getValue('invoicefox_document_to_make', Configuration::get('INVOICEFOX_DOCUMENT_TO_MAKE')),
				'invoicefox_proforma_days_valid' => Tools::getValue('invoicefox_proforma_days_valid', Configuration::get('INVOICEFOX_PROFORMA_DAYS_VALID')),
				'invoicefox_customer_general_payment_period' => Tools::getValue('invoicefox_customer_general_payment_period', Configuration::get('INVOICEFOX_CUSTOMER_GENERAL_PAYMENT_PERIOD')),
				'invoicefox_add_post_content_in_item_descr' => Tools::getValue('invoicefox_add_post_content_in_item_descr', Configuration::get('INVOICEFOX_ADD_POST_CONTENT_IN_ITEM_DESCR')),
				'invoicefox_partial_sum_label' => Tools::getValue('invoicefox_partial_sum_label', Configuration::get('INVOICEFOX_PARTIAL_SUM_LABEL')),
				'invoicefox_round_calculated_taxrate_to' => Tools::getValue('invoicefox_round_calculated_taxrate_to', Configuration::get('INVOICEFOX_ROUND_CALCULATED_TAXRATE_TO')),
				'invoicefox_round_calculated_netprice_to' => Tools::getValue('invoicefox_round_calculated_netprice_to', Configuration::get('INVOICEFOX_ROUND_CALCULATED_NETPRICE_TO')),
				'invoicefox_from_warehouse_id' => Tools::getValue('invoicefox_from_warehouse_id', Configuration::get('INVOICEFOX_FROM_WAREHOUSE_ID')),
				'invoicefox_tax_rate_on_shipping' => Tools::getValue('invoicefox_tax_rate_on_shipping', Configuration::get('INVOICEFOX_TAX_RATE_ON_SHIPPING')),
				'invoicefox_use_shop_document_numbers' => Tools::getValue('invoicefox_use_shop_document_numbers', Configuration::get('INVOICEFOX_USE_SHOP_DOCUMENT_NUMBERS')),
				'invoicefox_create_invfox_document_on_status' => Tools::getValue('invoicefox_create_invfox_document_on_status', Configuration::get('INVOICEFOX_CREATE_INVFOX_DOCUMENT_ON_STATUS')),
				'invoicefox_close_invfox_document_on_status' => Tools::getValue('invoicefox_close_invfox_document_on_status', Configuration::get('INVOICEFOX_CLOSE_INVFOX_DOCUMENT_ON_STATUS')),
				'invoicefox_tax_id' => Tools::getValue('invoicefox_tax_id', Configuration::get('INVOICEFOX_TAX_ID')),
				'invoicefox_tax_name' => Tools::getValue('invoicefox_tax_name', Configuration::get('INVOICEFOX_TAX_NAME')),
				'invoicefox_tax_location' => Tools::getValue('invoicefox_tax_location', Configuration::get('INVOICEFOX_TAX_LOCATION')),
				'invoicefox_tax_register' => Tools::getValue('invoicefox_tax_register', Configuration::get('INVOICEFOX_TAX_REGISTER')),
				'invoicefox_fiscalize' => Tools::getValue('invoicefox_fiscalize', Configuration::get('INVOICEFOX_FISCALIZE')),
			),
			'languages' => $this->context->controller->getLanguages()
		);

		return $helper->generateForm(array($fields_form));
	}

	public function run()
	{
		$this->processConfiguration();
		$html_confirmation_message = $this->module->display($this->file, 'getContent.tpl');
		$html_form = $this->renderForm();
		return $html_confirmation_message.$html_form;
	}
}
