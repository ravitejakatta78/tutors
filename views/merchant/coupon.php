<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use aryelds\sweetalert\SweetAlert;
use yii\helpers\Url;
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
    <div class="loading" style="display:none">
  		<img src="<?php echo Url::base(); ?>/img/load.gif" >
  	</div>
          <section>
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header d-flex align-items-center pt-0 pb-0">
                <h3 class="h4 col-md-6 pl-0 tab-title">Coupons</h3>
				<div class="col-md-6 text-right pr-0">
				<button type="button" class="btn btn-add btn-xs btn-hide" id="myBtn" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus mr-1"></i> Add Coupon</button>

				</div>
              </div>


              <div class="card-body">
                <div class="table-responsive">   
                  <table id="example" class="table table-striped table-bordered ">
                    <thead>
                      <tr>
						<th>S.No.</th>
						<th>Coupon Type</th> 
						<th>Code</th>
						<th>Price</th>
						<th>Max amount</th>
						<th>Fromdate</th>
						<th>Todate</th>       
						<th>Status</th>
						<th>Status Action</th>
						<th>Date & Time</th>
						<th>Actions</th>  
						
					</tr>
                    </thead>
		    <tbody>
			<?php $x= 1; foreach($couponModel as $couponModel) { ?>
						<tr>	
                    						
											<td><?=$x;?></td>

											<td><?=$couponModel['purpose'];?></td>
 

											<td><?=$couponModel['code'];?></td>
 
											<td><?php if($couponModel['type']=='amount'){ echo '<i class="fa fa-inr"></i>';}?><?=$couponModel['price'];?>
											<?php if($couponModel['type']=='percent'){ echo '%';}?></td>

											<td><?=$couponModel['maxamt'];?></td>
											<td><?=$couponModel['fromdate'];?></td>

											<td><?=$couponModel['todate'];?></td>	

											<td><?=$couponModel['status'];?> </td>
<td>
											<label class="switch">
						<input type="checkbox"  <?php if($couponModel['status']=='Active'){ echo 'checked';}?> onChange="changestatus('merchant_coupon','<?= $couponModel['ID']?>')">
						<span class="slider round"></span> 
						</label>
											</td>
											<td><?=$couponModel['reg_date'];?></td>
											<td class="icons"><a  onclick="editcoupon('<?= $couponModel['ID']?>');"><span class="fa fa-pencil"></span></a>
											<a  onclick="deletecoupon('<?= $couponModel['ID']?>');"><span class="fa fa-trash"></span></a>
											</td>										
										
									</tr>
			<?php } ?>
									                       </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>


<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Add Coupon</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
<?php	$form = ActiveForm::begin([
    		'id' => 'coupon-form',
		'options' => ['class' => 'form-horizontal','wrapper' => 'col-xs-12',],
        	'layout' => 'horizontal',
			 'fieldConfig' => [
        'horizontalCssClasses' => [
            
            'offset' => 'col-sm-offset-0',
            'wrapper' => 'col-sm-12 pl-0 pr-0',
        ],
		]
		]) ?>

        <!-- Modal body -->
        <div class="modal-body">
		

<div class="row">	

   
   
   <div class="col-md-4">
   	   <div class="form-group row">
	   <label class="control-label col-md-4">Coupon Type</label>
	   <div class="col-md-8">
	   <?= $form->field($model, 'purpose')->dropdownlist(['Single'=>'Single Use','Multiple'=>'Multiple Use']
				  ,['prompt'=>'Select Coupon Type'])->label(false); ?>
	   </div></div>
	</div>
   
   
<div class="col-md-4">
   	   <div class="form-group row">
	   <label class="control-label col-md-4">Coupon Code</label>
	   <div class="col-md-8">
	   <?= $form->field($model, 'code')->textinput(['class' => 'form-control','placeholder'=>'Coupon Code','onkeyup'=>'couponvalidation(event,this.value,this.id)'])->label(false); ?>
	   </div></div>
	   </div>
	   
   


   <div class="col-md-4">
   	   <div class="form-group row">
	   <label class="control-label col-md-4">Discount Type</label>
	   <div class="col-md-8">
	   <?= $form->field($model, 'type')->dropdownlist(['amount'=>'Amount','percent'=>'Percentage'], ['onchange'=>'percentagecheck()']
				  ,['prompt'=>'Select Discount Type'])->label(false); ?>
	   </div></div>
	</div>




<div class="col-md-4">
	   <div class="form-group row">
	   <label class="control-label col-md-4 maxamtdiscnt" >Max Amount of Discount</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'maxamt')->textinput(['class' => 'form-control','placeholder'=>'Max Amount of Discount'])->label(false); ?>
	   </div></div>
	   </div>



<div class="col-md-4">
	   <div class="form-group row">
	   <label class="control-label col-md-4">Validity From</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'fromdate')->textinput(['class' => 'form-control datepicker1','placeholder'=>'Validity From','readonly'=> true])->label(false); ?>
	   </div></div>
	   </div>

