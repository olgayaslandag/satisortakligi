<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_Model extends CI_Model {

	public function find($where=[]){

		return $this->db
		->join("wp_aff_affiliates_users", "wp_aff_affiliates_users.user_id=wp_users.ID", "LEFT")
		->where($where)
		->get("wp_users")
		->result();

	}

	public function customers(){

		return $this->db
		->join("wp_aff_affiliates_users", "wp_aff_affiliates_users.user_id=wp_users.ID")
		->get('wp_users')->result();

	}

	public function customersTakim($where_in=[]){

		return $this->db
		->join("wp_aff_affiliates_users", "wp_aff_affiliates_users.user_id=wp_users.ID")
		->where_in("wp_users.ID", $where_in)
		->get('wp_users')->result();

	}

	public function getUserMeta($where=[]){

		return $this->db
		->join("wp_aff_affiliates_users", "wp_aff_affiliates_users.user_id=wp_usermeta.user_id", "LEFT")
// 		->join("wp_aff_affiliates", "wp_aff_affiliates.affiliate_id=wp_aff_affiliates_users.affiliate_id", "LEFT")
		->where($where)
		->get("wp_usermeta")
		->result();

	}

	public function setUserMeta($data=[]){

		$this->db->insert("wp_usermeta", $data);
		return $this->db->insert_id();

	}

	public function delUserMeta($where=[]){
	    
		$this->db->where($where)->delete("wp_usermeta");
		
	}
	
	public function iller(){
	    
	    return $this->db->get("wp_il")->result();
	    
	}

}
