<?php $this->load->view('header');?>

<div class="container">
	<div class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<th width="150">Order Id</th>
					<th>Count</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="dup in duplicate" :key="dup.post_id">
					<td>{{dup.post_id}}</td>
					<td>{{dup.count}}</td>
					<td>{{findUser(dup.users)}}</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>



</div>	
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.16.6/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
var app = new Vue({
  el: '#app',
  data(){
  	return {
  		duplicate: <?php echo json_encode($duplicate); ?>,
  		users: <?php echo json_encode($users); ?>
  	}
  },

  mounted(){
  	
  },

  methods: {
  	findUser(ids){
  		var isimler = [];
  		ids.forEach(id => {
  			var index = this.users.findIndex(c => c.affiliate_id == id);
  			index > -1 ? isimler.push(this.users[index].name) : 1;
  		})
  		return isimler;
  	}
  }
});
</script>