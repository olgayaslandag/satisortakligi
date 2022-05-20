<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Automattic\WooCommerce\Client;


class Affiliate extends CI_Controller{
	
	public $json;

	public function __construct(){

		parent::__construct();
		$this->load->model('Affiliate_Model');
		date_default_timezone_set('Europe/Istanbul');


		$this->output->set_content_type('application/json');
	    $this->output->set_header('Access-Control-Allow-Origin: *');
	    $this->output->set_header('Access-Control-Allow-Methods: GET, OPTIONS');
	    $this->output->set_header('Access-Control-Allow-Methods: Content-Type, Content-Length, Accept-Encoding');
	    $this->json = (array)json_decode(file_get_contents('php://input'));
		
	}

	function affiliates(){

		$affiliates = $this->Affiliate_Model->affiliates();
		if($affiliates){

			echo json_encode([
				"status" => true,
				"message"=> "",
				"result" => $affiliates
			]);

		} else {

			echo json_encode([
				"status" => false,
				"message"=> "",
				"result" => null
			]);

		}

	}

	public function getChild($id){

		$id = $this->json['user_id'];

		$affiliates = $this->Affiliate_Model->getAffiliates($id);
		$s = $this->Affiliate_Model->getAffiliateUser(["affiliate_id" => $id]);
		if($s){
			$aff[] = $s[0];
		}
		foreach($affiliates as $affiliate){
			$veri = $this->Affiliate_Model->getAffiliteUserId(["user_id" => $affiliate->user_id]);
			if($veri){
				$d = $this->Affiliate_Model->getAffiliateUser(["affiliate_id" => $veri[0]->affiliate_id]);
				if($d){
					$aff[] = $d[0];
				}
			}
		}

		return $aff;

	}

	public function getTeam(){

		$aff = $this->getChild($this->json['user_id']);

		if(isset($aff)){
			
			echo json_encode([
				"status" => true,
				"message"=> null,
				"result" => $aff
			]);

		} else {

			echo json_encode([
				"status" => false,
				"message"=> "Ekibinizi henüz kurmadınız!",
				"result" => null,
			]);

		}

	}

	public function getOrder(){

		$this->load->model("Order_Model");

		$affiliates = $this->getChild($this->json['user_id']);

		foreach($affiliates as $affiliate){

			$ids[] = $affiliate->affiliate_id;

		}

		foreach($this->Order_Model->getAffiliatesOrders($ids) as $order){
			if(strstr($order->description, 'Order #')){
				$order_ids[] = $order->post_id;
				$orders[] = (Object)[
					"meta" => $this->Order_Model->getOrderMeta(["post_id" => $order->post_id]),
					"status" => $this->Order_Model->getOrderStatus(["ID" => $order->post_id]),
				];
			}

		}

		//$orders = $this->Order_Model->getOrders($order_ids);
		// $x=0;
		// foreach($orders as $order){
		// 	$orders[$x]->status
		// }

		if(isset($orders)){
			
			echo json_encode([
				"status" => true,
				"message"=> null,
				"result" => $orders
			]);

		} else {

			echo json_encode([
				"status" => false,
				"message"=> "Ekibinizi henüz kurmadınız!",
				"result" => null,
			]);

		}

	}

}