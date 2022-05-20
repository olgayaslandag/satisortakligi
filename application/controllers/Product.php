<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Automattic\WooCommerce\Client;


class Product extends CI_Controller{
	
	public function __construct(){

		parent::__construct();
		//$this->load->model('Customer_Model');
		date_default_timezone_set('Europe/Istanbul');


		$this->output->set_content_type('application/json');
	    $this->output->set_header('Access-Control-Allow-Origin: *');
	    $this->output->set_header('Access-Control-Allow-Methods: GET, OPTIONS');
	    $this->output->set_header('Access-Control-Allow-Methods: Content-Type, Content-Length, Accept-Encoding');
	    $this->json = (array)json_decode(file_get_contents('php://input'));
		
	}

	function products(){

		$woocommerce = new Client(
		    'https://elvanindunyasi.com.tr', 
		    'ck_e6470ee12065669b86c62cc8cf0bfa2ea8c0271b', 
		    'cs_9487f97846de01cde38abd98714dc266854882b3',
		    [
		        'version' => 'wc/v3',
		    ]
		);

		$products = $woocommerce->get('products');
		$x=0;
		foreach($products as $product){
			unset($products[$x]->description);
			unset($products[$x]->short_description);
			unset($products[$x]->context);
			unset($products[$x]->meta_data);
			unset($products[$x]->price_html);
			//unset($products[$x]->short_description);
			$products[$x]->variant = $woocommerce->get('products/'.$product->id.'/variations') ? $woocommerce->get('products/'.$product->id.'/variations') : null;
			$x++;
		}

		echo json_encode($products);

	}

}