<style>
ol, ul{padding-left: 32px!important;}
</style>
<?php $this->load->view('header', ['title' => 'Rapor']); ?>

<div class="container">
	<form @submit.prevent="guncelle()" class="float-start m-0">
		<ul class="list-inline p-0">
			<li class="list-inline-item">
				<select class="form-select form-select-sm mt-1" v-model="filterForm.durum">
            <option 
            	v-for="(durum, index) in durumlar" :key="index"
            	:value="durum">{{getStatus(durum)}}</option>
            <option :value="'order'">Sipariş</option>
        </select>
			</li>
			<li class="list-inline-item">
				<select class="form-select form-select-sm" v-model="filterForm.yil">
					<option :value="null">Yıl Seçin</option>
					<option v-for="item in yillar" :key="item" :value="item">{{item}}</option>
				</select>
			</li>
			<li class="list-inline-item">
				<select class="form-select form-select-sm" v-model="filterForm.ay">
					<option :value="null">Ay Seçin</option>
					<option v-for="item in aylar" :key="item" :value="item">{{aylarText[item]}}</option>
				</select>
			</li>
			<li class="list-inline-item">
				<button type="submit" class="btn btn-primary btn-sm">Güncelle</button>
			</li>
		</ul>
	</form>
	<div class="clearfix"></div>
	<div class="table-responsive">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>Sipariş No</th>
					<th>Toplam</th>
					<th>G.Toplam</th>
					<th>Tarih</th>
					<th>G.Tarih</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="veri in veriler" :key="veri.siparis_no">
					<td>{{veri.siparis_no}}</td>
					<td>{{veri.fiyat}}</td>
					<td>{{veri.komisyonlu ? veri.komisyonlu : veri.fiyat}}</td>
					<td>{{veri.tarih}}</td>
					<td>{{veri.update}}</td>
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
	name: 'rapor',
  data: {
  	url: "<?php echo base_url(); ?>",
    veriler: <?php echo json_encode($veriler); ?>,
    filterForm: {
    	durum: <?php echo json_encode($durum); ?>,
			yil: <?php echo json_encode($yil); ?>,
			ay: Number(<?php echo json_encode($ay); ?>),
		},
		durumlar: ["wc-completed", "wc-awaiting-shipment", 'wc-processing', 'wc-cancelled', 'wc-sevk', 'trash', 'wc-on-hold', 'wc-pending', 'wc-refunded'],
		aylarText: ["Ay Seçin", "Ocak", "Şubat", "Mart", "Nisan", "Mayıs", "Haziran", "Temmuz", "Ağustos", "Eylül", "Ekim", "Kasım", "Aralık"],
  },

  created(){
    
  },

  methods:{
  	statuses(){
  		var data = [];

  	},
  	guncelle(){
			var data = [];
			for (const [key, value] of Object.entries(this.filterForm)) {
				if(value || value == 0){
					data.push(value + "/");
				}
			}
  		const url = this.url + "Home/rapor/" + data.join("");
  		window.location.href = url;
  	},
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
  },

  computed: {
  	aylar(){
			var aylar = [];
			for(var i=1; i<13; i++){
				aylar.push(i);
			}
			return aylar;
		},

		yillar(){
			var yillar = [];
			for(var i=2021;i<=new Date().getFullYear(); i++){
				yillar.push(i);
			}
			return yillar;
		},
  }
});
</script>
</body>
</html>
