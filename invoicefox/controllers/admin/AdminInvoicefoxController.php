<?php
class AdminInvoicefoxController extends ModuleAdminController
{
	public function __construct()
	{
		$this->context = Context::getContext();
		$this->context->controller = $this;
		
		// Enable bootstrap
		$this->bootstrap = true;

		// Call of the parent constructor method
		parent::__construct();

		
	}

	
	public function initContent()
	{
		parent::initContent();
	}
}