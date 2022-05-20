<?php $this->load->view('header', ['title' => 'Yuzde Ayarları']);?>

<div class="container">
  <div class="row mt-4 mb-4">
    <div class="col-12 col-sm">
      <div class="btn-group" role="group" aria-label="Basic example">
        <a class="btn btn-sm btn-light" href="<?php echo base_url('Ayarlar/yuzdeler'); ?>">Yüzdeler</a>
        <a class="btn btn-sm btn-secondary" href="#">Ekipler</a>
        <a class="btn btn-sm btn-light" href="<?php echo base_url('Ayarlar/cirolar'); ?>">Cirolar</a>
        <a class="btn btn-sm btn-light" href="<?php echo base_url('Ayarlar/haricler'); ?>">Hariçler</a>
      </div>
    </div>

    <div class="col-12 col-sm">
      <form class="row g-1 float-end" @submit.prevent="filter()">
        <div class="col-auto">
          <select class="form-select form-select-sm" v-model="filterForm.seviye">
            <option :value="null">Tüm Seviyeler</option>
            <option :value="1">1. Seviye</option>
            <option :value="2">2. Seviye</option>
            <option :value="3">3. Seviye</option>
          </select>
        </div>

        <div class="col-auto">
          <select class="form-select form-select-sm" v-model="filterForm.yil">
            <option :value="null">Yıl Seçin</option>
            <option :value="2022">2022</option>
            <option :value="2021">2021</option>
          </select>
        </div>

        <div class="col-auto">
          <select class="form-select form-select-sm" v-model="filterForm.ay">
            <option :value="null">Ay Seçin</option>
            <option v-for="ay in aylar" :key="ay" :value="ay">{{aylarText[ay]}}</option>
          </select>
        </div>

        <div class="col-auto">
          <button class="btn btn-primary btn-sm">Güncelle</button>
        </div>
      </form>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col">
      <button class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#yeniEkleForm" aria-expanded="false" aria-controls="yeniEkleForm">Yeni Ekle</button>

      <div class="collapse" id="yeniEkleForm">
        <div class="card card-body mt-3">
          <form @submit.prevent="ekle()">
            <div class="form-group mb-2">
              <label for="yil">Yıl Seçin</label>
              <select class="form-select" v-model="form.ekip_yil" required>
                <option :value="2022">2022</option>
                <option :value="2021">2021</option>
              </select>
            </div>

            <div class="form-group mb-2">
              <label for="ay">Ay Seçin</label>
              <select class="form-select" v-model="form.ekip_ay" required>
                <option v-for="ay in aylar" :key="ay" :value="ay">{{aylarText[ay]}}</option>
              </select>
            </div>

            <div class="form-group mb-2">
              <label for="seviye">Seviye Seçin</label>
              <select class="form-select" v-model="form.ekip_seviye" required>
                <option :value="0">0. Seviye</option>
                <option :value="1">1. Seviye</option>
                <option :value="2">2. Seviye</option>
                <option :value="3">3. Seviye</option>
              </select>
            </div>

            <div class="form-group mb-2">
              <label for="yuzde">Kişi Sayısı Girin</label>
              <input type="number" class="form-control" v-model="form.ekip_kisi" required>
            </div>

            <div class="form-group">
              <label for="yuzde">Yüzde Girin</label>
              <input type="number" class="form-control" v-model="form.ekip_prim" required>
            </div>

            <button type="submit" class="btn btn-primary btn-sm mt-2">Kaydet</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col">
      <div class="table-responsive" v-if="ekipler.length > 0">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Yıl</th>
              <th scope="col">Ay</th>
              <th scope="col">Seviye</th>
              <th scope="col">Kişi</th>
              <th scope="col">Yüzde</th>
              <th scope="col" width="130"></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="ekip in ekipler" :key="ekip.ekip_id">
              <td>{{ekip.ekip_yil}}</td>
              <td>{{ekip.ekip_ay}}</td>
              <td>{{ekip.ekip_seviye}}</td>
              <td>{{ekip.ekip_kisi}}</td>
              <td>+%{{ekip.ekip_prim}}</td>
              <td width="130">
                <ul class="list-inline m-0">
                  <li class="list-inline-item">
                    <a class="btn btn-secondary badge bg-secondary" href="#" @click="secilenItem = ekip" data-bs-toggle="modal" data-bs-target="#exampleModal">düzenle</a>
                  </li>

                  <li class="list-inline-item">
                    <a class="btn btn-danger badge bg-danger" href="#" @click="sil(ekip.ekip_id)">sil</a>
                  </li>
                </ul>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-else>
        <p>Seçili filtreye ait veri bulunamadı!</p>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body" v-if="secilenItem">
        <div class="form-group mb-2">
          <label for="yil">Yıl Seçin</label>
          <select class="form-select" v-model="secilenItem.ekip_yil">
            <option :value="2022">2022</option>
            <option :value="2021">2021</option>
          </select>
        </div>

        <div class="form-group mb-2">
          <label for="ay">Ay Seçin</label>
          <select class="form-select" v-model="secilenItem.ekip_ay">
            <option v-for="ay in aylar" :key="ay" :value="ay">{{aylarText[ay]}}</option>
          </select>
        </div>

        <div class="form-group mb-2">
          <label for="seviye">Seviye Seçin</label>
          <select class="form-select" v-model="secilenItem.ekip_seviye">
            <option :value="0">0. Seviye</option>
            <option :value="1">1. Seviye</option>
            <option :value="2">2. Seviye</option>
            <option :value="3">3. Seviye</option>
          </select>
        </div>

        <div class="form-group">
          <label for="yuzde">Kişi Sayısı Girin</label>
          <input type="number" class="form-control" v-model="secilenItem.ekip_kisi">
        </div>

        <div class="form-group">
          <label for="yuzde">Yüzde Girin</label>
          <input type="number" class="form-control" v-model="secilenItem.ekip_prim">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">İptal</button>
        <button type="submit" class="btn btn-primary" data-bs-dismiss="modal" @click="update()">Kaydet</button>
      </div>
    </div>
  </div>
