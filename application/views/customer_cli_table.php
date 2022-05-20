<?php $this->load->view('header'); ?>
<div class="container">
  <div class="mt-3">
  	<div class="table-responsive">
  		<table class="table table-bordered table-hover">
  			<thead>
  				<tr>
  					<th>#</th>
  					<th>İsim</th>
  					<th>TC</th>
  					<th>Uygulamacı</th>
  					<th>U.Harita</th>
  					<th>Aktif</th>
  					<th>Şehir</th>
  					<th>İlçe</th>
  					<th>Instagram</th>
  					<th>Tel</th>
  				</tr>
  			</thead>
  			<tbody>
  				<tr v-for="customer in customers" :key="customer.ID">
  					<td>{{customer.ID}}</td>
  					<td>{{customer.adsoyad}}</td>
  					<td>{{customer.tc_kimlik}}</td>
  					<td>{{customer._temsilci ? 1 : 0}}</td>
  					<td>{{customer._temsilci_harita ? 1 : 0}}</td>
  					<td>{{customer._affiliate_disabled ? 1 : 0}}</td>
  					<td>{{getSehir(customer._temsilci_sehir)}}</td>
  					<td>{{customer._temsilci_ilce}}</td>
  					<td>{{customer._temsilci_instagram}}</td>
  					<td>{{customer._temsilci_tel}}</td>
  				</tr>
  			</tbody>
  		</table>
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
  	id: null,
  	customers: <?php echo json_encode($customers); ?>,
		url: "<?php echo base_url(); ?>",
		iller: <?php echo json_encode($iller); ?>,
		aktifPasif: 1,
		time: <?php echo json_encode(date("Y-m-d")); ?>
  },

  methods: {
  	getSehir(kod){
  		var data = {
  			il_ad: null,
  			il_kodId: null,
  			il_plaka: null,
  			il_kod: null
  		};
  		var index = this.iller.findIndex(c => c.il_kod == kod);
  		if(index > -1){
  			data = this.iller[index];
  		}
  		return data.il_ad ? data.il_ad : kod;
  	}
  },

});
</script>
</body>
</html>