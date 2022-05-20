<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public $json;

	public function __construct(){

		parent::__construct();
		$this->load->model('User_Model');
		date_default_timezone_set('Europe/Istanbul');

		header("Access-Control-Allow-Origin: *");
		$this->output->set_content_type('application/json');
	    $this->output->set_header('Access-Control-Allow-Origin: *');
	    $this->output->set_header('Access-Control-Allow-Methods: GET, OPTIONS');
	    $this->output->set_header('Access-Control-Allow-Methods: Content-Type, Content-Length, Accept-Encoding');
	    $this->json = (array)json_decode(file_get_contents('php://input'));

	}

	public function cikis(){

		$this->session->set_userdata( 'login', false );
		echo json_encode([
			"status" => true,
			"result" => null,
			"message"=> null,
		]);
		die();
	}

	public function login(){

		if($this->json){

			$this->load->library('phpass');
			$user_pass = $this->json['sifre'];
			$user_mail = $this->json['eposta'];
			
			$user = $this->User_Model->find(["user_email" => $user_mail]);

			if( $this->phpass->check($user_pass, $user[0]->user_pass) ){
			    
			    $this->load->model('Affiliate_Model');
			    $aff_id = $this->Affiliate_Model->getAffiliteUserId(["user_id" => $user[0]->ID]);
				
				if($aff_id){
				
    				foreach($user[0] as $key=>$val){
    
    					$this->session->set_userdata($key,$val);
    					
    				}
    				$this->session->set_userdata("aff_id", $aff_id);
    				$this->session->set_userdata('login', true);
    
    				echo json_encode([
    					"result" => $user[0],
    					"status" => true,
    					"message"=> "Giriş başarılı bir şekilde gerçekleştirildi."
    				]);
    				
				} else {
				    
				    echo json_encode([
    					"result" => $this->json,
    					"status" => false,
    					"message"=> "Satış ortaklığı programına kayıtlı değilsiniz!"
    				]);
				    
				}

			} else {

				echo json_encode([
					"result" => $this->json,
					"status" => false,
					"message"=> "Hatalı bilgi gönderdiniz!"
				]);

			}

		} else {

			echo json_encode([
				'status' => false,
				"result" => $this->json,
				"message"=> "Herhangi bir bilgi göndermediniz!"
			]);

		}

	}

	public function users(){

		$data = $this->User_Model->users();
		if($data){

			$x=0;
			foreach($data as $user){

				$x++;
			}
			echo json_encode([
				"status" => true,
				"result" => $data,
				"message"=> ""
			]);

		} else {

			echo json_encode([
				"status" => false,
				"result" => null,
				"message"=> ""
			]);

		}

	}

	public function update(){

		if( count($this->json) ){

			$user_id = $this->json['affiliate_id'];
			$aff_id = $this->json["aff_id"];

			unset($this->json["aff_id"]);
			unset($this->json['affiliate_id']);

			$data = [];
			foreach( $this->json as $k=>$v ){

                if($v === true){
                    $v = 1;
                }elseif($v === false){
                    $v = 0;
                }

                if($k=="_affiliate_disabled"){
                	if($v == 0){
                		$this->User_Model->updateAff(["affiliate_id" => $aff_id], ["status" => "pending"]);
                	}else{
                		$this->User_Model->updateAff(["affiliate_id" => $aff_id], ["status" => "active"]);
                	}
                }
				$find = $this->User_Model->get_usermeta(["user_id" => $user_id, "meta_key" => $k]);
				if($find){

					$this->User_Model->update_usermeta(
						[
						    "user_id" => $user_id, 
						    "meta_key" => $k
						],
						['meta_value' => $v]
					);

				}else{

					$this->User_Model->set_usermeta(
						[
						    "user_id" => $user_id, 
						    "meta_key" => $k, 
						    "meta_value" => $v
						]
					);

				}

				$data[$k] = $v;

			}

			echo json_encode([
				"status" => true,
				"message"=> "Bilgiler başarılı bir şekilde güncellendi.",
				"result" => $data,
			]);
			

		} else {

			echo json_encode([
				"status" => false,
				"message"=> "Eksik bilgi gönderdiniz!",
				"result" => $this->json,
			]);

		}

		

	}

	public function getAffiliates(){

		$aff_id = $this->json['aff_id'];
		$aff = $this->User_Model->getAffiliates(["affiliate_id" => $aff_id]);

		if($aff){

			echo json_encode([
				"status" => true,
				"result" => $aff,
				"message"=> ""
			]);

		} else {

			echo json_encode([
				"status" => false,
				"result" => $this->json,
				"message"=> "Kullanici bulunamadi"
			]);

		}

	}
	
	public function profil(){
	    
	    $this->output->set_content_type('text/html;charset=UTF-8');
	    $this->load->model("Customer_Model");
	    $aff = $this->Customer_Model->getUserMeta(["wp_usermeta.user_id" => $this->session->userdata("ID")]);
        $user = (Object)[];
        foreach($aff as $af){
			if($af->meta_key == "affiliate_parent"){
				$user->affiliate_parent = $af->meta_value;
			}
			if($af->meta_key == "affiliate_supervisor"){
				$user->affiliate_supervisor = $af->meta_value == 0 ? null : $af->meta_value;
			}
			if($af->meta_key == "first_name"){
				$user->ad = $af->meta_value;
			}
			if($af->meta_key == "last_name"){
				$user->soyad = $af->meta_value;
			}
			if($af->meta_key == "tc_kimlik"){
				$user->tc_kimlik = $af->meta_value;
			}
			if($af->meta_key == "affiliate_percent"){
				$user->affiliate_percent = $af->meta_value;
			}
			if($af->meta_key == "affiliate_lower_percent"){
				$user->affiliate_lower_percent = $af->meta_value;
			}
			if($af->meta_key == "_affiliate_disabled"){
				$user->_affiliate_disabled = $af->meta_value;
			}
			if($af->meta_key == "_temsilci"){
			    $user->_temsilci = $af->meta_value;
			}
			if($af->meta_key == "_temsilci_sehir"){
			    $user->_temsilci_sehir = $af->meta_value;
			}
			if($af->meta_key == "_temsilci_ilce"){
			    $user->_temsilci_ilce = $af->meta_value;
			}
			if($af->meta_key == "_temsilci_instagram"){
			    $user->_temsilci_instagram = $af->meta_value;
			}
			if($af->meta_key == "_temsilci_tel"){
			    $user->_temsilci_tel = $af->meta_value;
			}
			if($af->meta_key == "billing_phone"){
			    $user->billing_phone = $af->meta_value;
			}
		}

		if(!isset($user->affiliate_parent)){
			$user->affiliate_parent = 0;
		}
		if(!isset($user->affiliate_supervisor)){
			$user->affiliate_supervisor = false;
		}
		if(!isset($user->tc_kimlik)){
			$user->tc_kimlik = null;
		}
		if(!isset($user->affiliate_percent)){
			$user->affiliate_percent = null;
		}
		if(!isset($user->affiliate_lower_percent)){
			$user->affiliate_lower_percent = null;
		}
		if(!isset($user->_affiliate_disabled)){
			$user->_affiliate_disabled = true;
		}
		if($user->_affiliate_disabled == 0){
		    $user->_affiliate_disabled = false;
		}
		if(!isset($user->_temsilci)){
			$user->_temsilci = false;
		}
		if($user->_temsilci == 0){
		    $user->_temsilci = false;
		}
		if(!isset($user->_temsilci_sehir)){
			$user->_temsilci_sehir = null;
		}
		if(!isset($user->_temsilci_instagram)){
			$user->_temsilci_instagram = null;
		}
		if(!isset($user->_temsilci_tel)){
			$user->_temsilci_tel = null;
		}
		if(!isset($user->billing_phone)){
			$user->billing_phone = null;
		}


	    $this->load->view("profile", [
	        "user" => $user,
	        "iller"=> $this->Customer_Model->iller(),
	    ]);
	}
	
	public function takim(){
	    
	    redirect(base_url(), 'refresh');
	    $this->output->set_content_type('text/html;charset=UTF-8');
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
				if($af->meta_key == "_affiliate_disabled"){
					$customers[$x]->_affiliate_disabled = $af->meta_value;
				}
				if($af->meta_key == "_temsilci"){
				    $customers[$x]->_temsilci = $af->meta_value;
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
			if(!isset($customers[$x]->_temsilci)){
				$customers[$x]->_temsilci = false;
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
	    
	    $this->load->view("takim_agac", $data);
	    
	}
	
	public function affUpdate(){
	    
	    $user_id = $this->json["user_id"];
	    unset($this->json["user_id"]);
	    $data = $this->User_Model->getAffFromUser(["user_id" => $user_id]);
	    if($data){
	        
	        if($this->User_Model->updateAff(["affiliate_id" => $data[0]->affiliate_id], $this->json)){
	            
	            echo json_encode([
        	        "status" => true,
        	        "message"=> "Bilgileriniz başarıyla güncellendi.",
        	        "result" => $this->json
    	        ]);
	            
	        } else {
	            
	            echo json_encode([
        	        "status" => false,
        	        "message"=> "Bilgiler güncellenirken DB hatası oluştu!",
        	        "result" => $this->json
    	        ]);
    	        
	        }
	        
	    } else {
	        
	        echo json_encode([
    	        "status" => false,
    	        "message"=> "Böyle bir satış ortağı bulunamadı!",
    	        "result" => $this->json
	        ]);
	    
	    }
	    
	    
	}

    public function updateAllCustomers(){
        
        $x=0;
        foreach( $this->User_Model->getAffiliates() as $customer ){
            $aff_id = $customer->affiliate_id;
            $user_id = $customer->user_id;
            $veri = $this->User_Model->getUserMetaFromAff(["affiliate_id" => $aff_id]);
            
            $ad = null;
            foreach($veri as $v){
                if($v->meta_key == "first_name"){
                    $ad = $v->meta_value;
                }
                if($v->meta_key == "last_name"){
                    $ad .= " ".$v->meta_value;
                    break;
                }
                
            }
            
            // if($ad){
            //     if($aff_id != 1){
            //         $this->User_Model->updateWpUser(["ID" => $user_id], ["display_name" => $ad]);
            //         if($this->User_Model->updateAff(["affiliate_id" => $aff_id], ["name" => $ad])){
        	   //         $this->User_Model->updateWpUser(["ID" => $user_id], ["display_name" => $ad]);
        	   //         $x++;
        	            
        	   //     }    
            //     }
                
            // }
            
        }
        
        echo $x. " tane bilgi güncellendi.";
        
    }
    
}
