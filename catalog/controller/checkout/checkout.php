<?php  
class ControllerCheckoutCheckout extends Controller { 
	public function index() {
		// Validate cart has products and has stock.





		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
	  		$this->redirect($this->url->link('checkout/cart'));
    	}

		// Validate minimum quantity requirments.
		$products = $this->cart->getProducts();

        try{
            $this->load->model('checkout/statistics');

            $this->model_checkout_statistics->saveProducts($products);
        }
        catch(Exception $e)
        {

        }




		foreach ($products as $product) {
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}

			if ($product['minimum'] > $product_total) {
				$this->redirect($this->url->link('checkout/cart'));
			}
		}



		if($this->customer->isLogged())
        {
             $this->loggedStrategy();
        }
        else
        {
             $this->guestStrategy();
        }

		$this->data['logged'] = $this->customer->isLogged();
		$this->data['shipping_required'] = $this->cart->hasShipping();


  	}


    public function useCoupon()
    {

        $this->load->model('checkout/coupon');
        $json = array();
        // sprawdzamy czy kupon jest ok
        $coupon_info = $this->model_checkout_coupon->getCoupon($this->request->post['coupon']);

        // jeśli nie plujemy errorem
        if (!$coupon_info) {
            $json['result'] = $this->language->get('error_coupon');
        }
        else
        {
            $json['result'] = 'ok';
        }


        if (isset($this->request->post['coupon']) AND $json['result']=='ok') {
            $this->session->data['coupon'] = $this->request->post['coupon'];

            $this->session->data['success'] = $this->language->get('text_coupon');
        }

        $this->response->setOutput(json_encode($json));
    }
	
	public function country() {
		$json = array();
		
		$this->load->model('localisation/country');

    	$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);
		
		if ($country_info) {
			$this->load->model('localisation/zone');

			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']		
			);
		}
		
		$this->response->setOutput(json_encode($json));
	}

    /*
     *  w przypadku gdy użytkownik nie jest zalogowany
     */
    private function guestStrategy(){



        // end lang

        $this->getInputNames();

        // login populate
        $this->data['guest_checkout'] = ($this->config->get('config_guest_checkout') && !$this->config->get('config_customer_price') && !$this->cart->hasDownload());

        if (isset($this->session->data['account'])) {
            $this->data['account'] = $this->session->data['account'];
        } else {
            $this->data['account'] = 'register';
        }

        $this->data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');

        // populate guest
        if (isset($this->session->data['guest']['firstname'])) {
            $this->data['firstname'] = $this->session->data['guest']['firstname'];
        } else {
            $this->data['firstname'] = '';
        }

        if (isset($this->session->data['guest']['lastname'])) {
            $this->data['lastname'] = $this->session->data['guest']['lastname'];
        } else {
            $this->data['lastname'] = '';
        }

        if (isset($this->session->data['guest']['email'])) {
            $this->data['email'] = $this->session->data['guest']['email'];
        } else {
            $this->data['email'] = '';
        }

        if (isset($this->session->data['guest']['telephone'])) {
            $this->data['telephone'] = $this->session->data['guest']['telephone'];
        } else {
            $this->data['telephone'] = '';
        }

        if (isset($this->session->data['guest']['fax'])) {
            $this->data['fax'] = $this->session->data['guest']['fax'];
        } else {
            $this->data['fax'] = '';
        }

        if (isset($this->session->data['guest']['payment']['company'])) {
            $this->data['company'] = $this->session->data['guest']['payment']['company'];
        } else {
            $this->data['company'] = '';
        }

        $this->load->model('account/customer_group');

        $this->data['customer_groups'] = array();

        if (is_array($this->config->get('config_customer_group_display'))) {
            $customer_groups = $this->model_account_customer_group->getCustomerGroups();

            foreach ($customer_groups as $customer_group) {
                if (in_array($customer_group['customer_group_id'], $this->config->get('config_customer_group_display'))) {
                    $this->data['customer_groups'][] = $customer_group;
                }
            }
        }

        if (isset($this->session->data['guest']['customer_group_id'])) {
            $this->data['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
        } else {
            $this->data['customer_group_id'] = $this->config->get('config_customer_group_id');
        }

        // Company ID
        if (isset($this->session->data['guest']['payment']['company_id'])) {
            $this->data['company_id'] = $this->session->data['guest']['payment']['company_id'];
        } else {
            $this->data['company_id'] = '';
        }

        // Tax ID
        if (isset($this->session->data['guest']['payment']['tax_id'])) {
            $this->data['tax_id'] = $this->session->data['guest']['payment']['tax_id'];
        } else {
            $this->data['tax_id'] = '';
        }

        if (isset($this->session->data['guest']['payment']['address_1'])) {
            $this->data['address_1'] = $this->session->data['guest']['payment']['address_1'];
        } else {
            $this->data['address_1'] = '';
        }

        if (isset($this->session->data['guest']['payment']['address_2'])) {
            $this->data['address_2'] = $this->session->data['guest']['payment']['address_2'];
        } else {
            $this->data['address_2'] = '';
        }

        if (isset($this->session->data['guest']['payment']['postcode'])) {
            $this->data['postcode'] = $this->session->data['guest']['payment']['postcode'];
        } elseif (isset($this->session->data['shipping_postcode'])) {
            $this->data['postcode'] = $this->session->data['shipping_postcode'];
        } else {
            $this->data['postcode'] = '';
        }

        if (isset($this->session->data['guest']['payment']['city'])) {
            $this->data['city'] = $this->session->data['guest']['payment']['city'];
        } else {
            $this->data['city'] = '';
        }

        if (isset($this->session->data['guest']['payment']['country_id'])) {
            $this->data['country_id'] = $this->session->data['guest']['payment']['country_id'];
        } elseif (isset($this->session->data['shipping_country_id'])) {
            $this->data['country_id'] = $this->session->data['shipping_country_id'];
        } else {
            $this->data['country_id'] = $this->config->get('config_country_id');
        }

        if (isset($this->session->data['guest']['payment']['zone_id'])) {
            $this->data['zone_id'] = $this->session->data['guest']['payment']['zone_id'];
        } elseif (isset($this->session->data['shipping_zone_id'])) {
            $this->data['zone_id'] = $this->session->data['shipping_zone_id'];
        } else {
            $this->data['zone_id'] = '';
        }

        $this->load->model('localisation/country');

        $this->data['countries'] = $this->model_localisation_country->getCountries();

        $this->data['shipping_required'] = $this->cart->hasShipping();

        if (isset($this->session->data['guest']['shipping_address'])) {
            $this->data['shipping_address'] = $this->session->data['guest']['shipping_address'];
        } else {
            $this->data['shipping_address'] = true;
        }
        // end


        // shipping address populate
        if (isset($this->session->data['guest']['shipping']['firstname'])) {
            $this->data['shipping_firstname'] = $this->session->data['guest']['shipping']['firstname'];
        } else {
            $this->data['shipping_firstname'] = '';
        }

        if (isset($this->session->data['guest']['shipping']['lastname'])) {
            $this->data['shipping_lastname'] = $this->session->data['guest']['shipping']['lastname'];
        } else {
            $this->data['shipping_lastname'] = '';
        }

        if (isset($this->session->data['guest']['shipping']['company'])) {
            $this->data['shipping_company'] = $this->session->data['guest']['shipping']['company'];
        } else {
            $this->data['shipping_company'] = '';
        }

        if (isset($this->session->data['guest']['shipping']['address_1'])) {
            $this->data['shipping_address_1'] = $this->session->data['guest']['shipping']['address_1'];
        } else {
            $this->data['shipping_address_1'] = '';
        }

        if (isset($this->session->data['guest']['shipping']['address_2'])) {
            $this->data['shipping_address_2'] = $this->session->data['guest']['shipping']['address_2'];
        } else {
            $this->data['shipping_address_2'] = '';
        }

        if (isset($this->session->data['guest']['shipping']['postcode'])) {
            $this->data['shipping_postcode'] = $this->session->data['guest']['shipping']['postcode'];
        } elseif (isset($this->session->data['shipping_postcode'])) {
            $this->data['shipping_postcode'] = $this->session->data['shipping_postcode'];
        } else {
            $this->data['shipping_postcode'] = '';
        }

        if (isset($this->session->data['guest']['shipping']['city'])) {
            $this->data['shipping_city'] = $this->session->data['guest']['shipping']['city'];
        } else {
            $this->data['shipping_city'] = '';
        }

        if (isset($this->session->data['guest']['shipping']['country_id'])) {
            $this->data['shipping_country_id'] = $this->session->data['guest']['shipping']['country_id'];
        } elseif (isset($this->session->data['shipping_country_id'])) {
            $this->data['shipping_country_id'] = $this->session->data['shipping_country_id'];
        } else {
            $this->data['shipping_country_id'] = $this->config->get('config_country_id');
        }

        if (isset($this->session->data['guest']['shipping']['zone_id'])) {
            $this->data['shipping_zone_id'] = $this->session->data['guest']['shipping']['zone_id'];
        } elseif (isset($this->session->data['shipping_zone_id'])) {
            $this->data['shipping_zone_id'] = $this->session->data['shipping_zone_id'];
        } else {
            $this->data['shipping_zone_id'] = '';
        }

        $this->load->model('localisation/country');

        $this->data['countries'] = $this->model_localisation_country->getCountries();

        // end

        // shipping method populate

        if (isset($this->session->data['guest'])) {
            $shipping_address = $this->session->data['guest']['shipping'];
        }
        else
        {
            $shipping_address = $this->shippingAddressStub();
        }

        if (!empty($shipping_address)) {
            // Shipping Methods
            $quote_data = array();

            $this->load->model('setting/extension');

            $results = $this->model_setting_extension->getExtensions('shipping');

            foreach ($results as $result) {
                if ($this->config->get($result['code'] . '_status')) {
                    $this->load->model('shipping/' . $result['code']);

                    $quote = $this->{'model_shipping_' . $result['code']}->getQuote($shipping_address);

                    if ($quote) {
                        $quote_data[$result['code']] = array(
                            'title'      => $quote['title'],
                            'quote'      => $quote['quote'],
                            'sort_order' => $quote['sort_order'],
                            'error'      => $quote['error']
                        );
                    }
                }
            }

            $sort_order = array();

            foreach ($quote_data as $key => $value) {
                $sort_order[$key] = $value['sort_order'];
            }

            array_multisort($sort_order, SORT_ASC, $quote_data);

            $this->session->data['shipping_methods'] = $quote_data;
        }

        if (empty($this->session->data['shipping_methods'])) {
            $this->data['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->session->data['shipping_methods'])) {
            $this->data['shipping_methods'] = $this->session->data['shipping_methods'];
        } else {
            $this->data['shipping_methods'] = array();
        }

        if (isset($this->session->data['shipping_method']['code'])) {
            $this->data['code'] = $this->session->data['shipping_method']['code'];
        } else {
            $this->data['code'] = '';
        }

        if (isset($this->session->data['comment'])) {
            $this->data['comment'] = $this->session->data['comment'];
        } else {
            $this->data['comment'] = '';
        }

        // payment method populate

        if (isset($this->session->data['guest'])) {
            $payment_address = $this->session->data['guest']['payment'];
        }
        else
        {
            $payment_address = $this->shippingAddressStub();
        }

        if (!empty($payment_address)) {
            // Totals
            $total_data = array();
            $total = 0;
            $taxes = $this->cart->getTaxes();

            $this->load->model('setting/extension');

            $sort_order = array();

            $results = $this->model_setting_extension->getExtensions('total');

            foreach ($results as $key => $value) {
                $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
            }

            array_multisort($sort_order, SORT_ASC, $results);

            foreach ($results as $result) {
                if ($this->config->get($result['code'] . '_status')) {
                    $this->load->model('total/' . $result['code']);

                    $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
                }
            }

            // Payment Methods
            $method_data = array();

            $this->load->model('setting/extension');

            $results = $this->model_setting_extension->getExtensions('payment');

            foreach ($results as $result) {
                if ($this->config->get($result['code'] . '_status')) {
                    $this->load->model('payment/' . $result['code']);

                    $method = $this->{'model_payment_' . $result['code']}->getMethod($payment_address, $total);

                    if ($method) {
                        $method_data[$result['code']] = $method;
                    }
                }
            }

            $sort_order = array();

            foreach ($method_data as $key => $value) {
                $sort_order[$key] = $value['sort_order'];
            }

            array_multisort($sort_order, SORT_ASC, $method_data);

            $this->session->data['payment_methods'] = $method_data;

        }

        if (empty($this->session->data['payment_methods'])) {
            $this->data['error_warning'] = sprintf($this->language->get('error_no_payment'), $this->url->link('information/contact'));
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->session->data['payment_methods'])) {
            $this->data['payment_methods'] = $this->session->data['payment_methods'];
        } else {
            $this->data['payment_methods'] = array();
        }

        if (isset($this->session->data['payment_method']['code'])) {
            $this->data['code'] = $this->session->data['payment_method']['code'];
        } else {
            $this->data['code'] = '';
        }

        if (isset($this->session->data['comment'])) {
            $this->data['comment'] = $this->session->data['comment'];
        } else {
            $this->data['comment'] = '';
        }

        if ($this->config->get('config_checkout_id')) {
            $this->load->model('catalog/information');

            $information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));

            if ($information_info) {
                $this->data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/info', 'information_id=' . $this->config->get('config_checkout_id'), 'SSL'), $information_info['title'], $information_info['title']);
            } else {
                $this->data['text_agree'] = '';
            }
        } else {
            $this->data['text_agree'] = '';
        }

        if (isset($this->session->data['agree'])) {
            $this->data['agree'] = $this->session->data['agree'];
        } else {
            $this->data['agree'] = '';
        }
		


        // confirm populate

        $this->data['products'] = array();

        foreach ($this->cart->getProducts() as $product) {
            $option_data = array();

            foreach ($product['option'] as $option) {
                if ($option['type'] != 'file') {
                    $value = $option['option_value'];
                } else {
                    $filename = $this->encryption->decrypt($option['option_value']);

                    $value = utf8_substr($filename, 0, utf8_strrpos($filename, '.'));
                }

                $option_data[] = array(
                    'name'  => $option['name'],
                    'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
                );
            }



            $this->data['products'][] = array(
                'product_id' => $product['product_id'],
                'name'       => $product['name'],
                'model'      => $product['model'],
                'option'     => $option_data,
                'quantity'   => $product['quantity'],
                'subtract'   => $product['subtract'],
                'price'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))),
                'total'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']),
                'href'       => $this->url->link('product/product', 'product_id=' . $product['product_id']),

            );
        }

        // Gift Voucher
        $this->data['vouchers'] = array();

        if (!empty($this->session->data['vouchers'])) {
            foreach ($this->session->data['vouchers'] as $voucher) {
                $this->data['vouchers'][] = array(
                    'description' => $voucher['description'],
                    'amount'      => $this->currency->format($voucher['amount'])
                );
            }
        }


        $total_data = array();
        $total = 0;
        $taxes = $this->cart->getTaxes();

        $this->load->model('setting/extension');

        $sort_order = array();

        $results = $this->model_setting_extension->getExtensions('total');

        foreach ($results as $key => $value) {
            $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
        }

        array_multisort($sort_order, SORT_ASC, $results);

        foreach ($results as $result) {
            if ($this->config->get($result['code'] . '_status')) {
                $this->load->model('total/' . $result['code']);

                $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
            }
        }

        $sort_order = array();

        foreach ($total_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $total_data);

        $this->data['totals'] = $total_data;

        $this->data['action_finalize'] = $this->url->link('checkout/checkout/finalize', '', 'SSL');



        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/checkout_guest.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/checkout/checkout_guest.tpl';
        } else {
            $this->template = 'default/template/checkout/checkout_guest.tpl';
        }

        $this->children = array(
            'common/column_left',
            'common/column_right',
            'common/content_top',
            'common/content_bottom',
            'common/footer',
            'common/header'
        );

        $this->response->setOutput($this->render());
    }

    /*
     * w przypadku gdy jest zalogowany
     */
    private function loggedStrategy()
    {



        if(!$this->customer->isLogged())
        {
            $this->redirect($this->url->link('checkout/checkout', '', 'SSL'));
        }

        $this->getInputNames();





        // payment

        if (isset($this->session->data['payment_firstname'])) {
            $this->data['payment_firstname'] = $this->session->data['payment_firstname'];
        } else {
            $this->data['payment_firstname'] = $this->customer->getFirstName();
        }

        if (isset($this->session->data['payment_lastname'])) {
            $this->data['payment_lastname'] = $this->session->data['payment_lastname'];
        } else {
            $this->data['payment_lastname'] = $this->customer->getLastName();
        }

        if (isset($this->session->data['payment_company'])) {
            $this->data['payment_company'] = $this->session->data['payment_company'];
        } else {
            $this->data['payment_company'] = '';
        }


        if (isset($this->session->data['payment_address_1'])) {
            $this->data['payment_address_1'] = $this->session->data['payment_address_1'];
        } else {
            $this->data['payment_address_1'] = '';
        }

        if (isset($this->session->data['payment_address_2'])) {
            $this->data['payment_address_2'] = $this->session->data['payment_address_2'];
        } else {
            $this->data['payment_address_2'] = '';
        }


        if (isset($this->session->data['payment_postcode'])) {
            $this->data['payment_postcode'] = $this->session->data['payment_postcode'];
        } elseif (isset($this->session->data['payment_postcode'])) {
            $this->data['payment_postcode'] = $this->session->data['payment_postcode'];
        } else {
            $this->data['payment_postcode'] = '';
        }

        if (isset($this->session->data['payment_city'])) {
            $this->data['payment_city'] = $this->session->data['payment_city'];
        } else {
            $this->data['payment_city'] = '';
        }

        if (isset($this->session->data['payment_country_id'])) {
            $this->data['payment_country_id'] = $this->session->data['payment_country_id'];
        } elseif (isset($this->session->data['payment_country_id'])) {
            $this->data['payment_country_id'] = $this->session->data['payment_country_id'];
        } else {
            $this->data['payment_country_id'] = '';
        }

        if (isset($this->session->data['payment_zone_id'])) {
            $this->data['payment_zone_id'] = $this->session->data['payment_zone_id'];
        } elseif (isset($this->session->data['payment_zone_id'])) {
            $this->data['payment_zone_id'] = $this->session->data['payment_zone_id'];
        } else {
            $this->data['payment_zone_id'] = '';
        }

        // shipping

        if (isset($this->session->data['shipping_firstname'])) {
            $this->data['shipping_firstname'] = $this->session->data['shipping_firstname'];
        } else {
            $this->data['shipping_firstname'] = $this->customer->getFirstName();
        }

        if (isset($this->session->data['shipping_lastname'])) {
            $this->data['shipping_lastname'] = $this->session->data['shipping_lastname'];
        } else {
            $this->data['shipping_lastname'] = $this->customer->getLastName();
        }

        if (isset($this->session->data['shipping_company'])) {
            $this->data['shipping_company'] = $this->session->data['shipping_company'];
        } else {
            $this->data['shipping_company'] = '';
        }


        if (isset($this->session->data['shipping_address_1'])) {
            $this->data['shipping_address_1'] = $this->session->data['shipping_address_1'];
        } else {
            $this->data['shipping_address_1'] = '';
        }

        if (isset($this->session->data['shipping_address_2'])) {
            $this->data['shipping_address_2'] = $this->session->data['shipping_address_2'];
        } else {
            $this->data['shipping_address_2'] = '';
        }


        if (isset($this->session->data['shipping_postcode'])) {
            $this->data['shipping_postcode'] = $this->session->data['shipping_postcode'];
        } elseif (isset($this->session->data['shipping_postcode'])) {
            $this->data['shipping_postcode'] = $this->session->data['shipping_postcode'];
        } else {
            $this->data['shipping_postcode'] = '';
        }

        if (isset($this->session->data['shipping_city'])) {
            $this->data['shipping_city'] = $this->session->data['shipping_city'];
        } else {
            $this->data['shipping_city'] = '';
        }

        if (isset($this->session->data['shipping_country_id'])) {
            $this->data['shipping_country_id'] = $this->session->data['shipping_country_id'];
        } elseif (isset($this->session->data['shipping_country_id'])) {
            $this->data['shipping_country_id'] = $this->session->data['shipping_country_id'];
        } else {
            $this->data['shipping_country_id'] = '';
        }

        if (isset($this->session->data['shipping_zone_id'])) {
            $this->data['shipping_zone_id'] = $this->session->data['shipping_zone_id'];
        } elseif (isset($this->session->data['shipping_zone_id'])) {
            $this->data['shipping_zone_id'] = $this->session->data['shipping_zone_id'];
        } else {
            $this->data['shipping_zone_id'] = '';
        }



        $this->load->model('account/customer_group');

        $this->data['customer_groups'] = array();

        if (is_array($this->config->get('config_customer_group_display'))) {
            $customer_groups = $this->model_account_customer_group->getCustomerGroups();

            foreach ($customer_groups as $customer_group) {
                if (in_array($customer_group['customer_group_id'], $this->config->get('config_customer_group_display'))) {
                    $this->data['customer_groups'][] = $customer_group;
                }
            }
        }

        if (isset($this->session->data['customer_group_id'])) {
            $this->data['customer_group_id'] = $this->session->data['customer_group_id'];
        } else {
            $this->data['customer_group_id'] = $this->config->get('config_customer_group_id');
        }

        $this->load->model('account/address');
        // get customer address





        $this->load->model('localisation/country');

        $this->data['countries'] = $this->model_localisation_country->getCountries();

        $this->data['shipping_required'] = $this->cart->hasShipping();

        $this->load->model('localisation/country');

        $this->data['countries'] = $this->model_localisation_country->getCountries();

        // end

        // shipping method populate

        if (isset($this->session->data['guest'])) {
            $shipping_address = $this->session->data['guest']['shipping'];
        }
        else
        {
            $shipping_address = $this->shippingAddressStub();
        }

        if (!empty($shipping_address)) {
            // Shipping Methods
            $quote_data = array();

            $this->load->model('setting/extension');

            $results = $this->model_setting_extension->getExtensions('shipping');

            foreach ($results as $result) {
                if ($this->config->get($result['code'] . '_status')) {
                    $this->load->model('shipping/' . $result['code']);

                    $quote = $this->{'model_shipping_' . $result['code']}->getQuote($shipping_address);

                    if ($quote) {
                        $quote_data[$result['code']] = array(
                            'title'      => $quote['title'],
                            'quote'      => $quote['quote'],
                            'sort_order' => $quote['sort_order'],
                            'error'      => $quote['error']
                        );
                    }
                }
            }

            $sort_order = array();

            foreach ($quote_data as $key => $value) {
                $sort_order[$key] = $value['sort_order'];
            }

            array_multisort($sort_order, SORT_ASC, $quote_data);

            $this->session->data['shipping_methods'] = $quote_data;
        }

        if (empty($this->session->data['shipping_methods'])) {
            $this->data['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->session->data['shipping_methods'])) {
            $this->data['shipping_methods'] = $this->session->data['shipping_methods'];
        } else {
            $this->data['shipping_methods'] = array();
        }

        if (isset($this->session->data['shipping_method']['code'])) {
            $this->data['code'] = $this->session->data['shipping_method']['code'];
        } else {
            $this->data['code'] = '';
        }

        if (isset($this->session->data['comment'])) {
            $this->data['comment'] = $this->session->data['comment'];
        } else {
            $this->data['comment'] = '';
        }

        // payment method populate

        if (isset($this->session->data['guest'])) {
            $payment_address = $this->session->data['guest']['payment'];
        }
        else
        {
            $payment_address = $this->shippingAddressStub();
        }

        if (!empty($payment_address)) {
            // Totals
            $total_data = array();
            $total = 0;
            $taxes = $this->cart->getTaxes();

            $this->load->model('setting/extension');

            $sort_order = array();

            $results = $this->model_setting_extension->getExtensions('total');

            foreach ($results as $key => $value) {
                $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
            }

            array_multisort($sort_order, SORT_ASC, $results);

            foreach ($results as $result) {
                if ($this->config->get($result['code'] . '_status')) {
                    $this->load->model('total/' . $result['code']);

                    $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
                }
            }

            // Payment Methods
            $method_data = array();

            $this->load->model('setting/extension');

            $results = $this->model_setting_extension->getExtensions('payment');

            foreach ($results as $result) {
                if ($this->config->get($result['code'] . '_status')) {
                    $this->load->model('payment/' . $result['code']);

                    $method = $this->{'model_payment_' . $result['code']}->getMethod($payment_address, $total);

                    if ($method) {
                        $method_data[$result['code']] = $method;
                    }
                }
            }

            $sort_order = array();

            foreach ($method_data as $key => $value) {
                $sort_order[$key] = $value['sort_order'];
            }

            array_multisort($sort_order, SORT_ASC, $method_data);

            $this->session->data['payment_methods'] = $method_data;

        }

        if (empty($this->session->data['payment_methods'])) {
            $this->data['error_warning'] = sprintf($this->language->get('error_no_payment'), $this->url->link('information/contact'));
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->session->data['payment_methods'])) {
            $this->data['payment_methods'] = $this->session->data['payment_methods'];
        } else {
            $this->data['payment_methods'] = array();
        }

        if (isset($this->session->data['payment_method']['code'])) {
            $this->data['code'] = $this->session->data['payment_method']['code'];
        } else {
            $this->data['code'] = '';
        }

        if (isset($this->session->data['comment'])) {
            $this->data['comment'] = $this->session->data['comment'];
        } else {
            $this->data['comment'] = '';
        }

        if ($this->config->get('config_checkout_id')) {
            $this->load->model('catalog/information');

            $information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));

            if ($information_info) {
                $this->data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/info', 'information_id=' . $this->config->get('config_checkout_id'), 'SSL'), $information_info['title'], $information_info['title']);
            } else {
                $this->data['text_agree'] = '';
            }
        } else {
            $this->data['text_agree'] = '';
        }

        if (isset($this->session->data['agree'])) {
            $this->data['agree'] = $this->session->data['agree'];
        } else {
            $this->data['agree'] = '';
        }
		


        // confirm populate

        $this->data['products'] = array();

        foreach ($this->cart->getProducts() as $product) {
            $option_data = array();

            foreach ($product['option'] as $option) {
                if ($option['type'] != 'file') {
                    $value = $option['option_value'];
                } else {
                    $filename = $this->encryption->decrypt($option['option_value']);

                    $value = utf8_substr($filename, 0, utf8_strrpos($filename, '.'));
                }

                $option_data[] = array(
                    'name'  => $option['name'],
                    'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
                );
            }




            $this->data['products'][] = array(
                'product_id' => $product['product_id'],
                'name'       => $product['name'],
                'model'      => $product['model'],
                'option'     => $option_data,
                'quantity'   => $product['quantity'],
                'subtract'   => $product['subtract'],
                'price'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))),
                'total'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']),
                'href'       => $this->url->link('product/product', 'product_id=' . $product['product_id']),


            );
        }

        // Gift Voucher
        $this->data['vouchers'] = array();

        if (!empty($this->session->data['vouchers'])) {
            foreach ($this->session->data['vouchers'] as $voucher) {
                $this->data['vouchers'][] = array(
                    'description' => $voucher['description'],
                    'amount'      => $this->currency->format($voucher['amount'])
                );
            }
        }


        $total_data = array();
        $total = 0;
        $taxes = $this->cart->getTaxes();

        $this->load->model('setting/extension');

        $sort_order = array();

        $results = $this->model_setting_extension->getExtensions('total');

        foreach ($results as $key => $value) {
            $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
        }

        array_multisort($sort_order, SORT_ASC, $results);

        foreach ($results as $result) {
            if ($this->config->get($result['code'] . '_status')) {
                $this->load->model('total/' . $result['code']);

                $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
            }
        }

        $sort_order = array();

        foreach ($total_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $total_data);

        $this->data['totals'] = $total_data;

        $this->data['action_finalize'] = $this->url->link('checkout/checkout/finalize', '', 'SSL');

        // address payment

        if (isset($this->session->data['payment_address_id'])) {
            $this->data['payment_address_id'] = $this->session->data['payment_address_id'];
        } else {
            $this->data['payment_address_id'] = $this->customer->getAddressId();
        }

        if (isset($this->session->data['payment_country_id'])) {
            $this->data['payment_country_id'] = $this->session->data['payment_country_id'];
        } else {
            $this->data['payment_country_id'] = $this->config->get('config_country_id');
        }

        $customer_group_info = $this->model_account_customer_group->getCustomerGroup($this->customer->getCustomerGroupId());

        if ($customer_group_info) {
            $this->data['company_id_display'] = $customer_group_info['company_id_display'];
        } else {
            $this->data['company_id_display'] = '';
        }

        if ($customer_group_info) {
            $this->data['company_id_required'] = $customer_group_info['company_id_required'];
        } else {
            $this->data['company_id_required'] = '';
        }

        if ($customer_group_info) {
            $this->data['tax_id_display'] = $customer_group_info['tax_id_display'];
        } else {
            $this->data['tax_id_display'] = '';
        }

        if ($customer_group_info) {
            $this->data['tax_id_required'] = $customer_group_info['tax_id_required'];
        } else {
            $this->data['tax_id_required'] = '';
        }


        $this->data['addresses'] = $this->model_account_address->getAddresses();

        // shipping

        if (isset($this->session->data['shipping_address_id'])) {
            $this->data['shipping_address_id'] = $this->session->data['shipping_address_id'];
        } else {
            $this->data['shipping_address_id'] = $this->customer->getAddressId();
        }

        $this->data['addresses'] = $this->model_account_address->getAddresses();

        if (isset($this->session->data['shipping_country_id'])) {
            $this->data['shipping_country_id'] = $this->session->data['shipping_country_id'];
        } else {
            $this->data['shipping_country_id'] = $this->config->get('config_country_id');
        }


        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/checkout_logged.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/checkout/checkout_logged.tpl';
        } else {
            $this->template = 'default/template/checkout/checkout_logged.tpl';
        }

        $this->children = array(
            'common/column_left',
            'common/column_right',
            'common/content_top',
            'common/content_bottom',
            'common/footer',
            'common/header'
        );

        $this->response->setOutput($this->render());
    }


    public function getInputNames()
    {

        // start lang
        $this->language->load('checkout/checkout');
        // login lang - box logowania
        $this->data['text_new_customer'] = $this->language->get('text_new_customer');
        $this->data['text_returning_customer'] = $this->language->get('text_returning_customer');
        $this->data['text_checkout'] = $this->language->get('text_checkout');
        $this->data['text_register'] = $this->language->get('text_register');
        $this->data['text_guest'] = $this->language->get('text_guest');
        $this->data['text_i_am_returning_customer'] = $this->language->get('text_i_am_returning_customer');
        $this->data['text_register_account'] = $this->language->get('text_register_account');
        $this->data['text_forgotten'] = $this->language->get('text_forgotten');

        $this->data['entry_email'] = $this->language->get('entry_email');
        $this->data['entry_password'] = $this->language->get('entry_password');

        $this->data['button_login'] = $this->language->get('button_login');


        // geust lang - szczeguły pałtności
        $this->data['text_select'] = $this->language->get('text_select');
        $this->data['text_none'] = $this->language->get('text_none');
        $this->data['text_your_details'] = $this->language->get('text_your_details');
        $this->data['text_your_account'] = $this->language->get('text_your_account');
        $this->data['text_your_address'] = $this->language->get('text_your_address');

        $this->data['entry_firstname'] = $this->language->get('entry_firstname');
        $this->data['entry_lastname'] = $this->language->get('entry_lastname');
        $this->data['entry_email'] = $this->language->get('entry_email');
        $this->data['entry_telephone'] = $this->language->get('entry_telephone');
        $this->data['entry_fax'] = $this->language->get('entry_fax');
        $this->data['entry_company'] = $this->language->get('entry_company');
        $this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
        $this->data['entry_company_id'] = $this->language->get('entry_company_id');
        $this->data['entry_tax_id'] = $this->language->get('entry_tax_id');
        $this->data['entry_address_1'] = $this->language->get('entry_address_1');
        $this->data['entry_address_2'] = $this->language->get('entry_address_2');
        $this->data['entry_postcode'] = $this->language->get('entry_postcode');
        $this->data['entry_city'] = $this->language->get('entry_city');
        $this->data['entry_country'] = $this->language->get('entry_country');
        $this->data['entry_zone'] = $this->language->get('entry_zone');
        $this->data['entry_shipping'] = $this->language->get('entry_shipping');

        //checkout lang
        $this->document->setTitle($this->language->get('heading_title'));
        $this->document->addScript('catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js');
        $this->document->addStyle('catalog/view/javascript/jquery/colorbox/colorbox.css');

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_cart'),
            'href'      => $this->url->link('checkout/cart'),
            'separator' => $this->language->get('text_separator')
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('checkout/checkout', '', 'SSL'),
            'separator' => $this->language->get('text_separator')
        );

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_checkout_option'] = $this->language->get('text_checkout_option');
        $this->data['text_checkout_account'] = $this->language->get('text_checkout_account');
        $this->data['text_checkout_payment_address'] = $this->language->get('text_checkout_payment_address');
        $this->data['text_checkout_shipping_address'] = $this->language->get('text_checkout_shipping_address');
        $this->data['text_checkout_shipping_method'] = $this->language->get('text_checkout_shipping_method');
        $this->data['text_checkout_payment_method'] = $this->language->get('text_checkout_payment_method');
        $this->data['text_checkout_confirm'] = $this->language->get('text_checkout_confirm');
        $this->data['text_modify'] = $this->language->get('text_modify');

        $this->data['logged'] = FALSE;
        $this->data['shipping_required'] = $this->cart->hasShipping();



        // shipping address lang - szczeguły dostawy ( inny adres ) - odpala się tylko na click
        $this->data['text_address_existing'] = $this->language->get('text_address_existing');
        $this->data['text_address_new'] = $this->language->get('text_address_new');





        //
        $this->data['text_order_confirm'] = $this->language->get('text_order_confirm');

        // shipping method lang
        $this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
        $this->data['text_comments'] = $this->language->get('text_comments');

        // payment method alng
        $this->data['text_payment_method'] = $this->language->get('text_payment_method');
        $this->data['text_comments'] = $this->language->get('text_comments');

        // pay address lang
        $this->data['text_address_existing'] = $this->language->get('text_address_existing');
        $this->data['text_address_new'] = $this->language->get('text_address_new');
        $this->data['text_select'] = $this->language->get('text_select');
        $this->data['text_none'] = $this->language->get('text_none');

        $this->data['entry_firstname'] = $this->language->get('entry_firstname');
        $this->data['entry_lastname'] = $this->language->get('entry_lastname');
        $this->data['entry_company'] = $this->language->get('entry_company');
        $this->data['entry_company_id'] = $this->language->get('entry_company_id');
        $this->data['entry_tax_id'] = $this->language->get('entry_tax_id');
        $this->data['entry_address_1'] = $this->language->get('entry_address_1');
        $this->data['entry_address_2'] = $this->language->get('entry_address_2');
        $this->data['entry_postcode'] = $this->language->get('entry_postcode');
        $this->data['entry_city'] = $this->language->get('entry_city');
        $this->data['entry_country'] = $this->language->get('entry_country');
        $this->data['entry_zone'] = $this->language->get('entry_zone');

        // confirm lang
        $this->data['column_name'] = $this->language->get('column_name');
        $this->data['column_model'] = $this->language->get('column_model');
        $this->data['column_quantity'] = $this->language->get('column_quantity');
        $this->data['column_price'] = $this->language->get('column_price');
        $this->data['column_total'] = $this->language->get('column_total');

        // logged address shipping / payment

        $this->data['text_address_existing'] = $this->language->get('text_address_existing');
        $this->data['text_address_new'] = $this->language->get('text_address_new');
        $this->data['text_select'] = $this->language->get('text_select');



    }

    private function shippingAddressStub(){

       $data = array(
           'country_id' => 1,
           'zone_id' => 1,
           'postcode' => 00000,
       );

        return $data;

    }

    public function finalize()
    {

    }

    public function validate()
    {
        $this->language->load('checkout/checkout');

        $json = array();
        // Validate cart has products and has stock.
        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            $json['redirect'] = $this->url->link('checkout/cart');
        }


        if(!$json)
        {

            if (isset($this->request->post['auto_account'])) {
                $this->session->data['auto_account'] = $this->request->post['auto_account'];
            }
            // payemnt validation
            if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
                $json['error']['firstname'] = $this->language->get('error_firstname');
            }

            if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
                $json['error']['lastname'] = $this->language->get('error_lastname');
            }



            if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
                $json['error']['email'] = $this->language->get('error_email');
            }

            if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
                $json['error']['telephone'] = $this->language->get('error_telephone');
            }

            // Customer Group
            $this->load->model('account/customer_group');

            if (isset($this->request->post['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->post['customer_group_id'], $this->config->get('config_customer_group_display'))) {
                $customer_group_id = $this->request->post['customer_group_id'];
            } else {
                $customer_group_id = $this->config->get('config_customer_group_id');
            }

            $customer_group = $this->model_account_customer_group->getCustomerGroup($customer_group_id);

            if ($customer_group) {
                // Company ID
                if ($customer_group['company_id_display'] && $customer_group['company_id_required'] && empty($this->request->post['company_id'])) {
                    $json['error']['company_id'] = $this->language->get('error_company_id');
                }

                // Tax ID
                if ($customer_group['tax_id_display'] && $customer_group['tax_id_required'] && empty($this->request->post['tax_id'])) {
                    $json['error']['tax_id'] = $this->language->get('error_tax_id');
                }
            }

            if ((utf8_strlen($this->request->post['address_1']) < 3) || (utf8_strlen($this->request->post['address_1']) > 128)) {
                $json['error']['address_1'] = $this->language->get('error_address_1');
            }

            if ((utf8_strlen($this->request->post['city']) < 2) || (utf8_strlen($this->request->post['city']) > 128)) {
                $json['error']['city'] = $this->language->get('error_city');
            }

            $this->load->model('localisation/country');

            $country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

            if ($country_info) {
                if ($country_info['postcode_required'] && (utf8_strlen($this->request->post['postcode']) < 2) || (utf8_strlen($this->request->post['postcode']) > 10)) {
                    $json['error']['postcode'] = $this->language->get('error_postcode');
                }

                // VAT Validation
                $this->load->helper('vat');

                if ($this->config->get('config_vat') && $this->request->post['tax_id'] && (vat_validation($country_info['iso_code_2'], $this->request->post['tax_id']) == 'invalid')) {
                    $json['error']['tax_id'] = $this->language->get('error_vat');
                }
            }

            if ($this->request->post['country_id'] == '') {
                $json['error']['country'] = $this->language->get('error_country');
            }

            if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '') {
               // $json['error']['zone'] = $this->language->get('error_zone');
            }

            // guest shipping validate
            if(!$this->request->post['shipping_address']){


            if ((utf8_strlen($this->request->post['shipping_firstname']) < 1) || (utf8_strlen($this->request->post['shipping_firstname']) > 32)) {
                $json['error']['shipping_firstname'] = $this->language->get('error_firstname');
            }

            if ((utf8_strlen($this->request->post['shipping_lastname']) < 1) || (utf8_strlen($this->request->post['shipping_lastname']) > 32)) {
                $json['error']['shipping_lastname'] = $this->language->get('error_lastname');
            }

            if ((utf8_strlen($this->request->post['shipping_address_1']) < 3) || (utf8_strlen($this->request->post['shipping_address_1']) > 128)) {
                $json['error']['shipping_address_1'] = $this->language->get('error_address_1');
            }

            if ((utf8_strlen($this->request->post['shipping_city']) < 2) || (utf8_strlen($this->request->post['shipping_city']) > 128)) {
                $json['error']['shipping_city'] = $this->language->get('error_city');
            }

            $this->load->model('localisation/country');

            $country_info = $this->model_localisation_country->getCountry($this->request->post['shipping_country_id']);

            if ($country_info && $country_info['postcode_required'] && (utf8_strlen($this->request->post['shipping_postcode']) < 2) || (utf8_strlen($this->request->post['shipping_postcode']) > 10)) {
                $json['error']['shipping_postcode'] = $this->language->get('error_postcode');
            }

            if ($this->request->post['shipping_country_id'] == '') {
                $json['error']['shipping_country'] = $this->language->get('error_country');
            }

            if (!isset($this->request->post['shipping_zone_id']) || $this->request->post['shipping_zone_id'] == '') {
               // $json['error']['shipping_zone'] = $this->language->get('error_zone');
            }

            }
            // end shipping



            // payment method
            if (!isset($this->request->post['payment_method'])) {
                $json['error']['payment'] = $this->language->get('error_payment');
            } elseif (!isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
                $json['error']['payment'] = $this->language->get('error_payment');
            }

            if ($this->config->get('config_checkout_id')) {
                $this->load->model('catalog/information');

                $information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));

                if ($information_info && !isset($this->request->post['agree'])) {
                    $json['error']['agree'] = sprintf($this->language->get('error_agree'), $information_info['title']);
                }

            }
            //

            // shipping method

            if (!isset($this->request->post['shipping_method'])) {
                $json['error']['shipping'] = $this->language->get('error_shipping');
            } else {
                $shipping = explode('.', $this->request->post['shipping_method']);

                if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
                    $json['error']['shipping'] = $this->language->get('error_shipping');
                }
            }

           // if everything OK
            // @ToDo treba to robić bo teraz jeśli jest jakikolwiek błąd to reszta zmian nawet dobrych się nie zapisze
           if(!$json){
               // payment
               $this->session->data['guest']['customer_group_id'] = $customer_group_id;
               $this->session->data['guest']['firstname'] = $this->request->post['firstname'];
               $this->session->data['guest']['lastname'] = $this->request->post['lastname'];
               $this->session->data['guest']['email'] = $this->request->post['email'];
               $this->session->data['guest']['telephone'] = $this->request->post['telephone'];
               $this->session->data['guest']['fax'] = $this->request->post['fax'];

               $this->session->data['guest']['payment']['firstname'] = $this->request->post['firstname'];
               $this->session->data['guest']['payment']['lastname'] = $this->request->post['lastname'];
               $this->session->data['guest']['payment']['company'] = $this->request->post['company'];
               $this->session->data['guest']['payment']['company_id'] = $this->request->post['company_id'];
               $this->session->data['guest']['payment']['tax_id'] = $this->request->post['tax_id'];
               $this->session->data['guest']['payment']['address_1'] = $this->request->post['address_1'];
               $this->session->data['guest']['payment']['address_2'] = $this->request->post['address_2'];
               $this->session->data['guest']['payment']['postcode'] = $this->request->post['postcode'];
               $this->session->data['guest']['payment']['city'] = $this->request->post['city'];
               $this->session->data['guest']['payment']['country_id'] = $this->request->post['country_id'];
               $this->session->data['guest']['payment']['zone_id'] = $this->request->post['zone_id'];

               $this->load->model('localisation/country');

               $country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

               if ($country_info) {
                   $this->session->data['guest']['payment']['country'] = $country_info['name'];
                   $this->session->data['guest']['payment']['iso_code_2'] = $country_info['iso_code_2'];
                   $this->session->data['guest']['payment']['iso_code_3'] = $country_info['iso_code_3'];
                   $this->session->data['guest']['payment']['address_format'] = $country_info['address_format'];
               } else {
                   $this->session->data['guest']['payment']['country'] = '';
                   $this->session->data['guest']['payment']['iso_code_2'] = '';
                   $this->session->data['guest']['payment']['iso_code_3'] = '';
                   $this->session->data['guest']['payment']['address_format'] = '';
               }

               $this->load->model('localisation/zone');

               $zone_info = $this->model_localisation_zone->getZone($this->request->post['zone_id']);

               if ($zone_info) {
                   $this->session->data['guest']['payment']['zone'] = $zone_info['name'];
                   $this->session->data['guest']['payment']['zone_code'] = $zone_info['code'];
               } else {
                   $this->session->data['guest']['payment']['zone'] = '';
                   $this->session->data['guest']['payment']['zone_code'] = '';
               }

               if (!empty($this->request->post['shipping_address'])) {
                   $this->session->data['guest']['shipping_address'] = true;
               } else {
                   $this->session->data['guest']['shipping_address'] = false;
               }

               // shipping
               $this->session->data['guest']['shipping']['firstname'] = trim($this->request->post['shipping_firstname']);
               $this->session->data['guest']['shipping']['lastname'] = trim($this->request->post['shipping_lastname']);
               $this->session->data['guest']['shipping']['company'] = trim($this->request->post['shipping_company']);
               $this->session->data['guest']['shipping']['address_1'] = $this->request->post['shipping_address_1'];
               $this->session->data['guest']['shipping']['address_2'] = $this->request->post['shipping_address_2'];
               $this->session->data['guest']['shipping']['postcode'] = $this->request->post['shipping_postcode'];
               $this->session->data['guest']['shipping']['city'] = $this->request->post['shipping_city'];
               $this->session->data['guest']['shipping']['country_id'] = $this->request->post['shipping_country_id'];
               $this->session->data['guest']['shipping']['zone_id'] = $this->request->post['shipping_zone_id'];

               $this->load->model('localisation/country');

               $country_info = $this->model_localisation_country->getCountry($this->request->post['shipping_country_id']);

               if ($country_info) {
                   $this->session->data['guest']['shipping']['country'] = $country_info['name'];
                   $this->session->data['guest']['shipping']['iso_code_2'] = $country_info['iso_code_2'];
                   $this->session->data['guest']['shipping']['iso_code_3'] = $country_info['iso_code_3'];
                   $this->session->data['guest']['shipping']['address_format'] = $country_info['address_format'];
               } else {
                   $this->session->data['guest']['shipping']['country'] = '';
                   $this->session->data['guest']['shipping']['iso_code_2'] = '';
                   $this->session->data['guest']['shipping']['iso_code_3'] = '';
                   $this->session->data['guest']['shipping']['address_format'] = '';
               }

               $this->load->model('localisation/zone');

               $zone_info = $this->model_localisation_zone->getZone($this->request->post['shipping_zone_id']);

               if ($zone_info) {
                   $this->session->data['guest']['shipping']['zone'] = $zone_info['name'];
                   $this->session->data['guest']['shipping']['zone_code'] = $zone_info['code'];
               } else {
                   $this->session->data['guest']['shipping']['zone'] = '';
                   $this->session->data['guest']['shipping']['zone_code'] = '';
               }

               $this->session->data['shipping_country_id'] = $this->request->post['shipping_country_id'];
               $this->session->data['shipping_zone_id'] = $this->request->post['shipping_zone_id'];
               $this->session->data['shipping_postcode'] = $this->request->post['shipping_postcode'];

               // end shiping

               // shipping method

               $shipping = explode('.', $this->request->post['shipping_method']);

               $this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];

               $this->session->data['comment'] = strip_tags($this->request->post['comment']);

               // edn ship method

               // pay method
               $this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];

               $this->session->data['comment'] = strip_tags($this->request->post['comment']);
               // end pay method

               // jesli zanaczono ze adres płatności jest taki sma jak wysyłki
               if(isset($this->request->post['shipping_address']))
               {
                   $this->session->data['guest']['shipping'] = $this->session->data['guest']['payment'];
               }


               // write order to DB
              $result = $this->finalStep();
              if($result)
              {
                  $json['redirect'] = $result;
              }

           }

            $this->response->setOutput(json_encode($json));

        }
    }

    public function loggedValidate()
    {

        // @todo wymienić na wersje z nowego opencarta ...
        $this->language->load('checkout/checkout');

        $this->load->model('account/address');

        error_reporting(E_ALL);
        ini_set('display_errors', '1');

        $json = array();

        if (!$this->customer->isLogged()) {
            $json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
        }

        if (!$this->cart->hasShipping()) {
            $json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
        }

        if ((!$this->cart->hasProducts() && (!isset($this->session->data['vouchers']) || !$this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            $json['redirect'] = $this->url->link('checkout/cart');
        }

        // shipping and payemnt address validate
        if (!$json) {


            // Customer Group
            $this->load->model('account/customer_group');

            if (isset($this->request->post['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->post['customer_group_id'], $this->config->get('config_customer_group_display'))) {
                $customer_group_id = $this->request->post['customer_group_id'];
            } else {
                $customer_group_id = $this->config->get('config_customer_group_id');
            }

            $customer_group = $this->model_account_customer_group->getCustomerGroup($customer_group_id);

            if ($customer_group) {
                // Company ID
                if ($customer_group['company_id_display'] && $customer_group['company_id_required'] && empty($this->request->post['company_id'])) {
                    $json['error']['company_id'] = $this->language->get('error_company_id');
                }

                // Tax ID
                if ($customer_group['tax_id_display'] && $customer_group['tax_id_required'] && empty($this->request->post['tax_id'])) {
                    $json['error']['tax_id'] = $this->language->get('error_tax_id');
                }
            }

            // payment method
            if (!isset($this->request->post['payment_method'])) {
                $json['error']['payment'] = $this->language->get('error_payment');
            } elseif (!isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
                $json['error']['payment'] = $this->language->get('error_payment');
            }

            if ($this->config->get('config_checkout_id')) {
                $this->load->model('catalog/information');

                $information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));

                if ($information_info && !isset($this->request->post['agree'])) {
                    $json['error']['agree'] = sprintf($this->language->get('error_agree'), $information_info['title']);
                }
				

            }
            //

            // shipping method

            if (!isset($this->request->post['shipping_method'])) {
                $json['error']['shipping'] = $this->language->get('error_shipping');
            } else {
                $shipping = explode('.', $this->request->post['shipping_method']);

                if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
                    $json['error']['shipping'] = $this->language->get('error_shipping');
                }
            }



        }
        // koniec walidacji

        if(!$json)
        {
            if ($this->request->post['shipping_address'] == 'existing') {
                if (!isset($this->request->post['shipping_address_id'])) {
                    $json['error']['warning'] = $this->language->get('error_address');
                }

                if (!$json) {
                    $this->session->data['shipping_address_id'] = $this->request->post['shipping_address_id'];

                    $address_info = $this->model_account_address->getAddress($this->request->post['shipping_address_id']);

                    if ($address_info) {
                        //    $this->tax->setZone($address_info['country_id'], $address_info['zone_id']);
                    }


                }
            }




            if ($this->request->post['shipping_address'] == 'new') {



                if ((strlen(utf8_decode($this->request->post['shipping_firstname'])) < 1) || (strlen(utf8_decode($this->request->post['shipping_firstname'])) > 32)) {
                    $json['error']['shipping_firstname'] = $this->language->get('error_firstname');
                }

                if ((strlen(utf8_decode($this->request->post['shipping_lastname'])) < 1) || (strlen(utf8_decode($this->request->post['shipping_lastname'])) > 32)) {
                    $json['error']['shipping_lastname'] = $this->language->get('error_lastname');
                }

                if ((strlen(utf8_decode($this->request->post['shipping_address_1'])) < 3) || (strlen(utf8_decode($this->request->post['shipping_address_1'])) > 64)) {
                    $json['error']['shipping_address_1'] = $this->language->get('error_address_1');
                }

                if ((strlen(utf8_decode($this->request->post['shipping_city'])) < 2) || (strlen(utf8_decode($this->request->post['shipping_city'])) > 128)) {
                    $json['error']['shipping_city'] = $this->language->get('error_city');
                }

                $this->load->model('localisation/country');

                $country_info = $this->model_localisation_country->getCountry($this->request->post['shipping_country_id']);

                if ($country_info && $country_info['postcode_required'] && (strlen(utf8_decode($this->request->post['shipping_postcode'])) < 2) || (strlen(utf8_decode($this->request->post['shipping_postcode'])) > 10)) {
                    $json['error']['shipping_postcode'] = $this->language->get('error_postcode');
                }

                if ($this->request->post['shipping_country_id'] == '') {
                    $json['error']['shipping_country'] = $this->language->get('error_country');
                }

                if ($this->request->post['shipping_zone_id'] == '') {
                    $json['error']['shipping_zone'] = $this->language->get('error_zone');
                }

                if (!$json) {

                    // rewrite data
                    $data = array(
                        'firstname' => $this->request->post['shipping_firstname'],
                        'lastname' => $this->request->post['shipping_lastname'],
                        'address_1' => $this->request->post['shipping_address_1'],
                        'address_2' => $this->request->post['shipping_address_2'],
                        'company' => $this->request->post['shipping_company'],
                        'city' => $this->request->post['shipping_city'],
                        'postcode' => $this->request->post['shipping_postcode'],
                        'country_id' => $this->request->post['shipping_country_id'],
                        'zone_id' => $this->request->post['shipping_zone_id'],
                    );

                    $this->session->data['shipping_address_id'] = $this->model_account_address->addAddress($data);

                    if ($this->cart->hasShipping()) {
                        //    $this->tax->setZone($this->request->post['shipping_country_id'], $this->request->post['shipping_zone_id']);
                    }

                }
            }


            if ($this->request->post['payment_address'] == 'existing') {
                if (!isset($this->request->post['payment_address_id'])) {
                    $json['error']['warning'] = $this->language->get('error_address');
                }

                if (!$json) {
                    $this->session->data['payment_address_id'] = $this->request->post['payment_address_id'];

                    $address_info = $this->model_account_address->getAddress($this->request->post['payment_address_id']);

                    if ($address_info) {
                        //   $this->tax->setZone($address_info['country_id'], $address_info['zone_id']);
                    }


                }
            }


            if ($this->request->post['payment_address'] == 'new') {
                if ((strlen(utf8_decode($this->request->post['payment_firstname'])) < 1) || (strlen(utf8_decode($this->request->post['payment_firstname'])) > 32)) {
                    $json['error']['payment_firstname'] = $this->language->get('error_firstname');
                }

                if ((strlen(utf8_decode($this->request->post['payment_lastname'])) < 1) || (strlen(utf8_decode($this->request->post['payment_lastname'])) > 32)) {
                    $json['error']['payment_lastname'] = $this->language->get('error_lastname');
                }

                if ((strlen(utf8_decode($this->request->post['payment_address_1'])) < 3) || (strlen(utf8_decode($this->request->post['payment_address_1'])) > 64)) {
                    $json['error']['payment_address_1'] = $this->language->get('error_address_1');
                }

                if ((strlen(utf8_decode($this->request->post['payment_city'])) < 2) || (strlen(utf8_decode($this->request->post['payment_city'])) > 128)) {
                    $json['error']['payment_city'] = $this->language->get('error_city');
                }

                $this->load->model('localisation/country');

                $country_info = $this->model_localisation_country->getCountry($this->request->post['payment_country_id']);

                if ($country_info && $country_info['postcode_required'] && (strlen(utf8_decode($this->request->post['payment_postcode'])) < 2) || (strlen(utf8_decode($this->request->post['payment_postcode'])) > 10)) {
                    $json['error']['payment_postcode'] = $this->language->get('error_postcode');
                }

                if ($this->request->post['payment_country_id'] == '') {
                    $json['error']['payment_country'] = $this->language->get('error_country');
                }

                if ($this->request->post['payment_zone_id'] == '') {
                    $json['error']['payment_zone'] = $this->language->get('error_zone');
                }

                if (!$json) {

                    // rewrite data
                    $data = array(
                        'firstname' => $this->request->post['payment_firstname'],
                        'lastname' => $this->request->post['payment_lastname'],
                        'address_1' => $this->request->post['payment_address_1'],
                        'address_2' => $this->request->post['payment_address_2'],
                        'company' => $this->request->post['payment_company'],
                        'city' => $this->request->post['payment_city'],
                        'postcode' => $this->request->post['payment_postcode'],
                        'country_id' => $this->request->post['payment_country_id'],
                        'zone_id' => $this->request->post['payment_zone_id'],
                    );

                    $this->session->data['payment_address_id'] = $this->model_account_address->addAddress($data);

                    if ($this->cart->hasShipping()) {
                        //     $this->tax->setZone($this->request->post['payment_country_id'], $this->request->post['payment_zone_id']);
                    }

                }
            }
        }


        if(!$json)
        {






                $shipping = explode('.', $this->request->post['shipping_method']);

                $this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];

                $this->session->data['comment'] = strip_tags($this->request->post['comment']);

                // edn ship method

                // pay method
                $this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];

                $this->session->data['comment'] = strip_tags($this->request->post['comment']);
                // end pay method


                // write order to DB
                $result = $this->finalStep();
                if($result)
                {
                    $json['redirect'] = $result;
                }





        }

        $this->response->setOutput(json_encode($json));
    }

    public function getPayment()
    {
        $json = array();
        if(isset($this->session->data['payment_method']['code']))
        {


             if(!file_exists(DIR_APPLICATION.'controller/payment/'.$this->session->data['payment_method']['code'].'.php'))
             {
                 $json['error']['payment_file'] = $this->language->get('error_payment_file');
             }
        }
        else
        {
            $json['error']['payment'] = $this->language->get('error_payment');

        }

        if(!$json)
        {

            $json['payment'] = $this->getChild('payment/' . $this->session->data['payment_method']['code']);
        }


        $this->response->setOutput(json_encode($json));
    }



    private function finalStep(){

        $redirect = '';

        if ($this->cart->hasShipping()) {
            // Validate if shipping address has been set.
            $this->load->model('account/address');

            if ($this->customer->isLogged() && isset($this->session->data['shipping_address_id'])) {
                $shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);
            } elseif (isset($this->session->data['guest'])) {
                $shipping_address = $this->session->data['guest']['shipping'];
            }

            if (empty($shipping_address)) {
                $redirect = $this->url->link('checkout/checkout', '', 'SSL');
            }

            // Validate if shipping method has been set.
            if (!isset($this->session->data['shipping_method'])) {
                $redirect = $this->url->link('checkout/checkout', '', 'SSL');
            }
        } else {
            unset($this->session->data['shipping_method']);
            unset($this->session->data['shipping_methods']);
        }

        // Validate if payment address has been set.
        $this->load->model('account/address');

        if ($this->customer->isLogged() && isset($this->session->data['payment_address_id'])) {
            $payment_address = $this->model_account_address->getAddress($this->session->data['payment_address_id']);
        } elseif (isset($this->session->data['guest'])) {
            $payment_address = $this->session->data['guest']['payment'];
        }

        if (empty($payment_address)) {
            $redirect = $this->url->link('checkout/checkout', '', 'SSL');
        }

        // Validate if payment method has been set.
        if (!isset($this->session->data['payment_method'])) {
            $redirect = $this->url->link('checkout/checkout', '', 'SSL');
        }

        // Validate cart has products and has stock.
        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            $redirect = $this->url->link('checkout/cart');
        }

        // Validate minimum quantity requirments.
        $products = $this->cart->getProducts();

        foreach ($products as $product) {
            $product_total = 0;

            foreach ($products as $product_2) {
                if ($product_2['product_id'] == $product['product_id']) {
                    $product_total += $product_2['quantity'];
                }
            }

            if ($product['minimum'] > $product_total) {
                $redirect = $this->url->link('checkout/cart');

                break;
            }
        }

        if(!$redirect)
        {
            $total_data = array();
            $total = 0;
            $taxes = $this->cart->getTaxes();

            $this->load->model('setting/extension');

            $sort_order = array();

            $results = $this->model_setting_extension->getExtensions('total');

            foreach ($results as $key => $value) {
                $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
            }

            array_multisort($sort_order, SORT_ASC, $results);

            foreach ($results as $result) {
                if ($this->config->get($result['code'] . '_status')) {
                    $this->load->model('total/' . $result['code']);

                    $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
                }
            }

            $sort_order = array();

            foreach ($total_data as $key => $value) {
                $sort_order[$key] = $value['sort_order'];
            }

            array_multisort($sort_order, SORT_ASC, $total_data);

            $this->language->load('checkout/checkout');

            $data = array();

            $data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
            $data['store_id'] = $this->config->get('config_store_id');
            $data['store_name'] = $this->config->get('config_name');

            if ($data['store_id']) {
                $data['store_url'] = $this->config->get('config_url');
            } else {
                $data['store_url'] = HTTP_SERVER;
            }

            if ($this->customer->isLogged()) {
                $data['customer_id'] = $this->customer->getId();
                $data['customer_group_id'] = $this->customer->getCustomerGroupId();
                $data['firstname'] = $this->customer->getFirstName();
                $data['lastname'] = $this->customer->getLastName();
                $data['email'] = $this->customer->getEmail();
                $data['telephone'] = $this->customer->getTelephone();
                $data['fax'] = $this->customer->getFax();

                $this->load->model('account/address');

                $payment_address = $this->model_account_address->getAddress($this->session->data['payment_address_id']);
            } elseif (isset($this->session->data['guest'])) {
                $data['customer_id'] = 0;
                $data['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
                $data['firstname'] = $this->session->data['guest']['firstname'];
                $data['lastname'] = $this->session->data['guest']['lastname'];
                $data['email'] = $this->session->data['guest']['email'];
                $data['telephone'] = $this->session->data['guest']['telephone'];
                $data['fax'] = $this->session->data['guest']['fax'];

                $payment_address = $this->session->data['guest']['payment'];
            }

            $data['payment_firstname'] = $payment_address['firstname'];
            $data['payment_lastname'] = $payment_address['lastname'];
            $data['payment_company'] = $payment_address['company'];
            $data['payment_company_id'] = $payment_address['company_id'];
            $data['payment_tax_id'] = $payment_address['tax_id'];
            $data['payment_address_1'] = $payment_address['address_1'];
            $data['payment_address_2'] = $payment_address['address_2'];
            $data['payment_city'] = $payment_address['city'];
            $data['payment_postcode'] = $payment_address['postcode'];
            $data['payment_zone'] = $payment_address['zone'];
            $data['payment_zone_id'] = $payment_address['zone_id'];
            $data['payment_country'] = $payment_address['country'];
            $data['payment_country_id'] = $payment_address['country_id'];
            $data['payment_address_format'] = $payment_address['address_format'];

            if (isset($this->session->data['payment_method']['title'])) {
                $data['payment_method'] = $this->session->data['payment_method']['title'];
            } else {
                $data['payment_method'] = '';
            }

            if (isset($this->session->data['payment_method']['code'])) {
                $data['payment_code'] = $this->session->data['payment_method']['code'];
            } else {
                $data['payment_code'] = '';
            }

            if ($this->cart->hasShipping()) {
                if ($this->customer->isLogged()) {
                    $this->load->model('account/address');

                    $shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);
                } elseif (isset($this->session->data['guest'])) {
                    $shipping_address = $this->session->data['guest']['shipping'];
                }

                $data['shipping_firstname'] = $shipping_address['firstname'];
                $data['shipping_lastname'] = $shipping_address['lastname'];
                $data['shipping_company'] = $shipping_address['company'];
                $data['shipping_address_1'] = $shipping_address['address_1'];
                $data['shipping_address_2'] = $shipping_address['address_2'];
                $data['shipping_city'] = $shipping_address['city'];
                $data['shipping_postcode'] = $shipping_address['postcode'];
                $data['shipping_zone'] = $shipping_address['zone'];
                $data['shipping_zone_id'] = $shipping_address['zone_id'];
                $data['shipping_country'] = $shipping_address['country'];
                $data['shipping_country_id'] = $shipping_address['country_id'];
                $data['shipping_address_format'] = $shipping_address['address_format'];

                if (isset($this->session->data['shipping_method']['title'])) {
                    $data['shipping_method'] = $this->session->data['shipping_method']['title'];
                } else {
                    $data['shipping_method'] = '';
                }

                if (isset($this->session->data['shipping_method']['code'])) {
                    $data['shipping_code'] = $this->session->data['shipping_method']['code'];
                } else {
                    $data['shipping_code'] = '';
                }
            } else {
                $data['shipping_firstname'] = '';
                $data['shipping_lastname'] = '';
                $data['shipping_company'] = '';
                $data['shipping_address_1'] = '';
                $data['shipping_address_2'] = '';
                $data['shipping_city'] = '';
                $data['shipping_postcode'] = '';
                $data['shipping_zone'] = '';
                $data['shipping_zone_id'] = '';
                $data['shipping_country'] = '';
                $data['shipping_country_id'] = '';
                $data['shipping_address_format'] = '';
                $data['shipping_method'] = '';
                $data['shipping_code'] = '';
            }

            $product_data = array();

            foreach ($this->cart->getProducts() as $product) {
                $option_data = array();

                foreach ($product['option'] as $option) {
                    if ($option['type'] != 'file') {
                        $value = $option['option_value'];
                    } else {
                        $value = $this->encryption->decrypt($option['option_value']);
                    }

                    $option_data[] = array(
                        'product_option_id'       => $option['product_option_id'],
                        'product_option_value_id' => $option['product_option_value_id'],
                        'option_id'               => $option['option_id'],
                        'option_value_id'         => $option['option_value_id'],
                        'name'                    => $option['name'],
                        'value'                   => $value,
                        'type'                    => $option['type']
                    );
                }

                $kaucje = array(
                    'zw' => 'zwrotna',
                    'bzw' => 'bezzwrotna',
                    0 => false,
                );


                $product_data[] = array(
                    'product_id' => $product['product_id'],
                    'name'       => $product['name'],
                    'model'      => $product['model'],
                    'option'     => $option_data,
                    'download'   => $product['download'],
                    'quantity'   => $product['quantity'],
                    'subtract'   => $product['subtract'],
                    'price'      => $product['price'],
                    'total'      => $product['total'],
                    'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
                    'reward'     => $product['reward'],

                );
            }

            // Gift Voucher
            $voucher_data = array();

            if (!empty($this->session->data['vouchers'])) {
                foreach ($this->session->data['vouchers'] as $voucher) {
                    $voucher_data[] = array(
                        'description'      => $voucher['description'],
                        'code'             => substr(md5(mt_rand()), 0, 10),
                        'to_name'          => $voucher['to_name'],
                        'to_email'         => $voucher['to_email'],
                        'from_name'        => $voucher['from_name'],
                        'from_email'       => $voucher['from_email'],
                        'voucher_theme_id' => $voucher['voucher_theme_id'],
                        'message'          => $voucher['message'],
                        'amount'           => $voucher['amount']
                    );
                }
            }

            $data['products'] = $product_data;
            $data['vouchers'] = $voucher_data;
            $data['totals'] = $total_data;
            $data['comment'] = $this->session->data['comment'];
            $data['total'] = $total;

            if (isset($this->request->cookie['tracking'])) {
                $this->load->model('affiliate/affiliate');

                $affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);
                $subtotal = $this->cart->getSubTotal();

                if ($affiliate_info) {
                    $data['affiliate_id'] = $affiliate_info['affiliate_id'];
                    $data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
                } else {
                    $data['affiliate_id'] = 0;
                    $data['commission'] = 0;
                }
            } else {
                $data['affiliate_id'] = 0;
                $data['commission'] = 0;
            }

            $data['language_id'] = $this->config->get('config_language_id');
            $data['currency_id'] = $this->currency->getId();
            $data['currency_code'] = $this->currency->getCode();
            $data['currency_value'] = $this->currency->getValue($this->currency->getCode());
            $data['ip'] = $this->request->server['REMOTE_ADDR'];

            if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
                $data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
            } elseif(!empty($this->request->server['HTTP_CLIENT_IP'])) {
                $data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
            } else {
                $data['forwarded_ip'] = '';
            }

            if (isset($this->request->server['HTTP_USER_AGENT'])) {
                $data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
            } else {
                $data['user_agent'] = '';
            }

            if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
                $data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
            } else {
                $data['accept_language'] = '';
            }

            $this->load->model('checkout/order');

            $this->session->data['order_id'] = $this->model_checkout_order->addOrder($data);

            $f = function(){
                $p = '';

                $chars = 'zxcvbnmasdfghjklqwertyuiop1234567890ZXCVBNMASDFGHJKLQWERTYUIOP';

                for($i=0;$i<8;$i++)
                {
                    $p .= $chars[rand(0,strlen($chars)-1)];
                }

                return $p;
            };

            //zapisanie konta usera
            if(isset($this->session->data['auto_account']))
            {
                $uData = array(
                    'store_id' => $this->config->get('config_store_id'),
                    'firstname' => $data['payment_firstname'],
                    'lastname' => $data['payment_lastname'],
                    'email' => $data['email'],
                    'telephone' => $data['telephone'],
                    'fax' => $data['fax'],
                    'password' => $f(),
                    'company' => $data['payment_company'],
                    'company_id' => $data['payment_company_id'],
                    'tax_id' => $data['payment_tax_id'],
                    'address_1' => $data['payment_address_1'],
                    'address_2' => $data['payment_address_2'],
                    'city' => $data['payment_city'],
                    'postcode' => $data['payment_postcode'],
                    'country_id' => $data['payment_country_id'],
                    'zone_id' => $data['payment_zone_id'],
                    'show_cred_email' => '1',

                );

                $this->load->model('account/customer');

                $this->model_account_customer->addCustomer($uData);

            }

            return false;
        }
        else
        {
             return $redirect;


        }
    }

    public function reloadTotals()
    {


        $shipping = explode('.', $this->request->post['shipping_method']);

        $this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];

        $json= array();
        $total_data = array();
        $total = 0;
        $taxes = $this->cart->getTaxes();

        $this->load->model('setting/extension');

        $sort_order = array();

        $results = $this->model_setting_extension->getExtensions('total');

        foreach ($results as $key => $value) {
            $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
        }

        array_multisort($sort_order, SORT_ASC, $results);

        foreach ($results as $result) {
            if ($this->config->get($result['code'] . '_status')) {
                $this->load->model('total/' . $result['code']);

                $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
            }
        }

        $sort_order = array();

        foreach ($total_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $total_data);

        $json['totals'] = $total_data;

        $this->response->setOutput(json_encode($json));
    }

    public function zone() {
        $output = '<option value="">' . $this->language->get('text_select') . '</option>';

        $this->load->model('localisation/zone');

        $results = $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']);

        foreach ($results as $result) {
            $output .= '<option value="' . $result['zone_id'] . '"';

            if (isset($this->request->get['zone_id']) && ($this->request->get['zone_id'] == $result['zone_id'])) {
                $output .= ' selected="selected"';
            }

            $output .= '>' . $result['name'] . '</option>';
        }

        if (!$results) {
            $output .= '<option value="0">' . $this->language->get('text_none') . '</option>';
        }

        $this->response->setOutput($output);
    }

}
?>