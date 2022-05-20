<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Temsilci extends CI_Controller {
    
    private $json_data;
    
    public function __construct(){
        
        parent::__construct();
        
        $this->load->model("Temsilci_Model");
        $this->output->set_content_type('application/json');
        $this->output->set_header('Access-Control-Allow-Origin: *');
        $this->output->set_header('Access-Control-Allow-Methods: GET, OPTIONS');
        $this->output->set_header('Access-Control-Allow-Methods: Content-Type, Content-Length, Accept-Encoding');
        $this->json_data = (array)json_decode(file_get_contents('php://input'));
        
    }
    
    public function getTemsilci(){
        header('Access-Control-Allow-Origin: https://elvanindunyasi.com.tr');
        $kod = $this->json_data["kod"];
    
        if($kod){
            
            $temsilciler = $this->Temsilci_Model->getTemsilciler(["meta_key" => "_temsilci_sehir", "meta_value" => $kod]);
            if($temsilciler){
                
                $x=0;
                foreach($temsilciler as $temsilci){
                    
                    $veri = $this->Temsilci_Model->getTemsilciler(["user_id" => $temsilci->user_id]);
                    $data = null;
                    foreach($veri as $v){
                        if($v->meta_key == "first_name"){
                            $temsilciler[$x]->name = $v->meta_value;
                        }
                        if($v->meta_key == "last_name"){
                            $temsilciler[$x]->lastname = $v->meta_value;
                        }
                        if($v->meta_key == "_temsilci_tel"){
                            $temsilciler[$x]->phone = $v->meta_value;
                        }
                        if($v->meta_key == "_temsilci"){
                            $temsilciler[$x]->_temsilci = $v->meta_value == 0 ? false : $v->meta_value;
                        }
                        if($v->meta_key == "_temsilci_harita"){
                            $temsilciler[$x]->_temsilci_harita = $v->meta_value == 0 ? false : $v->meta_value;
                        }
                        if($v->meta_key == "_temsilci_sehir"){
                            $temsilciler[$x]->_temsilci_sehir = $v->meta_value;
                        }
                        if($v->meta_key == "_temsilci_ilce"){
                            $temsilciler[$x]->_temsilci_ilce = $v->meta_value;
                        }
                        if($v->meta_key == "_temsilci_instagram"){
        				    $temsilciler[$x]->_temsilci_instagram = $v->meta_value;
        				}
                        
                    }
                    
                    if(!isset($temsilciler[$x]->_temsilci)){
                        $temsilciler[$x]->_temsilci = false;
                    }
                    if(!isset($temsilciler[$x]->_temsilci_harita)){
                        $temsilciler[$x]->_temsilci_harita = false;
                    }
                    if(!isset($temsilciler[$x]->_temsilci_sehir)){
                        $temsilciler[$x]->_temsilci_sehir = "";
                    }
                    if(!isset($temsilciler[$x]->_temsilci_ilce)){
                        $temsilciler[$x]->_temsilci_ilce = "";
                    }
                    if(!isset($temsilciler[$x]->_temsilci_instagram)){
        				$temsilciler[$x]->_temsilci_instagram = null;
        			}
                    
                    $x++;
                    
                }
                echo json_encode([
                    "status" => true,
                    "message"=> "",
                    "result" => $temsilciler,
                ]);
                
            } else {
                
                echo json_encode([
                    "status" => false,
                    "message"=> "Sistemde bu şehire ait temsilci bulunamadı!",
                    "result" => null,
                ]);
                
            }

        } else {
            
            echo json_encode([
                "status" => false,
                "message"=> "Şehir kodunu göndermediniz!",
                "result" => null,
            ]);
            
        }
        
    }
    
    public function iller(){
        
        echo json_encode( $this->Temsilci_Model->iller() );
        
    }
    
}