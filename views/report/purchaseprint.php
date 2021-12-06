<style>
.container{width:1024px;}
.head-title{text-align:center}
.frmtodate{float:left;}
.curdate{float:right;}
.table{width:100%}
.table tr td, .table tr th{border:1px solid #333;padding:5px;}
</style>
<script src="<?= Yii::$app->request->baseUrl.'/js/jquery2.2.4.min.js';?>"></script>
<script src="<?= Yii::$app->request->baseUrl.'/js/jquery.table2excel.js'?>"></script>
<title>Purchase Report</title>
<div class="container">
	<div class="head-title">
		<h2><?= $storename;?></h2>
		<h3>Purchase Report</h3>
	</div>
	<div class="frmtodate">
	<h5>From: <?= $sdate; ?> To <?= $edate; ?></h5>
	</div>
	<div class="curdate">
	<!-- <h5>Print Date: <?= date('d-m-y');?></h5>-->
	<button class="exportToExcel" >Export to XLS</button>
	
	</div>
		
	<table class="table table2excel table2excel_with_colors" id="myTable" cellspacing="0">
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
	<tbody>
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
	<td colspan="4" align="center" ><strong><?= $res[$i]['reg_date'] ?> Total</strong></td>
		<td align="right"><strong><?= array_sum($sub_purchase_quantity)??0 ?></strong></td>
		<td align="right"><strong><?= array_sum($sub_purchase_price)??0 ?></strong></td>		
	</tr>
	
	
	<?php 
	$subRow = 0;
	unset($sub_purchase_quantity);
	unset($sub_purchase_price);
	}
		
	} ?>
	<tr>
	<td colspan="4"align="center"><strong>Total</strong></td>
	<td align="right"> <strong><?= array_sum($purchase_quantity_total)??0;?></strong></td>	
	<td align="right"><strong><?= array_sum($purchase_price_total)??0;?></strong></td>
	</tr>
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
<script>
		/*	$(function() {
				$(".exportToExcel").click(function(e){
					var tablelength = $('.table2excel tr').length;
					if(tablelength){
						var preserveColors = ($('.table2excel').hasClass('table2excel_with_colors') ? true : false);
						alert(preserveColors);
						$('.table2excel').table2excel({
							exclude: ".noExl",
							name: "Excel Document Name",
							filename: "Purchase Report" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
							fileext: ".xls",
							exclude_img: true,
							exclude_links: true,
							exclude_inputs: true,
							preserveColors: preserveColors
						});
					}
				});
				
			});*/
				$(".exportToExcel").click(function(e){
var type = '<?= $type; ?>';

if(type == '1'){
var res = '<?= $resjson; ?>';
var orgres = '<?= $orgres; ?>';
var storename = '<?= $storename; ?>';

  var form=document.createElement('form');
      
		form.setAttribute('method','post');
        form.setAttribute('action','../../../PHPExcel/exceldownload.php');
        form.setAttribute('target','_blank');



    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "res");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", res);
    form.appendChild(hiddenField);
	
    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "orgres");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", orgres);
    form.appendChild(hiddenField);	
	
    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "storename");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", storename);
    form.appendChild(hiddenField);		

    document.body.appendChild(form);
    form.submit();    

	
}else{
  var res = '<?= $orgres; ?>';
var storename = '<?= $storename; ?>'
  var form=document.createElement('form');
      
		form.setAttribute('method','post');
        form.setAttribute('action','../../../PHPExcel/consolidatepurchase.php');
        form.setAttribute('target','_blank');



    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "res");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", res);
    form.appendChild(hiddenField);
	
    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "storename");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", storename);
    form.appendChild(hiddenField);		

    document.body.appendChild(form);
    form.submit();    
	
}

				});

			</script>