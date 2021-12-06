
<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
<style>
.fixed {position:fixed; top:0;right: 15px;width: 335px;}
</style>
<header class="page-header">

          </header>

		<section class="col-md-12">
		  <div class="row">
          
		  <div class="col-md-8" >
		  <div class="card">
		  <div class="card-header">
		  <h3 class="h4"><?= $tableDet['name'];?> Order (<?= $orderDet['order_id']?>) <span style="color:red"><?php if($orderDet['orderprocess'] == '3') { ?> Cancelled <?php  } ?></span></h3>
		  </div>
		  <div class="card-body p-3">
		<?php for($i=0;$i<count($orderProdDet);$i++) { ?>
		<div class="col-xs-12 item-shdw p-2 mb-2">
		  <div class="row">
		  <div class="col-md-3 text-center">
		  <img src="<?= Yii::$app->request->baseUrl.'/uploads/productimages/'.Utility::product_details($orderProdDet[$i]['product_id'],'image');?>" class="rounded-circle" width="100px" height="100px">
		  </div>
		  <div class="col-md-6">
			<div class="col-md-12 row">
			<h6> <?= Utility::product_details($orderProdDet[$i]['product_id'],'title') ; ?> <?php 
			$food_cat_type_id = Utility::product_details($orderProdDet[$i]['product_id'],'food_category_quantity');
			if(!empty($food_cat_type_id)) { echo "(".Utility::food_cat_qty_det($food_cat_type_id,'food_type_name').")"; } ?> </h6>
			</div>
			<div class="option-label"><h6>Qty : <?= $orderProdDet[$i]['count'];?></h6></div>
		  </div>
		  <div class="col-md-3 text-right pr-5 pt-3">
		  <div class="">
		  <h6>Amount</h6>
		  <h6 class="amt"><i class="fa fa-inr"></i> <?= $orderProdDet[$i]['price'];?></h6>
		  </div>
		  </div>
		  </div>
		
		</div>
		<?php } ?>
		
						
						
						
		  </div>
		  </div>
		  </div>
		  <div class="col-md-4">
			<div class="card" id="task_flyout">
		  <div class="card-header d-flex align-items-center pt-0 pb-0">
                <h3 class="h4 col-md-6 pl-0 tab-title">Bill</h3>
				<div class="col-md-6 text-right pr-0">
				<button type="button" class="btn btn-add btn-xs" ><i class="fa fa-print"></i></button>
				</div>
              </div>
		  <div class="card-body p-3">
		  <div class="row">
			<div class="col-md-8">Amount</div>
			<div class="col-md-4 text-right"><i class="fa fa-inr"></i> <?= number_format($orderDet['amount'], 2, '.', ' ');?></div>
		  </div>
		  <hr>
		  <div class="row">
			<div class="col-md-8">Coupon Amount <?php if(!empty($orderDet['coupon'])) { echo  "(".$orderDet['coupon'].")" ; } ?> </div>
			<div class="col-md-4 text-right"><i class="fa fa-inr"></i> <?= number_format($orderDet['couponamount'], 2, '.', ' ');?></div>
		  </div>
		  <hr>
		  <div class="row">
			<div class="col-md-8">Tax Amount</div>
			<div class="col-md-4 text-right"><i class="fa fa-inr"></i> <?= number_format($orderDet['tax'], 2, '.', ' ');?></div>
		  </div>
		  <hr>
		  <div class="row">
			<div class="col-md-8">Tip Amount</div>
			<div class="col-md-4 text-right"><i class="fa fa-inr"></i> <?= number_format($orderDet['tips'], 2, '.', ' ');?></div>
		  </div>
		  <hr>
		  <div class="row">
			<div class="col-md-8">Subscription Amount</div>
			<div class="col-md-4 text-right"><i class="fa fa-inr"></i> <?= number_format($orderDet['subscription'], 2, '.', ' ');?></div>
		  </div>
		  <hr>
		  <div class="row">
			<div class="col-md-8">Total Amount</div>
			<div class="col-md-4 text-right"><i class="fa fa-inr"></i> <?= number_format($orderDet['totalamount'], 2, '.', ' ');?></div>
		  </div>
		  <hr>
		  <div class="row">
			<div class="col-md-8">Payment Status</div>
			<div class="col-md-4 text-right"><?= $orderDet['paymenttype']=='cash' ? 'Cash' : 'Online payment'; ?></div>
		  </div>
		  </div>
		  </div>
		  </div>
        </section>
<script>
$(window).scroll(function(){
      if ($(this).scrollTop() > 135) {
          $('#task_flyout').addClass('fixed');
      } else {
          $('#task_flyout').removeClass('fixed');
      }
  });
</script>