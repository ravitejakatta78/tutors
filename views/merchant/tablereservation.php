<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use aryelds\sweetalert\SweetAlert;
?>
<header class="page-header">
                      <?php 
foreach (Yii::$app->session->getAllFlashes() as $message) {
    echo SweetAlert::widget([
        'options' => [
            'title' => (!empty($message['title'])) ? Html::encode($message['title']) : 'Title Not Set!',
            'text' => (!empty($message['text'])) ? Html::encode($message['text']) : 'Text Not Set!',
            'type' => (!empty($message['type'])) ? $message['type'] : SweetAlert::TYPE_INFO,
            'timer' => (!empty($message['timer'])) ? $message['timer'] : 4000,
            'showConfirmButton' =>  (!empty($message['showConfirmButton'])) ? $message['showConfirmButton'] : true
        ]
    ]);
}
?>
          </header>
          <section>
          <div class="col-lg-12">
            <div class="card">
              
              <div class="card-header d-flex align-items-center pt-0 pb-0">
                <h3 class="h4 col-md-6 pl-0 tab-title">New Reservations</h3>
				<div class="col-md-6 text-right pr-0">
<!--				<button type="button" class="btn btn-add btn-xs" id="myBtn" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus mr-1"></i> Add Gallery</button>
-->				</div>
              </div>
              <div class="card-body">
			  
                <div class="table-responsive">   
                  <table id="example" class="table table-striped table-bordered ">
                    <thead>
                      <tr>
									 	<th>S No</th>
                                    <th>User</th>
                                    <th>Table</th> 
									<th>Date</th> 
									<th>Time</th> 
									<th>Status</th> 
                      </tr>
                    </thead>
		    <tbody>
								<?php $x=1; 
										foreach($restablereservation as $productlist){
								?>
                                  <tr>
                                 	<td><?php echo $x;?></td>
                                 	<td><?php echo \app\helpers\Utility::user_details($productlist['user_id'],'name');?></td> 
                                 	<td><?php echo \app\helpers\Utility::table_details($productlist['table_id'],'name');?></td>
                                 	<td><?php echo $productlist['bookdate'];?></td>
                                 	<td><?php echo $productlist['booktime'];?></td>  
									 <td> 									 
										<select class="form-control changestatus" name="status" data-orderid="<?php echo $productlist["ID"];?>">
											
											<option value="0" <?php if($productlist['status']==''||$productlist['status']=='0'){ echo 'selected';}?>>Pending</option>
											<option value="1" <?php if($productlist['status']=='1'){ echo 'selected';}?>>Approved</option>
											<option value="2" <?php if($productlist['status']=='2'){ echo 'selected';}?>>Reject</option>
										</select>
									</td> 
									</tr>
									<?php $x++; }?>                     </tbody>
                  </table>
		  
                </div>
              </div>
            </div>
          </div>
        </section>
		<?php
$script = <<< JS
    $('#example').DataTable();
JS;
$this->registerJs($script);
?>
<script>
	 $(document).on('change','.changestatus',function(){	
	 var checked = $(this).data('orderid');
	 
	 var changestatus = $(this).val();
	 if(changestatus == '1'){
				 var label = 'Approve';
	 }
	else{
						 var label = 'Reject';
	}
	 swal({
				title: "Are you sure,you want to "+ label, 
				type: "warning",
				showCancelButton: true
		    })
		    	.then((result) => {
					if (result.value) {
					   $.ajax({				
	 url : 'reservationstatus',		 
	 type: "POST",		
	 data: {			
	 checked:checked,			
	 changestatus:changestatus			
	 },				
	 success: function(res){	
	 location.reload();
	 /* slience is golden */	
	 }					
	 });
					}
				});
	 
	 
	 
	 
	 
	 			
	 });
</script>




