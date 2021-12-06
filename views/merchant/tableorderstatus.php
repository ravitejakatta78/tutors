
<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use aryelds\sweetalert\SweetAlert;
?>
<header class="page-header">
<?php if (Yii::$app->session->hasFlash('success')): 
   echo SweetAlert::widget([
    'options' => [
        'title' => "Order!",
        'text' => "Order Placed Successfully",
        'type' => SweetAlert::TYPE_SUCCESS,
		'timer' => 3000,
    ]
]);
 endif; ?>
          </header>
          <section>
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header d-flex align-items-center pt-0 pb-0">
                <h3 class="h4 col-md-6 pl-0 tab-title">Curent Orders</h3>
				<div class="col-md-6 text-right pr-0">
				

				</div>
              </div>


              <div class="card-body">
                <div class="table-responsive">   
                  <table id="example" class="table table-striped table-bordered ">
                    <thead>
                      <tr>
                         <th>S No</th>
						<th>Table Name</th>
						<th>Current Status</th>
						<th>Amount</th>
						<th>Bill</th>
						<th>Status</th>
				   <th>Action</th>
                      </tr>
                    </thead>
						<tbody>
							<?php for($i=0;$i<count($tableDetails);$i++) { ?>
							<tr>
							<td><?= $i+1 ;?></td>
							<td><?= $tableDetails[$i]['name'] ;?></td>
							<td><?= Utility::orderstatus_details($tableDetails[$i]['orderprocess'] ?? 5) ;?></td>
							<td><?= $tableDetails[$i]['totalamount'] ;?></td>
							<td class="icons">
							<?php if(isset($tableDetails[$i]['orderprocess'])) { ?>
							<a onclick="billview('<?= $tableDetails[$i]['ID'] ;?>')"><span class="fa fa-eye"></span></a>
							<?php } ?>
							</td>
							<td>
							<?php if(isset($tableDetails[$i]['orderprocess'])) {
								?>
							<select id="change_status_<?= $tableDetails[$i]['ID'] ?>">
							<?php $orderFlowArr = Utility::orderflowdropdown($tableDetails[$i]['orderprocess']);
							foreach($orderFlowArr as $k => $v) { ?>
						<option value="<?= $k;?>" <?php if($tableDetails[$i]['orderprocess'] == $k){ echo "selected"; } ?>><?= $v;?></option>
						<?php } ?>

							</select>
							<?php } ?>
							</td>
							<td class="icons">
							<?php if(isset($tableDetails[$i]['orderprocess'])) { ?>
							<a onclick="tableorderstatuschange('<?= $tableDetails[$i]['ID'] ;?>','<?= $tableDetails[$i]['tableId'];?>')"><i class="fa fa-pencil-square-o"></i></a>
							<?php } ?>
							</td>
							
							</tr>
							<?php } ?>
						</tbody>
                  </table>

                </div>
              </div>
            </div>
          </div>
<div id="updatepilotstatus" class="modal fade" role="dialog">
<div class="modal-dialog modal-lg" >
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Assign Pilot</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
	    <div class="modal-body" id="pilotbody">
		<select id="pilotassign" name="pilotassign">
		<option value="">Select</option>
		<?php for($s=0;$s<count($resServiceBoy);$s++){?>
		<option value="<?= $resServiceBoy[$s]['ID'] ?>"><?= $resServiceBoy[$s]['name'] ?></option>
		<?php } ?>
		
		</select>
		<input type="hidden" id="currentid">
				<input type="hidden" id="tableId">
						<input type="hidden" id="chageStatusId">
		</div>
   <div class="modal-footer">
		<?= Html::submitButton('Assign', ['class'=> 'btn btn-add' , 'onclick'=>"changeorderwithpilot()"]); ?>
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
$(document).ready(function(){
	

});
function tableorderstatuschange(id,tableId)
{
	var chageStatusId= $("#change_status_"+id).val();
var data = $("#change_status_"+id).select2('data')
var select2text = data[0].text.trim();
if(select2text == 'Accept'){
	$("#currentid").val(id);
	$("#tableId").val(tableId);
	$("#chageStatusId").val(chageStatusId);
	$('#updatepilotstatus').modal('show');
}
else{
	changepilotstatus(id,chageStatusId,tableId,'');
}
	
}
function changepilotstatus(id,chageStatusId,tableId,pilotassign){
	var request = $.ajax({
  url: "tableorderstatuschange",
  type: "POST",
  data: {id : id,chageStatusId : chageStatusId,tableId:tableId,pilotassign},
}).done(function(msg) {
	
	swal({
				title: "Order Status", 
				text: "Order Status Changed Sucessfully", 
				type: "success",
				confirmButtonText: "Ok",
				showCancelButton: false
		    })
		    	.then((result) => {
					if (result.value) {
						location.reload();
					}
				});

	});	
}
function changeorderwithpilot(){
	
	var id = $("#currentid").val();
	var tableId = $("#tableId").val();
	var chageStatusId = $("#chageStatusId").val();
	var pilotassign = $("#pilotassign").val();
	changepilotstatus(id,chageStatusId,tableId,pilotassign);
}
</script>
