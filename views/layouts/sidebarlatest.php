<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
$actionId = Yii::$app->controller->action->id;
$merchant_id = Yii::$app->user->identity->merchant_id;
$merchantDet = \app\models\Merchant::findOne($merchant_id);
$roleId = Yii::$app->user->identity->emp_role;
$sqlPermissionsArr = 'select * from merchant_permission_role_map mprp inner join merchant_permissions mp 
		on mp.ID = mprp.permission_id where merchant_id = \''.$merchant_id.'\' and mprp.employee_id=\''.$roleId.'\' and permission_status = \'1\'';
$permissionsArr = Yii::$app->db->createCommand($sqlPermissionsArr)->queryAll();
$permissions = (array_column($permissionsArr,'process_name'));
?>
<nav class="side-navbar">
          <!-- Sidebar Header-->
          <div class="sidebar-header d-flex align-items-center">
            <div class="avatar"><a href="<?php if($roleId == '0') { echo Url::to(['merchant/updatemerchatprofile']); } else {echo "#"; }?>">
			<?php if(!empty($merchantDet['coverpic'])){ ?>
			<img src="<?= 'http://'.$_SERVER['SERVER_NAME'].'/development/merchantimages/'.$merchantDet['logo'];?>" alt="..." class="img-fluid rounded-circle">	
			<?php }else{ ?>
			<img src="<?= Yii::$app->request->baseUrl.'/img/default-profile-pic.png'?>" alt="..." class="img-fluid rounded-circle">	
			<?php } ?>
			</a></div>
            <div class="title">
              <h1 class="h4"><a href="<?= Url::to(['merchant/tableplaceorder'])?>" style="text-decoration: none;"><?= $merchantDet['storename']; ?></a></h1>
              <p><?= $merchantDet['location']; ?></p>
            </div>
          </div>
          <!-- Sidebar Navidation Menus-->
          <ul class="list-unstyled">
		  <?php if($roleId == '0' || in_array('DASHBOARD',$permissions) || in_array('TRANSACTION DASHBOARD',$permissions)) { ?>
        <!--  <li class="<?php if($actionId == 'merchantdashboard' || $actionId == 'transcationdashboard' ) { echo "active" ;} ?>">
			<!--<a href="#maindropdownDropdown" aria-expanded="<?php if($actionId == 'merchantdashboard' || $actionId == 'transcationdashboard' ) { echo "true" ;}else { "false"; }  ?>" data-toggle="collapse">-->
			<!--<i class="fa fa-home"></i>Insights </a>-->
           <!--<ul id="maindropdownDropdown" class="collapse list-unstyled <?php if($actionId == 'merchantdashboard' || $actionId == 'transcationdashboard' ) { echo "collapse show" ;} ?>">-->
                    <?php if($roleId == '0' || in_array('DASHBOARD',$permissions) ) { ?>
                	<li class="<?php if($actionId == 'merchantdashboard') { echo "active" ;} ?>"><a href="<?= Url::to(['merchant/merchantdashboard']); ?>"><i class="fa fa-home"></i>Dashboard</a></li>
               <?php } ?><hr>
			<!--	<?php if($roleId == '0' || in_array('TRANSACTION DASHBOARD',$permissions) ) { ?>-->
			<!--	<li class="<?php if($actionId == 'transcationdashboard') { echo "active" ;} ?>"><a href="<?= Url::to(['merchant/transcationdashboard']); ?>">Transaction</a></li>-->
			<!--	<?php } ?>-->
			<!--  </ul>-->
   <!--         </li>  -->
		  <?php } ?>
		 <!-- <?php if($roleId == '0' || in_array('Food Categories',$permissions) || in_array('Items',$permissions)  ) { ?>-->
			<!--<li class="<?php if($actionId == 'food-categeries' || $actionId == 'product-list' ) { echo "active" ;} ?>">-->
			<!--<a href="#exampledropdownDropdown" aria-expanded="<?php if($actionId == 'food-categeries' || $actionId == 'product-list' ) { echo "true" ;}else { "false"; }  ?>" data-toggle="collapse"> <i class="fa fa-cube"></i>Manage Items </a>-->
   <!--           <ul id="exampledropdownDropdown" class="collapse list-unstyled <?php if($actionId == 'food-categeries' || $actionId == 'product-list' ) { echo "collapse show" ;} ?>">-->
   <!--             <?php if($roleId == '0' || in_array('Food Categories',$permissions) ) { ?>-->
			<!--	<li class="<?php if($actionId == 'food-categeries') { echo "active" ;} ?>"><a href="<?= Url::to(['merchant/food-categeries']); ?>">Food Categories</a></li>-->
   <!--             <?php }  if($roleId == '0' || in_array('Items',$permissions) ) { ?> -->
			<!--	<li class="<?php if($actionId == 'product-list') { echo "active" ;} ?>"><a href="<?= Url::to(['merchant/product-list']); ?>">Items</a></li>-->
			<!--	<?php } ?>-->
			<!--</ul>-->
   <!--         </li>-->
		 <!-- <?php } ?>-->
		 <?php if($roleId == '0' || in_array('Food Categories',$permissions) ) { ?> 
           <li class="<?php if($actionId == 'food-categeries'|| $actionId == 'product-list') { echo "active" ;} ?>">
			<a href="<?php echo Url::to(['merchant/product-list']); ?>"> <i class="fa fa-cube"></i>Manage Items </a></li>
		 <?php } ?>
		 <?php if($roleId == '0' || in_array('Manage Tables',$permissions) ) { ?> 
           <li class="<?php if($actionId == 'managetable' || $actionId == 'sections') { echo "active" ;} ?>">
			<a href="<?php echo Url::to(['merchant/managetable']); ?>"> <i class="fa fa-table"></i>Manage Table </a></li>
		 <?php } ?>
		 <!-- <?php if($roleId == '0' || in_array('Manage Tables',$permissions)  ) { ?>-->
			<!--<li class="<?php if($actionId == 'managetable') { echo "active" ;} ?>">-->
			<!--<a href="#manageTableDropdown" aria-expanded="<?php if($actionId == 'managetable') { echo "true" ;}else { "false"; }  ?>" data-toggle="collapse"> <i class="fa fa-table"></i>Manage Tables</a>-->
   <!--           <ul id="manageTableDropdown" class="collapse list-unstyled <?php if($actionId == 'managetable') { echo "collapse show" ;} ?>">-->
   <!--             <?php if($roleId == '0' || in_array('Manage Tables',$permissions) ) { ?>-->
			<!--	<li class="<?php if($actionId == 'managetable') ?>"><a href="<?= Url::to(['merchant/sections']); ?>">Sections</a></li>-->
   <!--             <?php } ?>-->
   <!--             <?php if($roleId == '0' || in_array('Manage Tables',$permissions) ) { ?>-->
			<!--	<li class="<?php if($actionId == 'managetable') ?>"><a href="<?= Url::to(['merchant/managetable']); ?>">Tables</a></li>-->
   <!--             <?php } ?>-->
			<!--</ul>-->
   <!--         </li>-->
		 <!-- <?php } ?>-->
		  <?php if($roleId == '0' || in_array('Manage Gallery',$permissions)) { ?>
            <li class="<?php if($actionId == 'gallery') { echo "active" ;} ?>">
			<a href="<?php echo Url::to(['merchant/gallery']); ?>"> <i class="fa fa-picture-o"></i>Manage Gallery </a></li>
					  <?php } ?>
		  <?php if($roleId == '0' || in_array('Manage Pilot',$permissions) ) { ?>
            <li class="<?php if($actionId == 'pilot') { echo "active" ;} ?>">
				<a href="<?php echo Url::to(['merchant/pilot']); ?>"> <i class="fa fa-user"></i>Manage Pilot </a>
			</li>
					  <?php } ?>
		 <!-- <?php if($roleId == '0' || in_array('KDS',$permissions) || in_array('Place Order',$permissions) || in_array('Table Order',$permissions) || in_array('Order History',$permissions) ) { ?>-->
   <!--         <li class="<?php if($actionId == 'currentorders' || $actionId == 'parcels'  || $actionId == 'viewkdsone' || $actionId == 'tableplaceorder' || $actionId == 'placeorder' || $actionId == 'orders') { echo "active" ;} ?>">-->
			<!--<a href="#orderDropdown" aria-expanded="<?php if($actionId == 'currentorders' || $actionId == 'parcels'  || $actionId == 'viewkdsone' || $actionId == 'tableplaceorder' || $actionId == 'placeorder' || $actionId == 'orders') { echo "true" ;} else {"false";} ?>" data-toggle="collapse"> <i class="fa fa-list-alt"></i>Orders </a>-->
   <!--           <ul id="orderDropdown" class="collapse list-unstyled <?php if($actionId == 'currentorders' || $actionId == 'parcels'  || $actionId == 'viewkdsone' || $actionId == 'tableplaceorder' || $actionId == 'placeorder' || $actionId == 'orders') { echo "collapse show" ;} ?>">-->
			<!--	<?php if( $roleId == '0' || in_array('Place Order',$permissions) ) {?>-->
			<!--	<li class="<?php if($actionId == 'tableplaceorder' || $actionId == 'placeorder') { echo "active" ;} ?>"><a href="<?= Url::to(['merchant/tableplaceorder']); ?>">Place Order</a></li>-->
			<!--	<?php } if($roleId == '0' || in_array('Table Order',$permissions)) { ?>-->
			<!--	<li class="<?php if($actionId == 'currentorders' || $actionId == 'parcels' ) { echo "active" ;} ?>"><a href="<?= Url::to(['merchant/currentorders']); ?>">Running Orders</a></li>-->
   <!--             <?php } if($roleId == '0' || in_array('Order History',$permissions)) { ?>-->
   <!--             <li class="<?php if($actionId == 'orders') { echo "active" ;} ?>"><a href="<?= Url::to(['merchant/orders']); ?>">Order History</a></li>-->
			<!--	<?php } if($roleId == '0' || in_array('KDS',$permissions)) { ?>-->
   <!--             <li class="<?php if($actionId == 'viewkdsone') { echo "active" ;} ?>"><a href="<?= Url::to(['merchant/viewkdsone']); ?>">KDS</a></li>-->
			<!--	<?php } ?>-->
			<!--</ul>-->
   <!--         </li>-->
			<!--		  <?php } ?>-->
			<?php if($roleId == '0' || in_array('KDS',$permissions) || in_array('Place Order',$permissions) || in_array('Table Order',$permissions) || in_array('Order History',$permissions) ) { ?>
            <li class="<?php if($actionId == 'tableplaceorder' || $actionId == 'placeorder' || $actionId == 'currentorders' || $actionId == 'orders'  || $actionId == 'parcels' || $actionId == 'viewkdsone') { echo "active" ;} ?>"><a href="<?= Url::to(['merchant/tableplaceorder']); ?>"><i class="fa fa-list-alt"></i>Orders </a>
            </li>
					  <?php } ?>
		  <?php if($roleId == '0' || in_array('Coupons',$permissions) ) { ?>
			
            <li class="<?php if($actionId == 'coupon') { echo "active" ;} ?> ">
				<a href="<?php echo Url::to(['merchant/coupon']); ?>"> <i class="fa fa-tags"></i>Coupons </a>
			</li>
            		  <?php } ?>
		  <?php if($roleId == '0' || in_array('Ratings',$permissions)) { ?>

            <li class="<?php if($actionId == 'rating') { echo "active" ;} ?> ">
				<a href="<?php echo Url::to(['merchant/rating']); ?>"> <i class="fa fa-star-o"></i>Ratings </a>
			</li>
					  <?php } ?>
		  <?php if($roleId == '0' || in_array('Recipe Management',$permissions) || in_array('Add Stock',$permissions) || in_array('Ingredients Stock',$permissions)
|| in_array('Ingredients',$permissions) || in_array('Vendor List',$permissions)
			  ) { ?>
			<li class="<?php 
			if($actionId == 'ingredients' || $actionId == 'recipeproducts' || $actionId == 'addinventory' || $actionId == 'vendorlist' 
			|| $actionId == 'ingredientstock' || $actionId == 'viewrecpie') { echo "active" ;} ?>">
			<a href="#inventoryDropdown" aria-expanded="<?php if($actionId == 'ingredients' || $actionId == 'viewrecpie'  || $actionId == 'ingredientstock' 
			|| $actionId == 'recipeproducts' || $actionId == 'addinventory' || $actionId == 'vendorlist' ) { echo "true" ;}else { "false"; }  ?>" data-toggle="collapse">
			<i class="fa fa-home"></i>Inventory </a>
              <ul id="inventoryDropdown" class="collapse list-unstyled <?php if($actionId == 'ingredients' || $actionId == 'viewrecpie' || $actionId == 'vendorlist' || $actionId == 'recipeproducts' || $actionId == 'addinventory' || $actionId == 'ingredientstock' ) { echo "collapse show" ;} ?>">
				<?php if($roleId == '0' || in_array('Recipe Management',$permissions)) { ?>
				<li class="<?php if($actionId == 'recipeproducts' || $actionId == 'viewrecpie') { echo "active" ;} ?>"><a href="<?= Url::to(['merchant/recipeproducts']); ?>">Recipe Management</a></li>
				<?php } if($roleId == '0' || in_array('Configurations',$permissions)) {  ?>
				<li class="<?php if($actionId == 'ingredients' ||  $actionId == 'vendorlist') { echo "active" ;} ?>"><a href="<?= Url::to(['merchant/ingredients']); ?>">Configurations</a></li>
				<?php } if($roleId == '0' || in_array('Add Stock',$permissions)) {  ?>
				<li class="<?php if($actionId == 'addinventory') { echo "active" ;} ?>"><a href="<?= Url::to(['merchant/addinventory']); ?>">Add Stock</a></li>
				<?php } if($roleId == '0' || in_array('Ingredients Stock',$permissions)) {  ?>
				<li class="<?php if($actionId == 'ingredientstock') { echo "active" ;} ?>"><a href="<?= Url::to(['merchant/ingredientstock']); ?>">Current Stock</a></li>
				<?php } if($roleId == '0' || in_array('Inventory Reports',$permissions)) {  ?>
				<li class="<?php if($actionId == 'stocksummary') { echo "active" ;} ?>"><a href="<?= Url::to(['merchant/stocksummary']); ?>">Reports</a></li>
				<?php } if($roleId == '0' || in_array('Inventory Reports',$permissions)) {  ?>
				<li class="<?php if($actionId == 'tax') { echo "active" ;} ?>"><a href="<?= Url::to(['merchant/taxes']); ?>">Tax</a></li>
				<?php } ?>
				</ul>
            </li>
		  <?php } ?>
		  <?php if($roleId == '0' || in_array('Employee List',$permissions) || in_array('Assign Permission',$permissions) || in_array('Add Role',$permissions) || in_array('Employee Attendance',$permissions) ) { ?>

			
			<li class="<?php if($actionId == 'employeelist' || $actionId == 'assignpermission' || $actionId == 'empattendancelist' || $actionId == 'editattendance' || $actionId == 'attendentview' || $actionId == 'assignrolepermission') { echo "active" ;} ?>">
			<a href="#empDropdown" aria-expanded="<?php if($actionId == 'employeelist' || $actionId == 'assignpermission' || $actionId == 'editattendance' || $actionId == 'attendentview' || $actionId == 'empattendancelist' || $actionId == 'assignrolepermission' ) { echo "true" ;}else { "false"; }  ?>" data-toggle="collapse">
			<i class="fa fa-home"></i>Employee Management </a>
              <ul id="empDropdown" class="collapse list-unstyled <?php if($actionId == 'employeelist' || $actionId == 'assignpermission' || $actionId == 'attendentview' || $actionId == 'editattendance' || $actionId == 'empattendancelist' || $actionId == 'assignrolepermission' ) { echo "collapse show" ;} ?>">
				<?php if($roleId == '0' || in_array('Employee List',$permissions)) { ?>
                <li class="<?php if($actionId == 'employeelist') { echo "active" ;} ?>"><a href="<?= Url::to(['merchant/employeelist']); ?>">Employee List</a></li>
				<?php } if($roleId == '0' ||in_array('Employee Attendance',$permissions)) { ?>
			<li class="<?php if($actionId == 'empattendancelist'|| $actionId == 'attendentview' || $actionId == 'editattendance') { echo "active" ;} ?>"><a href="<?= Url::to(['merchant/empattendancelist']); ?>">Employee Attendance</a></li>
				<?php } ?>
<?php  if($roleId == '0' ||in_array('Assign Permission',$permissions)) { ?>
			<li class="<?php if($actionId == 'assignpermission' || $actionId == 'assignrolepermission') { echo "active" ;} ?>">
			<a href="<?= Url::to(['merchant/assignpermission']); ?>">Assign Permissions</a></li>
				<?php } ?>				
			</ul>
            </li>
					  <?php } ?>
		  <?php if($roleId == '0' || in_array('Purchase Report',$permissions) || in_array('DASHBOARD',$permissions) || in_array('TRANSACTION DASHBOARD',$permissions)) { ?>
			
			<li class="<?php 
			if($actionId == 'reportpurchase' || $actionId == 'merchantdashboard' || $actionId == 'transcationdashboard' ) { echo "active" ;} ?>">
			<a href="#reportDropdown" aria-expanded="<?php if($actionId == 'reportpurchase' || $actionId == 'merchantdashboard' || $actionId == 'transcationdashboard' ) { echo "true" ;}else { "false"; }  ?>" data-toggle="collapse">
			<i class="fa fa-bar-chart"></i>Reports </a>
              <ul id="reportDropdown" class="collapse list-unstyled <?php if($actionId == 'reportpurchase' || $actionId == 'merchantdashboard' || $actionId == 'transcationdashboard') { echo "collapse show" ;} ?>">
<?php if($roleId == '0' || in_array('Purchase Report',$permissions)) { ?>             
			 <li class="<?php if($actionId == 'reportpurchase' ) { echo "active" ;} ?>"><a href="<?= Url::to(['report/reportpurchase']); ?>">Purchase</a></li>
<?php } ?>  
 <!--<?php if($roleId == '0' || in_array('DASHBOARD',$permissions) ) { ?>-->
	<!--			<li class="<?php if($actionId == 'merchantdashboard') { echo "active" ;} ?>"><a href="<?= Url::to(['merchant/merchantdashboard']); ?>">Insight</a></li>-->
 <!--               <?php } ?>-->
				<?php if($roleId == '0' || in_array('TRANSACTION DASHBOARD',$permissions) ) { ?>
				<li class="<?php if($actionId == 'transcationdashboard') { echo "active" ;} ?>"><a href="<?= Url::to(['merchant/transcationdashboard']); ?>">Transaction</a></li>
				<?php } ?>       
				                       <li class="<?php if($actionId == 'reportfeedback') { echo "active" ;} ?>"><a href="<?= Url::to(['report/reportfeedback']); ?>">Feedback </a></li>
                                <li class="<?php if($actionId == 'reportemployee') { echo "active" ;} ?>"><a href="<?= Url::to(['report/reportemployee']); ?>">Employee Report </a></li>
                                <li class="<?php if($actionId == 'reportpilot') { echo "active" ;} ?>"><a href="<?= Url::to(['report/reportpilot']); ?>">Pilot Report </a></li>
                                <li class="<?php if($actionId == 'reportcategory') { echo "active" ;} ?>"><a href="<?= Url::to(['report/reportcategory']); ?>">Category Wise Report </a></li>
                                <li class="<?php if($actionId == 'reporttablereservation') { echo "active" ;} ?>"><a href="<?= Url::to(['report/reporttablereservation']); ?>">Table Reservation Report </a></li>
		 
		   </ul>
            </li>
					  <?php } ?>
			  		  <?php if($roleId == '0' ) { ?>
            
    <li class="<?php 
			if($actionId == 'tablereservation' || $actionId == 'reservationhistory' ) { echo "active" ;} ?>">
			<a href="#resDropdown" aria-expanded="<?php if($actionId == 'tablereservation' || $actionId == 'reservationhistory' ) { echo "true" ;}else { "false"; }  ?>" data-toggle="collapse">
			<i class="fa fa-ticket"></i>Table Reservations </a>
              <ul id="resDropdown" class="collapse list-unstyled <?php if($actionId == 'tablereservation' || $actionId == 'reservationhistory' ) { echo "collapse show" ;} ?>">
<?php if($roleId == '0') { ?>             
			 <li class="<?php if($actionId == 'tablereservation' ) { echo "active" ;} ?>"><a href="<?= Url::to(['merchant/tablereservation']); ?>">New Reservations</a></li>
			  <li class="<?php if($actionId == 'reservationhistory' ) { echo "active" ;} ?>"><a href="<?= Url::to(['merchant/reservationhistory']); ?>">Reservation History</a></li>
<?php } ?>           
		   </ul>
            </li>
            		  <?php } ?>
            		  <?php if($roleId == '0' ) { ?>
            
    <li class="<?php 
			if($actionId == 'roomreservation' || $actionId == 'roomreservationhistory' ) { echo "active" ;} ?>">
			<a href="#roomDropdown" aria-expanded="<?php if($actionId == 'roomreservation' || $actionId == 'roomreservationhistory' ) { echo "true" ;}else { "false"; }  ?>" data-toggle="collapse">
			<i class="fa fa-hotel"></i>Room Reservations </a>
              <ul id="roomDropdown" class="collapse list-unstyled <?php if($actionId == 'roomreservation' || $actionId == 'roomreservationhistory' ) { echo "collapse show" ;} ?>">
<?php if($roleId == '0') { ?>             
			 <li class="<?php if($actionId == 'roomreservation' ) { echo "active" ;} ?>"><a href="<?= Url::to(['merchant/roomreservation']); ?>">Room Reservations</a></li>
			  <li class="<?php if($actionId == 'roomreservationhistory' ) { echo "active" ;} ?>"><a href="<?= Url::to(['merchant/roomreservationhistory']); ?>">Reservation History</a></li>
<?php } ?>           
		   </ul>
            </li>
            		  <?php } ?>
            		  <?php if($roleId == '0' ) { ?>
            
    <li class="<?php 
			if($actionId == 'prettycash' || $actionId == 'employeeadvances' || $actionId == 'vendorsettlements' ) { echo "active" ;} ?>">
			<a href="#expDropdown" aria-expanded="<?php if($actionId == 'prettycash' || $actionId == 'employeeadvances' || $actionId == 'vendorsettlements' ) { echo "true" ;}else { "false"; }  ?>" data-toggle="collapse">
			<i class="fa fa-money"></i>Expenses</a>
              <ul id="expDropdown" class="collapse list-unstyled <?php if($actionId == 'prettycash' || $actionId == 'employeeadvances' || $actionId == 'vendorsettlements' ) { echo "collapse show" ;} ?>">
<?php if($roleId == '0') { ?>             
			 <li class="<?php if($actionId == 'prettycash' ) { echo "active" ;} ?>"><a href="<?= Url::to(['merchant/prettycash']); ?>">Pretty Cash</a></li>
			  <li class="<?php if($actionId == 'employeeadvances' ) { echo "active" ;} ?>"><a href="<?= Url::to(['merchant/empattendancelist']); ?>">Employee Advances</a></li>
			  <li class="<?php if($actionId == 'vendorsettlements' ) { echo "active" ;} ?>"><a href="<?= Url::to(['merchant/vendorlist']); ?>">Vendor Settlements</a></li>
<?php } ?>           
		   </ul>
            </li>
            		  <?php } ?>
		 <!-- <?php if($roleId == '0' ) { ?>-->

   <!--         <li class="<?php if($actionId == 'accessforbidden') { echo "active" ;} ?> "> <a href="<?= Url::to(['merchant/accessforbidden']); ?>"> -->
			<!--<i class="fa fa-users"></i>Users </a></li>-->
   <!--         		  <?php } ?>-->

            
          </ul>
        </nav>