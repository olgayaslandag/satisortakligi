<?php $this->load->view('header'); ?>	
	<div v-if="user">
		<div class="container" id="list" v-if="orders">
			<div id="user-card">
	  			<span class="float-start mt-2">Hoşgeldin <b>{{user.display_name}}</b></span>
		        
		        <div class="float-end">
		        	<form @submit.prevent="guncelle()">
				        <div class="row">
				            <div class="col-12 col-sm">
				                <select class="form-select mt-1" v-model="orderType">
                                    <option value="sevk" :selected="orderType=='sevk' ? true : false">Sevk</option>
                                    <option value="order" :selected="orderType=='order' ? true : false">Sipariş</option>
                                </select>
				            </div>
				            <div class="col-12 col-sm">
				                <select class="form-select mt-1" v-model="aktifPasif">
                                    <option selected :value="null">Hepsi</option>
                                    <option :value="1">Pasif Gizle</option>
                                </select>
				            </div>
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
      	    
      	    <table class="table m-0">
                <thead>
                    <th scope="col">#</th>
                    <th scope="col" width="400">Ad Soyad</th>
                    <th scope="col">Tarih</th>
                    <th scope="col">Sevk Tarih</th>
                    <th scope="col">Durum</th>
                    <th scope="col">Ortak</th>
                    <th scope="col">S.Fiyat</th>
                    <th scope="col">K.Fiyat</th>
                    <!-- <th>Ust Id</th>
                    <th>Aff Id</th> -->
                </thead>
                <tbody>
                    <tr 
                        v-for="(order, index) in orders" 
                        :key="index" 
                        :class="{'text-danger': !order._affiliate_disabled}">
                        <td>{{order.ID}}</td>
                        <td>{{order.adsoyad}}</td>
                        <td>{{order.post_date.split(" ")[0]}}</td>
                        <td>{{order._sevk_tarih}}</td>
                        <td>{{getStatus(order.status)}}</td>
                        <td>{{order.affiliate_adsoyad}}</td>
                        <td>{{turkLirasi(order.toplam)}}</td>
                        <td>{{turkLirasi(order.komisyonlu)}}</td>
                        <!-- <td>{{order.affiliate_parent}}</td>
                        <td>{{order.user_id}}</td> -->
                    </tr>
                </tbody>
            </table>
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
        user: <?php echo json_encode($_SESSION); ?>,
        start: <?php echo json_encode($start[0]); ?>,
        finish: <?php echo json_encode($finish[0]); ?>,
        oorders: <?php echo json_encode($orders); ?>,
        orders: <?php echo json_encode($orders); ?>,
        aktifPasif: null,
        orderType: <?php echo json_encode($orderType); ?>,
    },
    
    mounted(){
      
    },
    
    methods:{
        
        getStatus(status){
            return status
      		.replace("wc-completed", "Tamamlandı")
      		.replace("wc-awaiting-shipment", "Kargoya Verildi")
      		.replace('wc-processing', 'Hazırlanıyor')
          	.replace('wc-cancelled', 'İptal Edildi')
          	.replace('wc-sevk', 'Sevk')
          	.replace('trash', 'Çöp')
          	.replace('wc-on-hold', 'Ödeme Bekleniyor')
          	.replace('wc-pending', 'Beklemede')
          	.replace('wc-refunded', 'İade')
        },
        
        guncelle(){
      		const url = this.url + "Main/allOrders/" + this.start + "/" + this.finish;
      		window.location.href = url;
      	},

        turkLirasi(price){
            var currency_symbol = "₺"
            var formattedOutput = new Intl.NumberFormat('tr-TR', {
              style: 'currency',
              currency: 'TRY',
              minimumFractionDigits: 2,
          });
          return formattedOutput.format(price).replace(currency_symbol, '');
        },
      
    },
    
    watch: {
        aktifPasif(aktifPasif){
            if(aktifPasif){
                this.orders = this.oorders.filter(c => c._affiliate_disabled == aktifPasif);
            }else{
                this.orders = this.oorders;
            }
        },
        
        orderType(type){
            var url = null;
            if(type == 'order'){
                url = "<?php echo base_url('Main/allOrdersByOrders/'.$start[0].'/'.$finish[0]); ?>";
            }else{
                url = "<?php echo base_url('Main/allOrders/'.$start[0].'/'.$finish[0]); ?>";
            }
            window.location.href = url;
        }
    }

});
</script>
</body>
</html>