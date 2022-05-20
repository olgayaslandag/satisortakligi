<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Ayarlar extends CI_Controller {

  public function __construct(){

    parent::__construct();
    $this->load->model("Ayarlar_Model");
    $adminIDs = [1, 4];
    if( !in_array($this->session->userdata("ID"), $adminIDs) )
      redirect( base_url("Home/dashboard") );

  }

  public function index(){

    $this->load->view("ayarlar/dashboard");

  }

  public function yuzdeler($yil=null, $ay=null, $seviye=null){
    $yil = $yil ? $yil : date('Y');
    $ay = $ay ? $ay : date('m');

    $queryWhere = ["prim_yil" => $yil, "prim_ay" => $ay];
    if($seviye){
      $queryWhere["prim_seviye"] = $seviye;
    }

    $data = [
      "yuzdeler" => $this->Ayarlar_Model->getYuzdeler($queryWhere),
      "yil" => $yil,
      "ay" => $ay,
      "seviye" => $seviye
    ];

    $this->load->view("ayarlar/yuzdeler", $data);

  }

  public function ekipler($yil=null, $ay=null, $seviye=null){
    $yil = $yil ? $yil : date('Y');
    $ay = $ay ? $ay : date('m');

    $queryWhere = ["ekip_yil" => $yil, "ekip_ay" => $ay];
    if($seviye){
      $queryWhere["ekip_seviye"] = $seviye;
    }

    $data = [
      "ekipler" => $this->Ayarlar_Model->getEkipler($queryWhere),
      "yil" => $yil,
      "ay" => $ay,
      "seviye" => $seviye
    ];

    $this->load->view("ayarlar/ekipler", $data);

  }

  public function cirolar($yil=null, $ay=null, $seviye=null){
    $yil = $yil ? $yil : date('Y');
    $ay = $ay ? $ay : date('m');

    $queryWhere = ["ciro_yil" => $yil, "ciro_ay" => $ay];
    if($seviye){
      $queryWhere["ciro_seviye"] = $seviye;
    }

    $data = [
      "cirolar" => $this->Ayarlar_Model->getCirolar($queryWhere),
      "yil" => $yil,
      "ay" => $ay,
      "seviye" => $seviye
    ];

    $this->load->view("ayarlar/cirolar", $data);

  }

  public function haricler($yil=null, $ay=null){
    $yil = $yil ? $yil : date('Y');
    $ay = $ay ? $ay : date('m');

    $queryWhere = ["haric_yil" => $yil, "haric_ay" => $ay];

    $data = [
      "haricler" => $this->Ayarlar_Model->getHaricler($queryWhere),
      "yil" => $yil,
      "ay" => $ay,
      "urunler" => $this->Ayarlar_Model->getProducts(),
    ];
    
    $this->load->view("ayarlar/haricler", $data);

  }

}
