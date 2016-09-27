<?php

class InvoicefoxActionOrderEditedController
{
	public function __construct($module, $file, $path)
	{
		$this->file = $file;
		$this->module = $module;
		$this->context = Context::getContext(); $this->_path = $path;
	}

	public function processConfiguration()
	{
		
		
	}

	public function renderForm()
	{
		
		
	}

	public function run()
	{
		
		$this->context->smarty->assign('params', $params);
		$html_confirmation_message = $this->module->display($this->file, 'actionOrderEdited.tpl');		
		return $html_confirmation_message;
	}
}
