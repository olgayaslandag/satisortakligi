<?php $this->load->view('header'); ?>	

<div class="container">
    <form @submit.prevent="guncelle" class="mt-3">
        <div class="row">
            <div class="col-12 col-sm">
                <div class="form-group mb-2">
                    <label>Ad</label>
                    <input name="adsoyad" class="form-control" v-model="user.ad" />
                </div>
            </div>
            <div class="col-12 col-sm">
                <div class="form-group mb-2">
                    <label>Soyad</label>
                    <input name="adsoyad" class="form-control" v-model="user.soyad" />
                </div>
            </div>
        </div>
        
        <div class="form-group mb-2">
            <label>Telefon</label>
            <input name="adsoyad" class="form-control" v-model="user._temsilci_tel" />
        </div>
        
        <div class="form-group mb-2">
            <label>Şehir</label>
            <select class="form-select" v-model="user._temsilci_sehir">
  	            <option :value="null">Şehir Seç</option>
  	            <option
  	                v-for="il in iller" 
  	                :key="il.il_id"
  	                :value="il.il_kod">{{il.il_plaka}} - {{il.il_ad}}</option>
  	        </select>
        </div>
        
        <div class="form-group mb-2">
            <label>İlçe</label>
            <input name="adsoyad" class="form-control" v-model="user._temsilci_ilce" />
        </div>
        
        <div class="form-group mb-2">
            <label>Instagram</label>
            <input name="adsoyad" class="form-control" v-model="user._temsilci_instagram" />
        </div>
        
        <div class="d-grid">
            <button class="btn btn-primary mt-2">Kaydet</button>
        </div>
    </form>
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
        user: <?php echo json_encode($user); ?>,
        user_id: <?php echo json_encode($this->session->userdata("ID"));?>,
        aff_id: <?php echo json_encode($this->session->userdata("aff_id"));?>,
        iller: <?php echo json_encode($iller); ?>,
        url: "https://satis.elvanindunyasi.com.tr/"
    },
    
    methods: {
        guncelle(){
  		var data = {
  		    first_name: this.user.ad,
  		    last_name: this.user.soyad,
  			affiliate_id: Number(this.user_id),
            aff_id: this.aff_id,
			_temsilci: this.user._temsilci,
			_temsilci_sehir: Number(this.user._temsilci_sehir),
			_temsilci_ilce: this.user._temsilci_ilce,
			_temsilci_instagram: this.user._temsilci_instagram,
			_temsilci_tel: this.user._temsilci_tel,
  		};
  		
  		var affData = {
  		    name: this.user.ad +" "+ this.user.soyad,
  		    user_id: Number(this.user_id)
  		}
  		console.log(data)
  		axios.post( this.url + "/User/update", JSON.stringify(data) ).then(r => {
  			if(r.data.status){
  			    axios.post( this.url + "/User/affUpdate", JSON.stringify(affData)).then(c => {
  			       if(c.data.status){
  			           Swal.fire({
    	  				title: 'Tebrikler',
    	  				icon: 'info',
    	  				text: r.data.message
    	  			   });
  			       } else {
  			           Swal.fire({
        	  				title: 'Hata Oluştu!',
        	  				icon: 'error',
        	  				text: r.data.message
        	  			});
  			       }
  			    })
  				
  			}else{
  				Swal.fire({
	  				title: 'Hata Oluştu!',
	  				icon: 'error',
	  				text: r.data.message
	  			});
  			}
  			
  		})
  	}
    }
});
</script>
</body>
</html>