<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Ayarlar_Model extends CI_Model {

  public function getYuzdeler($where=[]){

    return $this->db->where($where)->order_by("prim_yil ASC, prim_ay ASC")->get("satis_primler")->result();

  }

  public function updateYuzde($where=[], $data=[]){

    $this->db->where($where)->update("satis_primler", $data);
    return $this->db->affected_rows() > 0 ? TRUE : FALSE;

  }

  public function addYuzde($data=[]){

    $this->db->insert("satis_primler", $data);
    return $this->db->insert_id();

  }

  public function deleteYuzde($where=[]){

    $this->db->where($where)->delete("satis_primler");
    return $this->db->affected_rows() > 0 ? true : false;

  }




  public function getEkipler($where=[]){

    return $this->db->where($where)->order_by("ekip_yil ASC, ekip_ay ASC")->get("satis_ekip")->result();

  }

  public function updateEkip($where=[], $data=[]){

    $this->db->where($where)->update("satis_ekip", $data);
    return $this->db->affected_rows() > 0 ? TRUE : FALSE;

  }

  public function addEkip($data=[]){

    $this->db->insert("satis_ekip", $data);
    return $this->db->insert_id();

  }

  public function deleteEkip($where=[]){

    $this->db->where($where)->delete("satis_ekip");
    return $this->db->affected_rows() > 0 ? true : false;

  }




  public function getCirolar($where=[]){

    return $this->db->where($where)->order_by("ciro_yil ASC, ciro_ay ASC")->get("satis_ciro")->result();

  }

  public function updateCiro($where=[], $data=[]){

    $this->db->where($where)->update("satis_ciro", $data);
    return $this->db->affected_rows() > 0 ? TRUE : FALSE;

  }

  public function addCiro($data=[]){

    $this->db->insert("satis_ciro", $data);
    return $this->db->insert_id();

  }

  public function deleteCiro($where=[]){

    $this->db->where($where)->delete("satis_ciro");
    return $this->db->affected_rows() > 0 ? true : false;

  }





  public function getHaricler($where=[]){

    return $this->db->where($where)->order_by("haric_yil ASC, haric_ay ASC")->get("satis_haricler")->result();

  }

  public function updateHaric($where=[], $data=[]){

    $this->db->where($where)->update("satis_haricler", $data);
    return $this->db->affected_rows() > 0 ? TRUE : FALSE;

  }

  public function addHaric($data=[]){

    $this->db->insert("satis_haricler", $data);
    return $this->db->insert_id();

  }

  public function deleteHaric($where=[]){

    $this->db->where($where)->delete("satis_haricler");
    return $this->db->affected_rows() > 0 ? true : false;

  }

  public function getProducts(){

    return $this->db->select("ID, post_title")->where("post_type", "product")->get("wp_posts")->result();

  }

}
