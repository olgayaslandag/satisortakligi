<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Temsilci_Model extends CI_Model {
    
    public function getTemsilciler($where=[]){
        
        return $this->db->where($where)->order_by('rand()')->get("wp_usermeta")->result();
        
    }
    
    public function iller(){
        
        return $this->db->get("wp_il")->result();
        
    }
    
}