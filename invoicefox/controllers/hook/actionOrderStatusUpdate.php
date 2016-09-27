<?php
class InvoicefoxActionOrderStatusUpdateController
{
	public function __construct($module, $file, $path)
	{
		$this->file = $file;
		$this->module = $module;
		$this->context = Context::getContext(); 
		$this->_path = $path;
	}

	
	public function run($params)
	{
		
		$newComment = '';
		presta_invoicefox_trace($params['newOrderStatus']->name);
		$id_order=(int)$params['id_order'];
		$invoicefox_id = Db::getInstance()->getValue('SELECT invoicefox_id FROM `'._DB_PREFIX_.'invoicefox` WHERE `order_id` = '.(int)$params['id_order']);

		if(Configuration::get('INVOICEFOX_CREATE_INVFOX_DOCUMENT_ON_STATUS') == $params['newOrderStatus']->id && $invoicefox_id==''){
			error_log("IN ORDER STATUS HOOK PROCESS", E_USER_ERROR);
			$order = new Order((int)$id_order);
			if ($order) {
				
				presta_invoicefox_trace($order,0);			
				
				$api = new InvfoxAPI(Configuration::get('INVOICEFOX_API_KEY'), Configuration::get('INVOICEFOX_API_DOMAIN'), true);
			
				
				presta_invoicefox_trace("============ INVFOX::begin ===========");
				$customer=$order->getCustomer();
				$invoice_address = new Address((int)$order->{Configuration::get('PS_TAX_ADDRESS_TYPE', null, null, $order->id_shop)});
				$customer_address = $customer->getAddresses($this->context->language->id);
				
				
				 $r = $api->assurePartner(array(
				     'name' => $invoice_address->firstname." ".$invoice_address->lastname." ".$invoice_address->company,
				     'street' => $invoice_address->address1."\n". $invoice_address->address2,
				     'postal' => $invoice_address->postcode,
				     'city' =>$invoice_address->city,
				     'country' => $invoice_address->country,
				     'vatid' => $invoice_address->vat_number,
				     'phone' => $invoice_address->phone, 
				     'website' => "",
				     'email' => $customer->email,
				     'notes' => '',
				     'vatbound' => false,//!!$c->vat_number, TODO -- after (2)
				     'custaddr' => '',
				     'payment_period' => Configuration::get('INVOICEFOX_CUSTOMER_GENERAL_PAYMENT_PERIOD'),
				     'street2' => ''
				     ));

				presta_invoicefox_trace("============ INVFOX::assured partner ============");
				
				if ($r->isOk()) {
					$clientIdA = $r->getData();
					$clientId = $clientIdA[0]['id'];
					$date1 = $api->_toSIDate(date('Y-m-d'));
					$invid = str_pad($order_id, 5, "0", STR_PAD_LEFT);
					$data['products'] = array();

					$prducts=$order->getProducts();
					
					$body2 = array();
					$subtotal = 0;
					$producttax = 0;
	
					foreach($prducts as $product){
						  $product_options_text='';
						  
						  $subtotal = $subtotal + round($product['price'], 2);
						  presta_invoicefox_trace($product,0);
						  $body2[] = array(
								   'code' => $productMore['sku'],
								   'title' => $product['product_name']." ".$product['product_upc']."\n",
								   'qty' => $product['product_quantity'],
								   'mu' => '',
								   'price' => round($product['product_price'], 2),
								   'vat' => round($product['tax_rate']+$product['ecotax'], Configuration::get('INVOICEFOX_ROUND_CALCULATED_TAXRATE_TO')),
								   'discount' => 0
								   );
						  //$product['tax'] / $product['price'] * 100
						   $producttax = round($product['tax_rate']+$product['ecotax'], Configuration::get('INVOICEFOX_ROUND_CALCULATED_TAXRATE_TO'));
					}
				}
				
				$shipping = $order->total_shipping;
				$discounts = $order->discounts;

				if (Configuration::get('INVOICEFOX_DOCUMENT_TO_MAKE') != 'inventory' && ($shipping > 0 || $discounts > 0) ) {
	  
				  if ($shipping) {
					presta_invoicefox_trace("============ INVFOX:: adding shipping ============");
					
					
					$body2[] = array(
							 'title' => 'Shipping',
							 'qty' => 1,
							 'mu' => '',
							 'price' => $shipping,
							 'vat' => round( ($order->total_shipping_tax_incl-$order->total_shipping_tax_excl) / $order->shipping * 100, Configuration::get('round_calculated_taxrate_to')),
							 'discount' => 0
							 );
				  }
				  if ($coupon) {
					  //$couponvat = round($product['tax'] / $coupon['value'] * 100, $this->CONF['round_calculated_taxrate_to']);
					  $body2[] = array(
							 'title' => 'Discount',
							 'qty' => 1,
							 'mu' => '',
							 'price' => $discounts,
							 'vat' =>$producttax,
							 'discount' => 0
							 );
				  }
				}
	
				presta_invoicefox_trace("============ INVFOX::before create invoice call ============");
				$invoice_no = str_pad($order->invoice_number,5,'0',STR_PAD_LEFT);
				$invid = Configuration::get('INVOICEFOX_USE_SHOP_DOCUMENT_NUMBERS') ? $invoice_no : '';


				presta_invoicefox_trace($invoice_no);
				if (Configuration::get('INVOICEFOX_DOCUMENT_TO_MAKE') == 'invoice') {
					$r2 = $api->createInvoice(
								  array(
									'title' => $invid,
									'date_sent' => $date1,
									'date_to_pay' => $date1,
									'date_served' => $date1, // MAY NOT BE NULL IN SOME VERSIONS OF USER DBS
									'id_partner' => $clientId,
									'taxnum' => '-',
									'doctype' => 0,
									'id_document_ext' => $id_order,
									 'pub_notes' => $invoice_no
									),
								  $body2
								  );


					if ($r2->isOk()) {    
					  $invA = $r2->getData();
					  $query = "INSERT INTO "._DB_PREFIX_."invoicefox (invoicefox_id,order_id) VALUES ('".$invA[0]['id']."','".$id_order."');";
					  Db::getInstance()->Execute($query);
					  //$comment .= "- Račun št.: {$invoice_no} was created at {$this->CONF['APP_NAME']}.";
					}
				}
				elseif (Configuration::get('INVOICEFOX_DOCUMENT_TO_MAKE') == 'proforma') {
					   $r2 = $api->createProFormaInvoice(
										  array(
											'title' => $invoice_no,
											'date_sent' => $date1,
											'days_valid' => Configuration::get('INVOICEFOX_PROFORMA_DAYS_VALID'),
											'id_partner' => $clientId,
											'taxnum' => '-',
											 'pub_notes' => $invoice_no
											),
										  $body2
										  );


						if ($r2->isOk()) {    
						  $invA = $r2->getData();
						  //$comment .= "- pro forma Račun št.: {$invoice_no} was created at ".Configuration::get('INVOICEFOX_APP_NAME').".";
						}

				}
				elseif (Configuration::get('INVOICEFOX_DOCUMENT_TO_MAKE') == 'inventory') {
					$invoice_no = $invoice_no == "-" ? "" : $invoice_no ;
					$r2 = $api->createInventorySale(
									array(
										  'docnum' => $invoice_no,
										  'date_created' => $date1,
										  'id_contact_to' => $clientId,
										  'id_contact_from' => Configuration::get('INVOICEFOX_FROM_WAREHOUSE_ID'),
										  'taxnum' => '-',
										  'doctype' => 1,
										   'pub_notes' => $invoice_no
										  ),
									$body2
									);
					if ($r2->isOk()) {    
					  $invA = $r2->getData();
					  $comment .= "Inventory sales document No. {$invoice_no} was created at ".Configuration::get('INVOICEFOX_APP_NAME').".";
					}
				}
				presta_invoicefox_trace($r2);
				presta_invoicefox_trace("============ INVFOX::after create invoice ============");
      
			}
			
			error_log("IN ORDER STATUS HOOK PROCESS END", E_USER_ERROR);
		}
		elseif(Configuration::get('INVOICEFOX_CLOSE_INVFOX_DOCUMENT_ON_STATUS') == $params['newOrderStatus']->id){
			$order_id = $params['id_order'];

			$api = new InvfoxAPI(Configuration::get('INVOICEFOX_API_KEY'), Configuration::get('INVOICEFOX_API_DOMAIN'), true);
			$order = new Order((int)$id_order);
			$invoice_no = Configuration::get('INVOICEFOX_USE_SHOP_DOCUMENT_NUMBERS') ? str_pad($order->invoice_number,5,'0',STR_PAD_LEFT) : '-';
			$r = $api->markInvoicePaid($id_order);			  
			error_log("IN ORDER STATUS HOOK END", E_USER_ERROR);
		}

	}

