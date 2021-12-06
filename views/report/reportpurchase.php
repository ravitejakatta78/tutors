<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<header class="page-header">
    <script src="<?= Yii::$app->request->baseUrl.'/js/jquery2.2.4.min.js';?>"></script>
<script src="<?= Yii::$app->request->baseUrl.'/js/jquery.table2excel.js'?>"></script>
</header>
<section>
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header d-flex align-items-center pt-0 pb-0">
                <h3 class="h4 col-md-6 pl-0 tab-title">Purchase Report</h3>
				<div class="col-md-6 text-right pr-0">
				</div>
              </div>
              <div class="card-body">
               <form class="form-horizontal" method="POST" action="reportpurchase"> 
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
					<div class="input-group mb-3 mr-3">
					<select name="type" id="type">
					<option value="">Select</option>
					<option value="1">Individual</option>
					<option value="2">DayWise</option>
					</select>
				</div>
               </div>
               </div>
               </div>
                <div class="col">
                  <div class="form-group pt-3">
                    <button type="button" onclick="billviews()" class="btn btn-add btn-sm btn-search">Search</button>
                  </div>
                  </div>
                  <div class="col">
                    <div class="form-group text-center">
                        <button class="exportToExcel btn btn-add btn-sm" >Download</button>
                    </div>
                    </div>
			   </div>
			  </form>
			  <div class="table-responsive">   
			  <table id="example" class="table table-striped table-bordered  table2excel">
                    <thead>
                    <tr>
	<th >S.No</th>
	<th>Purchase Date</th>
	<?php if($type== '1'){ ?>
	<th>Purchase Number</th>
	<th>Ingredient Name</th>
	<th>Ingredient Quantity</th>
	<th>Ingredient Price</th>	
	<?php }else { ?>
	<th>Purchase Amount</th>
	<?php } ?>
	
	</tr>
                    </thead>
                    <?php 
	if($type == '1'){
	$chkDate = [];
	$chk_purchase_number = [];
	$subRow = 0;
$purchase_quantity_total =[];
$purchase_price_total = [];
	for($i=0,$subRow = 1;$i<count($res);$i++,$subRow++){
		$sub_purchase_quantity[] = $res[$i]['purchase_quantity'];	
		$sub_purchase_price[] = $res[$i]['purchase_price'];
		$purchase_quantity_total[] = $res[$i]['purchase_quantity'];	
		$purchase_price_total[] = $res[$i]['purchase_price'];
		?>
	<tr>
		<td align="center"><?= $i+1; ?></td>
		<?php if(!in_array($res[$i]['reg_date'],$chkDate)){ ?>
		<td align="center" rowspan="<?= $daterowSpanArr[$res[$i]['reg_date']];?>"><?= $res[$i]['reg_date']; ?></td>
		<?php 
		$chkDate[] = $res[$i]['reg_date'];

		
		} ?>
		
		
		<?php if(!in_array($res[$i]['purchase_number'],$chk_purchase_number)){ ?>
			<td rowspan="<?= $purchase_number_rowSpanArr[$res[$i]['purchase_number']];?>"><?= $res[$i]['purchase_number']; ?></td>
		<?php
			$chk_purchase_number[] = $res[$i]['purchase_number'];
		} ?>
	
		<td><?= $res[$i]['item_name']; ?></td>
		<td align="right"><?= $res[$i]['purchase_quantity']; ?></td>
		<td align="right"><?= $res[$i]['purchase_price']; ?></td>		
	</tr>
	<?php if($daterowSpanArr[$res[$i]['reg_date']] == $subRow) {

		?>
	<tr>
	<td colspan="4" align="center" ><strong> Total</strong></td>
		<td align="right"><strong><?= array_sum($sub_purchase_quantity)??0 ?></strong></td>
		<td align="right"><strong><?= array_sum($sub_purchase_price)??0 ?></strong></td>		
	</tr>
	
	
	<?php 
	$subRow = 0;
	unset($sub_purchase_quantity);
	unset($sub_purchase_price);
	}
		
	} ?>
	
	<?php } else if($type == '2' ) { 
	$purchase_amount_total = [];
		for($i=0;$i<count($res);$i++){ ?>
		<tr>
		<td align="center"><?= $i+1; ?></td>
		<td align="center"><?= $res[$i]['reg_date']; ?></td>
		<td align="right"><?= $res[$i]['purchase_amount']; ?></td>
		</tr>
		<?php 
		$purchase_amount_total[] = $res[$i]['purchase_amount'];
		} ?>
		<tr><td colspan="2" align="center"><strong>Total</strong></td>
		<td align="right"><strong><?= array_sum($purchase_amount_total)??0?></strong></td></tr>
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
        form.setAttribute('action','reportpurchase');
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
