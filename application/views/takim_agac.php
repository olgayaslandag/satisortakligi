<?php $this->load->view('header'); ?>
<div class="container">
    <div style="margin-left: -1.5rem;">
        <div class="ms-4 mt-3" v-if="customers.length < 1">
            <div class="mt-5">
                <p><b>Henüz takımında kimse yok!</b></p>
            </div>
        </div>
        
    	<div style="padding-right: 2rem;" v-html="drawTree(customers)"></div>
    
    
    
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
    	      		<tr>
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
    	  						<input v-model="secilenCustomer._affiliate_disabled" class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
    						</div>
    	      			</td>
    	      		</tr>
    	      		<tr>
    	      			<td><b>Takıma Ata</b></td>
    	      			<td>
    	      				<select class="form-control" v-model="secilenCustomer.affiliate_parent">
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
    	  						<input v-model="secilenCustomer._temsilci" class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
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
    	      		        <select class="form-control" v-model="secilenCustomer._temsilci_sehir">
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
    	      	</table>
    	      </div>
    	      <div class="modal-footer">
    	        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Kapat</button>
    	        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" @click="guncelle()">Kaydet</button>
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
  	ccustomers: <?php echo json_encode($customers); ?>,
  	//allCustomers: <?php echo json_encode($customers); ?>,
	customers: <?php echo json_encode(tree($customers, $this->session->userdata("ID"))); ?>,
	url: "<?php echo base_url(); ?>",
	iller: <?php echo json_encode($iller); ?>,
	allCustomers: [
	    {
	        ID: <?php echo json_encode($this->session->userdata("ID")); ?>,
	        user_email: <?php echo json_encode($this->session->userdata("user_email")); ?>,
	        affiliate_supervisor: 1
	    }
	]
  },
  
  mounted(){
      this.getUser(this.customers);
  },

  methods: {
  	drawTree(items){
    	var baslangic = '<ul class="mt-4">';
    	var data = [];
    	items.forEach(c => {
    		var takimKurabilir = c.affiliate_supervisor ? "<span class='badge badge-sm bg-primary'>T</span>" : "";
    		var uygulamaci = c._temsilci ? "<span class='badge badge-sm bg-success'>U</span>" : "";
    		var aktifPasif = c._affiliate_disabled ? "" : "<span class='badge badge-sm bg-danger'>P</span>";
    		var yuzde = "<span class='badge badge-sm bg-warning'>% " + c.affiliate_percent + "</span>";
    		data.push('<li class="list-group-item">');
    		data.push('<button \
						onClick="getir('+c.ID +')"\
						class="badge bg-transparent text-dark rounded-pill border-0"\
						data-bs-toggle="modal" \
						data-bs-target="#customerModal">\
							<i class="fas fa-cog"></i> &nbsp; &nbsp;')
    		data.push("#" + c.ID + " " + c.adsoyad + " " + takimKurabilir + " " + uygulamaci + " " + aktifPasif+ " " + yuzde);
    		data.push('</button>');
    		data.push('</li>');
    		data.push(this.drawTree(c.children));
    	});
    	var bitis = '</ul>';
    	return baslangic + data.join("") + bitis;
  	},

  	guncelle(){
  		var data = {
  			affiliate_id: this.secilenCustomer.user_id,
			affiliate_lower_percent: this.secilenCustomer.affiliate_lower_percent,
			affiliate_parent: this.secilenCustomer.affiliate_parent,
			affiliate_percent: this.secilenCustomer.affiliate_percent,
			affiliate_supervisor: this.secilenCustomer.affiliate_supervisor,
			_affiliate_disabled: this.secilenCustomer._affiliate_disabled,
			_temsilci: this.secilenCustomer._temsilci,
			_temsilci_sehir: this.secilenCustomer._temsilci_sehir,
			_temsilci_ilce: this.secilenCustomer._temsilci_ilce,
			_temsilci_instagram: this.secilenCustomer._temsilci_instagram,
			_temsilci_tel: this.secilenCustomer._temsilci_tel,
			
  		};
  		console.log('giden', data);
  		axios.post( this.url + "/User/update", JSON.stringify(data) ).then(r => {
  			if(r.data.status){
  				window.location.href = "<?php echo base_url('User/takim'); ?>";
  				Swal.fire({
	  				title: 'Tebrikler',
	  				icon: 'info',
	  				text: r.data.message
	  			});
  			}else{
  				Swal.fire({
	  				title: 'Hata Oluştu!',
	  				icon: 'error',
	  				text: r.data.message
	  			});
  			}
  			
  		})
  	},
  	
  	getUser(users){
  	    users.forEach(r => {
          this.allCustomers.push({
              ID: r.ID,
              user_email: r.user_email,
              affiliate_supervisor: r.affiliate_supervisor
          })
          if(r.children){
              this.getUser(r.children)
          }
      })
  	},
  },

  watch: {
  	id(id){
  		var index = this.ccustomers.findIndex(c => c.ID == id);
  		if(index > -1){
  			this.secilenCustomer = this.ccustomers[index];
  			console.log(this.secilenCustomer)
  		}
  	}
  }

});

function getir(id){
	app.id = id;
}
</script>
</body>
</html>