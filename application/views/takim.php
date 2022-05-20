<?php $this->load->view('header'); ?>
  <div v-if="user">
    <div class="container" v-if="customers">
      <div class="user-card">
        <div class="float-end">
          <form @submit.prevent="guncelle()">
            <div class="row">
              <div class="col-12 col-sm">
                <input name="start" v-model="start" required type="date" class="mt-1 form-control">
              </div>
              <div class="col-12 col-sm">
                <input name="finish" v-model="finish" required type="date" class="mt-1 form-control">
              </div>
              <div class="col-12 col-sm">
                <button class="btn btn-primary mt-1">Güncelle</button>
              </div>
              <div class="col d-none">
                <button class="btn btn-primary btn-sm">
                    <i class="fas fa-download"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="clearfix"></div>

      <div v-if="customers" v-html="drawTree(customers)"></div>

      <!-- <div v-for="(customer, ndx) in customers" :key="ndx">
        <div class="table-responsive" v-if="customer.orders.length > 0">
          <div class="user-card">
            <i class="fas fa-terminal"></i> <span>{{customer.adsoyad}}</span>
          </div>
          <table class="table">
            <thead>
              <th scope="col">#</th>
              <th scope="col">Ad Soyad</th>
              <th scope="col">Tarih</th>
              <th scope="col">Durum</th>
              <th scope="col">Ürünler</th>
              <th scope="col" class="text-end">Toplam</th>
            </thead>
            <tbody>
              <tr v-for="(order, index) in customer.orders" :key="index">
                <td>{{order.ID}}</td>
                <td>{{order.adsoyad}}</td>
                <td>{{order.post_date}}</td>
                <td>{{getStatus(order.post_status)}}</td>
                <td>
                  <button @click="getOrder(order.ID)" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#getOrder">Detay</button>
                </td>
                <td class="text-end">{{order.toplam}}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div v-else>
          <div class="user-card">
            <i class="fas fa-terminal"></i> <span>{{customer.adsoyad}}</span>
          </div>
          <div>
            <p>Henüz satış yapmadı!</p>
          </div>
        </div>
      </div> -->
    </div>

    <div class="container" v-else>
      <p>Takımınızda kimse yok!</p>
    </div>

    <!-- Order -->
    <div class="modal fade" id="getOrder" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content" v-if="secilenOrder">
          <div class="modal-body">
            <h5>{{secilenOrder.billing.first_name}} {{secilenOrder.billing.last_name}}</h5>
            <ul class="list-group">
              <li class="list-group-item" v-for="(item, index) in secilenOrder.line_items" :key="index">{{item.name}} x{{item.quantity}}</li>
            </ul>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Kapat</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>  



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.16.6/sweetalert2.all.min.js"></script>
<script>
var app = new Vue({
  el: '#app',
  data: {
    url: "<?php echo base_url(); ?>",
    message: 'Elvanın Dünyası Client!',
    ccustomers: <?php echo json_encode(tree($customers)); ?>,
    customers: null,
    orders: null,
    popup: null,
    secilenOrder: null,
    user: <?php echo json_encode($_SESSION); ?>,
    start: <?php echo json_encode($start[0]); ?>,
    finish: <?php echo json_encode($finish[0]); ?>,
  },

  mounted(){
    this.customers = this.ccustomers.filter(c => c.ID == this.user.ID);
  },

  methods:{
    popupBekleme(){
      this.popup = Swal.fire({
        icon: 'info',
        title: 'Lütfen Bekleyin.',
        text: 'Veriler getirilirken lütfen bekleyin!',
        showConfirmButton: false,
        allowOutsideClick: false,
      });
    },
    
    getStatus(status){
      return status
      .replace("wc-completed", "Tamamlandı")
      .replace("wc-awaiting-shipment", "Kargoya Verildi")
      .replace('wc-processing', 'Hazırlanıyor')
      .replace('wc-cancelled', 'İptal Edildi')
      .replace('wc-sevk', 'Sevk')
      .replace('trash', 'Çöp')
      .replace('wc-on-hold', 'Beklemede')
    },

    getOrder(order_id){
      this.popupBekleme();
      this.secilenOrder = null;
      axios.post( this.url + "/Order/getOrder", JSON.stringify({order_id: order_id}) ).then(r => {
        this.secilenOrder = r.data;
        this.popup.close();
      })
    },

    guncelle(){
      const url = this.url + "Customer_Cli/takim/" + this.start + "/" + this.finish;
      window.location.href = url;
    },

    drawTree(items, repeat=1){
      console.log(repeat)
      var baslangic = '<div class="table-responsive">';
      var data = [];
      var icon = '<i class="fas fa-terminal"></i>';
      items.forEach(t => {
        data.push(`<div class="user-card">\
              ${'<i class="fas fa-terminal"></i>'.repeat(repeat)} <span>${t.adsoyad}</span>\
            </div>\
            <table class="table">\
              <thead>\
                <th scope="col">#</th>\
                <th scope="col">Ad Soyad</th>\
                <th scope="col">Tarih</th>\
                <th scope="col">Durum</th>\
                <th scope="col">Ürünler</th>\
                <th scope="col" class="text-end">Toplam</th>\
              </thead>\
              <tbody>`);
        t.orders.forEach(c => {
          data.push('<tr>\
                <td>'+c.ID+'</td>\
                <td>'+c.adsoyad+'</td>\
                <td>'+c.post_date+'</td>\
                <td>'+this.getStatus(c.post_status)+'</td>\
                <td>\
                  <button onClick="getOrder('+c.ID+')" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#getOrder">Detay</button>\
                </td>\
                <td class="text-end">'+c.toplam+'</td>\
              </tr>');
        });
        data.push('</tbody></table>');
        repeat++;
        if(t.children.length > 0){
          data.push(this.drawTree(t.children, repeat));
        }
      });
      var bitis = '</div>';
      return baslangic + data.join("") + bitis;
    },
  },

})
function getOrder(id){
  app.getOrder(id);
}
</script>
</body>
</html>