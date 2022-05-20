<?php defined('BASEPATH') OR exit('No direct script access allowed');

if(!$this->session->userdata("login"))
  redirect( base_url("Login") );

$adminIDs = [1, 4]; ?>

<!doctype html>
<html lang="tr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo isset($title) ? $title.' - ' : null; ?>Elvanın Dünyası Client</title>
<meta name="googlebot" content="noindex, nofollow">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">

<style>
.static-item {
    /*border: 2px solid #f8f9fa;*/
    padding: 30px;
    margin-bottom: 30px;
    border-radius: 5px;
}
.static-item-title {
    display: block;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 13px;
}
.static-item-content {
    font-size: 20px;
    font-weight: 700;
}
/* .list-customers td{
  border-bottom: 0;
}
.list-customers .list-group-item+.list-group-item{
  border-top-width: 1px;
} */
</style>
<script>
function cikis(){
  var confirm = window.confirm("Emin Misin?");

  if(confirm){
    fetch("<?php echo base_url('User/cikis'); ?>")
    .then(response => response.json())
    .then(() => {
        window.location.href = "<?php echo base_url(); ?>"
    })
  }

  return false;
}
</script>


<style>
  .modal-footer {border: 0;}
  #user-card {margin-top: 20px; margin-bottom: 10px;display: flow-root;}
</style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light mb-2">
  <div class="container">
    <a class="navbar-brand" href="<?php echo base_url(); ?>">Satış Ortaklığı</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="<?php echo base_url(); ?>">Anasayfa</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo base_url("User/profil"); ?>">Profil</a>
        </li>
        <li class="nav-item d-none">
          <a class="nav-link" href="<?php echo base_url("User/takim"); ?>">Takım</a>
        </li>
        <li class="nav-item <?php echo !in_array($this->session->userdata("ID"), $adminIDs) ? 'd-none' : null; ?>">
          <a class="nav-link" href="<?php echo base_url('Customer_Cli/customers'); ?>">Ortaklık Ağacı</a>
        </li>
        <li class="nav-item <?php echo !in_array($this->session->userdata("ID"), $adminIDs) ? 'd-none' : null; ?>">
          <a class="nav-link" href="<?php echo base_url('Main/allOrdersByOrders'); ?>">Tüm Siparişler</a>
        </li>
        <li class="nav-item <?php echo !in_array($this->session->userdata("ID"), $adminIDs) ? 'd-none' : null; ?>">
          <a class="nav-link" href="<?php echo base_url('Customer_Cli/affiliates'); ?>">Liste</a>
        </li>
        <li class="nav-item <?php echo !in_array($this->session->userdata("ID"), $adminIDs) ? 'd-none' : null; ?>">
          <a class="nav-link" href="<?php echo base_url('Customer_Cli/siparisAktarma'); ?>">Sipariş Aktarma</a>
        </li>
        <li class="nav-item <?php echo !in_array($this->session->userdata("ID"), $adminIDs) ? 'd-none' : null; ?>">
          <a class="nav-link" href="<?php echo base_url('Home/rapor'); ?>">Rapor</a>
        </li>
        <li class="nav-item <?php echo !in_array($this->session->userdata("ID"), $adminIDs) ? 'd-none' : null; ?>">
          <a class="nav-link" href="<?php echo base_url('Ayarlar'); ?>">Ayarlar</a>
        </li>
        <li class="nav-item <?php echo !in_array($this->session->userdata("ID"), $adminIDs) ? 'd-none' : null; ?>">
          <a class="nav-link" href="<?php echo base_url('Customer_Cli/duplicate'); ?>">Duplicate</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#" onclick="cikis()">Çıkış</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<div id="app">