<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

  public function __construct(){
    parent::__construct();
    error_reporting(0);
    if(!$this->session->userdata("login"))
      redirect( base_url("Login") );
  }

	public function customers($start=null, $finish=null, $query=null){

		$this->load->model("Order_Model");
		$this->load->model("Customer_Model");
		$customers = $this->Customer_Model->customers();
		$x=0;
		foreach($customers as $customer){
			$aff = $this->Customer_Model->getUserMeta(["wp_usermeta.user_id" => $customer->ID]);
            $orders = $this->Order_Model->getAffiliatesOrdersMainByOrder([
				"wp_aff_referrals.affiliate_id" => $customer->affiliate_id,
				"wp_aff_referrals.datetime >=" => $start,
				"wp_aff_referrals.datetime <=" => $finish,
			]);


			$ids = [];
			foreach($orders as $order){
				if(strstr($order->description, "Order #")){
				    $_order_id = intval(str_replace("Order #", "", $order->description));
				    array_push($ids, $_order_id);
				}
			}


			foreach($aff as $af){
				if($af->meta_key == "affiliate_parent"){
					$customers[$x]->affiliate_parent = $af->meta_value;
					//break;
				}
				if($af->meta_key == "affiliate_supervisor"){
					$customers[$x]->affiliate_supervisor = $af->meta_value == 0 ? null : $af->meta_value;
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
				if($af->meta_key == "_affiliate_disabled"){
					$customers[$x]->_affiliate_disabled = $af->meta_value;
				}
				if($af->meta_key == "_temsilci"){
				    $customers[$x]->_temsilci = $af->meta_value;
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
			if(!isset($customers[$x]->_affiliate_disabled)){
				$customers[$x]->_affiliate_disabled = 1;
			}
			if(!isset($customers[$x]->tc_kimlik)){
				$customers[$x]->tc_kimlik = null;
			}
			if(!isset($customers[$x]->affiliate_percent)){
				$customers[$x]->affiliate_percent = 10;
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
			if(!isset($customers[$x]->_temsilci)){
				$customers[$x]->_temsilci = false;
			}
			if($customers[$x]->_temsilci == 0){
			    $customers[$x]->_temsilci = false;
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
							if( $meta->meta_key == "_order_total" ){
								$customers[$x]->orders[$y]->toplam = $meta->meta_value;
							}

						}

						$customers[$x]->orders[$y]->toplam = $customers[$x]->orders[$y]->toplam - $customers[$x]->orders[$y]->_order_shipping - $customers[$x]->orders[$y]->_order_shipping_tax;
                        //$customers[$x]->orders_total = $customers[$x]->orders_total + $customers[$x]->orders[$y]->toplam;
						$customers[$x]->orders[$y]->status = $order->post_status;



						if($order->post_status == "wc-completed" || $order->post_status == "wc-awaiting-shipment"){
							$customers[$x]->orders[$y]->hakedis = true;
						} else {
							$customers[$x]->orders[$y]->hakedis = false;
						}



						if($order->post_status == "wc-cancelled" || $order->post_status == "trash" || $order->post_status == "wc-on-hold" || $order->post_status == "wc-pending"){
							$customers[$x]->orders[$y]->siparis = false;
						} else {
							$customers[$x]->orders[$y]->siparis = true;
						}

					}
					$y++;
				}


                // Alt Takim Hakedisler
			}



			$x++;
		}

		return $customers;

	}

	public function index($start=null, $finish=null){
    $adminIDs = [1, 4];
    if( !in_array($this->session->userdata("ID"), $adminIDs) )
      redirect( base_url("Main/dashboard") );

		if(!$start || !$finish){
      $dateStart = new DateTime('first day of this month');
      $start = $dateStart->format('Y-m-d 00:00:00');
      $finish = date("Y-m-d 23:59:00");
		}else{
			$start = date_format(date_create($start), "Y-m-d 00:00:00");
			$finish = date_format(date_create($finish), "Y-m-d 23:59:59");
		}
		$start = date("Y-m-d 00:00:00", strtotime('-1 day',strtotime($start)));

    $this->load->model('Prim_Model');
		$data = [
      "prim_seviye" => $this->Prim_Model->getSeviyePrims(),
			"customers" => $this->customers($start, $finish),
			"start" => explode(" ", date("Y-m-d", strtotime('+1 day',strtotime($start)))),
			"finish" => explode(" ", $finish),
			"orderType" => 'sevk',
		];
		$this->load->view('home', $data);

	}

	public function indexOrder($start=null, $finish=null){

    $adminIDs = [1, 4];
    if( !in_array($this->session->userdata("ID"), $adminIDs) )
      redirect( base_url("Main/dashboard") );

		if(!$start || !$finish){
      $dateStart = new DateTime('first day of this month');
      $start = $dateStart->format('Y-m-d 00:00:00');
      $finish = date("Y-m-d 23:59:00");
		}else{
			$start = date_format(date_create($start), "Y-m-d 00:00:00");
			$finish = date_format(date_create($finish), "Y-m-d 23:59:59");
		}
		$start = date("Y-m-d 00:00:00", strtotime('-1 day',strtotime($start)));

		$data = [
			"customers" => $this->customers($start, $finish, 'order'),
			"start" => explode(" ", $start),
			"finish" => explode(" ", $finish),
			"orderType" => 'order',
		];
		$this->load->view('main_new', $data);

	}

	public function dashboard($start=null, $finish=null){

		if(!$start || !$finish){
      $dateStart = new DateTime('first day of this month');
      $start = $dateStart->format('Y-m-d 00:00:00');
      $finish = date("Y-m-d 23:59:00");
		}else{
			$start = date_format(date_create($start), "Y-m-d 00:00:00");
			$finish = date_format(date_create($finish), "Y-m-d 23:59:59");
		}
		$start = date("Y-m-d 00:00:00", strtotime('-1 day',strtotime($start)));

		$data = [
			"customers" => $this->customers($start, $finish),
			"start" => explode(" ", date("Y-m-d", strtotime('+1 day',strtotime($start)))),
			"finish" => explode(" ", $finish),
			"orderType" => 'sevk',
		];
		$this->load->view('dashboard_new', $data);

	}

	public function dashboardOrder($start=null, $finish=null){

		if(!$start || !$finish){
      $dateStart = new DateTime('first day of this month');
      $start = $dateStart->format('Y-m-d 00:00:00');
      $finish = date("Y-m-d 23:59:00");
		}else{
			$start = date_format(date_create($start), "Y-m-d 00:00:00");
			$finish = date_format(date_create($finish), "Y-m-d 23:59:59");
		}

		$data = [
			"customers" => $this->customers($start, $finish, 'order'),
			"start" => explode(" ", $start),
			"finish" => explode(" ", $finish),
			"orderType" => 'order',
		];
		$this->load->view('dashboard_new', $data);

	}

	public function allOrders($start=null, $finish=null){

    $this->load->model("User_Model");

    if(!$start || !$finish){
      $dateStart = new DateTime('first day of this month');
      $start = $dateStart->format('Y-m-d 00:00:00');
      $finish = date("Y-m-d 23:59:00");
		}else{
			$start = date_format(date_create($start), "Y-m-d 00:00:00");
			$finish = date_format(date_create($finish), "Y-m-d 23:59:59");
		}
		$start = date("Y-m-d 00:00:00", strtotime('-1 day',strtotime($start)));

		$this->load->model("Order_Model");
        $orders = $this->Order_Model->allOrders([
			"wp_postmeta.meta_key" => "_sevk_tarih",
			"wp_postmeta.meta_value >=" => $start,
			"wp_postmeta.meta_value <=" => $finish,
		]);


		$x=0;
		foreach( $orders as $order){

			$komisyonlu = $this->Order_Model->getMiraOrderPrice(["mira_order_order" => $order->ID]);
			$orders[$x]->komisyonlu = $komisyonlu ? $komisyonlu[0]->mira_order_price : 0;
				
	    foreach( $this->Order_Model->getOrderMeta(["post_id" => $order->ID]) as $meta){
				if( $meta->meta_key == "_billing_first_name" ){
					$orders[$x]->adsoyad = $meta->meta_value;
				}
				if( $meta->meta_key == "_billing_last_name" ){
					$orders[$x]->adsoyad .= " " . $meta->meta_value;
				}
        if( $meta->meta_key == "_order_shipping" ){
            $orders[$x]->_order_shipping = " " . $meta->meta_value;
        }
        if( $meta->meta_key == "_order_shipping_tax" ){
            $orders[$x]->_order_shipping_tax = " " . $meta->meta_value;
        }
				if( $meta->meta_key == "_order_total" ){
					$orders[$x]->toplam = $meta->meta_value;
				}
				if( $meta->meta_key == "_sevk_tarih" ){
				    $orders[$x]->_sevk_tarih = $meta->meta_value;
				}
			}

			$orders[$x]->toplam = $orders[$x]->toplam - $orders[$x]->_order_shipping - $orders[$x]->_order_shipping_tax;

			$orders[$x]->status = $order->post_status;



			if($order->post_status == "wc-completed" || $order->post_status == "wc-awaiting-shipment"){
				$orders[$x]->hakedis = true;
			} else {
				$orders[$x]->hakedis = false;
			}



			if($order->post_status == "wc-cancelled" || $order->post_status == "trash" || $order->post_status == "wc-on-hold" || $order->post_status == "wc-pending"){
				$orders[$x]->siparis = false;
			} else {
				$orders[$x]->siparis = true;
			}

			$affVarMi = $this->Order_Model->getAffiliatesOrdersMain(["wp_aff_referrals.post_id" => $order->ID]);
			if($affVarMi){
			    $aff_id = $affVarMi[0]->affiliate_id;
			    if($aff_id)
			        $orders[$x]->affiliate_adsoyad = $affVarMi[0]->name;
			}
			$x++;

		}

		$data = [
			"orders" => $orders,
			"start" => explode(" ", date("Y-m-d", strtotime('+1 day',strtotime($start)))),
			"finish" => explode(" ", $finish),
			"orderType" => "sevk",
		];

		$this->load->view('order_list', $data);

	}

	public function allOrdersByOrders($start=null, $finish=null){

    $this->load->model("User_Model");
    if(!$start || !$finish){
      $dateStart = new DateTime('first day of this month');
      $start = $dateStart->format('Y-m-d 00:00:00');
      $finish = date("Y-m-d 23:59:00");
		}else{
			$start = date_format(date_create($start), "Y-m-d 00:00:00");
			$finish = date_format(date_create($finish), "Y-m-d 23:59:59");
		}

		$start = date("Y-m-d 00:00:00", strtotime('-1 day',strtotime($start)));

		$this->load->model("Order_Model");
        $orders = $this->Order_Model->allOrdersByOrders([
			"wp_posts.post_date >=" => $start,
			"wp_posts.post_date <=" => $finish,
		]);


		$x=0;
		foreach( $orders as $order){
				$komisyonlu = $this->Order_Model->getMiraOrderPrice(["mira_order_order" => $order->ID]);
				$orders[$x]->komisyonlu = $komisyonlu ? $komisyonlu[0]->mira_order_price : 0;


		    foreach( $this->Order_Model->getOrderMeta(["post_id" => $order->ID]) as $meta){
				if( $meta->meta_key == "_billing_first_name" ){
					$orders[$x]->adsoyad = $meta->meta_value;
				}
				if( $meta->meta_key == "_billing_last_name" ){
					$orders[$x]->adsoyad .= " " . $meta->meta_value;
				}
        if( $meta->meta_key == "_order_shipping" ){
            $orders[$x]->_order_shipping = " " . $meta->meta_value;
        }
        if( $meta->meta_key == "_order_shipping_tax" ){
            $orders[$x]->_order_shipping_tax = " " . $meta->meta_value;
        }
				if( $meta->meta_key == "_order_total" ){
					$orders[$x]->toplam = $meta->meta_value;
				}
				if( $meta->meta_key == "_sevk_tarih" ){
				    $orders[$x]->_sevk_tarih = $meta->meta_value;
				}

			}

			$orders[$x]->toplam = $orders[$x]->toplam ? $orders[$x]->toplam - $orders[$x]->_order_shipping - $orders[$x]->_order_shipping_tax : 0;

			$orders[$x]->status = $order->post_status;



			if($order->post_status == "wc-completed" || $order->post_status == "wc-awaiting-shipment"){
				$orders[$x]->hakedis = true;
			} else {
				$orders[$x]->hakedis = false;
			}



			if($order->post_status == "wc-cancelled" || $order->post_status == "trash" || $order->post_status == "wc-on-hold" || $order->post_status == "wc-pending"){
				$orders[$x]->siparis = false;
			} else {
				$orders[$x]->siparis = true;
			}


			$affVarMi = $this->Order_Model->getAffiliatesOrdersMain(["wp_aff_referrals.post_id" => $order->ID]);
			if($affVarMi){
			    $aff_id = $affVarMi[0]->affiliate_id;
			    if($aff_id)
			        $orders[$x]->affiliate_adsoyad = $affVarMi[0]->name;
			}

			if($aff_id){
			    $userMeta = $this->User_Model->getUserMetaFromAff(["wp_aff_affiliates_users.affiliate_id" => $aff_id]);
			    if($userMeta){
			        $orders[$x]->user_id = $userMeta[0]->user_id;
			        foreach( $userMeta as $usermeta ){
	               if($usermeta->meta_key == "_affiliate_disabled"){
          					$orders[$x]->_affiliate_disabled = $usermeta->meta_value == 1 ? true : false;
          				}

          				if($usermeta->meta_key == "affiliate_parent"){
          					$orders[$x]->affiliate_parent = $usermeta->meta_value ? $usermeta->meta_value : false;
          				}
			        }
			    }
			}

			if(!isset($orders[$x]->_affiliate_disabled)){
				$orders[$x]->_affiliate_disabled = true;
			}

			$x++;

		}



		$data = [
			"orders" => $orders,
			"start" => explode(" ", $start),
			"finish" => explode(" ", $finish),
			"orderType" => "order",
		];

		$this->load->view('order_list', $data);

	}

}