	public function finalize($params){
		  $order_id = $params['id_order'];

		  $header['id']=0;
		  $header['id_invoice_sent_ext']=$order_id;
		  $header['id_register']=Configuration::get('INVOICEFOX_TAX_REGISTER'); // id of register
		  $header['fiscalize']=Configuration::get('INVOICEFOX_FISCALIZE');	// should fiscalize or not / cash invoice or not (send to FURS / Tax Office)
		  $header['id_location']=Configuration::get('INVOICEFOX_TAX_LOCATION'); // id of location
		  $header['op-tax-id']=Configuration::get('INVOICEFOX_TAX_ID');
		  $header['op-name']=Configuration::get('INVOICEFOX_TAX_NAME'); 
		  $api = new InvfoxAPI(Configuration::get('INVOICEFOX_API_KEY'), Configuration::get('INVOICEFOX_API_DOMAIN'), true);
		  $r = $api->finalizeInvoice($header);
		  $result=array();
		  $result['status']='fail';
		  if(is_array($r)){
			if(isset($r[0]['err'])){
				$result['error']=$r[0]['err'];
			}
			elseif(isset($r[0]['docnum'])){
				$result['status']='success';
				$result['docnum']=$r[0]['docnum'];
				$result['eor']=$r[0]['eor'];
				
				$data['doc_num']=pSQL($r[0]['docnum']);
				$data['is_finalize']=1;
				$invoicefox_id = Db::getInstance()->update('invoicefox',$data,' order_id = '.$order_id);
				
			}
			else{
				$result['error']='Error occured';
			}
			
		  }
		  else{
			  $result['error']='Error occured';
		  }
		  echo json_encode($result);
	}

	public function fiscol($params){
	  $order_id = $params['id_order'];
	  $api = new InvfoxAPI(Configuration::get('INVOICEFOX_API_KEY'), Configuration::get('INVOICEFOX_API_DOMAIN'), true);
	  $r = $api->getFiscalInfo($order_id);
	  echo "<pre>";
	  print_r($r);
	}

	public function print_invoice($params){
	  $order_id = $params['id_order'];
	  $hstyle = trim($params['hstyle']);
	  if($hstyle=='') $hstyle = 'basicVER3';
	  $api = new InvfoxAPI(Configuration::get('INVOICEFOX_API_KEY'), Configuration::get('INVOICEFOX_API_DOMAIN'), true);
	  $api->printInvoice($order_id,'invoice-sent',$hstyle);
	}

	
}