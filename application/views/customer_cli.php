<?php $this->load->view('header'); ?>
<div class="container">
	<div class="form-group col col-sm-2">
		<ul class="list-inline">
			<li class="list-inline-item">
				<select class="form-select" v-model="aktifPasif">
						<option :value="1">Aktif</option>
						<option :value="0">Hepsi</option>
				</select>
			</li>
			<li class="list-inline-item">
				<a href="<?php echo base_url("Customer_Cli/customersTable"); ?>" class="btn btn-default">Tablo</a>
			</li>
		</ul>
	</div>
  <div class="mt-3">
  	<div class="table-responsive">
  		<table class="table table-bordered table-hover">
  			<thead>
  				<tr>
  					<th>İsim</th>
  					<th width="50">T</th>
  					<th width="50">U</th>
  					<th width="50">H</th>
  					<th width="100"></th>
  				</tr>
  			</thead>
  			<tbody v-html="drawTree(customers)"></tbody>
  		</table>
  	</div>
  
  	<div v-if="customers" class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	  <div class="modal-dialog">
  	    <div class="modal-content">
  	      <div class="modal-body">
  	      	<table class="table" v-if="secilenCustomer">
  	      		<tr>
  	      			<td><b>ID:</b></td>
  	      			<td>#{{secilenCustomer.user_id}}</td>
  	      		</tr>
  	      		<tr>
  	      			<td><b>Ad Soyad:</b></td>
  	      			<td>{{secilenCustomer.adsoyad}}</td>
  	      		</tr>
  	      		<tr>
  	      			<td><b>TC Kimlik:</b></td>
  	      			<td>{{secilenCustomer.tc_kimlik}}</td>
  	      		</tr>
  	      		<tr>
  	      			<td><b>E-Posta:</b></td>
  	      			<td>{{secilenCustomer.user_email}}</td>
  	      		</tr>
  	      		<tr class="d-none">
  	      			<td><b> %</b></td>
  	      			<td><input v-model="secilenCustomer.affiliate_percent" type="text" class="form-control" placeholder="%" required></td>
  	      		</tr>
  	      		<tr>
  	      			<td><b>Takım Kurabilir?</b></td>
  	      			<td>
  	      				<div class="form-check form-switch">
  	  						<input v-model="secilenCustomer.affiliate_supervisor" class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
  						</div>
  	      			</td>
  	      		</tr>
  	      		<tr>
  	      			<td><b>Aktif?</b></td>
  	      			<td>
  	      				<div class="form-check form-switch">
  	  						<input class="form-check-input" type="checkbox" v-model="secilenCustomer._affiliate_disabled">
  						</div>
  	      			</td>
  	      		</tr>
  	      		<tr v-if="secilenCustomer._affiliate_disabled == false">
  	      			<td><b>Pasif Açıklama</b></td>
  	      			<td>
  	      				<textarea class="form-control" required v-model="secilenCustomer._affiliate_disabled_comment"></textarea>
  	      				<small>{{secilenCustomer._affiliate_disabled_time}}</small>
  	      			</td>
  	      		</tr>
  	      		<tr>
  	      			<td><b>Takıma Ata</b></td>
  	      			<td>
  	      				<select class="form-select" v-model="secilenCustomer.affiliate_parent">
  	      					<option :value="null">Takım Seç</option>
  	      					<option 
  	      						v-for="(customer, index) in allCustomers.filter(c => c.affiliate_supervisor == 1 && c.ID != secilenCustomer.ID)" 
  	      						:key="customer.ID"
  	      						:value="customer.ID">#{{customer.ID}} {{customer.user_email}}</option>
  	      				</select>
  	      			</td>
  	      		</tr>
  	      		<tr>
  	      			<td><b>Uygulamacı?</b></td>
  	      			<td>
  	      				<div class="form-check form-switch">
  	  						<input class="form-check-input" type="checkbox" v-model="secilenCustomer._temsilci">
  						</div>
  	      			</td>
  	      		</tr>
  	      		<tr>
  	      			<td><b>Haritada Görünsün?</b></td>
  	      			<td>
  	      				<div class="form-check form-switch">
  	  						<input class="form-check-input" type="checkbox" v-model="secilenCustomer._temsilci_harita">
  						</div>
  	      			</td>
  	      		</tr>
  	      		<tr>
  	      		    <td><b>Telefon</b></td>
  	      		    <td>
  	      		        <input class="form-control" v-model="secilenCustomer._temsilci_tel" type="text" />
  	      		        </div>
  	      		    </td>
  	      		</tr>
  	      		<tr>
  	      		    <td><b>Şehir</b></td>
  	      		    <td>
  	      		        <select class="form-select" v-model="secilenCustomer._temsilci_sehir">
  	      		            <option :value="null">Şehir Seç</option>
  	      		            <option
  	      		                v-for="il in iller" 
  	      		                :key="il.il_id"
  	      		                :value="il.il_kod">{{il.il_plaka}} - {{il.il_ad}}</option>
  	      		        </select>
  	      		    </td>
  	      		</tr>
  	      		<tr>
  	      		    <td><b>İlçe</b></td>
  	      		    <td>
  	      		        <input type="text" class="form-control" v-model="secilenCustomer._temsilci_ilce">
  	      		    </td>
  	      		</tr>
  	      		<tr>
  	      		    <td><b>Instagram</b></td>
  	      		    <td>
  	      		        <input type="text" class="form-control" v-model="secilenCustomer._temsilci_instagram">
  	      		    </td>
  	      		</tr>
  	      		<tr>
  	      			<td><b>Form Şehir</b></td>
  	      			<td>{{secilenCustomer._katilim_sehir}}</td>
  	      		</tr>
  	      		<tr>
  	      			<td><b>Form Ekip Lideri</b></td>
  	      			<td>{{secilenCustomer.ekip_lideri}}</td>
  	      		</tr>
  	      	</table>
  	      </div>
  	      <div class="modal-footer">
  	        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Kapat</button>
  	        <button type="button" class="btn btn-primary" @click="guncelle()">Kaydet</button>
  	      </div>
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
  	id: null,
  	secilenCustomer: null,
  	sabitcustomer: null,
  	allCustomers: <?php echo json_encode($customers); ?>,
		customers: <?php echo json_encode(tree($customers)); ?>,
		url: "<?php echo base_url(); ?>",
		iller: <?php echo json_encode($iller); ?>,
		aktifPasif: 1,
		time: <?php echo json_encode(date("Y-m-d")); ?>
  },

  methods: {
  	drawTree(items, repeat=0){
  		var data = [];
  		var itemList = this.aktifPasif != 0 ? items.filter(c => c._affiliate_disabled == this.aktifPasif) : items;

  		itemList.forEach(c => {
  			var takimKurabilir = c.affiliate_supervisor ? "<span class='badge badge-sm bg-primary'>T</span>" : "";
	  		var uygulamaci = c._temsilci ? "<span class='badge badge-sm bg-success'>U</span>" : "";
	  		var harita = c._temsilci_harita ? "<span class='badge badge-sm bg-info'>H</span>" : "";
	  		var aktifPasif = c._affiliate_disabled ? "" : "<span class='badge badge-sm bg-danger'>P</span>";
	  		var yuzde = "<span class='badge badge-sm bg-warning'>% " + c.affiliate_percent + "</span>";
	  		data.push('<tr>\
	  			<td><span style="margin-left: '+repeat*16+'px; display: inline-block;font-size:0.9rem;"">'+c.adsoyad+' ' + aktifPasif + '</span></td>\
	  			<td>'+ takimKurabilir +'</td>\
	  			<td>'+ uygulamaci +'</td>\
	  			<td>'+ harita +'</td>\
	  			<td><button class="badge bg-light text-dark border-0" onClick="getir('+c.ID +')" data-bs-toggle="modal" data-bs-target="#customerModal">Düzenle</button></td>\
	  			</tr>');
	  		if(c.children.length > 0){
            data.push(this.drawTree(c.children, repeat+1));
        }
  		});
  		return data.join("");
  	},

  	guncelle(){
  		if(!this.secilenCustomer._affiliate_disabled){
  			if(!this.secilenCustomer._affiliate_disabled_comment){
  				Swal.fire({
  					icon: 'info',
  					title: 'Yorum alanı boş bırakılamaz!',
  					text: 'Kullanıcı pasif olduğu için yorum alanını boş bırakamazsınız!',
  					confirmButtonText: 'Tamam'
  				});
  				return false;
  			}
  		}
  		var data = {
  			aff_id: this.secilenCustomer.affiliate_id,
  			affiliate_id: this.secilenCustomer.user_id,
				affiliate_lower_percent: this.secilenCustomer.affiliate_lower_percent,
				affiliate_parent: this.secilenCustomer.affiliate_parent,
				affiliate_percent: this.secilenCustomer.affiliate_percent,
				affiliate_supervisor: this.secilenCustomer.affiliate_supervisor,
				_affiliate_disabled: this.secilenCustomer._affiliate_disabled,
				_temsilci: this.secilenCustomer._temsilci,
				_temsilci_harita: this.secilenCustomer._temsilci_harita,
				_temsilci_sehir: this.secilenCustomer._temsilci_sehir,
				_temsilci_ilce: this.secilenCustomer._temsilci_ilce,
				_temsilci_instagram: this.secilenCustomer._temsilci_instagram,
				_temsilci_tel: this.secilenCustomer._temsilci_tel,
  		};

  		if(this.secilenCustomer._affiliate_disabled != this.sabitcustomer._affiliate_disabled){
  			if(!this.secilenCustomer._affiliate_disabled){
  				data._affiliate_disabled_comment = this.secilenCustomer._affiliate_disabled_comment+"||"+this.time;
  			}
  		}
  		console.log(data);

  		axios.post( this.url + "/User/update", JSON.stringify(data) ).then(r => {
  			if(r.data.status){
  				Swal.fire({
	  				title: 'Tebrikler',
	  				icon: 'info',
	  				text: r.data.message,
	  				confirmButtonText: 'Tamam'
	  			}).then(() => window.location.href = "<?php echo base_url('Customer_Cli/customers'); ?>" );
  			}else{
  				Swal.fire({
	  				title: 'Hata Oluştu!',
	  				icon: 'error',
	  				text: r.data.message,
	  				confirmButtonText: 'Tamam'
	  			});
  			}
  			
  		})
  	}
  },

  watch: {
  	id(id){
  		var index = this.allCustomers.findIndex(c => c.ID == id);
  		if(index > -1){
  			this.secilenCustomer = this.allCustomers[index];
  			this.sabitcustomer = {...this.allCustomers[index]};
  		}
  	}
  },

});

function getir(id){
	app.id = id;
}
</script>
</body>
</html>