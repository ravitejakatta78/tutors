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
		  <?php if($roleId == '0' || in_array('Place Order',$permissions) ) { ?>
			<a href="<?= \yii\helpers\Url::to(['merchant/newpos']); ?>"><li class="resp-tab-item <?php if($actionId == 'placeorder' || $actionId == 'newpos' ) { echo "resp-tab-active" ;} ?>" >Place Order</li></a>
		  <?php } if($roleId !== '0' || in_array('currentorders',$permissions) ) { ?>
		<a href="<?= \yii\helpers\Url::to(['merchant/currentorders']); ?>"><li class="resp-tab-item <?php if($actionId == 'currentorders') { echo "resp-tab-active" ;} ?>">Running Orders</li></a>
		  <?php } if($roleId == '0' || in_array('Order History',$permissions) ) { ?>		
		<a href="<?= \yii\helpers\Url::to(['merchant/orders']); ?>"><li class="resp-tab-item <?php if($actionId == 'orders') { echo "resp-tab-active" ;} ?>" >Order History</li></a>
		<a href="<?= \yii\helpers\Url::to(['merchant/viewkdsone']); ?>"><li class="resp-tab-item <?php if($actionId == 'viewkdsone') { echo "resp-tab-active" ;} ?>" >KDS</li></a>
		  <?php } ?>	
		  		<a href="<?= \yii\helpers\Url::to(['merchant/tableplaceorder']); ?>"><li class="resp-tab-item <?php if($actionId == 'tableplaceorder') { echo "resp-tab-active" ;} ?>" >Table Occupancy</li></a>

		</ul>
		  </div>