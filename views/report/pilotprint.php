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
<title>Pilot Report</title>
<div class="container">
	<div class="head-title">
		
		<h2>Consolidated Pilot Report</h2>
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
            <th>S.No</th>
            <th>Pilot Name</th>
            <th>Total orders served</th>
            <th>Accepted Orders</th>
            <th>Rejected Orders</th>
            <th>Bill Amount</th>
            
	</tr>
	</thead>
	<tbody>
	<?php 
	$i=0;
	foreach($pilotdet as $pilot){
            
	?>
	<tr>
		<td align="center"><?= $i+1; ?></td>
		<td align="center"><?= $pilot['name']; ?></td>
		<td align="center"><?= $pilot['total_served_orders']; ?></td>
                <td align="center"><?= $pilot['completed_orders']; ?></td>
                <td align="center"><?= $pilot['rejected_order']; ?></td>
                <td align="center"><?= round($pilot['totalamount'],2); ?></td>
	</tr>
	<?php $i++; } ?>
	</tbody>
	</table>
			
</div>
<script>
$(function() {
    $(".exportToExcel").click(function(e){
	var tablelength = $('.table2excel tr').length;
	if(tablelength){
            var preserveColors = ($('.table2excel').hasClass('table2excel_with_colors') ? true : false);
            $('.table2excel').table2excel({
		exclude: ".noExl",
		name: "Excel Document Name",
		filename: "Pilot Report" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
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