</div>


</div>
<?php $this->load->view('footer');?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.16.6/sweetalert2.all.min.js"></script>
<script>
var app = new Vue({
  el: '#app',
  data: {
    url: "<?php echo base_url(); ?>",
    ekipler: <?php echo json_encode($ekipler); ?>,
    secilenItem: null,
    aylarText: ["Ay Seçin", "Ocak", "Şubat", "Mart", "Nisan", "Mayıs", "Haziran", "Temmuz", "Ağustos", "Eylül", "Ekim", "Kasım", "Aralık"],
    filterForm: {
      yil: Number(<?php echo json_encode($yil); ?>),
      ay: Number(<?php echo json_encode($ay); ?>),
      seviye: <?php echo json_encode($seviye); ?>,
    },
    form: {
      ekip_yil: Number(<?php echo json_encode($yil); ?>),
      ekip_ay: Number(<?php echo json_encode($ay); ?>),
      ekip_seviye: <?php echo json_encode($seviye); ?>,
      ekip_kisi: null,
      ekip_prim: null,
    },
  },

  mounted(){

  },

  methods: {
    update(){
      axios.post(this.url + "Post_Ayarlar/updateEkip", JSON.stringify(this.secilenItem)).then(r => {
        if(r.data.status){
          var index = this.ekipler.findIndex(c => c.ekip_id == r.data.result.ekip_id);
          if(index > -1){
            this.ekipler[index] = r.data.result;
          }
        }

        Swal.fire({
          icon: r.data.status ? 'success' : 'error',
          title: r.data.status ? 'Tebrikler' : 'Ooops',
          text: r.data.message
        })
      }).catch(() => {
        Swal.fire({
          icon: 'error',
          title: 'Ooooppss!',
          text: 'Bağlantı hatası oluştu! Lütfen daha sonra tekrar deneyin.'
        })
      })
    },

    filter(){
      var url = this.url + "Ayarlar/ekipler/";
      for (const [key, value] of Object.entries(this.filterForm)) {
        if(value){
          url = url + value + "/"
        }
      }
  		window.location.href = url;
    },

    ekle(){
      axios.post(this.url + "Post_Ayarlar/addEkip", JSON.stringify(this.form)).then(r => {
        if(r.data.status){
          this.ekipler.push(r.data.result);

          var myCollapse = document.getElementById('yeniEkleForm')
          var bsCollapse = new bootstrap.Collapse(myCollapse, {
            toggle: true
          })

        }

        Swal.fire({
          icon: r.data.status ? 'success' : 'error',
          title: r.data.status ? 'Tebrikler' : 'Ooops',
          text: r.data.message
        })
      })
    },

    sil(id){
      Swal.fire({
        icon: 'info',
        title: 'Emin Misin?',
        text: 'Sistemden kalici olarak silinecek!',
        showCancelButton: true,
        confirmButtonText: 'Evet, sil gitsin!',
        cancelButtonText: 'Hayır, iptal et!',
        cancelButtonColor: '#dc3545',
      }).then(result => {
        if(result.isConfirmed){
          axios.post(this.url + "Post_Ayarlar/deleteEkip", JSON.stringify({ekip_id: id})).then(r => {
            if(r.data.status){
              var index = this.ekipler.findIndex(c => c.ekip_id == id);
              if(index > -1){
                this.ekipler.splice(index, 1);
              }
            }
            Swal.fire({
              icon: r.data.status ? 'success' : 'error',
              title: r.data.status ? 'Tebrikler' : 'Ooops',
              text: r.data.message
            })
          }).catch(() => {
            Swal.fire({
              icon: 'error',
              title: 'Ooooppss!',
              text: 'Bağlantı hatası oluştu! Lütfen daha sonra tekrar deneyin.'
            })
          })

        }
      });
    },
  },

  computed: {
    aylar(){
      var aylar = [];
			for(var i=1; i<13; i++){
				aylar.push(i);
			}
			return aylar;
    }
  }

});
</script>
