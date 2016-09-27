<?php
/**
 * @property Order $object
 */
class AdminOrdersController extends AdminOrdersControllerCore
{
    public function renderView()
    {
        
		$invoicefox_info = Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'invoicefox  WHERE order_id = '.(int)Tools::getValue('id_order'));
		if($invoicefox_info){
			$this->context->smarty->assign('invoicefox_id', $invoicefox_info['invoicefox_id']);
			$this->context->smarty->assign('invoicefox_info', $invoicefox_info);
		}
		else{
			$this->context->smarty->assign('invoicefox_id', '');
			$this->context->smarty->assign('invoicefox_info', '');
		}

		parent::renderView();
        
        $tpl_file = _PS_MODULE_DIR_.'/invoicefox/override/controllers/admin/templates/orders/helpers/view/view.tpl';

        $tpl = $this->context->smarty->createTemplate($tpl_file, $this->context->smarty);

        $tpl->assign($this->tpl_view_vars);

        return $tpl->fetch();
    }
}