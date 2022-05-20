<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Automattic\WooCommerce\Client;


class Customer extends CI_Controller{
	
	private $json;

	public function __construct(){

		parent::__construct();
		$this->load->model('Customer_Model');
		date_default_timezone_set('Europe/Istanbul');

		//header('Access-Control-Allow-Headers: *');
		header("Access-Control-Allow-Origin: *");

		$this->output->set_content_type('application/json');
	    $this->output->set_header('Access-Control-Allow-Origin: *');
	    $this->output->set_header('Access-Control-Allow-Methods: GET, OPTIONS');
	    $this->output->set_header('Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding');
	    $this->json = (Object)json_decode(file_get_contents('php://input'));
		
	}

	public function customers_new($start=null, $finish=null){
        
        if(!$this->session->userdata("login"))
            redirect( base_url("Login") );
        
        $adminIDs = [1, 4];
        if( !in_array($this->session->userdata("ID"), $adminIDs) )
            redirect( base_url("Dashboard") );


		$this->load->model("Order_Model");
		$this->load->model("Customer_Model");
		$customers = $this->Customer_Model->customers();
		$x=0;
		foreach($customers as $customer){
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
					//break;
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

						foreach( $this->Order_Model->getOrderMeta(["post_id" => $order->ID]) as $meta){
							if( $meta->meta_key == "_billing_first_name" ){
								$customers[$x]->orders[$y]->adsoyad = $meta->meta_value;
							}
							if( $meta->meta_key == "_billing_last_name" ){
								$customers[$x]->orders[$y]->adsoyad .= " " . $meta->meta_value;
							}

							if( $meta->meta_key == "_order_total" ){
								$customers[$x]->orders[$y]->toplam = $meta->meta_value;
							}

						}

						$customers[$x]->orders[$y]->status = $order->post_status;

						// foreach( $ids as $order_id ){
						// 	$item = $this->Order_Model->getOrderItem(["order_id" => $order_id]);
						// 	// if($item)
						// 	// 	$item[] = $this->Order_Model->getOrderItem(["order_id" => $order_id]);
							
						// 	if( $item ){
						// 		$customers[$x]->orders[$y]->item = $item;
						// 	}
						// }
					}
					$y++;
				}

			}

			

			$x++;
		}
		
		return $customers;
		
	}

	public function customers(){

		$this->load->model("Order_Model");
		$customers = $this->Customer_Model->customers();
		$x=0;
		foreach($customers as $customer){
			$aff = $this->Customer_Model->getUserMeta(["wp_usermeta.user_id" => $customer->ID]);
			$orders = $this->Order_Model->getAffiliatesOrders(["affiliate_id" => $customer->affiliate_id]);

			$ids = [];
			foreach($orders as $order){
				if(strstr($order->description, "Order #"))
					array_push($ids, intval(str_replace("Order #", "", $order->description)));
			}


			foreach($aff as $af){
				if($af->meta_key == "affiliate_parent"){
					$customers[$x]->affiliate_parent = $af->meta_value;
					//break;
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
			}

			if(!isset($customers[$x]->affiliate_parent)){
				$customers[$x]->affiliate_parent = null;
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
				$customers[$x]->affiliate_percent = null;
			}
			if(!isset($customers[$x]->affiliate_lower_percent)){
				$customers[$x]->affiliate_lower_percent = null;
			}


			$customers[$x]->orders = $ids ? $this->Order_Model->getOrders($ids) : [];
			
			if($customers[$x]->orders){
				$y = 0;
				foreach( $customers[$x]->orders as $order ){

					if($order->post_status){

						foreach( $this->Order_Model->getOrderMeta(["post_id" => $order->ID]) as $meta){
							if( $meta->meta_key == "_billing_first_name" ){
								$customers[$x]->orders[$y]->adsoyad = $meta->meta_value;
							}
							if( $meta->meta_key == "_billing_last_name" ){
								$customers[$x]->orders[$y]->adsoyad .= " " . $meta->meta_value;
							}

							if( $meta->meta_key == "_order_total" ){
								$customers[$x]->orders[$y]->toplam = $meta->meta_value;
							}

							$customers[$x]->orders[$y]->status = $this->Order_Model->getOrderStatus(["ID" => $order->ID]);
						}

						foreach( $ids as $order_id ){
							$item = $this->Order_Model->getOrderItem(["order_id" => $order_id]);
							// if($item)
							// 	$item[] = $this->Order_Model->getOrderItem(["order_id" => $order_id]);
							
							if( $item ){
								$customers[$x]->orders[$y]->item = $item;
							}
						}
					}
					$y++;
				}

			}

			

			$x++;
		}
		
		if(isset($customers)){

			echo json_encode([
				"status" => true,
				"message"=> "",
				"result" => $customers
			]);

		} else {

			echo json_encode([
				"status" => false,
				"message"=> "",
				"result" => null
			]);

		}
		
	}

	public function dashboard(){

		$this->load->model("Order_Model");
		$customer = $this->Customer_Model->find(["wp_users.ID" => $this->json->customer_id]);
		$customer = $customer[0];
		$customer->affiliate_code = md5($customer->affiliate_id);

		$aff = $this->Customer_Model->getUserMeta(["wp_usermeta.user_id" => $customer->ID]);
		$orders = $this->Order_Model->getAffiliatesOrders(["affiliate_id" => $customer->affiliate_id]);

		$ids = [];
		foreach($orders as $order){
			if(strstr($order->description, "Order #"))
				array_push($ids, intval(str_replace("Order #", "", $order->description)));
		}


		foreach($aff as $af){
			if($af->meta_key == "affiliate_parent"){
				$customer->affiliate_parent = $af->meta_value;
				//break;
			}
			if($af->meta_key == "affiliate_supervisor"){
				$customer->affiliate_supervisor = true;
			}
			if($af->meta_key == "first_name"){
				$customer->adsoyad = $af->meta_value;
			}
			if($af->meta_key == "last_name"){
				$customer->adsoyad .= " ".$af->meta_value;
			}
			if($af->meta_key == "tc_kimlik"){
				$customer->tc_kimlik = $af->meta_value;
			}
			if($af->meta_key == "affiliate_percent"){
				$customer->affiliate_percent = $af->meta_value;
			}
			if($af->meta_key == "affiliate_lower_percent"){
				$customer->affiliate_lower_percent = $af->meta_value;
			}
		}

		if(!isset($customer->affiliate_parent)){
			$customer->affiliate_parent = null;
		}
		if(!isset($customer->affiliate_supervisor)){
			$customer->affiliate_supervisor = false;
		}
		if(!isset($customer->adsoyad)){
			$customer->adsoyad = null;
		}
		if(!isset($customer->tc_kimlik)){
			$customer->tc_kimlik = null;
		}
		if(!isset($customer->affiliate_percent)){
			$customer->affiliate_percent = null;
		}
		if(!isset($customer->affiliate_lower_percent)){
			$customer->affiliate_lower_percent = null;
		}


		$customer->orders = $ids ? $this->Order_Model->getOrders($ids) : [];
		
		if($customer->orders){
			$y = 0;
			foreach( $customer->orders as $order ){

				if($order->post_status){

					foreach( $this->Order_Model->getOrderMeta(["post_id" => $order->ID]) as $meta){
						if( $meta->meta_key == "_billing_first_name" ){
							$customer->orders[$y]->adsoyad = $meta->meta_value;
						}
						if( $meta->meta_key == "_billing_last_name" ){
							$customer->orders[$y]->adsoyad .= " " . $meta->meta_value;
						}

						if( $meta->meta_key == "_order_total" ){
							$customer->orders[$y]->toplam = $meta->meta_value;
						}

						//$customer->orders[$y]->status = $this->Order_Model->getOrderStatus(["ID" => $order->ID]);
						$customer->orders[$y]->status = $order->post_status;
					}

					foreach( $ids as $order_id ){
						$item = $this->Order_Model->getOrderItem(["order_id" => $order_id]);
						// if($item)
						// 	$item[] = $this->Order_Model->getOrderItem(["order_id" => $order_id]);
						
						if( $item ){
							$customer->orders[$y]->item = $item;
						}
					}
				}
				$y++;
			}

		}

		if(isset($customer)){

			echo json_encode([
				"status" => true,
				"message"=> "",
				"result" => $customer
			]);

		} else {

			echo json_encode([
				"status" => false,
				"message"=> "",
				"result" => null
			]);

		}

	}

	public function update(){

		$data = (Object) [
			"affiliate_lower_percent" => $this->json->affiliate_lower_percent,
			"affiliate_parent" => $this->json->affiliate_parent,
			"affiliate_percent" => $this->json->affiliate_percent,
			"affiliate_supervisor" => $this->json->affiliate_supervisor,
			"user_id" => $this->json->user_id,
		];

		if($data->affiliate_lower_percent){
			$this->Customer_Model->setUserMeta([
				"user_id" => $data->user_id, 
				"meta_key" => "affiliate_lower_percent",
				"meta_value" => $data->affiliate_lower_percent
			]);
		}else{
			$this->Customer_Model->delUserMeta([
				"user_id" => $data->user_id, 
				"meta_key" => "affiliate_lower_percent"
			]);
		}


		if($data->affiliate_parent){
			$this->Customer_Model->setUserMeta([
				"user_id" => $data->user_id, 
				"meta_key" => "affiliate_parent",
				"meta_value" => $data->affiliate_parent,
			]);
		}else{
			$this->Customer_Model->delUserMeta([
				"user_id" => $data->user_id, 
				"meta_key" => "affiliate_parent"
			]);
		}


		if($data->affiliate_percent){
			$this->Customer_Model->setUserMeta([
				"user_id" => $data->user_id, 
				"meta_key" => "affiliate_percent",
				"meta_value" => $data->affiliate_percent,
			]);
		}else{
			$this->Customer_Model->delUserMeta([
				"user_id" => $data->user_id, 
				"meta_key" => "affiliate_percent"
			]);
		}

		if($data->affiliate_supervisor){
			$this->Customer_Model->setUserMeta([
				"user_id" => $data->user_id, 
				"meta_key" => "affiliate_supervisor",
				"meta_value" => $data->affiliate_supervisor,
			]);
		}else{
			$this->Customer_Model->delUserMeta([
				"user_id" => $data->user_id, 
				"meta_key" => "affiliate_supervisor"
			]);
		}

		echo json_encode([
			"status" => true,
			"message"=> "",
			"result" => $data,
		]);

	}

	public function getTakim(){

		$this->load->model("Order_Model");
		$getMeta = $this->Customer_Model->getUserMeta([
			"meta_key" => "affiliate_parent", 
			"meta_value" => $this->json->customer_id
		]);

		//echo json_encode($getMeta);exit;

		$idler = [];
		foreach( $getMeta as $meta )
			array_push($idler, $meta->user_id);

		if(count($idler) > 0){

			$customers = $this->Customer_Model->customersTakim($idler);

			$x=0;
			foreach($customers as $customer){
				$aff = $this->Customer_Model->getUserMeta(["wp_usermeta.user_id" => $customer->ID]);
				//$orders = $this->Order_Model->getAffiliatesOrders(["affiliate_id" => $customer->affiliate_id]);

				// $ids = [];
				// foreach($orders as $order){
				// 	if(strstr($order->description, "Order #"))
				// 		array_push($ids, intval(str_replace("Order #", "", $order->description)));
				// }


				foreach($aff as $af){
					if($af->meta_key == "affiliate_parent"){
						$customers[$x]->affiliate_parent = $af->meta_value;
						//break;
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
				}

				if(!isset($customers[$x]->affiliate_parent)){
					$customers[$x]->affiliate_parent = null;
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
					$customers[$x]->affiliate_percent = null;
				}
				if(!isset($customers[$x]->affiliate_lower_percent)){
					$customers[$x]->affiliate_lower_percent = null;
				}


				// $customers[$x]->orders = $ids ? $this->Order_Model->getOrders($ids) : [];
				
				// if($customers[$x]->orders){
				// 	$y = 0;
				// 	foreach( $customers[$x]->orders as $order ){

				// 		if($order->post_status){

				// 			foreach( $this->Order_Model->getOrderMeta(["post_id" => $order->ID]) as $meta){
				// 				if( $meta->meta_key == "_billing_first_name" ){
				// 					$customers[$x]->orders[$y]->adsoyad = $meta->meta_value;
				// 				}
				// 				if( $meta->meta_key == "_billing_last_name" ){
				// 					$customers[$x]->orders[$y]->adsoyad .= " " . $meta->meta_value;
				// 				}

				// 				if( $meta->meta_key == "_order_total" ){
				// 					$customers[$x]->orders[$y]->toplam = $meta->meta_value;
				// 				}

				// 				$customers[$x]->orders[$y]->status = $this->Order_Model->getOrderStatus(["ID" => $order->ID]);
				// 			}

				// 			foreach( $ids as $order_id ){
				// 				$item = $this->Order_Model->getOrderItem(["order_id" => $order_id]);
				// 				// if($item)
				// 				// 	$item[] = $this->Order_Model->getOrderItem(["order_id" => $order_id]);
								
				// 				if( $item ){
				// 					$customers[$x]->orders[$y]->item = $item;
				// 				}
				// 			}
				// 		}
				// 		$y++;
				// 	}

				// }

				$x++;
			}

		}
		
		
		if(isset($customers)){

			echo json_encode([
				"status" => true,
				"message"=> "",
				"result" => $customers
			]);

		} else {

			echo json_encode([
				"status" => false,
				"message"=> "",
				"result" => null
			]);

		}

	}

}