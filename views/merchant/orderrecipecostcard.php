
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
		  <div class="col-md-8">
			<div class="card" id="task_flyout">
		  <div class="card-header d-flex align-items-center pt-0 pb-0">
                <h3 class="h4 col-md-6 pl-0 tab-title">Order No : <?= $res[0]['order_id'];?> </h3>
				<div class="col-md-6 text-right pr-0">
				 <?= $res[0]['amount'];?>
				</div>
              </div>
		  <div class="card-body p-3">
			<table class="w-100 table table-bordered table-striped">
			<thead>
			<tr>
			<th>Product Name</th>
			<th>Product Price</th>
			<th>Ingredient</th>
			<th>Quantity</th>
			<th>Prepartion Price</th>
		<!--	<th>Price</th> -->
			</tr>
			</thead>
			<tbody>
			<?php 
			$ingredUnits = ["1"=>"Kg","2"=>"Litre","3"=>"Pieces","4"=>"Packets","5"=>"Grams","6"=>"Milli Litre"];
			$productArr = [];
			for($i=0;$i<count($res);$i++){ ?>
			<tr>
			<?php if(!in_array($res[$i]['product_id'],$productArr)){ ?>
			<td rowspan="<?= $productCountValues[$res[$i]['product_id']]?>"><?= $res[$i]['title_quantity']; ?></td>				
			<td rowspan="<?= $productCountValues[$res[$i]['product_id']]?>"><?= $res[$i]['cost_price']; ?></td>
			<?php } ?>

			
			<td><?= $res[$i]['ingredi_name'];?></td>
			<td><?php 
			if($res[$i]['ingred_units'] == '1' || $res[$i]['ingred_units'] == '2'){
				$used_qty = 	($res[$i]['ingredi_qty'] / 1000);
			}
			else{
				$used_qty =  $res[$i]['ingredi_qty'];
			}
			echo $used_qty . " (".Utility::purchase_quantity_type($res[$i]['ingred_units']).")";?></td>
			<td><i class="fa fa-inr"></i> <?= $res[$i]['ingredi_price']; ?></td>
			
		<!--	<td><i class="fa fa-inr"></i> <?php // $res[$i]['ingred_price'];?></td> -->
			</tr>
			<?php 
			$productArr[] = $res[$i]['product_id'];
			} ?>
			</tbody>
			<tfoot>
			<tr>
			<td colspan="4" class="text-right"><b>Total Amount</b></td>
			<td><i class="fa fa-inr"></i><b> <?=  array_sum(array_column($res,'ingredi_price')) ?? 0;?></b></td>
			</tr>
			</tfoot> 
			</table>
		 
		  </div>
		  </div>
		  </div>

		  </div>
        </section>
