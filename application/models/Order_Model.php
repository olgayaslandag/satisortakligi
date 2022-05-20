<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_Model extends CI_Model {

	public function orders(){

		return $this->db
		->where("post_status !=", "wc-cancelled")
		->where("post_status !=", "trash")
		->where("post_status !=", "wc-refunded")
		// ->where("post_status !=", "wc-pending")
		->where("post_type", "shop_order")
		->get("wp_posts")->result();

	}

	public function getOrderMeta($where=array()){

		return $this->db
		->where($where)
		->get("wp_postmeta")
		->result();

	}

	public function setOrderMeta($data=[]){

	    $this->db->insert("wp_postmeta", $data);
	    return $this->db->affected_rows() > 0 ? true : false;

	}

	public function updateOrderMeta($where=[], $data=[]){

	    $this->db->where($where)->update("wp_postmeta", $data);

	}

	public function getOrder($where=[]){

	    $data = $this->db->where($where)->get("wp_posts")->result();
	    return $data ? $data[0] : null;

	}

	public function getAffiliatesOrdersMainByOrder($where=[]){

		return $this->db
		->where($where)
		->where("post_status !=", "wc-cancelled")
		->where("post_status !=", "trash")
		// ->where("post_status !=", "wc-refunded")
		//->where("post_status !=", "wc-pending")
		->where("post_type", "shop_order")
		->join("wp_posts", "wp_posts.ID=wp_aff_referrals.post_id")
		->order_by("wp_aff_referrals.post_id", "DESC")
		->get("wp_aff_referrals")->result();

	}

	public function getAffiliatesOrdersMainByOrderSevk($where=[]){

		return $this->db
		->where($where)
		->where("post_status !=", "wc-cancelled")
		->where("post_status !=", "trash")
		// ->where("post_status !=", "wc-refunded")
		->where("post_type", "shop_order")
		->join("wp_posts", "wp_posts.ID=wp_aff_referrals.post_id")
		->join("wp_postmeta", "wp_postmeta.post_id=wp_aff_referrals.post_id")
		->order_by("wp_aff_referrals.post_id", "DESC")
		->get("wp_aff_referrals")->result();

	}

    public function getAffiliatesOrdersMain($where=[]){

		return $this->db
		->where($where)
        ->join("wp_aff_affiliates", "wp_aff_affiliates.affiliate_id=wp_aff_referrals.affiliate_id")
		->order_by("wp_aff_referrals.post_id", "DESC")
		->get("wp_aff_referrals")->result();

	}
	
	public function getAffiliatesOrdersMainApi($where=[]){
	    
	    return $this->db->where($where)->get("wp_aff_referrals")->result();
	    
	}
	
	public function delAffiliatesOrders($where=[]){
	    
	    $this->db->delete("wp_aff_referrals", $where);
	    return $this->db->affected_rows() > 0 ? true : false;
	    
	}

	public function getAffiliatesOrders($where_in=[], $where=[]){

		if(count($where) > 0){
			return $this->db
			->where_in('affiliate_id', $where_in)
			->where($where)
			->get("wp_aff_referrals")->result();
		} else {
			return $this->db
			->where_in('affiliate_id', $where_in)
			->get("wp_aff_referrals")->result();
		}

	}

	public function getOrders($where_in=[]){

		return $this->db
		->where_in('ID', $where_in)
		->where("post_status !=", "wc-cancelled")
		->where("post_status !=", "trash")
		// ->where("post_status !=", "wc-refunded")
		//->where("post_status !=", "wc-pending")
		->where("post_type", "shop_order")
		->order_by('ID', 'DESC')
		->get("wp_posts")
		->result();

	}

	public function getAllOrdersUser($where_in=[]){

		return $this->db
		->where_in('ID', $where_in)
		->where("post_status !=", "wc-cancelled")
		->where("post_status !=", "trash")
		// ->where("post_status !=", "wc-refunded")
		//->where("post_status !=", "wc-pending")
		->where("post_type", "shop_order")
		->get("wp_posts")
		->result();

	}

	public function getOrderStatus($where=[]){

		$data = $this->db->where($where)->get("wp_posts")->result();
		return $data ? $data[0]->post_status : null;

	}

	public function getOrderItem($where=[]){

		return $this->db->where($where)->get("wp_woocommerce_order_items")->result();

	}

	public function getAffiliatesUser($where=[]){

		$data = $this->db->where($where)->get("wp_aff_affiliates_users")->result();
		return $data ? $data[0] : false;

	}

	public function getAffiliatesData($where=[]){

		$data = $this->db->where($where)->get("wp_aff_affiliates")->result();
		return $data ? $data[0] : false;

	}

	public function updateAffiliateOrder($where=[], $data=[]){

		$this->db->where($where)->update('wp_aff_referrals', $data);

	}

	public function setAffiliateOrder($data=[]){

		$this->db->insert("wp_aff_referrals", $data);

	}

	public function allOrders($where=[]){

		return $this->db
		->where($where)
		->where("post_status !=", "wc-cancelled")
		->where("post_status !=", "trash")
		->where("post_status !=", "wc-refunded")
		//->where("post_status !=", "wc-pending")
		->where("post_type", "shop_order")
		->join("wp_postmeta", "wp_postmeta.post_id=wp_posts.ID")
		->order_by("wp_postmeta.meta_value", "DESC")
		->get("wp_posts")->result();

	}

	public function allOrdersByOrders($where=[]){

		return $this->db
		->where("post_status !=", "wc-cancelled")
		->where("post_status !=", "trash")
		->where("post_status !=", "wc-refunded")
		//->where("post_status !=", "wc-pending")
		->where("post_type", "shop_order")
		->where($where)
		//->join("wp_postmeta", "wp_postmeta.post_id=wp_posts.ID", "LEFT")
		->order_by("wp_posts.post_date", "DESC")
		->get("wp_posts")->result();

	}

	public function getOrderIdFromMiraOrderId($where=[]){

	    $data = $this->db->where($where)->get("wp_mira_order")->result();
	    return $data ? $data[0]->mira_product_order : false;

	}

	public function getOrderItems($where=[]){

		return $this->db->where($where)->get("wp_wc_order_product_lookup")->result();
	
	}

	public function duplicateFind(){

		return $this->db
		->select("COUNT(post_id) as count, post_id")
		->group_by("post_id")
		->having("COUNT(post_id) >1")
		->get("wp_aff_referrals")
		->result();

	}

	public function getMiraOrderPrice($where=[]){

		return $this->db->where($where)->get("wp_mira_order_price")->result();

	}

}
