<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Automattic\WooCommerce\Client;


class Order extends CI_Controller{
	
	public $json;

	public function __construct(){

		parent::__construct();
		$this->load->model('Order_Model');
		date_default_timezone_set('Europe/Istanbul');

		header("Access-Control-Allow-Origin: *");
		$this->output->set_content_type('application/json');
	    $this->output->set_header('Access-Control-Allow-Origin: *');
	    $this->output->set_header('Access-Control-Allow-Methods: GET, OPTIONS');
	    $this->output->set_header('Access-Control-Allow-Methods: Content-Type, Content-Length, Accept-Encoding');
	    $this->json = (Object)json_decode(file_get_contents('php://input'));
		
	}

	public function orders(){

		$orders = $this->Order_Model->orders();

		$x=0;
		foreach($orders as $order){
			$orders[$x]->meta = $this->Order_Model->getOrderMeta(["post_id" => $order->ID]);
			$x++;
		}

		if($orders){

			echo json_encode([
				"status" => true,
				"message"=> "",
				"result" => $orders
			]);

		} else {

			echo json_encode([
				"status" => false,
				"message"=> "",
				"result" => null
			]);

		}

	}

	public function getOrder(){

		$woocommerce = new Client(
		    'https://elvanindunyasi.com.tr/',
		    'ck_4786213346e916cf4750cd3e1755572a847c8af1',
		    'cs_995e9b3b4bc99e087ce5b8cafe6153469a40871a',
		    [
		        'wp_api' => true,
		        'version' => 'wc/v3'
		    ]
		);

		echo json_encode($woocommerce->get('orders/'.$this->json->order_id));

	}

	public function getOrderAktarma(){

		$woocommerce = new Client(
		    'https://elvanindunyasi.com.tr/',
		    'ck_4786213346e916cf4750cd3e1755572a847c8af1',
		    'cs_995e9b3b4bc99e087ce5b8cafe6153469a40871a',
		    [
		        'wp_api' => true,
		        'version' => 'wc/v3'
		    ]
		);

		$order['order'] = $woocommerce->get('orders/'.$this->json->order_id);

		$ortak = $this->Order_Model->getAffiliatesOrdersMain(["description" => "Order #".$this->json->order_id]);
		$order['satis_ortagi'] = $ortak ? $ortak[0] : ["affiliate_id" => 0];

		echo json_encode($order);

	}

	public function orderAktar(){

		if( !isset($this->json->order_id) || !isset($this->json->aff_id) ){

			$result = [
				"status" => false,
				"message"=> "",
				"result" => $this->json,
			];

		} else {

			$order_id = $this->json->order_id;
			$affiliate_id = (int)$this->json->aff_id;
			$user_id = $this->Order_Model->getAffiliatesUser(["affiliate_id" => $affiliate_id]);
			$user_id = intval($user_id->user_id);

			$varmi = $this->Order_Model->getAffiliatesOrdersMainApi(["post_id" => $order_id]);
			
			if($varmi){
			    if(count($varmi) > 1){
			        $this->Order_Model->delAffiliatesOrders([
			        	"referral_id !=" => $varmi[count($varmi)-1]->referral_id, 
			        	"post_id" => $order_id
			        ]);
			    }

				$this->Order_Model->updateAffiliateOrder(
					["referral_id" => $varmi[count($varmi)-1]->referral_id],
					[
						"affiliate_id" => $affiliate_id,
						"user_id" => $user_id,
						"datetime" => $varmi[count($varmi)-1]->datetime,
					]
				);

			} else {

                $order = $this->Order_Model->getOrders(["ID" => $order_id]);
                $order = $order[0];
                
                if($order){
                    $this->Order_Model->setAffiliateOrder(
    					[
    						"affiliate_id" => $affiliate_id,
    						"user_id" => $user_id,
    						"post_id" => $order_id,
    						"description" => "Order #".$order_id,
    						"datetime" => $order->post_date,
    					]
    				);    
                }

			}
			$result = [
				"status" => true,
				"message"=> [
					"order_id" => $order_id,
					"affiliate_id" => $affiliate_id,
					"user_id" => $user_id,
				],
				"result" => null
			];
		}

		echo json_encode($result);

	}

	public function getAllOrdersUser(){

		$_ids = $this->json->ids;
		$start = isset($this->json->start) ? $this->json->start : null;
		$finish = isset($this->json->finish) ? $this->json->finish : null;

		if(!$start || !$finish){
	        $dateStart = new DateTime('first day of this month');
	        $start = $dateStart->format('Y-m-d 00:00:00');
	        $finish = date("Y-m-d 23:59:00");
		}else{
			$start = date_format(date_create($start), "Y-m-d 00:00:00");
			$finish = date_format(date_create($finish), "Y-m-d 23:59:59");
		}

		$ids = [];
		foreach($_ids as $id){

			$veri = $this->Order_Model->getAffiliatesOrdersMain([
				"affiliate_id" => $id,
				"datetime >=" => $start,
				"datetime <=" => $finish,
			]);

			foreach($veri as $v){
				if(strstr($v->description, "Order #")){
					array_push($ids, intval($v->post_id));
				}
			}

		}

		$orders = count($ids) > 0 ? $this->Order_Model->getAllOrdersUser($ids) : [];




		$y = 0;
		foreach( $orders as $order ){

			if($order->post_status){

				foreach( $this->Order_Model->getOrderMeta(["post_id" => $order->ID]) as $meta){
					if( $meta->meta_key == "_billing_first_name" ){
						$orders[$y]->adsoyad = $meta->meta_value;
					}
					if( $meta->meta_key == "_billing_last_name" ){
						$orders[$y]->adsoyad .= " " . $meta->meta_value;
					}

					if( $meta->meta_key == "_order_total" ){
						$orders[$y]->toplam = $meta->meta_value;
					}

				}

				$orders[$y]->status = $order->post_status;

			}
			$y++;
		}



		if(count($orders) > 0){

			echo json_encode([
				"status" => true,
				"message"=> "",
				"result" => $orders,
			]);

		} else {

			echo json_encode([
				"status" => false,
				"message"=> "",
				"result" => [
					'gelen' => $this->json,
					'start' => $start,
					'finish'=> $finish
				],
			]);

		}

	}
	
