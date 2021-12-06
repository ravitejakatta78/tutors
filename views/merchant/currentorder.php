
<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use aryelds\sweetalert\SweetAlert;
$actionId = Yii::$app->controller->action->id;
?>
<style>
.fixed {position:fixed; top:0;right: 15px;width: 245px;}
.placeorder{background:#FD8B02;border:1px solid #FD8B02;color:#fff;border-radius:24px;}
.placeorder:hover{background:#28a745 !important;border:1px solid #28a745;color:#fff;}
.placeordercnl{border:1px solid #FD8B02;color:#fff;border-radius:24px;}
.placeordercnl:hover{border:1px solid #28a745;color:#fff;}
.orderactive{background: #f4b76e;}
.food-order .tile {
    background: #fff9f1;
    padding: 15px 15px;
    border-radius: 16px;
    box-shadow: 0px 4px 4px 4px #dfdfdf;
    min-height: 120px;
    width: 100%;
    margin-bottom: 30px;
    border: 1px solid #f9a540;
}
</style>
<script src="<?= Yii::$app->request->baseUrl.'/js/bootstrap-typeahead.js'?>"></script>



<header class="page-header">
<?php /*if (Yii::$app->session->hasFlash('success')): 
   echo SweetAlert::widget([
    'options' => [
        'title' => "Order!",
        'text' => "Order Placed Successfully",
        'type' => SweetAlert::TYPE_SUCCESS,
		'timer' => 3000,
    ]
]);
 endif; */ ?>
          </header>
 
		<section class="col-md-12">
		    <?= \Yii::$app->view->renderFile('@app/views/merchant/_orders.php',['actionId'=>$actionId]); ?>
		<div class="card-header d-flex align-items-center pt-0 pb-0">
                <h3 class="h4 col-md-6 pl-0 tab-title">Running Orders</h3>
				<div class="col-md-6 text-right pr-0">
				<button type="button" class="btn btn-add btn-xs placeorder" onclick="placeOrder('PARCEL','','')" ><i class="fa fa-plus mr-1"></i>New Order</button>
				</div>
              </div>
			  
		<div class="card mb-2">	
		<div class="card-body">
		<div class="row">
		<div class="col-md-6 text-center">
		<?php $orderCount = (array_values(array_filter(array_column($tableDetails,'orderprocess'))));

$parcelCount = (array_values(array_filter(array_column($parcelDetails,'orderprocess')))); 		?>
		<button class="btn orderactive btn-block" onclick="runningOrder('1')">Dine In <span class="ml-1 badge badge-success"><?= count($orderCount) ?? 0; ?></span></button>
		</div>
		<div class="col-md-6 text-center">
		<button class="btn btn-block btn-default" onclick="runningOrder('2')">Takeaway <span class="ml-1 badge badge-success"><?= count($parcelCount) ?? 0; ?></span></button>
		</div>
		</div>
				</div>
				</div>
				
		  <div class="row" style="margin-top:5px;">
          <div class="col-lg-12">
            <div class="card">
              
              <div class="card-header d-flex align-items-center">
                <h3 class="h4 float-left col-md-8">Dine In</h3>
				<div class="col-md-4 float-right">
				<input type="text" class="form-control" id="myInput">
				</div>
              </div>
              <div class="card-body p-3">
                <div class="main row"> 
                <!-- TENTH EXAMPLE -->
				
				<?php for($i=0;$i<count($tableDetails);$i++){

					  $table_status = ($tableDetails[$i]['table_status'] ?? 5);
					?>
					<div class="col-md-4 filtersearch food-order">
                <div class="tile active">
				<div class="row">
				<div class="col-md-8">
				<?= $tableDetails[$i]['name'];  ?>
				</div>
				<div class="col-md-4">
				<?= date('h:i A',strtotime($tableDetails[$i]['reg_date']));  ?>
				</div>
				</div>
				<div class="row">
				<div class="col-md-8">
				<?= Utility::orderstatus_details($tableDetails[$i]['orderprocess']) ; ?> 
				</div>
				<div class="col-md-4">
				<?= '₹ '.round($tableDetails[$i]['totalamount'],2);  ?>
				</div>
				</div>
				<div class="row">
				<div class="col-md-8">
				Bill View
				</div>
				<div class="col-md-4">
				<button type="button" class="badge badge-success mb-0" onclick="billview('<?= $tableDetails[$i]['ID'] ;?>');"><i class="fa fa-eye" title="BILL"></i></button>
				<button type="button" class="badge badge-success mb-0" onclick="placeOrder('<?= $tableDetails[$i]['ID'] ;?>','<?= $tableDetails[$i]['name'] ;?>','<?= $tableDetails[$i]['current_order_id'] ;?>')"><i class="fa fa-plus" title="Add More"></i></button>

				</div>
				</div>
				<div class="row">
				<div class="col-md-8">
				Order Type
				</div>
				<div class="col-md-4">
							<?php if($tableDetails[$i]['ordertype'] == 1){ echo 'Online'; }else{  echo 'Offline'; }  ?> 

				</div>
				</div>
				<div class="row">
				<div class="col-md-8">
				Pilot
				</div>
				<div class="col-md-4">
					 <?php $pilotName = \app\helpers\Utility::serviceboy_details($tableDetails[$i]['serviceboy_id'],'name');  
					 if(!empty($pilotName)){
					     echo $pilotName;
					 }else{
					     echo 'UnAssigned';
					 }
					 ?> 

				</div>
				</div>				
				<div class="text-center mt-1">
				<button class="placeorder btn" onclick="changepilotstatus('<?= $tableDetails[$i]['tableId'] ;?>','<?= $tableDetails[$i]['current_order_id'] ;?>','4','')">Completed</button>
		<!--		<button class="btn btn-danger btn-xs placeordercnl" onclick="tableorderstatuschange('<?= $tableDetails[$i]['ID'] ;?>','<?= $tableDetails[$i]['tableId'] ;?>','<?= $tableDetails[$i]['orderprocess'] ;?>')">Cancelled</button> -->
				<button class="btn btn-danger btn-xs placeordercnl" onclick="changepilotstatus('<?= $tableDetails[$i]['tableId'] ;?>','<?= $tableDetails[$i]['current_order_id'] ;?>','3','')">Cancelled</button>
				</div>
			</div>
				</div>
				<?php } ?>
				
				
				
				
				

            </div>
              </div>
            </div>
          </div>
		  
		  </div>
<div  id="updatepilotstatus" class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Status</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
       <div class="row>
	   <div class="col-md-4">
	   <div class="form-group">
	   <label>Status</label>
	   <select id="nextstatus" class="form-control">
	   </select>
	   <input type="hidden" id="orderid">
	   <input type="hidden" id="tableId">
	   </div>
	   </div>
	   </div>
      

      <!-- Modal footer -->
      <div class="modal-footer">
	  
	          <button type="button" class="btn btn-success" onclick="changepilotstatus()" data-dismiss="modal">Confirm</button>

        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
        </section>
		<script src="<?= Yii::$app->request->baseUrl.'/js/fontfaceobserver.min.js'?>"></script>
		<script src="<?= Yii::$app->request->baseUrl.'/js/menu.js'?>"></script>
<script>
$(document).ready(function(){
$(".nav__item:hover").parents('.nav__outer-wrap').css("overflow-x", "hidden");
});
$(window).scroll(function(){
      if ($(this).scrollTop() > 135) {
          $('#task_flyout').addClass('fixed');
      } else {
          $('#task_flyout').removeClass('fixed');
      }
  });
 





</script>
<script>
$(document).ready(function(){
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $(".filtersearch").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});

function tableorderstatuschange(id,tableId,orderprocess){
	$("#tableId").val(tableId);
	$("#orderid").val(id);
	$('#updatepilotstatus').modal('show');
	if(orderprocess == '1'){
		$("#nextstatus").html('<option value="2">Served</option><option value="3">Canceled</option>');

	}
	if(orderprocess == '2'){
		$("#nextstatus").html('<option value="4">Completed</option>');
	}
					$("#nextstatus").select2();

}

function changepilotstatus(tableId,id,chageStatusId,pilotassign){
//	var tableId = $("#tableId").val();
//	var id = $("#orderid").val();
//	var chageStatusId = $("#nextstatus").val();
//	var pilotassign = '';
	if(chageStatusId == ''){
/*	swal({
				title: "Select Status", 
				text: "Please Select the status", 
				type: "warning",
				confirmButtonText: "Ok",
				showCancelButton: false
		    })
		    	.then((result) => {
					if (result.value) {
						location.reload();
					}
				});*/


	return false;
	}
	
	    swal({
				title: "Are you sure??", 
				type: "warning",
				showCancelButton: true
		    })
		    	.then((result) => {
					if (result.value) {
						var request = $.ajax({
  url: "tableorderstatuschange",
  type: "POST",
  data: {id : id,chageStatusId : chageStatusId,tableId:tableId,pilotassign:pilotassign},
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
				});
	

}


function runningOrder(orderType)
{
if(orderType == 1){
	var url = 'currentorders';
	
}else{
		var url = 'parcels';
}
	    var form=document.createElement('form');
        form.setAttribute('method','post');
        form.setAttribute('action',url);
        //form.setAttribute('target','_blank');

   

    document.body.appendChild(form);
    form.submit();   
}
function placeOrder(id,name,current_order_id)
{
	        var form=document.createElement('form');
        form.setAttribute('method','post');
        form.setAttribute('action','placeorder');
        //form.setAttribute('target','_blank');

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "tableid");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", id);
    form.appendChild(hiddenField);

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "tableName");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", name);
    form.appendChild(hiddenField);

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "current_order_id");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", current_order_id);
    form.appendChild(hiddenField);

    document.body.appendChild(form);
    form.submit();   
}

function placeOrder(id,name,current_order_id)
{
	        var form=document.createElement('form');
        form.setAttribute('method','post');
        form.setAttribute('action','placeorder');
        //form.setAttribute('target','_blank');

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "tableid");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", id);
    form.appendChild(hiddenField);

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "tableName");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", name);
    form.appendChild(hiddenField);

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "current_order_id");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", current_order_id);
    form.appendChild(hiddenField);

    document.body.appendChild(form);
    form.submit();   
}

</script>