<div class="col-md-4">
	   <div class="form-group row">
	   <label class="control-label col-md-4">Validity To</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'todate')->textinput(['class' => 'form-control datepicker2','placeholder'=>'Validity To','readonly'=> true])->label(false); ?>
	   </div></div>
	   </div>







<div class="col-md-4">
	   <div class="form-group row">
	   <label class="control-label col-md-4 amtdiscnt">Amount of Discount</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'price')->textinput(['class' => 'form-control','placeholder'=>'Amount Of Discount','onchange'=>'percentagecheck()'])->label(false); ?>
	   </div></div>
	   </div>




<div class="col-md-4">
	   <div class="form-group row">
	   <label class="control-label col-md-4">Min Order Amount</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'minorderamt')->textinput(['class' => 'form-control','placeholder'=>'Min Order Amount'])->label(false); ?>
	   </div></div>
	   </div>



	   
	   <div class="col-md-4">
	   <div class="form-group row">
	   <label class="control-label col-md-4">Description</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'description')->textarea(['class' => 'form-control','placeholder'=>'Description'])->label(false); ?>
	   </div></div>
	   </div>
	   
	   
	   
	   
	   
	   <div class="col-md-12">
	  <table id="tblAddRow" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th><input type="checkbox" id="checkedAll"/></th>
            <th>Product Name</th>
            
        </tr>
    </thead>
    <tbody>
        <?php for($p=0;$p<count($product);$p++) { ?>
		<tr>
            <td>
                <input name="ckcDel[]" class="individualcheck" onclick="checkeditem('<?= $product[$p]['ID']; ?>')" type="checkbox" value="<?= $product[$p]['ID']; ?>" />
            </td>
            <td>
                 <?= $product[$p]['titlewithqty']; ?>
            </td>

        </tr>
		<?php } ?>
       
    </tbody>
</table>
</div>
	   </div>
	   </div>
	   <div class="modal-footer">
		<button type="submit" class="btn btn-add btn-hide">Add Coupon</button>
      </div> 

<?php ActiveForm::end() ?>      
 </div>  
        
        
        
      </div>
    </div>
  </div>
  
  <div id="editcoupon" class="modal fade" role="dialog">
<div class="modal-dialog modal-xl" >
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Edit Coupon</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
	    <div class="modal-body" id="editcouponbody">
		
		</div>	
		
		  
		
	</div>
	</div>
</div>
        </section>
				

		<?php

$script = <<< JS
JS;
$this->registerJs($script);
?>
<script>
function couponvalidation(evt,couponcode,couponcodeid){
        var iKeyCode = (evt.which) ? evt.which : evt.keyCode;
		if(iKeyCode == 32){
			couponcode = couponcode.substring(0, couponcode.length - 1);
			$("#"+couponcodeid).val(couponcode);
		            return false;
		}

}
function deletecoupon(id){
//	var res = confirm('Are you sure want to delete??')
		    swal({
				title: "Are you sure want to delete??", 
				type: "warning",
				showCancelButton: true
		    })
		    	.then((result) => {
					if (result.value) {
					    var request = $.ajax({
						  url: "deletecoupon",
						  type: "POST",
						  data: {id : id},
						}).done(function(msg) {
							
							location.reload();
						});
					}
				});

}

$(document).ready(function(){

$('#merchantcoupon-product').multiselect({

includeSelectAllOption: true

});
$('.datepicker1 ').datepicker({
            uiLibrary: 'bootstrap',
			format: 'yyyy-mm-dd',
			startDate: nowDate 
        });
		 $('.datepicker2 ').datepicker({
            uiLibrary: 'bootstrap',
			format: 'yyyy-mm-dd',
			
        });
$('#example').DataTable();

	$('#tblAddRow').DataTable({order: [],
columnDefs: [ { orderable: false, targets: [0] } ]});
	var nowDate = new Date();
var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);
 


});
// For select all checkbox in table
$('#checkedAll').click(function (e) {
	//e.preventDefault();
    $(this).closest('#tblAddRow').find('td input:checkbox').prop('checked', this.checked);
});
function checkeditem(id)
{
	$("#checkedAll").prop("checked", false);
}

function datepickerfrom(){
var nowDate = new Date();
var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);
 $('.datepicker3 ').datepicker({
            uiLibrary: 'bootstrap',
			format: 'yyyy-mm-dd',
			startDate: nowDate 
        });
		}
		function datepickerto(){
var nowDate = new Date();
var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);
 $('.datepicker4 ').datepicker({
            uiLibrary: 'bootstrap',
			format: 'yyyy-mm-dd',
			startDate: nowDate 
        });
		}
function percentagecheck(){
    var merchantcoupon_type = $("#merchantcoupon-type").val();
    var merchantcoupon_price = $("#merchantcoupon-price").val();
    if(merchantcoupon_type == 'percent'){
		$('.amtdiscnt').html('Percentage of Discount');
        if(merchantcoupon_price != '' && merchantcoupon_price > 100){
                       swal(
				'Warning!',
				'Coupon Percentage Should Not Be Greater Than 100 %',  
				'warning'
			);
			$("#merchantcoupon-price").val('');
        }
    }
	else{
		$('.amtdiscnt').html('Amount of Discount');
	}
}
</script>
