<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
$actionId = Yii::$app->controller->action->id;
?>

<style>
.ktchn-title{cursor:pointer;}
</style>

        <!-- Side Navbar -->
        
<div class="ktchn-mainpage">

<header class="page-header" style="padding: 5px 0px !important;">
	<!--<div class="text-right">
		<a href="<?= Url::to(['site/index'])?>"><button class="btn btn-add btn-sm"><i class="fa fa-mail-reply"></i> Back to Home</button></a>
	</div>-->
</header>

		<section class="col-md-12">
		    <?= \Yii::$app->view->renderFile('@app/views/merchant/_orders.php',['actionId'=>$actionId]); ?>
		    <button type="button" class="btn btn-info float-right">Clear All</button>
		<div class="row">
		<div class="col-md-9">
		<div class="row">
		  <?php if(count($tableres) > 0) { 
		  $clr = 0;
		  $color1 = 'color1';
		  $color2 = 'color2';
		  //echo "<pre>";print_r($tabletimeres);exit;
		  foreach($tabletimeres as $tableid => $tabletime) { ?>
		  	<div class="col-md-4">
			<input type="hidden" value="<?= $tableorderidres[$tableid];?>" id="table_order_id_<?= $tableid; ?>">

		  		<div style="cursor: pointer;" class="kitchen-table-head <?php if($clr % 2 == 0) { echo $color1; }else { echo $color2; }  ?> col-md-12" id="<?=$tableid ?>">
		  			
		  			<div class="float-left">
		  				<div class="time"><?= date('h:i A',strtotime($tabletime));?> <span class="badge badge-dark">Online</span></div>
		  				<div class="tableno">Table: <?= $tableres[$tableid] ?? $tableid ; ?></div>
		  			</div>
		  			<div class="float-right">
		  				<div class="time">Pilot</div>
		  				<div class="tableno">#<?=substr($tableorderidres[$tableid],-6) ?></div>
		  			</div>
		  		</div>
		  		<div class="kitchen-table-body <?php if($clr % 2 == 0) { echo $color1; }else { echo $color2; }  ?> col-md-12">
		  			<?php 
					//echo "<pre>";print_r($resindex[$tableid]);exit;
					for($i=0;$i<count($resindex[$tableid]);$i++) { ?>
					<div class="row" onclick="productDeliver('<?= $resindex[$tableid][$i]['order_product_id'];?>')">
		  				<div class="col-md-2">
		  					<div class="ktchn-qty">
			  					<?= $resindex[$tableid][$i]['orderCount'].'x' ?>
			  				</div>
		  				</div>
		  				<div class="col-md-8">
		  					<div class="ktchn-title">
		  						<?= $resindex[$tableid][$i]['title_quantity'] ;?>
		  					</div>

		  				</div>
						<?php if(!empty($resindex[$tableid][$i]['item_deliver_status'])) { ?>
						<div class="col-md-2" id="<?= $resindex[$tableid][$i]['order_product_id'];?>">
						<i class="fa fa-check color-green"></i>
						</div>							
						<?php } ?>

		  			</div>
					<?php if(count($resindex[$tableid]) - 1 != $i) { ?>
		  			<div class="divider"></div>
					<?php } } ?>
		  			
		  		</div>
		  	</div>
		  <?php 
		  $clr++;
		  } ?> 
		  <div class="clearfix"></div>
		  <?php } ?>
</div>
</div>
<div class="col-md-3 float-right">
<nav class="side-navbar kitchen-sidebar">
          <div class="kitchen-menu-list">
          	<?php 		$countSummary = \yii\helpers\ArrayHelper::index($res, null, 'food_category');?>
			<div class="col-md-12">
          		<div class="float-left">
          		<strong>Products</strong>
          	</div>
          	<div class="float-right">
          		<strong>Qty</strong>
          	</div>
          	</div>
          	<div class="clearfix"></div>
          	<?php foreach($countSummary as $foodtypename => $foodsummary) {
			for($c=0;$c<count($countSummary[$foodtypename]);$c++) {
				$foodtypeSummary[$countSummary[$foodtypename][$c]['title_quantity']][$c] =  $countSummary[$foodtypename][$c]['orderCount'];

			}
				?>
			<div class="kitchen-menu-head">
          		<h4 class="text-center"><?= $foodtypename;?></h4>
          	</div>
			<div class="kitchen-menu-body">
          		<ul>
			<?php foreach($foodtypeSummary as $productname => $orderCountArr) { ?>
          	
          			<li class="row">
          				<div class="float-left col-md-10 pl-0 pr-0"><?= $productname; ?></div>
          				<div class="float-right kitchen-item-count"><?= array_sum($foodtypeSummary[$productname])?></div>
          			</li>
          			          		
			<?php } 
			unset($foodtypeSummary); ?>
			
</ul>
          	</div>
			<?php } ?>
          </div>
         
        </nav>
</div>
</div>
	
        </section>
<script>
$(document).ready(function(){
	setTimeout(function() { location.reload(); }, 20000);
});
$(window).scroll(function(){
      if ($(this).scrollTop() > 135) {
          $('#task_flyout').addClass('fixed');
      } else {
          $('#task_flyout').removeClass('fixed');
      }
  });

$(".kitchen-table-head").click(function(){
	
	swal({
				title: "Is food prepared ?", 
				//text: "You will be redirected to https://utopian.io", 
				type: "warning",
				confirmButtonText: "Yes!",
				showCancelButton: true
		    })
		    	.then((result) => {
					if (result.value) {
	var id = $("#table_order_id_"+this.id).val();			
	
						var request = $.ajax({
  url: "tableorderstatuschange",
  type: "POST",
  data: {tableId : this.id,id:id,kdschange:1},
}).done(function(msg) {
	location.reload();
});
					} else if (result.dismiss === 'cancel') {
					    
					}
				})
});

function productDeliver(orderProductId){
	swal({
				title: "Is Item prepared ?", 
				//text: "You will be redirected to https://utopian.io", 
				type: "warning",
				confirmButtonText: "Yes!",
				showCancelButton: true
		    })
		    	.then((result) => {
					if (result.value) {
	
	var request = $.ajax({
  url: "tableorderproductdeliver",
  type: "POST",
  data: {orderProductId : orderProductId},
	}).done(function(msg) {
	location.reload();
	});
					} else if (result.dismiss === 'cancel') {
					    
					}
				})					


					
}  
function deletetables(id){
//	var res = confirm('Are you sure want to delete??')
		    swal({
				title: "Are you sure want to delete??", 
				type: "warning",
				showCancelButton: true
		    })
		    	.then((result) => {
					if (result.value) {
					    var request = $.ajax({
						  url: "deletetables",
						  type: "POST",
						  data: {id : id},
						}).done(function(msg) {
							
							location.reload();
						});
					}
				});

}

</script>
        </div>
      
