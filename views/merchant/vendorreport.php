<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
$actionId = Yii::$app->controller->action->id;
?>
<header class="page-header">
    <script src="<?= Yii::$app->request->baseUrl.'/js/jquery2.2.4.min.js';?>"></script>
<script src="<?= Yii::$app->request->baseUrl.'/js/jquery.table2excel.js'?>"></script>
</header>
<section>
            <?= \Yii::$app->view->renderFile('@app/views/merchant/_reporthorizontal.php',['actionId'=>$actionId]); ?>
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header d-flex align-items-center pt-0 pb-0">
                <h3 class="h4 col-md-6 pl-0 tab-title">Vendor Report</h3>
				<div class="col-md-6 text-right pr-0">
				</div>
              </div>
              <div class="card-body">
               <form class="form-horizontal" method="POST" action="vendorreport"> 
               <div class="row stock">
			  <div class="col-md-3">
			  <div class="form-group row">
                    <label class="control-label col-md-4 pt-2">Start Date:</label>
                    	<div class="col-md-8">
                  <div class="input-group mb-3 mr-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                  </div>
                  <input type="text" class="form-control datepicker1" id="sdate" name="edate" placeholder="End Date" value="<?= $sdate ; ?>">
                </div>
				</div>
				</div>
				</div>
				 <div class="col-md-3">
			   <div class="form-group row">
                    <label class="control-label col-md-4 pt-2">End Date:</label>
                    <div class="col-md-8">
                  <div class="input-group mb-3 mr-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                  </div>
                  <input type="text" class="form-control datepicker2" id="edate" name="edate" placeholder="End Date" value="<?= $edate ; ?>">
                </div>
				</div>
				</div>
				</div>
				<div class="col-md-3">
			   <div class="form-group row">
                    <label class="control-label col-md-4 pt-1">Type:</label>
                    <div class="col-md-8">
					<div class="input-group-prepend mb-3 mr-3">
					<select name="type" id="type">
					<option value="">Select Any</option>
					<option value="1">Detailed view</option>
					<option value="2">Overall view</option>
					</select>
				</div>
               </div>
               </div>
               </div>
                <div class="col mt-3">
                  <div class="form-group">
                    <button type="button" onclick="billviews()" class="btn btn-add btn-sm btn-search">Search</button>
                  </div>
                  </div>
                  <div class="col">
                    <div class="form-group text-center">
                        <button class="exportToExcel btn btn-add btn-sm" >Download</button>
                    </div>
                    </div>
			  </form>
			 
			  <div class="table-responsive">   
			  <table id="example" class="table table-striped table-bordered  table2excel">
                    <thead>
                    <tr>
	<th >S.No</th>
	<th>Vendor Type</th>
	<th>vendor Name</th>
	<?php if($type== '1'){ ?>
	<th>Date</th>
	<th>Purchased No</th>
	<th>Billed by</th>
	<th>No.of Items</th>	
	<th>Quntity</th>
	<th>Amount</th>
	<th>Paid</th>
	<th>Balance</th>
	<?php }else { ?>
	<th>No.of Purchasals</th>
	<th>No.of Items</th>
	<th>Amount</th>
	<th>Paid</th>
	<th>Balance</th>
	<?php } ?>
	
	</tr>
                    </thead>
                    <?php 
	if($type == '1'){

		
		?>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
	
	
	<?php } else if($type == '2' ) { 
		?>
		<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<?php } ?>
	</tbody>
                  </table>
                  </div>
                  </div>
                  </div>
                  </div>
		</section>
		
		
		<script>
		function billviews(){
			


		var type = $("#type").val();
			if(type == ''){
				swal(
				'Warning!',
				'Please Select Type',
				'warning'
			);
return false;			
			}
		var sdate = $("#sdate").val();
		var edate = $("#edate").val();
        var form=document.createElement('form');
        form.setAttribute('method','post');
        form.setAttribute('action','vendorreport');
        form.setAttribute('target','_self');

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "type");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", type);
    form.appendChild(hiddenField);


    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "sdate");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", sdate);
    form.appendChild(hiddenField);


    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "edate");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", edate);
    form.appendChild(hiddenField);


    document.body.appendChild(form);
    form.submit();    


}
		</script>
		<script>
$(function() {
    $(".exportToExcel").click(function(e){
	var tablelength = $('.table2excel tr').length;
	if(tablelength){
            var preserveColors = ($('.table2excel').hasClass('table2excel_with_colors') ? true : false);
            $('.table2excel').table2excel({
		exclude: ".noExl",
		name: "Excel Document Name",
		filename: "Category Wise Report" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
		fileext: ".xls",
		exclude_img: true,
		exclude_links: true,
		exclude_inputs: true,
		preserveColors: preserveColors
            });
	}
    });
});
</script>
		<?php
$script = <<< JS
    $('#example').DataTable();
JS;
$this->registerJs($script);
?>
<script>
$(document).ready(function(){
	
	$('#example').DataTable();
});
</script>
