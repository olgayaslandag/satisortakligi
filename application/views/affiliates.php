<?php $this->load->view('header');?>
  <div>
  	<div class="container">
  	
  		<div class="table-responsive" >
  			<table class="table" id="tablo">
  				<thead>
            <th scope="col">#</th>
  					<th scope="col">Ad Soyad</th>
  					<th scope="col">E-Posta</th>
  					<th scope="col">Code</th>
  				</thead>
  				<tbody>
  					<tr v-for="(aff, index) in affiliates.filter(c => c.email != null && c.email != 'olgay@dinamikdizayn.net')" :key="aff.affiliate_id">
                        <td>{{index+1}}</td>
  						<td>{{aff.name}}</td>
  						<td>{{aff.email}}</td>
  						<td>
                <pre style="font-size: 11px;background-color: #ccc;padding: 5px;margin: 0;width: 455px;white-space: pre-line;">
                  https://elvanindunyasi.com.tr/?ref={{aff.affiliate_code}}
                </pre>
              </td>
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
  	url: "<?php echo base_url(); ?>",
    message: 'Elvanın Dünyası Client!',
    affiliates: <?php echo json_encode($affiliates); ?>
  },

})
</script>
</body>
</html>