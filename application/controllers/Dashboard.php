<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}

	private function customers($start=null, $finish=null){

		$this->load->model("Order_Model");
		$this->load->model("Customer_Model");
		$customers = $this->Customer_Model->customers();

		$x=0;
		foreach($customers as $customer){
			$customers[$x]->affiliate_code = md5($customer->affiliate_id);
			$aff = $this->Customer_Model->getUserMeta(["wp_usermeta.user_id" => $customer->ID]);
			$orders = $this->Order_Model->getAffiliatesOrdersMain([
				"affiliate_id" => $customer->affiliate_id,
				"datetime >=" => $start,
				"datetime <=" => $finish,
			]);


			$ids = [];
			foreach($orders as $order){
				if(strstr($order->description, "Order #"))
					array_push($ids, intval(str_replace("Order #", "", $order->description)));
			}


			foreach($aff as $af){
				if($af->meta_key == "affiliate_parent"){
					$customers[$x]->affiliate_parent = $af->meta_value;
				}
				if($af->meta_key == "affiliate_supervisor"){
					$customers[$x]->affiliate_supervisor = true;
				}
				if($af->meta_key == "first_name"){
					$customers[$x]->adsoyad = $af->meta_value;
				}
				if($af->meta_key == "last_name"){
					$customers[$x]->adsoyad .= " ".$af->meta_value;
				}
				if($af->meta_key == "tc_kimlik"){
					$customers[$x]->tc_kimlik = $af->meta_value;
				}
				if($af->meta_key == "affiliate_percent"){
					$customers[$x]->affiliate_percent = $af->meta_value;
				}
				if($af->meta_key == "affiliate_lower_percent"){
					$customers[$x]->affiliate_lower_percent = $af->meta_value;
				}
				if($af->meta_key == "affiliate_lower_percent_2nd"){
					$customers[$x]->affiliate_lower_percent = $af->meta_value;
				}
				if($af->meta_key == "affiliate_lower_percent_3r"){
					$customers[$x]->affiliate_lower_percent = $af->meta_value;
				}
			}

			if(!isset($customers[$x]->affiliate_parent)){
				$customers[$x]->affiliate_parent = 0;
			}
			if(!isset($customers[$x]->affiliate_supervisor)){
				$customers[$x]->affiliate_supervisor = false;
			}
			if(!isset($customers[$x]->adsoyad)){
				$customers[$x]->adsoyad = null;
			}
			if(!isset($customers[$x]->tc_kimlik)){
				$customers[$x]->tc_kimlik = null;
			}
			if(!isset($customers[$x]->affiliate_percent)){
				$customers[$x]->affiliate_percent = 15;
			}
			if(!isset($customers[$x]->affiliate_lower_percent)){
				$customers[$x]->affiliate_lower_percent = 0;
			}
			if(!isset($customers[$x]->affiliate_lower_percent_2nd)){
				$customers[$x]->affiliate_lower_percent_2nd = 0;
			}
			if(!isset($customers[$x]->affiliate_lower_percent_3rd)){
				$customers[$x]->affiliate_lower_percent_3rd = 0;
			}


			$customers[$x]->orders = $ids ? $this->Order_Model->getOrders($ids) : [];

			if($customers[$x]->orders){
				$y = 0;
				foreach( $customers[$x]->orders as $order ){

					if($order->post_status){

				// 		$meta = $this->Order_Model->getOrderMeta(["post_id" => $order->ID]);
				// 		$meta = $meta[0];

						foreach( $this->Order_Model->getOrderMeta(["post_id" => $order->ID]) as $meta){
							if( $meta->meta_key == "_billing_first_name" ){
								$customers[$x]->orders[$y]->adsoyad = $meta->meta_value;
							}
							if( $meta->meta_key == "_sevk_tarih" ){
								$customers[$x]->orders[$y]->sevk_tarih = $meta->meta_value;
							}
							if( $meta->meta_key == "_billing_last_name" ){
								$customers[$x]->orders[$y]->adsoyad .= " " . $meta->meta_value;
							}
              if( $meta->meta_key == "_order_shipping" ){
                  $customers[$x]->orders[$y]->_order_shipping = " " . $meta->meta_value;
              }
              if( $meta->meta_key == "_order_shipping_tax" ){
                  $customers[$x]->orders[$y]->_order_shipping_tax = " " . $meta->meta_value;
              }
              if( $meta->meta_key == "_billing_phone"){
              	$customers[$x]->orders[$y]->billing_phone = $meta->meta_value;
              }
							if( $meta->meta_key == "_order_total" ){
								$customers[$x]->orders[$y]->toplam = $meta->meta_value;
							}

						}




						if(in_array($order->post_status, ["wc-completed", "wc-awaiting-shipment"])){
							$customers[$x]->orders[$y]->hakedis = true;
						} else {
							$customers[$x]->orders[$y]->hakedis = false;
						}

						if($order->post_status == "wc-refunded"){
							$customers[$x]->orders[$y]->iade = true;
						}else{
							$customers[$x]->orders[$y]->iade = false;
						}

						if(in_array($order->post_status, ["wc-cancelled", "trash", "wc-on-hold", "wc-pending", "wc-refunded"])){
							$customers[$x]->orders[$y]->siparis = false;
						} else {
							$customers[$x]->orders[$y]->siparis = true;
						}

						$customers[$x]->orders[$y]->status = $order->post_status;
						$customers[$x]->orders[$y]->siparis_tarih = strftime("%e %B %Y %X", strtotime($order->post_date));

					}

					$y++;
				}

			}



			$x++;
		}

		return $customers;

	}

	public function index($yil=null, $ay=null){

		if(!$this->session->userdata("login"))
    	redirect( base_url("Login") );

    $adminIDs = [1, 4];
    if( !in_array($this->session->userdata("ID"), $adminIDs) )
      redirect( base_url("Home/dashboard") );

		$yil = $yil ? (int)$yil : (int)date('Y');
 	 	$ay = $ay ? (int)$ay : (int)date('m');

		$data = [
			"customers" => $this->customers($start, $finish),
			"start" => explode(" ", $start),
			"finish" => explode(" ", $finish),
			"user_id" => $this->session->userdata("ID")
		];

		$this->load->view('dashboard', $data);

	}
}
