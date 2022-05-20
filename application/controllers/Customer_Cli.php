<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_Cli extends CI_Controller{

	public function __construct(){

		parent::__construct();

	}
	
	public function rutbe(){
	    
	    $adminIDs = [1, 4];
        if( !in_array($this->session->userdata("ID"), $adminIDs) )
            redirect( base_url("Dashboard") );
            
	}

	public function customers($start=null, $finish=null){

		error_reporting(0);
		
		if(!$this->session->userdata("login"))
            redirect( base_url("Login") );
        
        
        
        $this->rutbe();
            
		$this->load->model("Order_Model");
		$this->load->model("Customer_Model");

		$customers = $this->Customer_Model->customers();
		$x=0;
		foreach($customers as $customer){
			$aff = $this->Customer_Model->getUserMeta(["wp_usermeta.user_id" => $customer->ID]);
			if($start & $finish){

				$start = date('Y-m-d', strtotime($start));
				$finish = date('Y-m-d', strtotime($finish));
				
				$orders = $this->Order_Model->getAffiliatesOrders([
					"affiliate_id" => $customer->affiliate_id
				],[
					"datetime >=" => $start,
					"datetime <=" => $finish,
				]);

			}else{

				$orders = $this->Order_Model->getAffiliatesOrders([
					"affiliate_id" => $customer->affiliate_id
				]);

			}

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
					$customers[$x]->affiliate_supervisor = $af->meta_value == 0 ? false : $af->meta_value;
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
				if($af->meta_key == "_affiliate_disabled"){
					$customers[$x]->_affiliate_disabled = $af->meta_value == 0 ? false : $af->meta_value;
				}
				if($af->meta_key == "_affiliate_disabled_comment"){
					$deger = explode("||", $af->meta_value); 
					$customers[$x]->_affiliate_disabled_comment = $deger[0];
					$customers[$x]->_affiliate_disabled_time = $deger[1];
				}
				if($af->meta_key == "_katilim_sehir"){
				    $customers[$x]->_katilim_sehir = $af->meta_value;
				}
				if($af->meta_key == "ekip_lideri"){
				    $customers[$x]->ekip_lideri = $af->meta_value;
				}
				if($af->meta_key == "_temsilci"){
				    $customers[$x]->_temsilci = $af->meta_value == 0 ? false : $af->meta_value;
				}
				if($af->meta_key == "_temsilci_harita"){
				    $customers[$x]->_temsilci_harita = $af->meta_value == 0 ? false : $af->meta_value;
				}
				if($af->meta_key == "_temsilci_sehir"){
				    $customers[$x]->_temsilci_sehir = $af->meta_value;
				}
				if($af->meta_key == "_temsilci_ilce"){
				    $customers[$x]->_temsilci_ilce = $af->meta_value;
				}
				if($af->meta_key == "_temsilci_instagram"){
				    $customers[$x]->_temsilci_instagram = $af->meta_value;
				}
				if($af->meta_key == "_temsilci_tel"){
				    $customers[$x]->_temsilci_tel = $af->meta_value;
				}
				if($af->meta_key == "billing_phone"){
				    $customers[$x]->billing_phone = $af->meta_value;
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
				$customers[$x]->affiliate_percent = null;
			}
			if(!isset($customers[$x]->affiliate_lower_percent)){
				$customers[$x]->affiliate_lower_percent = null;
			}
			if(!isset($customers[$x]->_affiliate_disabled)){
				$customers[$x]->_affiliate_disabled = true;
			}
			if($customers[$x]->_affiliate_disabled == 0){
			    $customers[$x]->_affiliate_disabled = false;
			}
			if(!isset($customers[$x]->_affiliate_disabled_time)){
				$customers[$x]->_affiliate_disabled_time = null;
			}
			if(!isset($customers[$x]->_affiliate_disabled_comment)){
				$customers[$x]->_affiliate_disabled_comment = null;
			}
			if(!isset($customers[$x]->_temsilci)){
				$customers[$x]->_temsilci = false;
			}
			if(!isset($customers[$x]->_temsilci_harita)){
				$customers[$x]->_temsilci_harita = false;
			}
			if($customers[$x]->_temsilci == 0){
			    $customers[$x]->_temsilci = false;
			}
			if(!isset($customers[$x]->_temsilci_sehir)){
				$customers[$x]->_temsilci_sehir = null;
			}
			if(!isset($customers[$x]->_temsilci_instagram)){
				$customers[$x]->_temsilci_instagram = null;
			}
			if(!isset($customers[$x]->_temsilci_tel)){
				$customers[$x]->_temsilci_tel = null;
			}
			if(!isset($customers[$x]->billing_phone)){
				$customers[$x]->billing_phone = null;
			}
			if(!isset($customers[$x]->_katilim_sehir)){
				$customers[$x]->_katilim_sehir = null;
			}
			if(!isset($customers[$x]->ekip_lideri)){
				$customers[$x]->ekip_lideri = null;
			}

			$x++;
		}

        $data["iller"] = $this->Customer_Model->iller();
		$data['customers'] = $customers;
		$this->load->view("customer_cli", $data);
		
	}

	private function getTakim($start, $finish){

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
						
						$meta = $this->Order_Model->getOrderMeta(["post_id" => $order->ID]);
						$meta = $meta[0];

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

						if($order->post_status == "wc-completed" || $order->post_status == "wc-awaiting-shipment"){

							$customers[$x]->orders[$y]->hakedis = true;

						} else {

							$customers[$x]->orders[$y]->hakedis = false;
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

	private function getTakimm($start, $finish){
        
        error_reporting(0);
		$this->load->model("Order_Model");
		$this->load->model("Customer_Model");
		$getMeta = $this->Customer_Model->getUserMeta([
			"meta_key" => "affiliate_parent", 
			"meta_value" => $this->session->userdata("ID")
		]);

		$idler = [];
		foreach( $getMeta as $meta )
			array_push($idler, $meta->user_id);

		if(count($idler) > 0){

			$customers = $this->Customer_Model->customersTakim($idler);

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

		}
		
		return $customers;
		// $data['customers'] = $customers;
		// $this->load->view("takim", $data);

	}

	public function takim($start=null, $finish=null){

		if(!$this->session->userdata("login"))
            redirect( base_url("Login") );

		if(!$start || !$finish){
	        $dateStart = new DateTime('first day of this month');
	        $start = $dateStart->format('Y-m-d 00:00:00');
	        $finish = date("Y-m-d 23:59:00");
	        //redirect( base_url("Dashboard/index/{$start}/{$finish}"), "refresh" );
		}else{
			$start = date_format(date_create($start), "Y-m-d 00:00:00");
			$finish = date_format(date_create($finish), "Y-m-d 23:59:59");
		}

		$data = [
			"customers" => $this->getTakim($start, $finish),
			"start" => explode(" ", $start),
			"finish" => explode(" ", $finish),
			"user_id" => $this->session->userdata("ID")
		];
		$this->load->view("takim", $data);

	}

	public function affiliates(){

		$this->load->model("Affiliate_Model");
		$affiliates = $this->Affiliate_Model->getOnlyAffiliates();

		$x=0;
		foreach($affiliates as $aff){

			$affiliates[$x]->affiliate_code = md5( $aff->affiliate_id );
			$x++;

		}

		$data = [
			"affiliates" => $affiliates
		];

		$this->load->view("affiliates", $data);

	}

	public function siparisAktarma(){
	    
	    $this->rutbe();
        
		$this->load->model("User_Model");
		$data['affiliates'] = $this->User_Model->getAffiliates();
		
        
		$x=0;
		foreach($data['affiliates'] as $aff){
		    $v = $this->User_Model->get_usermeta(["user_id" => $aff->user_id, "meta_key" => "_affiliate_disabled"]);
	        $data["affiliates"][$x]->_affiliate_disabled = $v ? $v[0]->meta_value : "1";
		    $x++;
		}

		$this->load->view("aktarma", $data);

	}
    
    public function satisOrtagiOlmayanOrders(){
        
        $this->load->view("satisOrtagiOlmayanOrders");
        
    }

    public function duplicate(){

    	$this->load->model("Order_Model");
    	$this->load->model("Affiliate_Model");

    	$data['duplicate'] = $this->Order_Model->duplicateFind();
		$data['users'] = $this->Affiliate_Model->getOnlyAffiliates();

    	$x=0;
    	
    	foreach($data['duplicate'] as $d){
    		$veri = $this->Order_Model->getAffiliatesOrdersMainApi(["post_id" => $d->post_id]);
    		foreach($veri as $v){
    			$data['duplicate'][$x]->users[] = $v->affiliate_id;	
    		}
    		
    		$x++;
    	}


    	unset($data['duplicate'][0]);
    	unset($data['duplicate'][1]);
    	unset($data['duplicate'][2]);

    	$this->load->view("duplicate", $data);

    }

    public function customersTable($start=null, $finish=null){

		error_reporting(0);
		
		if(!$this->session->userdata("login"))
            redirect( base_url("Login") );
        
        
        
        $this->rutbe();
            
		$this->load->model("Order_Model");
		$this->load->model("Customer_Model");

		$customers = $this->Customer_Model->customers();
		$x=0;
		foreach($customers as $customer){
			$aff = $this->Customer_Model->getUserMeta(["wp_usermeta.user_id" => $customer->ID]);
			
			foreach($aff as $af){
				if($af->meta_key == "affiliate_parent"){
					$customers[$x]->affiliate_parent = $af->meta_value;
					//break;
				}
				if($af->meta_key == "affiliate_supervisor"){
					$customers[$x]->affiliate_supervisor = $af->meta_value == 0 ? false : $af->meta_value;
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
				if($af->meta_key == "_affiliate_disabled"){
					$customers[$x]->_affiliate_disabled = $af->meta_value == 0 ? false : $af->meta_value;
				}
				if($af->meta_key == "_affiliate_disabled_comment"){
					$deger = explode("||", $af->meta_value); 
					$customers[$x]->_affiliate_disabled_comment = $deger[0];
					$customers[$x]->_affiliate_disabled_time = $deger[1];
				}
				if($af->meta_key == "_temsilci"){
				    $customers[$x]->_temsilci = $af->meta_value == 0 ? false : $af->meta_value;
				}
				if($af->meta_key == "_temsilci_harita"){
				    $customers[$x]->_temsilci_harita = $af->meta_value == 0 ? false : $af->meta_value;
				}
				if($af->meta_key == "_temsilci_sehir"){
				    $customers[$x]->_temsilci_sehir = $af->meta_value;
				}
				if($af->meta_key == "_temsilci_ilce"){
				    $customers[$x]->_temsilci_ilce = $af->meta_value;
				}
				if($af->meta_key == "_temsilci_instagram"){
				    $customers[$x]->_temsilci_instagram = $af->meta_value;
				}
				if($af->meta_key == "_temsilci_tel"){
				    $customers[$x]->_temsilci_tel = $af->meta_value;
				}
				if($af->meta_key == "billing_phone"){
				    $customers[$x]->billing_phone = $af->meta_value;
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
				$customers[$x]->affiliate_percent = null;
			}
			if(!isset($customers[$x]->affiliate_lower_percent)){
				$customers[$x]->affiliate_lower_percent = null;
			}
			if(!isset($customers[$x]->_affiliate_disabled)){
				$customers[$x]->_affiliate_disabled = true;
			}
			if($customers[$x]->_affiliate_disabled == 0){
			    $customers[$x]->_affiliate_disabled = false;
			}
			if(!isset($customers[$x]->_affiliate_disabled_time)){
				$customers[$x]->_affiliate_disabled_time = null;
			}
			if(!isset($customers[$x]->_affiliate_disabled_comment)){
				$customers[$x]->_affiliate_disabled_comment = null;
			}
			if(!isset($customers[$x]->_temsilci)){
				$customers[$x]->_temsilci = false;
			}
			if(!isset($customers[$x]->_temsilci_harita)){
				$customers[$x]->_temsilci_harita = false;
			}
			if($customers[$x]->_temsilci == 0){
			    $customers[$x]->_temsilci = false;
			}
			if(!isset($customers[$x]->_temsilci_sehir)){
				$customers[$x]->_temsilci_sehir = null;
			}
			if(!isset($customers[$x]->_temsilci_instagram)){
				$customers[$x]->_temsilci_instagram = null;
			}
			if(!isset($customers[$x]->_temsilci_tel)){
				$customers[$x]->_temsilci_tel = null;
			}
			if(!isset($customers[$x]->billing_phone)){
				$customers[$x]->billing_phone = null;
			}

			$x++;
		}

        $data["iller"] = $this->Customer_Model->iller();
		$data['customers'] = $customers;
		$this->load->view("customer_cli_table", $data);
		
	}

}