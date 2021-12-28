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
		  <?php if($roleId == '0' || in_array('Items',$permissions) ) { ?>
		<a href="<?= \yii\helpers\Url::to(['merchant/managetable']); ?>"><li class="resp-tab-item <?php if($actionId == 'managetable') { echo "resp-tab-active" ;} ?>">Spots</li></a>
		  <?php } if($roleId == '0' || in_array('Manage Tables',$permissions) ) { ?>
			<a href="<?= \yii\helpers\Url::to(['merchant/sections']); ?>"><li class="resp-tab-item <?php if($actionId == 'sections') { echo "resp-tab-active" ;} ?>" >Sections</li></a>	
        <?php } if($roleId == '0' || in_array('Manage Tables',$permissions) ) { ?>
			<a href="<?= \yii\helpers\Url::to(['merchant/menuqr']); ?>"><li class="resp-tab-item <?php if($actionId == 'menuqr') { echo "resp-tab-active" ;} ?>" >Menu QR</li></a>	
        <?php } ?>
        </ul>
    </div>
    	