<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_Model extends CI_Model {

	public function find($where=[]){

		return $this->db
		->where($where)
		->get("wp_users")->result();

	}
	
	public function updateWpUser($where=[], $data=[]){
	    
	    $this->db->where($where)->update("wp_users", $data);
	    return $this->db->affected_rows() > 0 ? TRUE : FALSE;
	    
	}

	public function users(){

		return $this->db->get('wp_users')->result();

	}

	public function get_usermeta($where=[]){

		return $this->db->where($where)->get("wp_usermeta")->result();

	}

	public function update_usermeta($where=[], $data=[]){

		$this->db->where($where)->update("wp_usermeta", $data);
		return $this->db->affected_rows() > 0 ? true : false;

	}

	public function set_usermeta($data=[]){

		$this->db->insert("wp_usermeta", $data);
		return $this->db->insert_id();

	}
	

	public function getAffiliates(){

		return $this->db
		// ->where("status", "active")
		->join("wp_aff_affiliates_users", "wp_aff_affiliates_users.affiliate_id=wp_aff_affiliates.affiliate_id")
		->get("wp_aff_affiliates")->result();

	}
	
	public function getUserMetaFromAff($where=[]){
	    
	    return $this->db
	    ->join("wp_aff_affiliates_users", "wp_aff_affiliates_users.user_id=wp_usermeta.user_id")
	    ->where($where)->get("wp_usermeta")->result();
	    
	}
	
	public function getAffFromUser($where=[]){
	    
	    return $this->db->where($where)->get("wp_aff_affiliates_users")->result();
	}
	
	public function updateAff($where=[], $data=[]){
	    
	    $this->db->where($where)->update("wp_aff_affiliates", $data);
	    return $this->db->affected_rows() > 0 ? TRUE : FALSE;
	    
	}

}
