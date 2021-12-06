<?php 
$merchant_id = Yii::$app->user->identity->merchant_id;
$roleId = Yii::$app->user->identity->emp_role;
$sqlPermissionsArr = 'select * from merchant_permission_role_map mprp inner join merchant_permissions mp 
		on mp.ID = mprp.permission_id where merchant_id = \''.$merchant_id.'\' and mprp.employee_id=\''.$roleId.'\' and permission_status = \'1\'';
$permissionsArr = Yii::$app->db->createCommand($sqlPermissionsArr)->queryAll();
$permissions = (array_column($permissionsArr,'process_name'));

?>

  <div class="col-md-12">
		  <ul class="resp-tabs-list">
		  <?php  if($roleId == '0' || in_array('Inventory Reports',$permissions) ) { ?>		
		<a href="<?= \yii\helpers\Url::to(['merchant/stocksummary']); ?>"><li class="resp-tab-item <?php if($actionId == 'stocksummary') { echo "resp-tab-active" ;} ?>" >Stock Summary Report</li></a>
		<a href="<?= \yii\helpers\Url::to(['merchant/consumption']); ?>"><li class="resp-tab-item <?php if($actionId == 'consumption') { echo "resp-tab-active" ;} ?>" >Consumption Report</li></a>
		<a href="<?= \yii\helpers\Url::to(['merchant/purchase']); ?>"><li class="resp-tab-item <?php if($actionId == 'purchase') { echo "resp-tab-active" ;} ?>" >Purchase Report</li></a>
		<a href="<?= \yii\helpers\Url::to(['merchant/vendorreport']); ?>"><li class="resp-tab-item <?php if($actionId == 'vendorreport') { echo "resp-tab-active" ;} ?>" >Vendor Report</li></a>
		<a href="<?= \yii\helpers\Url::to(['merchant/wastagereport']); ?>"><li class="resp-tab-item <?php if($actionId == 'wastagereport') { echo "resp-tab-active" ;} ?>" >Wastage Report</li></a>
		  <?php } ?>		
		</ul>
		  </div>