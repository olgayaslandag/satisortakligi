<style>
ol, ul{padding-left: 32px!important;}
</style>
<?php $this->load->view('header', ['title' => 'Dashboard']); ?>
		<div v-if="user">
			<div class="container" id="list" v-if="customers">
				<div id="user-card">
		  			<span class="float-start mt-2">Hoşgeldin <b>{{seUser ? seUser.adsoyad : null}}</b></span>
						<div class="float-end d-none d-sm-block">
							<div class="float-end">
								<button class="btn btn-success" @click="exportTableToExcel()" style="margin-top: 5px;margin-left: 5px;">
										<i class="fas fa-cloud-download-alt"></i>
								</button>
							</div>
		        	<form @submit.prevent="guncelle()" class="float-end m-0">
								<ul class="list-inline p-0">
									<li class="list-inline-item">
										<select class="form-select form-select-sm mt-1" v-model="filterForm.orderType">
	                      <option :value="'sevk'">Sevk</option>
	                      <option :value="'order'">Sipariş</option>
	                  </select>
									</li>
									<li class="list-inline-item">
										<select class="form-select form-select-sm mt-1" v-model="filterForm.aktifPasif">
	                      <option :value="0">Hepsi</option>
	                      <option :value="1">Pasif Gizle</option>
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
	        	</div>

	        	<div class="float-end d-block d-sm-none">
	        		<button class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
						    Filtre
						  </button>
						  <div class="collapse" id="filterCollapse">
						  	<form @submit.prevent="guncelle()" class="float-start m-0">
									<ul class="list-inline p-0">
										<li class="list-inline-item">
											<select class="form-select form-select-sm mt-1" v-model="filterForm.orderType">
		                      <option :value="'sevk'">Sevk</option>
		                      <option :value="'order'">Sipariş</option>
		                  </select>
										</li>
										<li class="list-inline-item">
											<select class="form-select form-select-sm mt-1" v-model="filterForm.aktifPasif">
		                      <option :value="0">Hepsi</option>
		                      <option :value="1">Pasif Gizle</option>
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
								<div class="float-end">
									<button class="btn btn-success" @click="exportTableToExcel()" style="margin-top: 5px;margin-left: 5px;">
											<i class="fas fa-cloud-download-alt"></i>
									</button>
								</div>
						  </div>
	        	</div>
		  		</div>
			</div>
			<div class="container" v-if="seviyeTespit">
				<div class="list-customers table-responsive" style="zoom: 0.85;">
          <table class="table table-bordered table-hover" id="veriler">
             <thead v-if="orderType=='sevk'">
                <th scope="col-2">Ad Soyad</th>
                <th scope="col">Id No</th>
								<th scope="col"></th>
								<th scope="col"></th>
                <th scope="col"></th>
                <th scope="col"></th>
                <th scope="col" class="text-center">%</th>
								<th scope="col">%U</th>
								<th scope="col">%E</th>
								<th scope="col">%C</th>
								<th scope="col">%T</th>
                <th scope="col" class="text-end">TakımH.</th>
                <th scope="col" class="text-end">KendiH.</th>
                <th scope="col" class="text-end">DiğerH.</th>
                <th scope="col" class="text-end">ToplamH.</th>
								<th scope="col" class="text-end">Satış</th>
								<th scope="col" class="text-end">İade</th>
								<th scope="col" class="text-end d-none">İHariç</th>
								<th scope="col" class="text-end d-none">Sİade</th>

								<th scope="col" class="text-end d-none">İHHak</th>
								<th scope="col" class="text-end d-none">İSHak</th>
          			<th scope="col" class="text-end">D.Satış</th>
          			<th scope="col" class="text-end">T.Satış</th>
            </thead>
            <thead v-else>
            		<th>Ad Soyad</th>
                <th width="100">Id No</th>
								<th width="50"></th>
								<th width="50"></th>
								<th width="50"></th>
                <th width="100"></th>
                <th width="50" class="text-center">%</th>
                <th scope="col" class="text-end">İade</th>
                <th scope="col" class="text-end d-none">İHariç</th>
                <th width="150" class="text-end">Hazırlanıyor</th>
                <th width="150" class="text-end">Ö.Bekleniyor</th>
                <th width="150" class="text-end">Sipariş</th>
            </thead>
						<tbody v-html="drawTree(customers, aktifPasif)"></tbody>
          </table>
		    </div>

				<!-- Satışlar -->
				<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				  <div class="modal-dialog modal-xl">
				    <div class="modal-content">
				      <div class="modal-body">
				      	<div v-if="secilenOrtak != null">
			      	    <div class="row">
			      	        <div class="col-sm-3">
        		      	    <select class="form-select form-select-sm mb-3" v-model="secilenDurum">
      		      	        <option :value="null">Durum Seçin</option>
      		      	        <option value="wc-completed">Tamamlandı</option>
      		      	        <option value="wc-awaiting-shipment">Kargolandı</option>
      		      	        <option value="wc-processing">Hazırlanıyor</option>
      		      	        <!-- <option value="wc-cancelled">İptal Edildi</option> -->
      		      	        <option value="wc-sevk">Sevk</option>
      		      	        <!-- <option value="trash">Çöp</option> -->
      		      	        <option value="wc-on-hold">Ödeme Bekleniyor</option>
      		      	        <option value="wc-pending">Beklemede</option>
      		      	        <option :value="null">Hepsi</option>
        		      	    </select>
			      	        </div>
			      	        <div class="col-sm-9 text-end">
		      	            <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Kapat</button>
			      	        </div>
			      	    </div>
			      	    <div class="table-responsive" v-if="secilenOrtakSiparisler.length > 0">
						      	<table class="table">
						      		<thead>
						      			<th scope="col">#</th>
						      			<th scope="col">Ad Soyad</th>
						      			<th scope="col">Tarih</th>
						      			<th scope="col">Tel</th>
						      			<th scope="col">Sevk Tarih</th>
						      			<th scope="col">Durum</th>
	                      <th scope="col">Detay</th>
	                      <th scope="col">Kargo</th>
						      			<th scope="col" class="text-end">Toplam</th>
						      		</thead>
						      		<tbody>
							      		<tr v-for="(order, index) in secilenOrtakSiparisler" :key="order.ID" :class="classAta(order)">
							      			<td>{{order.ID}}</td>
							      			<td>{{order.adsoyad}}</td>
							      			<td>{{order.post_date.split(" ")[0]}}</td>
							      			<td>{{order.billing_phone}}</td>
							      			<td>{{order.sevk_tarih}}</td>
							      			<td>{{getStatus(order.post_status)}}</td>
	                        <td><button class="badge bg-primary border-0" @click="getOrder(order.ID)">Detay</button></td>
	                        <td><button class="badge bg-warning border-0" @click="getKargo(order.ID)">Kargo</button></td>
							      			<td class="text-end">{{turkLirasi(order.toplam)}}</td>
							      		</tr>
	                      <tr>
	                        <td></td>
	                        <td></td>
	                        <td></td>
	                        <td></td>
	                        <td></td>
	                        <td></td>
	                        <td></td>
	                        <td class="text-end"><strong>Toplam Satış</strong></td>
	                        <td class="text-end"><strong>{{turkLirasi(toplamSatisListe(secilenOrtakSiparisler))}}</strong></td>
	                      </tr>
	                      <tr>
	                        <td></td>
	                        <td></td>
	                        <td></td>
	                        <td></td>
	                        <td></td>
	                        <td></td>
	                        <td></td>
	                        <td class="text-end"><strong>Toplam Sipariş</strong></td>
	                        <td class="text-end"><strong>{{turkLirasi(toplamSiparisListe(secilenOrtakSiparisler))}}</strong></td>
	                      </tr>
						      		</tbody>
						      	</table>
					      	</div>
					      	<div v-else>
					      		<p class="text-danger">Henüz satış gerçekleştirilmedi!</p>
					      	</div>
						    </div>
				      </div>
				      <!--<div class="modal-footer">-->
				      <!--  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>-->
				      <!--</div>-->
				    </div>
				  </div>
				</div>
			</div>
			<div class="d-flex justify-content-center" style="height: 400px;" v-else>
				<div v-if="primler.length == 0" class="align-self-center">
					<span class="h5">Sistemde seçtiğiniz döneme ait prim oranları belirlenmemiş!</span>
				</div>
				<div v-else class="align-self-center">
					<span class="h5">Veriler Yükleniyor...</span>
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
	name: 'home',
  data: {
    user_id: <?php echo $_SESSION["ID"]; ?>,
  	url: "<?php echo base_url(); ?>",
    message: 'Elvanın Dünyası Client!',
    ccustomers: <?php echo json_encode($customers); ?>,
    primler: <?php echo json_encode($prim_seviye); ?>,
    orders: null,
    popup: null,
    secilenOrtak: null,
    secilenOrtakId: null,
    user: <?php echo json_encode($_SESSION); ?>,
    ay: <?php echo json_encode($ay); ?>,
    yil: <?php echo json_encode($yil); ?>,
		filterForm: {
			yil: <?php echo json_encode($yil); ?>,
			ay: <?php echo json_encode($ay); ?>,
			orderType: <?php echo json_encode($orderType); ?>,
			aktifPasif: <?php echo json_encode($aktifPasif); ?>,
		},
    secilenDurum: null,
    aktifPasif: <?php echo json_encode($aktifPasif); ?>,
    orderType: <?php echo json_encode($orderType); ?>,
    extraColumns: <?php echo $_SESSION["ID"]; ?> == 4 ? true : false,
		seviyeTespit: false,
		aylarText: ["Ay Seçin", "Ocak", "Şubat", "Mart", "Nisan", "Mayıs", "Haziran", "Temmuz", "Ağustos", "Eylül", "Ekim", "Kasım", "Aralık"],
		toplamlar: {
			toplamSatis: 0
		},
		satis_ciro: <?php echo json_encode($satis_ciro); ?>,
		satis_ekip: <?php echo json_encode($satis_ekip); ?>,
		sabitKargo: 25,
		haricItemler: <?php echo json_encode($haricler); ?>
  },

  created(){
    
  },

  methods:{
  	haricleriBul(id){
  		var toplam = 0;
      var index = this.ccustomers.findIndex(c => c.ID == id);
  		if(this.ccustomers[index]){
        var orders = this.ccustomers[index].orders.filter(c => c.hakedis === true);
        orders.forEach(c => {
        		c.items.forEach(t => {
        			this.haricItemler.forEach(r => {
        				if(t.product_id == r.haric_product){
        					toplam = toplam + Number(t.product_net_revenue) + Number(t.tax_amount);
        					// if(r.post_status == "wc-refunded"){
        					// 	toplam = toplam - Number(t.product_net_revenue) - Number(t.tax_amount);
        					// }else{
        					// 	toplam = toplam + Number(t.product_net_revenue) + Number(t.tax_amount);
        					// }
        				}
        			});
	        	})
        })
  		}
  		return Number(toplam);
  	},

  	iadeHaricleriBul(id){
  		var toplam = 0;
      var index = this.ccustomers.findIndex(c => c.ID == id);
  		if(this.ccustomers[index]){
        var orders = this.ccustomers[index].orders.filter(c => c.iade === true);
        orders.forEach(c => {
      		c.items.forEach(t => {
      			this.haricItemler.forEach(r => {
      				if(t.product_id == r.haric_product){
      					toplam = toplam + Number(t.product_net_revenue) + Number(t.tax_amount);
      				}
      			});
        	})
        })
  		}
  		return Number(toplam);
  	},

  	durumaGoreBul(id, durum){
  		var toplam = 0;
      var index = this.ccustomers.findIndex(c => c.ID == id);
  		if(this.ccustomers[index]){
        var orders = this.ccustomers[index].orders.filter(c => c.status == durum);
        toplam = orders.reduce((total, item) => total = total + Number(item.toplam), 0);
  		}
  		return Number(toplam);
  	},

  	hariclerHakedis(id){
  		var toplam = 0;
      var index = this.ccustomers.findIndex(c => c.ID == id);
  		if(this.ccustomers[index]){
        var orders = this.ccustomers[index].orders.filter(c => c.hakedis === true);
        orders.forEach(c => {
        		c.items.forEach(t => {
        			this.haricItemler.forEach(r => {
        				if(t.product_id == r.haric_product){
        					var fiyat = Number(t.product_net_revenue) + Number(t.tax_amount);
        					toplam = toplam + (fiyat * r.haric_yuzde / 100);
        				}
        			});
	        	})
        })
  		}
  		return Number(toplam);
  	},

  	iadeHariclerHakedis(id){
  		var toplam = 0;
      var index = this.ccustomers.findIndex(c => c.ID == id);
  		if(this.ccustomers[index]){
        var orders = this.ccustomers[index].orders.filter(c => c.iade === true);
        orders.forEach(c => {
        		c.items.forEach(t => {
        			this.haricItemler.forEach(r => {
        				if(t.product_id == r.haric_product){
        					var fiyat = Number(t.product_net_revenue) + Number(t.tax_amount);
        					toplam = toplam + (fiyat * r.haric_yuzde / 100);
        				}
        			});
	        	})
        })
  		}
  		return Number(toplam);
  	},

  	brutSatis(id){
  		var hakedis = 0;
  		//var iadeHakedis = 0;

      var index = this.ccustomers.findIndex(c => c.ID == id);
  		if(this.ccustomers[index]){
        var orders = this.ccustomers[index].orders.filter(c => c.hakedis === true);
  			hakedis = orders.reduce((total, item) => total = total + Number(item.toplam), 0);

  			// var ordersIades = this.ccustomers[index].orders.filter(c => c.iade === true);
  			// iadeHakedis = ordersIades.reduce((total, item) => total = total + Number(item.toplam), 0);
  		}
  		var haricler = this.haricleriBul(id);
  		// var iadeHaricler = this.iadeHaricleriBul(id);
      // return Number(hakedis) - Number(haricler) - Number(iadeHakedis) + Number(iadeHaricler);
      return Number(hakedis) - Number(haricler);
  	},

  	brutIade(id){
  		var iadeHakedis = 0;

      var index = this.ccustomers.findIndex(c => c.ID == id);
  		if(this.ccustomers[index]){
  			var ordersIades = this.ccustomers[index].orders.filter(c => c.iade === true);
  			iadeHakedis = ordersIades.reduce((total, item) => total = total + Number(item.toplam), 0);
  		}
  		var iadeHaricler = this.iadeHaricleriBul(id);
      return Number(iadeHakedis) + Number(iadeHaricler);
  	},

  	toplamSatis(id){
  		var hakedis = 0;
      var index = this.ccustomers.findIndex(c => c.ID == id);
  		if(this.ccustomers[index]){
        var orders = this.ccustomers[index].orders.filter(c => c.hakedis === true);
  			hakedis = orders.reduce((total, item) => total = total + Number(item.toplam), 0);
  		}
      return Number(hakedis);
  	},

    toplamSiparis(id){
      var hakedis = 0;
      var index = this.ccustomers.findIndex(c => c.ID == id);
      if(this.ccustomers[index]){
        hakedis = this.ccustomers[index].orders.filter(c => c.siparis).reduce((total, item) => total = total + Number(item.toplam), 0);
      }
      return Number(hakedis.toFixed(2));
    },

    toplamSatisListe(orders){
       hakedis = orders.filter(c => c.hakedis).reduce((total, item) => total = total + Number(item.toplam), 0);
  		return Number(hakedis.toFixed(2));
  	},

    toplamSiparisListe(orders){
        hakedis = orders.filter(c => c.siparis).reduce((total, item) => total = total + Number(item.toplam), 0);
        return Number(hakedis.toFixed(2));
    },

  	hakedis(item, yuzde){
  		var brutSatis = this.brutSatis(item.ID);
  		return Number(brutSatis) * yuzde / 100;
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

  	guncelle(){
			var data = [];
			for (const [key, value] of Object.entries(this.filterForm)) {
				if(value || value == 0){
					data.push(value + "/");
				}
			}
  		const url = this.url + "Home/index/" + data.join("");
  		window.location.href = url;
  	},

  	takimHakedislerIslem(items, yuzdeUst, tsatis=0, tiade=0, hakedis=0, yuzdeFark=0, sira=0){
  	    items.forEach(c => {
	        if(sira == 1){
						const yuzdeGetir = this.yuzdeGetir(c, c.seviye);
						const yuzde = yuzdeGetir.toplam;
						yuzdeFark = Number(yuzdeUst) - Number(yuzde)
            tsatis = 0;
	        }

	        if(sira > 0){
            tsatis = tsatis + this.brutSatis(c.ID);
            tiade = tiade + this.brutIade(c.ID);
	        }

	        if( c.children.length > 0 ){
              hakedis = this.takimHakedislerIslem(c.children, yuzdeUst, tsatis, tiade, hakedis, yuzdeFark, sira+1);
              tsatis = 0;
              tiade = 0;
	        }else{
	            hakedis = yuzdeFark > 0 ? hakedis + (tsatis * yuzdeFark / 100) - (tiade * yuzdeFark / 100) : hakedis;
	            tsatis = 0;
	            tiade = 0;
	        }

  	    });

  	    return hakedis;
  	},

  	drawTree(items, aktifPasif, repeat=0){
      var width = 530 - (repeat * 32);
      var data = [];
			var tekrar = "&nbsp&nbsp".repeat(repeat*2);
      var itemList = aktifPasif != 0 ? items.filter(c => c._affiliate_disabled == this.aktifPasif) : items;

      itemList.forEach(t => {
        var seviye = repeat > 3 ? 3 : repeat;
				var yuzdeGetir = this.yuzdeGetir(t, seviye);

				var yuzde = yuzdeGetir.yuzde;
				var uygulamaciOran = yuzdeGetir.uygulamaciPuan;
				var ekipOran = yuzdeGetir.ekipPuan;
				var ciroOran = yuzdeGetir.ciroPuan;
				var toplamOran = yuzdeGetir.toplam;


        var iadeler = this.durumaGoreBul(t.ID, "wc-refunded");
        var iadeHar = this.iadeHaricleriBul(t.ID);
        var standardIadeler = iadeler - iadeHar;
        var iadeHariclerHakedis = this.iadeHariclerHakedis(t.ID);


        var takimHakedisler = this.takimHakedislerIslem([t], toplamOran);
        var hariclerHakedis = this.hariclerHakedis(t.ID) - Number(iadeHariclerHakedis);
        var toplamHakedisler = Number(takimHakedisler) + Number(this.hakedis(t, toplamOran)) + Number(hariclerHakedis) - Number(standardIadeler * toplamOran / 100);
        var kendiHakedis = Number(this.hakedis(t, toplamOran)) - Number(standardIadeler * toplamOran / 100);
        

        var takimKurabilir = t.affiliate_supervisor ? "<span class='badge badge-sm bg-primary ms-1'>T</span>" : "";
        var uygulamaci = t._temsilci ? "<span class='badge badge-sm bg-warning'>U</span>" : "";
        var haritaci = t._temsilci_harita ? "<span class='badge badge-sm bg-success'>H</span>" : "";
        var aktif = t._affiliate_disabled == 1 ? "" : "<span class='badge badge-sm bg-danger'>P</span>";

        var sevkColumns = this.orderType == 'sevk' ? '<td>' + uygulamaciOran + '</td>\
					<td>' + ekipOran + '</td>\
					<td>' + ciroOran + '</td>\
					<td>' + toplamOran + '</td>\
          <td class="text-end">'+this.turkLirasi(takimHakedisler)+'</td>\
          <td class="text-end">'+this.turkLirasi(kendiHakedis)+'</td>\
          <td class="text-end">'+this.turkLirasi(Number(hariclerHakedis))+'</td>\
          <td class="text-end">'+this.turkLirasi(toplamHakedisler)+'</td>\
					<td class="text-end">'+this.turkLirasi(Number(this.brutSatis(t.ID)) - Number(standardIadeler))+'</td>\
					<td class="text-end">'+this.turkLirasi(iadeler)+'</td>\
					<td class="text-end d-none">'+this.turkLirasi(iadeHar)+'</td>\
					<td class="text-end d-none">'+this.turkLirasi(standardIadeler)+'</td>\
					<td class="text-end d-none">'+this.turkLirasi(iadeHariclerHakedis)+'</td>\
					<td class="text-end d-none">'+this.turkLirasi(Number(standardIadeler * toplamOran / 100))+'</td>\
          <td class="text-end">'+this.turkLirasi(this.haricleriBul(t.ID) - iadeHar)+'</td>' : '';

        var siparisColumns = this.orderType != 'sevk' ? '\
        	<td class="text-end">'+this.turkLirasi(iadeler)+'</td>\
        	<td class="text-end d-none">'+this.turkLirasi(iadeHar)+'</td>\
        	<td class="text-end">'+this.turkLirasi(this.durumaGoreBul(t.ID, "wc-processing"))+'</td>\
          <td class="text-end">'+this.turkLirasi(this.durumaGoreBul(t.ID, "wc-on-hold"))+'</td>' : '';

				data.push('<tr>\
					<td><span style="margin-left: '+repeat*32+'px; display: inline-block;font-size:0.9rem;"">'+t.adsoyad+' ' + aktif + '</span></td>\
					<td>' + t.ID + ' </td>\
					<td> ' + takimKurabilir + ' </td>\
					<td> ' + uygulamaci + ' </td>\
					<td> ' +  haritaci + ' </td>\
          <td>\
            <button onClick="secilenOrtakFunction('+t.ID+')" class="badge badge-sm bg-primary border-0" data-bs-toggle="modal" data-bs-target="#exampleModal">Detay</button>\
          </td>\
          <td>'+yuzde+'</td>' + sevkColumns + '\
          '+siparisColumns+'<td class="text-end">'+this.turkLirasi(this.toplamSiparis(t.ID) - iadeler)+'</td></tr>');

        if(t.children.length > 0){
            data.push(this.drawTree(t.children, aktifPasif, repeat+1));
        }

      });
      return data.join("");
    },

    secilenOrtakFunction(id){
      this.secilenOrtak = this.ccustomers.findIndex(c => c.ID == id);
    },

    getOrder(order_id){
      this.popupBekleme();
      this.secilenOrder = null;
      axios.post( this.url + "/Order/getOrder", JSON.stringify({order_id: order_id}) ).then(r => {
        this.secilenOrder = r.data;
        this.popup.close();
        var items = "";
        this.secilenOrder.line_items.forEach(c => {
          items += c.name + ' x ' + c.quantity + '<br/>';
        });
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            content: 'no-padding',
            htmlContainer: ''
          },
        });


        swalWithBootstrapButtons.fire({
          title: 'Siparişteki Ürünler',
          html: items,
          showCloseButton: true,
          showCancelButton: true,
          focusConfirm: false,
          confirmButtonText:
            '<i class="fa fa-thumbs-up"></i> Kapat',
          confirmButtonAriaLabel: 'Thumbs up, great!',
          cancelButtonAriaLabel: 'Thumbs down',
          showCancelButton: false,
          showConfirmButton: false,
        })
      })
    },

    popupBekleme(){
      this.popup = Swal.fire({
        icon: 'info',
        title: 'Lütfen Bekleyin.',
        text: 'Veriler getirilirken lütfen bekleyin!',
        showConfirmButton: false,
        allowOutsideClick: false,
      });
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

  	classAta(order){
	    var classText = null;
	    if(order.hakedis){
	        classText = 'text-success';
	    }else{
	        if(order.siparis){
	            classText = 'text-warning';
	        }else{
	            classText = 'text-danger';
	        }
	    }
	    return classText;
  	},

		seviye(items, aktifPasif, repeat=0){
			var itemList = aktifPasif != 0 ? items.filter(c => c._affiliate_disabled == this.aktifPasif) : items;

      itemList.forEach(t => {

        var seviye = repeat > 3 ? 3 : repeat;

				var index = this.ccustomers.findIndex(c => c.ID == t.ID);
				if(index > -1){
					this.ccustomers[index].seviye = seviye;
				}

				if(t.children.length > 0){
					this.seviye(t.children, aktifPasif, repeat+1)
				}
			});
			this.seviyeTespit = true;
		},

		tree(items, parentId=0){
			var data = [];
			items.forEach(c => {
				if(c.affiliate_parent == parentId){
					const children = this.tree(items, c.ID);
					c.children = children ? children : [];
					data.push(c);
				}
			})
			return data;
		},

		treeSelf(items, id=0){
			var data = [];
			items.forEach(c => {
				if(c.ID == id){
					const children = this.tree(items, c.ID);
					c.children = children ? children : [];
					data.push(c);
				}
			});
			return data;
		},

		ciroTespit(item, seviye){
			const ciro = this.satis_ciro.filter(c => c.ciro_seviye == seviye);
			const satis = this.toplamSatis(item.ID);
			var data = 0;
			ciro.forEach(c => {
				if(satis >= Number(c.ciro_min) & satis <= Number(c.ciro_max)){
					data = Number(c.ciro_prim);
				}
			});
			return data;
		},

		ekipSayisi(items, sayi=0, repeat=0){
			items.forEach(c => {
				if(c._affiliate_disabled){
					if(this.ciroTespit(c, c.seviye)){
						sayi++;
					}
				}
			});
			return sayi;
		},

		yuzdeGetir(items, seviye){
			var ekipSayi = 0;
			const ekip = this.ekipSayisi(items.children);

			const primler = this.primler.filter(c => c.prim_ay==this.ay & c.prim_yil==this.yil);
			const satis_ekip = this.satis_ekip.filter(c => c.ekip_seviye == seviye);
			satis_ekip.forEach(c => {
				if(ekip >= Number(c.ekip_kisi)){
					ekipSayi = c.ekip_prim
				}
			})

			const satis = this.toplamSatis(items.ID);
			const ekipPuan = seviye < 3 ? ekipSayi : 0;

			var uygulamaciPuan = items._temsilci ? 1 : 0;
			var ciroPuan = this.ciroTespit(items, seviye);
			var yuzde = primler.filter(p => Number(p.prim_seviye) == seviye);
			yuzde = yuzde.length > 0 ? yuzde[0] : primler[primler.length];
			yuzde = yuzde ? yuzde.prim_yuzde : 0;

			const res = {
				toplam: Number(yuzde) + Number(ekipPuan) + Number(uygulamaciPuan) + Number(ciroPuan),
				ekipPuan: Number(ekipPuan),
				yuzde: Number(yuzde),
				uygulamaciPuan: Number(uygulamaciPuan),
				ciroPuan: Number(ciroPuan)
			};

			return res;
		},

		exportTableToExcel(tableID='veriler', filename = ''){
	    var downloadLink;
	    var dataType = 'application/vnd.ms-excel';
	    var tableSelect = document.getElementById(tableID);
	    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

			filename = filename ? filename + '.xls':'excel_data.xls';
	    downloadLink = document.createElement("a");
	    document.body.appendChild(downloadLink);

	    if(navigator.msSaveOrOpenBlob){
	        var blob = new Blob(['\ufeff', tableHTML], {
	            type: dataType
	        });
	        navigator.msSaveOrOpenBlob( blob, filename);
	    }else{
	        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
	        downloadLink.download = filename;
	        downloadLink.click();
	    }
		},

		async getKargo(id){
			const result = await axios.post(this.url + "/Order/getKargo", JSON.stringify({id: id}));
			const sonuc = result.data;
			if(sonuc.QueryResult){
				alert(sonuc.QueryResult.Collection.KARGO_TAKIP_NO);
			}else{
				alert("Kargo takip numarası bulunamadı!")
			}
		}
  },

  watch: {
    secilenOrtak(secilen){
      this.secilenOrtakSiparisler = this.orderType=='sevk' ? this.ccustomers[secilen].orders.filter(c => c.hakedis==true) : this.ccustomers[secilen].orders;
      this.secilenDurum = null;
    },

    secilenDurum(durum){
      if(this.secilenOrtakSiparisler){
        if(durum){
          this.secilenOrtakSiparisler = this.ccustomers[this.secilenOrtak].orders.filter(c => c.post_status == durum);
        }else{
          this.secilenOrtakSiparisler = this.ccustomers[this.secilenOrtak].orders;
        }
      }
    },

    orderType(type){
      var url = null;
      if(type == 'order'){
        url = "<?php echo base_url('Home/indexOrder/'.$yil.'/'.$ay); ?>";
      }else{
        url = "<?php echo base_url('Home/index/'.$yil.'/'.$ay); ?>";
      }
      window.location.href = url;
    },

		customers(customers){
			this.seviye(customers, this.aktifPasif);
		}
  },

  computed: {
    seUser(){
      var index = this.ccustomers.findIndex(c => c.ID == this.user_id)
	    return index > -1 ? this.ccustomers[index] : null;
    },

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

		customers(){
			const customers = this.tree(this.ccustomers);
			this.seviye(customers, this.aktifPasif);
			return customers;
		},

  }
});

function secilenOrtakFunction(id){
  app.secilenOrtakFunction(id);
}
</script>
</body>
</html>
