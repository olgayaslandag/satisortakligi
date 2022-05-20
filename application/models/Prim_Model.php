<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Prim_Model extends CI_Model {

  public function getSeviyePrims($where=[]){

    return $this->db->where($where)->get("satis_primler")->result();

  }

  public function getSatisCiro($where=[]){

    return $this->db->where($where)->order_by("ciro_min", "ASC")->get("satis_ciro")->result();

  }

  public function getSatisEkip($where=[]){

    return $this->db->where($where)->order_by("ekip_kisi", "ASC")->get("satis_ekip")->result();

  }

  public function haricler($where=[]){

    return $this->db->where($where)->get("satis_haricler")->result();

  }

}
