<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Affiliate_Model extends CI_Model {

	public function affiliates(){

		return $this->db
		->where("wp_aff_affiliates.status !=", "deleted")
		->join("wp_aff_affiliates", "wp_aff_affiliates.affiliate_id=wp_aff_affiliates_users.user_id")
		->join("wp_users", "wp_users.ID=wp_aff_affiliates_users.affiliate_id")
		->get("wp_aff_affiliates_users")->result();

	}

	public function getAffiliates($id){

		return $this->db
		->like("meta_key", "affiliate_parent")
		->like("meta_value", $id)
		->get("wp_usermeta")
		->result();

	}

	public function getAffiliteUserId($where=[]){

		return $this->db->where($where)->get("wp_aff_affiliates_users")->result();

	}

	public function getAffiliateUser($where=[]){

		return $this->db->where($where)->get("wp_aff_affiliates")->result();

	}

	public function getOnlyAffiliates(){

		return $this->db->where("status !=", "deleted")->get("wp_aff_affiliates")->result();

	}

}