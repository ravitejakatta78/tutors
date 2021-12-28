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
		  <?php if($roleId == '0' || in_array('Vendor List',$permissions) ) { ?>
		<a href="<?= \yii\helpers\Url::to(['merchant/product-list']); ?>"><li class="resp-tab-item <?php if($actionId == 'product-list') { echo "resp-tab-active" ;} ?>">Items</li></a>		
		  <?php }  if($roleId == '0' || in_array('Food Categories',$permissions) ) { ?>
			<a href="<?= \yii\helpers\Url::to(['merchant/food-categeries']); ?>"><li class="resp-tab-item <?php if($actionId == 'food-categeries') { echo "resp-tab-active" ;} ?>" >Item Categories</li></a>
        <?php } if($roleId == '0' || in_array('Food Categories',$permissions) ) { ?>
			<a href="<?= \yii\helpers\Url::to(['merchant/foodsections']); ?>"><li class="resp-tab-item <?php if($actionId == 'foodsections') { echo "resp-tab-active" ;} ?>" >Menu Sections</li></a>
        <?php }if($roleId == '0' || in_array('Food Categories',$permissions) ) { ?>
			<a href="<?= \yii\helpers\Url::to(['merchant/bannerdetails']); ?>"><li class="resp-tab-item <?php if($actionId == 'bannerdetails') { echo "resp-tab-active" ;} ?>" >Banners</li></a>
        <?php } ?>
        </ul>
    </div>






    