	public function importSevk($tarih = null){
	    
	    $url = "http://bianca.miraerp.com:82/MiraWebService.svc?wsdl";
	    $tarih = $tarih ? date("d.m.Y", strtotime($tarih)) : date("d.m.Y");
	    // $tarih = date("d.m.Y", strtotime("31/10/2021"));
	    try{
	        
            $curl = curl_init();
    		curl_setopt_array($curl, array(
    		  CURLOPT_URL => "https://srv.elvanindunyasi.com.tr/Mira/sevkTarihleri",
    		  CURLOPT_RETURNTRANSFER => true,
    		  CURLOPT_ENCODING => "",
    		  CURLOPT_MAXREDIRS => 10,
    		  CURLOPT_TIMEOUT => 30,
    		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    		  CURLOPT_CUSTOMREQUEST => "POST",
    		  CURLOPT_POSTFIELDS => json_encode([
    		      "tarih" => $tarih, 
    		      "url" => $url
    		  ]),
    		  CURLOPT_HTTPHEADER => [
    		    "accept: application/json",
    		    "Content-Type: application/json",
    		  ],
    		));
    
    		$response = curl_exec($curl);
    		$err = curl_error($curl);
    
    		curl_close($curl);
    
    		$data = json_decode($response);
    		if($data->GetDispatchListPEResult){
    		    if(isset($data->GetDispatchListPEResult->TransferOrder)){
    		        $x=0;
    		        foreach($data->GetDispatchListPEResult->TransferOrder as $order){
    		            $veri[] = (Object)[
    		                "order_id" => $order->SaleOrder,
    		                "irsal_id" => $order->OrderID,
    		                "sevk_tar" => date("Y-m-d", strtotime($tarih))
    		            ];
    		            
    		            $order_id = $this->Order_Model->getOrderIdFromMiraOrderId(["mira_soap_order_id" => $order->SaleOrder]);
    		            if($order_id){
    		                $sevkVarMi = $this->Order_Model->getOrderMeta(["post_id" => $order_id, "meta_key" => "_sevk_tarih"]);
    		                if(!$sevkVarMi){
    		                    $this->Order_Model->setOrderMeta(["post_id" => $order_id, "meta_key" => "_sevk_tarih", "meta_value" => date("Y-m-d", strtotime($tarih))]);
    		                    $this->Order_Model->setOrderMeta(["post_id" => $order_id, "meta_key" => "_irsaliye_id", "meta_value" => $order->OrderID]);
    		                    $x++;
    		                }
    		              //  else{
    		              //      $this->Order_Model->updateOrderMeta(["post_id" => $order_id, "meta_key" => "_sevk_tarih"],["meta_value" => date("Y-m-d", strtotime($tarih))]);
    		              //      $x++;
    		              //  }
    		            }
    		        }
    		        echo $x . " tane siparis guncellendi.";
    		    }else{
    		        echo "TransferOrder bulunamadı!";
    		    }
    		} else {
    		    echo "veri yok";
    		}
    
        } catch(Exception $e){
            echo $e->getMessage();
        }
        
	    
	}

	public function getKargo(){

		if($this->json->id){

			$wsdl = "https://customerservices.araskargo.com.tr/ArasCargoCustomerIntegrationService/ArasCargoIntegrationService.svc?wsdl";
	        $user = "biancaboya";
	        $pass = "Bianca12345.";
	        $code = "2121654551542";
	        $id   = "1000" . $this->json->id;

	        try{
	            $client = new SoapClient($wsdl);
	            $sonuc = $client->GetQueryJSON([
	                "loginInfo" => "<LoginInfo><UserName>biancaboya</UserName><Password>Bianca12345.</Password><CustomerCode>2121654551542</CustomerCode></LoginInfo>",
	    	        "queryInfo" => "<QueryInfo><QueryType>39</QueryType><IntegrationCode>".$id."</IntegrationCode></QueryInfo>",
	            ]);
	            $sonuc = $sonuc->GetQueryJSONResult;
	            //echo json_encode($sonuc);
	            
	            
	            // QueryResult->Collection->KARGO_TAKIP_NO
	            echo $sonuc;
	        } catch(Exception $a){
	            echo json_encode($a);
	        }

		}

	}

}