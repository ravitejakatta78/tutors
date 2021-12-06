
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
                <h3 class="h4 col-md-6 pl-0 tab-title"><?= $res[0]['title_quantity'];?> </h3>
				<div class="col-md-6 text-right pr-0">
				<i class="fa fa-inr"></i> <?= $res[0]['price'];?>
				</div>
              </div>
		  <div class="card-body p-3">
			<table class="w-100 table table-bordered table-striped">
			<thead>
			<tr>
			<th>Ingredient</th>
			<th>Quantity</th>
		<!--	<th>Price</th> -->
			</tr>
			</thead>
			<tbody>
			<?php 
			$ingredUnits = ["1"=>"Kg","2"=>"Litre","3"=>"Pieces","4"=>"Packets","5"=>"Grams","6"=>"Milli Litre"];
			for($i=0;$i<count($res);$i++){ ?>
			<tr>
			<td><?= $res[$i]['item_name'];?></td>
			<td><?= $res[$i]['ingred_quantity'] . " (".$ingredUnits[$res[$i]['ingred_units']].")";?></td>
		<!--	<td><i class="fa fa-inr"></i> <?php // $res[$i]['ingred_price'];?></td> -->
			</tr>
			<?php } ?>
			</tbody>
		<!--	<tfoot>
			<tr>
			<td colspan="2" class="text-right"><b>Total Amount</b></td>
			<td><i class="fa fa-inr"></i><b><?php //  array_sum(array_column($res,'ingred_price')) ?? 0;?></b></td>
			</tr>
			</tfoot> -->
			</table>
		 
		  </div>
		  </div>
		  </div>
			<div class="col-md-4">
          <div class="row">
            <div class="col-md-12 mb-2">
              <img src="https://www.madrascurrycup.com/images/grab-now/Offer-ad.jpg" width="100%">
            </div>
            <div class="col-md-12 mb-2">
              <div id="demo" class="carousel slide" data-ride="carousel">

  <!-- Indicators -->
  <ul class="carousel-indicators">
    <li data-target="#demo" data-slide-to="0" class="active"></li>
    <li data-target="#demo" data-slide-to="1"></li>
    <li data-target="#demo" data-slide-to="2"></li>
  </ul>
  
  <!-- The slideshow -->
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="https://i.pinimg.com/474x/27/d3/48/27d3480199613eab6fc4232dc71b7247.jpg" alt="Los Angeles" width="100%" height="200">
    </div>
    <div class="carousel-item">
      <img src="https://discount-offers.weebly.com/uploads/1/1/3/8/113848799/published/swiggy-coupons.jpg?1512369960" alt="Chicago" width="100%" height="200">
    </div>
    <div class="carousel-item">
      <img src="https://i.pinimg.com/originals/eb/c9/cd/ebc9cd97f5ab3d529f4d0a149caaf159.png" alt="New York" width="100%" height="200">
    </div>
  </div>
  
  <!-- Left and right controls -->
  <a class="carousel-control-prev" href="#demo" data-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </a>
  <a class="carousel-control-next" href="#demo" data-slide="next">
    <span class="carousel-control-next-icon"></span>
  </a>
</div>
            </div>
            <div class="col-md-12 mb-2">
              <img src="https://www.dealsnation.in/assets/couponimg/DLSNTNPRD79581542352412.jpg" width="100%">
            </div>
          </div>
        </div>
		  </div>
        </section>
