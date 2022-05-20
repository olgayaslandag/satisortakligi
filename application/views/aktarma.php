<?php $this->load->view('header');?>
	<div class="container">
	
    <form @submit.prevent="bul()">
      <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Sipariş No</label>
        <input type="text" v-model="order_id" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
        <div id="emailHelp" class="form-text">Sipariş numarasını girin.</div>
      </div>
      <button type="submit" class="btn btn-primary">Bul</button>
    </form>
		
    <div v-if="arandi" class="mt-5">
      <div class="table-responsive" v-if="order">
        <table class="table" id="tablo">
          <thead>
            <th scope="col">#</th>
            <th scope="col">Sipariş Sahibi</th>
            <th scope="col">Sipariş Tarihi</th>
            <th scope="col">Durum</th>
            <th scope="col">Satış Ortağı</th>
          </thead>
          <tbody>
            <tr>
              <td>{{order.id}}</td>
              <td>{{order.billing.first_name}} {{order.billing.last_name}}</td>
              <td>{{order.date_created}}</td>
              <td>{{getStatus(order.status)}}</td>
              <td>{{satisOrtagiGetir(satisOrtagi.affiliate_id)}}<button data-bs-toggle="modal" data-bs-target="#exampleModal" class="badge badge-sm bg-primary ms-3 border-0">değiştir</button></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-else>
        <p>Sipariş bulunamadı!</p>
      </div>
    </div>
	</div>

  <!-- Modal -->
  <div class="modal fade" id="exampleModal" aria-hidden="true" v-if="order">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <form @submit.prevent="degisDialog(order.id)">
         
              <label>Satış Ortağı Seçin</label>
              <select class="form-control mt-1 select2" v-model='secilenUser'>
                <option :value="null">Seçin...</option>
                <option 
                  v-for="aff in affiliates" 
                  :key="aff.affiliate_id"
                  v-if="aff._affiliate_disabled == 1"
                  :value="aff" style="text-transform: uppercase;">
                    <!--{{aff.user_id}} - {{aff.name}}-->
                    {{aff.name}} - #{{aff.user_id}}
                </option>
              </select>
              <button class="btn btn-primary mt-4">Değiştir</button>
          </form>
        </div>
        <!-- <div class="modal-footer">
          <button type="button" class="btn btn-primary" @click="degisDialog(order.id)" data-bs-dismiss="modal">Kaydet</button>
        </div> -->
      </div>
    </div>
  </div>
</div>	

<style>
.select2-container--open {
    z-index: 9999999
}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.16.6/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
var app = new Vue({
  el: '#app',
  data: {
  	url: "<?php echo base_url(); ?>",
    message: 'Elvanın Dünyası Client!',
    order_id: null,
    order: null,
    secilenOrder:null,
    popup: null,
    arandi: false,
    satisOrtagi: null,
    affiliates: <?php echo json_encode($affiliates); ?>,
    secilenUser: null,
  },

  methods: {
    popupBekleme(){
      this.popup = Swal.fire({
        icon: 'info',
        title: 'Lütfen Bekleyin.',
        text: 'Veriler getirilirken lütfen bekleyin!',
        showConfirmButton: false,
        allowOutsideClick: false,
      });
    },

    bul(){
      this.arandi = true;
      this.popupBekleme();

      axios.post( this.url + "/Order/getOrderAktarma", JSON.stringify({order_id: this.order_id}) ).then(r => {
        if(r.data){
          this.order = r.data.order;
          this.satisOrtagi = r.data.satis_ortagi;
          this.popup.close();
          //this.secilenUser = r.data.satis_ortagi.affiliate_id;
        }
      }).catch(() => {
        this.popup.close();
      })
    },

    getStatus(status){
        return status
        .replace("completed", "Tamamlandı")
        .replace("awaiting-shipment", "Kargoya Verildi")
        .replace('processing', 'Hazırlanıyor')
        .replace('cancelled', 'İptal Edildi')
        .replace('sevk', 'Sevk')
        .replace('trash', 'Çöp')
        .replace('on-hold', 'Ödeme Bekleniyor')
        .replace('pending', 'Beklemede')
    },

    degisDialog(order_id){
      if(this.secilenUser){
        if(this.secilenUser.affiliate_id != this.satisOrtagi.affiliate_id){
          axios.post( this.url + "/Order/orderAktar", JSON.stringify({order_id: order_id, aff_id: this.secilenUser.affiliate_id})).then(r => {
            if(r.data.status){
              this.satisOrtagi.affiliate_id = this.secilenUser.affiliate_id;
              Swal.fire({
                  icon: 'success',
                  title: 'Tebrikler',
                  text: 'Sipariş ilgili kişiye başarıyla aktarıldı',
                  confirmButtonText: 'Tamam'
              }).then(() => location.reload() );
            }
          })
        }else{
          alert("Zaten satış sahibini olan ortağı seçtiniz!");
        }
      }else{
        alert("Satış ortağı seçmediniz!");
      }
    },

    satisOrtagiGetir(affiliate_id){
      var data = "Yok";
      var index = this.affiliates.findIndex(c => c.affiliate_id == affiliate_id);
      if(index > -1){
        data = this.affiliates[index].name;
      }
      return data;
    },
  },
  
  created(){

    this.affiliates = this.affiliates.sort(function(a, b) {
      var nameA = a.name.toUpperCase(); // ignore upper and lowercase
      var nameB = b.name.toUpperCase(); // ignore upper and lowercase
      if (nameA < nameB) {
        return -1;
      }
      if (nameA > nameB) {
        return 1;
      }
    
      // names must be equal
      return 0;
    });
  }

})
</script>
</body>
</html>