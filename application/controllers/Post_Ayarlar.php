<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Post_Ayarlar extends CI_Controller{

	private $json;

	public function __construct(){

		parent::__construct();

		$this->load->model('Ayarlar_Model');
		date_default_timezone_set('Europe/Istanbul');

		// header("Access-Control-Allow-Origin: *");
		$this->output->set_content_type('application/json');
    $this->output->set_header('Access-Control-Allow-Origin: *');
    $this->output->set_header('Access-Control-Allow-Methods: GET, OPTIONS');
    $this->output->set_header('Access-Control-Allow-Methods: Content-Type, Content-Length, Accept-Encoding');
    $this->json = (Object)json_decode(file_get_contents('php://input'));

	}

  public function updateYuzde(){
    $json = $this->json;

    $id = $json->prim_id;
    unset($json->prim_id);

    if($this->Ayarlar_Model->updateYuzde(["prim_id" => $id], $json)){

      $data = $this->Ayarlar_Model->getYuzdeler(["prim_id" => $id]);
      if($data){

        echo json_encode([
          "status" => true,
          "message" => "Veri sistemde başarıyla güncellendi.",
          "result" => $data[0]
        ]);

      } else {

        echo json_encode([
          "status" => false,
          "message" => "Veri sistemde güncellendi fakat geri çağrılamadı!",
          "result" => $json
        ]);

      }

    } else {

      echo json_encode([
        "status" => false,
        "message" => "Veri sistemde güncellenemedi!",
        "result" => $json
      ]);
    }

  }

  public function addYuzde(){

    $id = $this->Ayarlar_Model->addYuzde($this->json);
    if($id){

      $data = $this->Ayarlar_Model->getYuzdeler(["prim_id" => $id]);
      if($data){

        echo json_encode([
          "status" => true,
          "message" => "Veri sistemde başarıyla eklendi.",
          "result" => $data[0]
        ]);

      } else {

        echo json_encode([
          "status" => false,
          "message" => "Veri sistemde eklenemedi!",
          "result" => $json
        ]);

      }

    }

  }

  public function deleteYuzde(){
    $json = $this->json;
    if($json->prim_id){

      if($this->Ayarlar_Model->deleteYuzde(["prim_id" => $json->prim_id])){

        echo json_encode([
          "status" => true,
          "message"=> "Veri sistemden başarıyla silindi.",
          "result" => $json
        ]);

      } else {

        echo json_encode([
          "status" => false,
          "message"=> "Veri sistemden silinemedi!",
          "result" => $json
        ]);

      }
    } else {

      echo json_encode([
        "status" => false,
        "message"=> "ID bilgisi göndermediniz!",
        "result" => $json
      ]);

    }

  }




  public function updateEkip(){

    $json = $this->json;

    $id = $json->ekip_id;
    unset($json->ekip_id);

    if($this->Ayarlar_Model->updateEkip(["ekip_id" => $id], $json)){

      $data = $this->Ayarlar_Model->getEkipler(["ekip_id" => $id]);
      if($data){

        echo json_encode([
          "status" => true,
          "message" => "Veri sistemde başarıyla güncellendi.",
          "result" => $data[0]
        ]);

      } else {

        echo json_encode([
          "status" => false,
          "message" => "Veri sistemde güncellendi fakat geri çağrılamadı!",
          "result" => $json
        ]);

      }

    } else {

      echo json_encode([
        "status" => false,
        "message" => "Veri sistemde güncellenemedi!",
        "result" => $json
      ]);
    }

  }

  public function addEkip(){

    $id = $this->Ayarlar_Model->addEkip($this->json);
    if($id){

      $data = $this->Ayarlar_Model->getEkipler(["ekip_id" => $id]);
      if($data){

        echo json_encode([
          "status" => true,
          "message" => "Veri sistemde başarıyla eklendi.",
          "result" => $data[0]
        ]);

      } else {

        echo json_encode([
          "status" => false,
          "message" => "Veri sistemde eklenemedi!",
          "result" => $json
        ]);

      }

    }

  }

  public function deleteEkip(){
    $json = $this->json;
    if($json->ekip_id){

      if($this->Ayarlar_Model->deleteEkip(["ekip_id" => $json->ekip_id])){

        echo json_encode([
          "status" => true,
          "message"=> "Veri sistemden başarıyla silindi.",
          "result" => $json
        ]);

      } else {

        echo json_encode([
          "status" => false,
          "message"=> "Veri sistemden silinemedi!",
          "result" => $json
        ]);

      }
    } else {

      echo json_encode([
        "status" => false,
        "message"=> "ID bilgisi göndermediniz!",
        "result" => $json
      ]);

    }

  }




  public function updateCiro(){

    $json = $this->json;

    $id = $json->ciro_id;
    unset($json->ciro_id);

    if($this->Ayarlar_Model->updateCiro(["ciro_id" => $id], $json)){

      $data = $this->Ayarlar_Model->getCirolar(["ciro_id" => $id]);
      if($data){

        echo json_encode([
          "status" => true,
          "message" => "Veri sistemde başarıyla güncellendi.",
          "result" => $data[0]
        ]);

      } else {

        echo json_encode([
          "status" => false,
          "message" => "Veri sistemde güncellendi fakat geri çağrılamadı!",
          "result" => $json
        ]);

      }

    } else {

      echo json_encode([
        "status" => false,
        "message" => "Veri sistemde güncellenemedi!",
        "result" => $json
      ]);
    }

  }

  public function addCiro(){

    $id = $this->Ayarlar_Model->addCiro($this->json);
    if($id){

      $data = $this->Ayarlar_Model->getCirolar(["ciro_id" => $id]);
      if($data){

        echo json_encode([
          "status" => true,
          "message" => "Veri sistemde başarıyla eklendi.",
          "result" => $data[0]
        ]);

      } else {

        echo json_encode([
          "status" => false,
          "message" => "Veri sistemde eklenemedi!",
          "result" => $json
        ]);

      }

    }

  }

  public function deleteCiro(){
    $json = $this->json;
    if($json->ciro_id){

      if($this->Ayarlar_Model->deleteCiro(["ciro_id" => $json->ciro_id])){

        echo json_encode([
          "status" => true,
          "message"=> "Veri sistemden başarıyla silindi.",
          "result" => $json
        ]);

      } else {

        echo json_encode([
          "status" => false,
          "message"=> "Veri sistemden silinemedi!",
          "result" => $json
        ]);

      }
    } else {

      echo json_encode([
        "status" => false,
        "message"=> "ID bilgisi göndermediniz!",
        "result" => $json
      ]);

    }

  }



  public function updateHaric(){

    $json = $this->json;

    $id = $json->haric_id;
    unset($json->haric_id);

    if($this->Ayarlar_Model->updateHaric(["haric_id" => $id], $json)){

      $data = $this->Ayarlar_Model->getHaricler(["haric_id" => $id]);
      if($data){

        echo json_encode([
          "status" => true,
          "message" => "Veri sistemde başarıyla güncellendi.",
          "result" => $data[0]
        ]);

      } else {

        echo json_encode([
          "status" => false,
          "message" => "Veri sistemde güncellendi fakat geri çağrılamadı!",
          "result" => $json
        ]);

      }

    } else {

      echo json_encode([
        "status" => false,
        "message" => "Veri sistemde güncellenemedi!",
        "result" => $json
      ]);
    }

  }

  public function addHaric(){

    $id = $this->Ayarlar_Model->addHaric($this->json);
    if($id){

      $data = $this->Ayarlar_Model->getHaricler(["haric_id" => $id]);
      if($data){

        echo json_encode([
          "status" => true,
          "message" => "Veri sistemde başarıyla eklendi.",
          "result" => $data[0]
        ]);

      } else {

        echo json_encode([
          "status" => false,
          "message" => "Veri sistemde eklenemedi!",
          "result" => $json
        ]);

      }

    }

  }

  public function deleteHaric(){
    $json = $this->json;
    if($json->haric_id){

      if($this->Ayarlar_Model->deleteHaric(["haric_id" => $json->haric_id])){

        echo json_encode([
          "status" => true,
          "message"=> "Veri sistemden başarıyla silindi.",
          "result" => $json
        ]);

      } else {

        echo json_encode([
          "status" => false,
          "message"=> "Veri sistemden silinemedi!",
          "result" => $json
        ]);

      }
    } else {

      echo json_encode([
        "status" => false,
        "message"=> "ID bilgisi göndermediniz!",
        "result" => $json
      ]);

    }

  }

}
