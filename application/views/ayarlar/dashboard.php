<?php $this->load->view('header');?>

<div class="container">
  <div class="row">
    <div class="col">
      <div class="btn-group" role="group" aria-label="Basic example">
        <a class="btn btn-light" href="<?php echo base_url('Ayarlar/yuzdeler'); ?>">Yüzdeler</a>
        <a class="btn btn-light" href="<?php echo base_url('Ayarlar/ekipler'); ?>">Ekipler</a>
        <a class="btn btn-light" href="<?php echo base_url('Ayarlar/cirolar'); ?>">Cirolar</a>
        <a class="btn btn-light" href="<?php echo base_url('Ayarlar/haricler'); ?>">Hariçler</a>
      </div>
    </div>
  </div>
</div>

<?php $this->load->view('footer');?>
