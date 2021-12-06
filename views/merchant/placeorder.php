
<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use aryelds\sweetalert\SweetAlert;
$actionId = Yii::$app->controller->action->id;

?>
<style>
.fixed {position:fixed; top:0;right: 15px;width: 245px;}
.placeorder{background:#FD8B02;border:1px solid #FD8B02;color:#fff;border-radius:24px;}
.placeorder:hover{bacground:#28a745;border:1px solid #28a745;}
.scroller{max-height:300px !important;}
.nav__scroll--left{left:0px !important;}
</style>
<style> 
            div.scroll { 
                margin: 4px, 4px; 
                padding: 4px; 
                width:100%;
                overflow-x: auto; 
                overflow-y: hidden; 
            } 
            .fixed {
    position: fixed;
    top: 0;
    right: 15px;
    width: 320px;
}
section {
    padding: 5px 0;
}
.nav__link {
    display: block;
    padding: 1px 4px !important;
    cursor: pointer;
    vertical-align: top;
    font-size: 14px;
    padding-top:0px;
    text-align: center;
}
.nav__item {
    height: 35px;
    flex-shrink: 0;
    background: #D07A12;
    border-radius: 8px;
    margin-left: 5px;
    margin-bottom: 3px;
    text-align: center;
    box-shadow: 2px 2px 4px #D98B2B80;
    color: #fff;
    font-size: 15px;
    font-weight: 700;
    line-height: 30px;
}


        </style>
<script src="<?= Yii::$app->request->baseUrl.'/js/bootstrap-typeahead.js'?>"></script>



<header class="page-header">
<?php 
$kot = 0;
if (Yii::$app->session->hasFlash('success')): 
   echo SweetAlert::widget([
    'options' => [
        'title' => "Order!",
        'text' => "Order Placed Successfully",
        'type' => SweetAlert::TYPE_SUCCESS,
		'timer' => 3000,
    ]
]);
$kot = 1;
 endif;  
 $prodSectionId = array_column($productDetails,'food_category_id','ID');
 ?>
          </header>
<script>
<?php if(!empty($_GET['current_order_id']) && $kot == 1){ ?>
	bckot(<?= $_GET['current_order_id']; ?>);
<?php 
$kot = 0;
} ?>
function bckot(id){

var form=document.createElement('form');
        form.setAttribute('method','post');
        form.setAttribute('action','tablekot');
        form.setAttribute('target','_blank');

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "id");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", id);
    form.appendChild(hiddenField);

    document.body.appendChild(form);
    form.submit();    


}
</script>
		
		       
			  <div class="card-header d-flex align-items-center pt-1 pb-0">
                <!--<h3 class="h4 col-md-6 pl-0 tab-title">Menu</h3>-->
                 <?= \Yii::$app->view->renderFile('@app/views/merchant/_orders.php',['actionId'=>$actionId]); ?>
				<div class="col-md-6 text-right pr-0">
					<!--<button type="button" class="btn btn-add btn-xs placeorder" onclick="runningOrder('<?= $tableid; ?>')" >Running Orders</button>-->
				</div>
              </div>
              <section class="col-md-12">
              <div class="row">
              <section class="col-md-2">
                  <div>
 <!--   <ul style="width:140px;display: inline-block;">-->
	<!--</ul>-->
<nav id="nav" class="nav nav--scrollable">
        <div>
             <ul>
                 <label class="nav nav--scrollable ml-2">CATEGORIES</label>
                 
            <?php 
            $categeryCount = count($allcategeries); ?>
<li class="nav__item red nav__menu-item nav__menu-item--has-children " tabindex="0">
<span class="nav__link nav__link--has-dropdown"  onclick="selectiemproduts('')">
                        <i></i>All Categories
                        
                    </span>
                   
</li>

            <?php for($c=0;$c<$categeryCount;$c++) { ?>
                          
<li class="nav__item <?php if($allcategeries[$c]['merchant_id'] == '' || $allcategeries[$c]['merchant_id'] == '24') { ?> green <?php } else if($allcategeries[$c]['merchant_id'] == '1') { ?> orange
<?php	} else  { ?> red
<?php	} ?> nav__menu-item nav__menu-item--has-children " tabindex="0">
<span class="nav__link nav__link--has-dropdown"  onclick="selectiemproduts('<?= htmlspecialchars($allcategeries[$c]['food_category']);?>')">
                        <i></i> <?= $allcategeries[$c]['food_category'];?>
                        
                    </span>
                   
</li>
			<?php } ?>
        
           </ul>

        </div>
    </nav>
    </div>

              </section>
		
<!--		<ul style="width:140px;display: inline-block;">-->
<!--	<li class="nav__item nav__menu-item nav__menu-item--has-children active" tabindex="0" >-->
<!--<span class="nav__link nav__link--has-dropdown" onclick="placeOrder('PARCEL','','')">-->
<!--                        <i class="fa fa-cubes" ></i> Takeaway-->
                        
<!--                    </span>-->
                   
<!--</li>-->
<!--	</ul>-->
<div class="col-md-7">
<nav id="nav" class="nav nav--scrollable mt-0 w-100">
        <div>
             <ul id="nav__inner-wrap" class="nav__inner-wrap" style="font-size:14px;padding:0px;">
            <?php for($t=0;$t<count($tableDetails);$t++) { ?>
                          
<li class="nav__item <?php if($tableDetails[$t]['orderprocess'] == '' || $tableDetails[$t]['orderprocess'] == '5') { ?> green <?php } else if($tableDetails[$t]['orderprocess'] == '2') { ?> orange
<?php	} else  { ?> red
<?php	} ?> nav__menu-item nav__menu-item--has-children " tabindex="0">
<span class="nav__link nav__link--has-dropdown" onclick="placeOrder('<?= $tableDetails[$t]['ID']?>','<?= $tableDetails[$t]['name']?>','<?= $tableDetails[$t]['current_order_id']?>')">
                        <i></i> <?= $tableDetails[$t]['name'];?>
                        
                    </span>
                   
</li>
			<?php } ?>
        
           </ul>

        </div>
    </nav>
    <div class="card">
              
              <div class="card-header d-flex align-items-center">
                <h3 class="h4 float-left col-md-8">Items List</h3>
				<div class="col-md-4 float-right">
				<input type="text" class="form-control" id="myInput">
				</div>
              </div>
              <div class="card-body p-3">
                <div class="main row"> 
                <!-- TENTH EXAMPLE -->
				
				<?php 
				$row = 0;
				//echo "<pre>";print_r($idArr);exit;
				foreach($mainProducts as $key => $value){ 
				$food_cat_arr = array_values(array_filter(array_column(array_values($mainArr[$key]),'food_category_quantity')));
				?>
				<div class="col-md-4 filtersearch">
                <div class="view view-tenth">
			<?php 	
			if(empty($imgArr[$value])){ ?>
						<div class="title-noimg"><?= $key ;?></div>
			<?php }
			//else if (file_exists( '../..'.\yii\helpers\Url::to(['uploads/productimages/'.$imgArr[$value]]) )) {
else if (!empty($imgArr[$value]) ) {
				?>
  <img src="<?= Yii::$app->request->baseUrl.'/uploads/productimages/'.$imgArr[$value];?>" />
  					<div class="res-title"><?= $key ;?></div>
 <?php }
 else{  ?>
	 					<div class="title-noimg"><?= $key ;?></div>
<?php  }?>

                   <div class="search-title"><?= !empty($foodCategoryNameArr[$key]) ? $foodCategoryNameArr[$key] : '' ; ?></div>

                    <div class="mask col-md-12">
					<div class="op-text"><?= $key ;?></div>
					<div class="row">
					
					<?php if(!empty($food_cat_arr)) {
						?>
					<div class="col-md-12">
						<?php 
$tabelDet = \app\models\Tablename::findOne($tableid);
						for($fc=0;$fc<count($food_cat_arr);$fc++){
						    $productId = $idArr[$key.'_'.$food_cat_arr[$fc]];
					        $sqlSectionPrice = 'select * from section_item_price_list 
					        where merchant_id= \''.Yii::$app->user->identity->merchant_id.'\' and item_id = \''.$productId.'\' 
					        and section_id = \''.$tabelDet['section_id'].'\'';
					        $resSectionPrice = Yii::$app->db->createCommand($sqlSectionPrice)->queryOne();
					        if(!empty($resSectionPrice)){
					            if($resSectionPrice['section_item_sale_price'] > 0){
					                $priceItem = $resSectionPrice['section_item_sale_price'];
					            }else{
					                $priceItem = $priceArr[$key.'_'.$food_cat_arr[$fc]];    
					            }
					            
					        }else{
					            $priceItem = $priceArr[$key.'_'.$food_cat_arr[$fc]];
					        }
					        
						?>					
					<a onclick="productorder('<?= $idArr[$key.'_'.$food_cat_arr[$fc]]; ?>','<?= $key;?>','<?= $food_cat_qty_det[$food_cat_arr[$fc]]; ?>'
					,'<?= $priceItem; ?>')"><div><i class="fa fa-dot-circle-o" ></i> 
					<?= $food_cat_qty_det[$food_cat_arr[$fc]] ?> -  <i class="fa fa-inr"> <?php echo $priceItem;?></i></div></a>
						<?php } ?>
					</div>
					<?php  }else{
					$productId = $idArr[$key.'_'];
					        $sqlSectionPrice = 'select * from section_item_price_list 
					        where merchant_id= \''.Yii::$app->user->identity->merchant_id.'\' and item_id = \''.$productId.'\' 
					        and section_id = \''.$tabelDet['section_id'].'\'';
					        $resSectionPrice = Yii::$app->db->createCommand($sqlSectionPrice)->queryOne();
					        if(!empty($resSectionPrice)){
					            if($resSectionPrice['section_item_sale_price'] > 0){
					                $priceItem = $resSectionPrice['section_item_sale_price'];
					            }else{
					                $priceItem = $priceArr[$key.'_'];    
					            }
					            
					        }else{
					            $priceItem = $priceArr[$key.'_'];
					        }
					?>
					<div class="col-md-12">
					
					  <a onclick="productorder('<?= $idArr[$key.'_']; ?>','<?= $key;?>','','<?= $priceItem; ?>')"><div>
					  <i class="fa fa-dot-circle-o" ></i> <?php echo $key ?> - <i class="fa fa-inr"> <?php echo $priceItem;   ?></i></div></a>
                      
					</div>
						
					<?php } ?>
					
					</div>
					</div>
                </div>
				</div>
				<?php $row++; } ?>
				
				

            </div>
              </div>
            </div>
    </div>
    <div class="col-md-3" >
         <div class="card image">
             <div class="card-header">
		  <div class="row">
		  <h3 class="col-md-12 h4 text-center">SECTIONS</h3><br>
		  <?php 
		  $sectionsArr = array_column($tableSections,'section_name','section_id');
		  $se = 1 ;
		  foreach($sectionsArr as $col => $val){ ?>
		  
		  <button type="button" class="btn btn-add btn-xs m-auto" onclick="tablesection('<?= $col; ?>')" ><?= $val; ?></button>

		  <?php 
if($se % 2 == 0){
    echo '<br><br><br>';
}
		  $se++;
		  } ?>
		  </div>
		  </div>
         </div>
		  <div class="card m-auto" id="task_flyout">
		  <div class="card-header">
		  <div class="row">
		  <h3 class="col-md-8 h4"><?php if(!empty($tableName)){ echo $tableName; } else { echo "Takeaway"; }?> Order
		  <?php 

		  $totalBill = array_sum(array_column($prevOrderDetails,'totalprice')) ?? 0;
		  
		  ?>
		  
		  <h6 class="col-md-4 text-right amt"><i class="fa fa-inr"></i> <span id="totalbill"><?= $totalBill;?></span></h6></h3>
		  </div>
		  </div>
		  <div class="card-body p-3 scroller">
		  <form method="POST"  >
		  <div id="addnewprod">
			<?php 
			$orderIdName = array_column($productDetails,'title','ID');
			$orderIdQty = array_column($productDetails,'food_category_quantity','ID');
			$previousIds = array_column($prevOrderDetails,'product_id');
			
			for($p=0;$p<count($prevOrderDetails);$p++) {
if(!empty($orderIdQty[$prevOrderDetails[$p]['product_id']])){
	$qtyName = $food_cat_qty_det[$orderIdQty[$prevOrderDetails[$p]['product_id']]];
	$qtyName = " (".$qtyName.")";
}
else{
	$qtyName = '';
}
				?>
						<div class="col-xs-12 item-shdw p-2 mb-2" id="productorder<?= $prevOrderDetails[$p]['product_id'] ?>">
		  <div class="row">
			<div class="col-md-9">
	  <h6><?= $orderIdName[$prevOrderDetails[$p]['product_id']]. $qtyName ;?>  			 </h6>
			</div>
			<div class="col-md-3 pl-0">
			<h6 class="amt"><i class="fa fa-inr"></i> <span id="priceIncrement_<?= $prevOrderDetails[$p]['product_id'] ?>"><?= $prevOrderDetails[$p]['price'] * $prevOrderDetails[$p]['count']?></span></h6>
			</div>
		  </div>
                            <div class="product-qty row">
                                <div class="option-label col-md-3 pr-1">Qty</div>
                                <div class="qty qty-changer col-md-7 pr-2">
                                    <fieldset>
                                        <button type="button" class="decrease"  onclick="orderdecrement('<?= $prevOrderDetails[$p]['product_id'] ?>','<?= $prevOrderDetails[$p]['price'] ?>')"></button>
                                        <input type="text" class="qty-input" value="<?= $prevOrderDetails[$p]['count']?>" name="quantity" id="quantity_<?= $prevOrderDetails[$p]['product_id'] ?>" data-min="1"  readonly="true">
                                        <input type="hidden" value="<?= $prevOrderDetails[$p]['product_id'] ?>" name="product[]" id="product_<?= $prevOrderDetails[$p]['product_id'] ?>"   readonly="true">
<input type="hidden" value="<?= $prevOrderDetails[$p]['count'] ?>" name="order_quantity[]" id="order_quantity_<?= $prevOrderDetails[$p]['product_id'] ?>"   readonly="true">
<input type="hidden" value="<?= $prevOrderDetails[$p]['price']?>" name="order_price[]" id="order_price_<?= $prevOrderDetails[$p]['product_id'] ?>"   readonly="true">
<input type="hidden" value="<?= $tableid ; ?>" name="tableid"    readonly="true">
				   <button type="button" class="increase" id="increment_<?= $prevOrderDetails[$p]['product_id'] ?>" onclick="orderincrement('<?= $prevOrderDetails[$p]['product_id'] ?>','<?= $prevOrderDetails[$p]['price'] ?>','1')"></button>
                                    </fieldset>
                                </div>			
								<!-- <div class="col-md-2 pl-0 pr-1 pt-1"><a><i class="fa fa-trash text-red border-red" onclick="deleteprodorder('<?= $prevOrderDetails[$p]['product_id'] ?>','1')"></i></a></div>   -->
                            </div>
                        </div>
			<?php } ?>
						
			</div>
			</form>
		  </div>
		  <div class="card-footer">
		  <div class="col-md-12">
						<div class="row">

					<!--	<input type="submit"  class="btn btn-add btn-block" value="Place Order" />
					<button type="button" class="btn btn-add btn-block" data-toggle="modal" data-target="#myModal">Place Order</button> -->
					<button type="button" class="btn btn-add btn-block placeorder mb-2" onclick="orderpreview()">Place Order</button>
					<div class="text-center m-auto">
						<?php  $c_order = (array_column($tableDetails,'current_order_id','ID'));
						
if($tableid != 'PARCEL') {
						?>
						<button type="button" class="btn btn-add btn-xs" onclick="billviewkot('<?= $c_order[$tableid] ;?>');">KOT</button>						
						
<?php } ?>
<button type="button" class="btn btn-add btn-xs mr-1" onclick="billview('<?= $c_order[$tableid] ;?>');">BILL</button>
						<?php 
						if(count($prevOrderDetails)){
						  $ordertypedet = \app\helpers\Utility::order_details($prevOrderDetails[0]['order_id'],'ordertype') ;
    						if($ordertypedet == '1'){
    						    $toShowPopUp = '1';   
    						}else{
    						    $toShowPopUp = '2';
    						}
						    
						}else{
						    $toShowPopUp = '0';
						}
						?>
						<button type="button" class="btn btn-add btn-xs" onclick = "statusPopUp('<?= $toShowPopUp; ?>','<?= !empty($prevOrderDetails) ? $prevOrderDetails[0]['order_id'] : "" ; ?>')">Check Out</button>
						</div>

					
						</div>
						
						</div>
		  </div>
		  </div>
		  </div>
    </div>
    </section>
<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Order Confirmation</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
			

	<form action="savetableorder" method="POST" onsubmit="return revalidate()">
<div class="row">	
<div class="col-md-5 scroller" style="max-height:445px !important;">	

	 						  <div id="edit_place_order_pop_up"></div>

</div>
<div class="col-md-2" style="padding-top:120px">
<div class="tax maintax click" style="cursor: pointer;" onclick="taxation('1')"><a>GST</a></div>
<div class="tax subtax click"  style="cursor: pointer;display:none " onclick="taxation('2')"><a>FOODQ TAX</a></div>
</div>
<div class="col-md-5">
<div  class="">
		  <div class="row">
			<div class="col-md-8">Amount</div>
			<div class="col-md-4 text-right"><i class="fa fa-inr"></i> <span id="popupamount">  </span></div>
			<input type="hidden" id="popupamounthidden" name="popupamount">
		  </div>
		  <hr class="mt-1 mb-1">
		  <div class="row">
			<div class="col-md-8">Coupon/Discount Amount </div>
			<div class="col-md-4 text-right"><i class="fa fa-inr"></i> <span id="popupcouponamt"><?= $prevFullSingleOrderDet['couponamount'] ?? 0 ?></div>
			<input type="hidden" id="couponamounthidden" value="<?= $prevFullSingleOrderDet['couponamount'] ?? '' ?>" name="couponamountpopup">
		  </div>
		  <hr class="mt-1 mb-1">
		  <div class="row">
			<div class="col-md-8">Tax Amount</div>
			<div class="col-md-4 text-right"><i class="fa fa-inr"></i> <span id="popuptaxamt"></span> </div>
			<input type="hidden" id="popuptaxamthidden" name="popuptaxamt">
		  </div>
		  <hr class="mt-1 mb-1" style="display:none">
		  <div class="row" style="display:none">
			<div class="col-md-8">Tip Amount</div>
			<div class="col-md-4 text-right"><i class="fa fa-inr"></i> <span id="popuptipamt"><?= 1;?></span></div>
			<input type="hidden" id="popuptipamthidden" value="0" name="popuptipamt">
		  </div>
		  <hr class="mt-1 mb-1" style="display:none">
		  <div class="row" style="display:none">
			<div class="col-md-8">Subscription Amount</div>
			<div class="col-md-4 text-right"><i class="fa fa-inr"></i> <span id="popupsubscriptionamt"></span></div>
			<input type="hidden" id="popupsubscriptionamthidden" value="0" name="popupsubscriptionamt">
		  </div>
		  <hr class="mt-1 mb-1">
		  <div class="row">
			<div class="col-md-8">Total Amount</div>
			<div class="col-md-4 text-right"><i class="fa fa-inr"></i> <span id="popuptotalamt"></span></div>
			<input type="hidden" id="popuptotalamthidden" name="popuptotalamt">
			<input type="hidden" id="popupdiscountnum" name="popupdiscountnum">
		  </div>
		  
		  </div>
		  <div class="form-group row mt-3">
		<!--<label class="control-label col-md-4">Customer Name</label>-->
		<div class="col-md-12">
		<input type="text" id="customer_name" name="customer_name" autocomplete="off" placeholder="Customer Name" value="<?= $prevOrderDetails[0]['name'] ?? null?>" class="form-control">
		</div>
		</div>
		<div class="form-group row">
		<!--<label class="control-label col-md-4">Customer Mobile</label>-->
		<div class="col-md-12">
		<input type="text" id="customer_mobile" autocomplete="off" name="customer_mobile" placeholder="Mobile Number" value="<?= $prevOrderDetails[0]['mobile'] ?? null?>" class="form-control">
		</div>
		</div>
		<div class="form-group row">
		<!--<label class="control-label col-md-4">Payment Mode</label>-->
		<div class="col-md-6">
		<input type="text" id="merchant_coupon" name="merchant_coupon" value="<?= $prevFullSingleOrderDet['coupon'] ?? '' ?>" autocomplete="off" placeholder="Apply Coupon" class="form-control">
		</div>
		<div class="col-md-6">
		<select class="form-control" name="payment_mode">
		<option value='cash' <?php if($prevFullSingleOrderDet['paymenttype'] == 'cash') { echo 'selected'; } ?>>Cash</option>
		<option value='online' <?php if($prevFullSingleOrderDet['paymenttype'] == 'online') { echo 'selected'; } ?>>Online</option>
		</select>
		</div>
		</div>
		
				<div class="form-group row">
		<!--<label class="control-label col-md-4">Apply Coupon</label>-->
		<div class="col-md-6">
		<select class="form-control" name="discount_mode" id="discount_mode" onchange="commontrigger()" >
		<option value=''>Discount Type</option>    
		<?php foreach($discountTypes as $key=>$value){ ?>
		    <option value="<?= $key; ?>"><?= $value; ?></option>
		<?php } ?>
		</select>
		</div>
		<div class="col-md-6">
		<input type="text" id="merchant_discount" name="merchant_discount" value="" autocomplete="off" placeholder="Discount" class="form-control" onchange="commontrigger()" onkeypress="return isNumber(event)">
		</div>
		</div>
		<div class="form-group row">
		        <div class="col-md-6">
		       <select class="form-control" name="splits">
		           <option value=''>Bill Split</option>
		           <option value='split'>No.of Splits</option>
		           <option value='payment'>Payment Wise</option>
		       </select>
		       
		    </div>
		    
		    <div class="col-md-4">
		<input type="text" id="merchant_splits" name="merchant_splits" value="" autocomplete="off" placeholder="Splits" class="form-control">
		</div>
		<div class="col-md-1">
		       <a title="bill"><span class="fa fa-credit-card pt-2"></span></a>
		   </div>
		

		</div>
		<div class="form-group row">
		<!--<label class="control-label col-md-4">Pilot</label>-->
		<div class="col-md-12">
		<select class="form-control" name="selectedpilot">
		<option value="">Select Pilot</option>
		<?php for($s=0;$s<count($resServiceBoy);$s++) { ?>
		<option value='<?= $resServiceBoy[$s]['ID']; ?>' <?php if( $resServiceBoy[$s]['ID'] == $prevFullSingleOrderDet['serviceboy_id'] ) { ?> selected <?php } ?> ><?= $resServiceBoy[$s]['name']; ?></option>
		<?php } ?>
		</select>
		</div>
		</div>
</div>

	   </div>
	   
	   </div>
	   
	   
	   
	   
	   <div class="modal-footer">
	   <div class="col-md-12 text-center">
		<button class="btn btn-danger btn-xs" style="border-radius:25px;" data-dismiss="modal">Cancel</button>
		<?= Html::submitButton('Confirm KOT', ['class'=> 'btn btn-add placeorder']); ?>
		<div class="text-center mt-3">
		<button type="button" class="btn btn-add btn-xs">KOT</button>
						<?php  $c_order = (array_column($tableDetails,'current_order_id','ID'));
						
if($tableid != 'PARCEL') {
						?>
						
						<button type="button" class="btn btn-add btn-xs mr-1" onclick="billview('<?= $c_order[$tableid] ;?>');">BILL</button>
<?php } ?>
						
						<?php 
						if(count($prevOrderDetails)){
						  $ordertypedet = \app\helpers\Utility::order_details($prevOrderDetails[0]['order_id'],'ordertype') ;
    						if($ordertypedet == '1'){
    						    $toShowPopUp = '1';   
    						}else{
    						    $toShowPopUp = '2';
    						}
						    
						}else{	
						    $toShowPopUp = '0';
						}
						?>
						<button type="button" class="btn btn-add btn-xs" onclick = "statusPopUp('<?= $toShowPopUp; ?>','<?= !empty($prevOrderDetails) ? $prevOrderDetails[0]['order_id'] : "" ; ?>')">Check Out</button>
						</div>

      </div> 
	  </div>
</form>
</div>
</div>
</div>

<div id="updateorderstatuschange" class="modal fade" role="dialog">
<div class="modal-dialog modal-md" >
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Order Close</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
	    <div class="modal-body" id="updateorderstatuschangebody">
			<div class="row">	

                               
<div class="col-md-6">	
	   <div class="form-group row">
	   <label class="control-label col-md-4">Payment Method</label>
	   <div class="col-md-8">
			      <select id="orderpaymethod">
			          <option value="Cash">Cash</option>
			          <option value="Card">Card</option>
			          <option value="Gpay">Gpay</option>
			          <option value="Pending">Pending</option>
			     </select>     
	   </div>
	   </div>
	   
	    <div class="form-group row">
	   <label class="control-label col-md-4">Company</label>
	   <div class="col-md-8">
			      <select id="orderorigin">
			          <option value="Swiggy">Swiggy</option>
			          <option value="Card">Zomato</option>
			          <option value="">None</option>
			     </select> 
			     <input type="hidden" id="curr_Order_id" >
	   </div>
	   </div>
	   
	   
	   </div>
	   </div>
		</div>	
		
		  <div class="modal-footer">
		<?= Html::submitButton('Confirm', ['class'=> 'btn btn-add btn-hide','id'=>'closeOrderStatus']); ?>
		<button class="btn btn-danger btn-xs" data-dismiss="modal">Cancel</button>

      </div> 
		
	</div>
	</div>
</div>
        </section>
		<script src="<?= Yii::$app->request->baseUrl.'/js/fontfaceobserver.min.js'?>"></script>
		<script src="<?= Yii::$app->request->baseUrl.'/js/menu.js'?>"></script>
<script>
$(document).ready(function(){
$(".nav__item:hover").parents('.nav__outer-wrap').css("overflow-x", "hidden");
});
$(window).scroll(function(){
      if ($(this).scrollTop() > 135) {
          $('#task_flyout').addClass('fixed');
      } else {
          $('#task_flyout').removeClass('fixed');
      }
  });
  function taxation(type)
  {
	if(type == '1'){
		 if( $(".maintax").hasClass("click") == true)
		 {
			 $(".maintax").removeClass("click");
			$("#popuptaxamt").html(0);

		 }
		 else{
			 $(".maintax").addClass("click");
			 	var nrmltotal = $("#totalbill").html();
		//		$("#popuptaxamt").html(((2.5/100)*parseInt(nrmltotal)).toFixed(2));
var calTaxAmt = calculateTaxAmount();
		$("#popuptaxamt").html(calTaxAmt.toFixed(2));
		 }
	}else{
		 if( $(".subtax").hasClass("click") == true)
		 {
			 $(".subtax").removeClass("click");
			 $("#popupsubscriptionamt").html(0);
			 
		 }
		 else{
			$(".subtax").addClass("click");
			 	var nrmltotal = $("#totalbill").html();
			//	$("#popuptaxamt").html(((2.5/100)*parseInt(nrmltotal)).toFixed(2));
var calTaxAmt = calculateTaxAmount();
		$("#popuptaxamt").html(calTaxAmt.toFixed(2));
			var subpercenttage = (Math.floor((1/100)*parseInt(nrmltotal)).toFixed(2));
			if(subpercenttage<1){
						var subpercenttage = 1;
					}
					if(subpercenttage>10){
						var  subpercenttage = 10;
					}
	$("#popupsubscriptionamt").html(subpercenttage);				
		 }
	}
	
commontrigger();	
   }
   function commontrigger(retCalAmt = 0,orderPreview = 0)
   {
	//    var nrml_total = parseInt($("#totalbill").html());
	var nrml_total = parseInt($("#popupamount").html());
	//	var subscription_tax = parseFloat($("#popupsubscriptionamt").html());
	    var subscription_tax = 0; 
		var tax_amt = parseFloat($("#popuptaxamt").html());
	    var tip_amt = 0;
		var coupon_amt = parseInt($("#popupcouponamt").html());	
		
    var discount_mode = $('#discount_mode').val();
    if($('#merchant_discount').val() > 0){
        var merchant_discount = $('#merchant_discount').val();    
    }
    else{
        var merchant_discount = 0;
    }
    




//		$("#popuptotalamt").html(nrml_total + subscription_tax +tax_amt +tip_amt-coupon_amt );
//		$("#popuptotalamthidden").val(nrml_total + subscription_tax +tax_amt +tip_amt-coupon_amt);
		$("#popupamounthidden").val(nrml_total);
		$("#popupsubscriptionamthidden").val(subscription_tax);
		$("#popuptaxamthidden").val(tax_amt.toFixed(2));
		$("#popuptipamthidden").val(tip_amt);

        var actualTotalAmt = (nrml_total + subscription_tax + tax_amt + tip_amt ).toFixed(2);
        $('.itemwise').hide();
	    if(discount_mode == '1'){
	        			$(".item_discnt").val(0);
			$(".item_type_discnt").val('');	
            var calAmount = parseFloat(merchant_discount);
        } else if(discount_mode == '2'){
            $(".item_discnt").val(0);
			$(".item_type_discnt").val('');	
            var calAmount = (parseFloat(actualTotalAmt) * parseFloat(merchant_discount)) / 100 ;
        }
        else if(discount_mode == '3'){
            itemwisediscount();
            var calAmount = retCalAmt;
        }
        else if(orderPreview == 1){
            var calAmount = coupon_amt;
        }
        else{
            var calAmount = 0;
        }
        var totalAmt = Math.round(actualTotalAmt - calAmount);
        if(totalAmt < 0){
            alert("Discount amount cannot be greater than total amount");
            $('#merchant_discount').val(0);
            return false;
        }
        else{
            $("#popupcouponamt").html(calAmount);
            $("#couponamounthidden").val(calAmount);
        }
		$("#popuptotalamt").html((totalAmt).toFixed(2));
		$("#popuptotalamthidden").val((totalAmt).toFixed(2));


		
   }
   function itemwisediscount(){
    $('.itemwise').show();
    $("#merchant_discount").val(0);
    return 0;
    
   }
   
   function itemdiscount(itemid){
       var calAmount = 0;
	   var runningCalAmount = 0;	   
	   var itd = $('.item_discnt').map((_,el) => el.value).get();
	   var ittd = $('.item_type_discnt').map((_,el) => el.value).get();
	   var itempriceclass = $('.itempriceclass').map((_,el) => el.value).get();
       if(itd.length > 0){
				for(var i = 0; i < itd.length ; i++){
					if(itd[i] > 0){
						if(itd[i] >  parseFloat(itempriceclass[i])){
							alert("Discount amount cannot be greater than item price");
                            $("#item_discount_num_"+itemid).val(0);
						}
						else{
						if(ittd[i] == '1'){
							calAmount = calAmount + parseFloat(itd[i]);
						}
						else if(ittd[i] == '2'){
							calAmount = calAmount + ((parseFloat(itempriceclass[i]) * parseFloat(itd[i])) / 100) ;
						}
						else{
							calAmount = calAmount;
							$("#item_discount_num_"+itemid).val(0)
						}
					}
						
					}
				}
	   }
	   
/*
        var itemdiscounttype = $("#item_discount_type_"+itemid).val();
        var itemdiscountnum = $("#item_discount_num_"+itemid).val();
        var priceIncrementPopup =  parseFloat($("#priceIncrementPopup_"+itemid).html());
        if(itemdiscounttype == '1'){
            calAmount = parseFloat(itemdiscountnum);            
        }
        else if(itemdiscounttype == '2'){
            calAmount = (parseFloat(priceIncrementPopup) * parseFloat(itemdiscountnum)) / 100 ;            
        }
        else{
            $("#item_discount_num_"+itemid).val(0)
        }
        
        if(calAmount > 0 ){
            if(calAmount > parseFloat(priceIncrementPopup)){
                            alert("Discount amount cannot be greater than item price");
                            $("#item_discount_num_"+itemid).val(0);
                            calAmount = 0;
            }else{
                calAmount = calAmount;                
            }

        }
        else{
            calAmount = 0;
        }
	*/
        commontrigger(calAmount);
   }
  function orderpreview(){
	   validate();
	var x = $("form").serializeArray();
	var order_quantity_arr = $("input[name='order_quantity[]']")
              .map(function(){return $(this).val();}).get();
	var order_price_arr = $("input[name='order_price[]']")
              .map(function(){return $(this).val();}).get();	
	var order_product_arr = $("input[name='product[]']")
              .map(function(){return $(this).val();}).get();				  
	var type = 2;		  
	$("#edit_place_order_pop_up").html('');
	var  mainProductsName = '<?php echo json_encode($mainProductsName); ?>';
	mainProductsName = JSON.parse(mainProductsName);
	var tableId = '<?php echo $tableid; ?>';
	var taxamt = 0;
		for(var i=0;i<order_quantity_arr.length;i++)
		{
			if(order_quantity_arr[i] > 0){
			 var merchantfoodTaxJsn =   '<?= json_encode($MerchantfoodTaxArr); ?>';
			 var prodSectionId = '<?= json_encode($prodSectionId); ?>';
			 var prodFoodSection = JSON.parse(prodSectionId);
			 var merchantfoodTaxArr = JSON.parse(merchantfoodTaxJsn);
			 var foodTaxArr = (merchantfoodTaxArr[prodFoodSection[order_product_arr[i]]]);
		     var foodTaxValue = 0;
		     var calFoodTax = 0;
			if(foodTaxArr.length > 0){
			    for(var f =0;f< foodTaxArr.length ; f++){
			        foodTaxValue = foodTaxArr[f]['tax_value'];
			        calFoodTax = calFoodTax + parseFloat(order_quantity_arr[i] * order_price_arr[i]) * (foodTaxValue/100); 
			    }
                taxamt = taxamt + calFoodTax; 
			}
			
			$("#edit_place_order_pop_up").append('<div class="col-xs-12 item-shdw p-2 mb-2" id="productorderpopup'+order_product_arr[i]+'">\
		  <div class="row">\
			<div class="col-md-9">\
	            <h6>'+mainProductsName[order_product_arr[i]]+'  			 </h6>\
			</div>\
			<div class="col-md-3 pl-0">\
			    <h6 class="amt"><i class="fa fa-inr"></i> <span  id="priceIncrementPopup_'+order_product_arr[i]+'">'+order_quantity_arr[i] * order_price_arr[i]+'</h6>\
		<input type="hidden" class="itempriceclass" value="'+order_quantity_arr[i] * order_price_arr[i]+'">\
		<input type="hidden" class="itemid" value="'+order_product_arr[i]+'">\
			</div>\
		  </div>\
            <div class="product-qty row">\
                <div class="option-label col-md-3 pr-1">Qty</div>\
                    <div class="qty qty-changer col-md-7 pr-2">\
                        <fieldset>\
                            <button type="button" class="decrease" onclick="orderdecrement('+order_product_arr[i]+','+order_price_arr[i]+','+type+')"></button>\
                                <input type="text" class="qty-input" value="'+order_quantity_arr[i]+'" name="quantity" id="quantityPopup_'+order_product_arr[i]+'" data-min="1" readonly="true">\
                                <input type="hidden" value="'+order_product_arr[i]+'" name="product_popup[]" id="product_'+order_product_arr[i]+'" readonly="true">\
<input type="hidden" value="'+order_quantity_arr[i]+'" name="order_quantity_popup[]" id="order_quantity_popup_'+order_product_arr[i]+'" readonly="true">\
<input type="hidden" value="'+order_price_arr[i]+'" name="order_price_popup[]" id="order_price_'+order_product_arr[i]+'" readonly="true">\
<input type="hidden" value="'+tableId+'" name="tableid" readonly="true">\
				   <button type="button" class="increase" id="increment_'+order_product_arr[i]+'" onclick="orderincrement('+order_product_arr[i]+','+order_price_arr[i]+','+type+')"></button>\
                        </fieldset>\
                    </div>			\
				</div><br>\
        <div class="row itemwise">\
            <div class="col-md-6">\
                <select class="form-control item_type_discnt" id="item_discount_type_'+order_product_arr[i]+'" name="item_discount_type[]" onchange="itemdiscount('+order_product_arr[i]+')">\
                    <option value="">Discount Type</option>\
                    <option value="1">Overall</option>\
                     <option value="2">Percentage</option>\
                </select>\
            </div>\
            <div class="col-md-6">\
                <input type="text" class="form-control item_discnt" autocomplete="off" id="item_discount_num_'+order_product_arr[i]+'" onchange="itemdiscount('+order_product_arr[i]+')" name="item_discount_num[]">\
            </div>\
        </div></div>'
						);
			}
		}	  
	var nrmltotal = $("#totalbill").html();
	$("#popupamount").html(nrmltotal);  
	$("#popuptaxamt").html(taxamt.toFixed(2));

	var subpercenttage = number_format(((1/100)*parseInt(nrmltotal)),2);
	if(subpercenttage<1){
						var subpercenttage = 1;
					}
					if(subpercenttage>10){
						var  subpercenttage = 10;
					}
	$("#popupsubscriptionamt").html(subpercenttage);				
commontrigger('',1);	
  }

  function orderincrement(id,price,type){
	  

	  var currentval = $("#quantity_"+id).val();
	  var nextval = parseInt(currentval)+1;
	  var priceInc = parseInt(price)*nextval;
	  $("#quantity_"+id).val(nextval);
	  $("#order_quantity_"+id).val(nextval);
	  $("#priceIncrement_"+id).html(priceInc);
	 $("#eachitemprice_"+id).val(priceInc);
var totalbill =parseInt($("#totalbill").html());
	  $("#totalbill").html(totalbill + parseInt(price));
	if(type == '2')
	{
		$("#quantityPopup_"+id).val(nextval);
		$("#order_quantity_popup_"+id).val(nextval);

		$("#priceIncrementPopup_"+id).html(priceInc);			
		$("#popupamount").html(totalbill + parseInt(price));
		var calTaxAmt = calculateTaxAmount();
		$("#popuptaxamt").html(calTaxAmt.toFixed(2));
		
		 var subpercenttage = number_format(((1/100)*parseInt(totalbill + parseInt(price))),2);
	if(subpercenttage<1){
						var subpercenttage = 1;
					}
					if(subpercenttage>10){
						var  subpercenttage = 10;
					}
	$("#popupsubscriptionamt").html(subpercenttage);
	
commontrigger();		
	
	}

	    }
    function orderdecrement(id,price,type){
		
	  var currentval = $("#quantity_"+id).val();
	  
	  var prevval = parseInt(currentval)-1;
	  if(prevval > '0'){
		 var priceInc = parseInt(price)*prevval;
		$("#quantity_"+id).val(prevval);  
	
		$("#order_quantity_"+id).val(prevval);
		$("#priceIncrement_"+id).html(priceInc);		
	 $("#eachitemprice_"+id).val(priceInc);

	  var totalbill =parseInt($("#totalbill").html());
	  $("#totalbill").html(totalbill - parseInt(price));
	  	if(type == '2')
		{
			$("#quantityPopup_"+id).val(prevval);
			$("#order_quantity_popup_"+id).val(prevval);

			$("#priceIncrementPopup_"+id).html(priceInc);			
			$("#popupamount").html(totalbill - parseInt(price));
        var calTaxAmt = calculateTaxAmount();
		$("#popuptaxamt").html(calTaxAmt.toFixed(2));
			var subpercenttage = number_format(((1/100)*parseInt(totalbill - parseInt(price))),2);
						if(subpercenttage<1){
						var subpercenttage = 1;
					}
					if(subpercenttage>10){
						var  subpercenttage = 10;
					}
	$("#popupsubscriptionamt").html(subpercenttage);
	
commontrigger();			
		}
	  }
	  
  }
  function calculateTaxAmount(removeid=''){
     var itid = $('.itemid').map((_,el) => el.value).get();
    var itempriceclass = $('.itempriceclass').map((_,el) => el.value).get();
    var taxamt = 0;
    for(var i=0;i<itid.length;i++){
    if(removeid != itid[i]){
        productId = itid[i];
        var merchantfoodTaxJsn =   '<?= json_encode($MerchantfoodTaxArr); ?>';
			 var prodSectionId = '<?= json_encode($prodSectionId); ?>';
			 var prodFoodSection = JSON.parse(prodSectionId);
			 var merchantfoodTaxArr = JSON.parse(merchantfoodTaxJsn);
			 var foodTaxArr = (merchantfoodTaxArr[prodFoodSection[productId]]);
		     var foodTaxValue = 0;
		     var calFoodTax = 0;
			if(foodTaxArr.length > 0){
			    for(var f =0;f< foodTaxArr.length ; f++){
			        foodTaxValue = foodTaxArr[f]['tax_value'];
			        calFoodTax = calFoodTax + parseFloat($("#priceIncrementPopup_"+productId).html()) * (foodTaxValue/100); 
			    }
                taxamt = taxamt + calFoodTax; 
			}
        }
    }
    return taxamt;
  } 
  
  function deleteprodorder(id,type)
  {
	var deletablePrice = parseInt($("#priceIncrement_"+id).html());
	var totalbill =parseInt($("#totalbill").html());
	var quantity =parseInt($("#quantity_"+id).val());
	
	$("#priceIncrement_"+id).html(deletablePrice/quantity);
	$("#totalbill").html(totalbill - deletablePrice);
    $("#productorder"+id).hide();
	
	  		$("#order_quantity_"+id).val(0);
			$("#order_price_"+id).val(deletablePrice/quantity);

			if(type == '2')
			{
				$("#productorderpopup"+id).hide();
				$("#popupamount").html(totalbill - deletablePrice);
				$("#order_quantity_popup_"+id).val(0);
			
var calTaxAmt = calculateTaxAmount(id);
		$("#popuptaxamt").html(calTaxAmt.toFixed(2));
		
					var subpercenttage = number_format(((1/100)*parseInt(totalbill - deletablePrice)),2);
							if(subpercenttage<1){
						var subpercenttage = 1;
					}
					if(subpercenttage>10){
						var  subpercenttage = 10;
					}
	$("#popupsubscriptionamt").html(subpercenttage);		
	 commontrigger();	
			}
  }
 function revalidate(){
	 var ord_qty = 0;
	  var values = $("input[name='order_quantity_popup[]']")
              .map(function(){
				ord_qty = parseInt(ord_qty) + parseInt($(this).val())  ;
				
				  return $(this).val();
				  }).get();

	  if(ord_qty == 0)
	  {
		//alert("Please Select Atleast One Product");  
		swal(
				'Warning!',
				'Please Select Atleast One Product',
				'warning'
			);
		return false;  
	  }
	  else if($("#customer_name").val() != '' && $("#customer_mobile").val() == '' ){
		alert("Please Fill Both Customer Name and Mobile Number");
			return false;
	  }
	  else if ($("#customer_name").val() != '' && $("#customer_mobile").val() == '' ){
		alert("Please Fill Both Customer Name and Mobile Number");
	  return false;
	  }
	  else if($("#merchant_coupon").val() != ''){

		  var coupons = [];
    <?php foreach($merchantcoupons as $key => $val){ ?>
        coupons.push('<?php echo $val; ?>');
    <?php } ?>
	  if (jQuery.inArray($("#merchant_coupon").val(), coupons)!='-1') {
		  	
		}
        else{
		  alert("Not a Valid Coupon");
		  $("#couponamounthidden").val(0);
		  $("#popupcouponamt").html(0);
		  commontrigger();
		 
		  return false;
		}
	  }
	  else if($("discount_mode").val() != ''){
	      
	  }
	  else{
		  $("#couponamounthidden").val(0);
		  $("#popupcouponamt").html(0);
		  commontrigger();
	  }

 }

 
 
  function validate()
  {
	  var ord_qty = 0;
	  var values = $("input[name='order_quantity[]']")
              .map(function(){
				ord_qty = parseInt(ord_qty) + parseInt($(this).val())  ;
				
				  return $(this).val();
				  }).get();

	  if(ord_qty == 0)
	  {
		swal(
				'Warning!',
				'Please Select Atleast One Product',
				'warning'
			);//alert("Please Select Atleast One Product");  
		
		return false;  
	  }else{
		  
	  $("#myModal").modal('show');
	  }
	  
  }
 



  
  var productorder = (function() {
	var arr = [];
    <?php foreach($previousIds as $key => $val){ ?>
        arr.push('<?php echo $val; ?>');
    <?php } ?>

  return function (id,product,qtyname,price) {
	  if (jQuery.inArray(id, arr)!='-1') {
		$("#order_quantity_"+id).val(0);
		$("#quantity_"+id).val(0);
		orderincrement(id,price);
		$("#productorder"+id).show();
	if($("#order_quantity_"+id).val() == 0){
		$("#quantity_"+id).val(1);
		$("#order_quantity_"+id).val(1);
	var totalbill =parseInt($("#totalbill").html());
	  $("#totalbill").html(totalbill + parseInt(price));
	
	}
		
	  }
	  else{

		  if(qtyname != ''){
			qtyname= ' ('+qtyname  +')';
		  }
		  tableId = '<?php echo $tableid; ?>';
			arr.push(id);
	var type = 1;

	$("#addnewprod").prepend('<div class="col-xs-12 item-shdw p-2 mb-2" id="productorder'+id+'">\
		  <div class="row">\
			<div class="col-md-9">\
	  <h6>'+product+ ' ' +qtyname + '			 </h6>\
			</div>\
			<div class="col-md-3 pl-0">\
			<h6 class="amt"><i class="fa fa-inr"></i> <span id="priceIncrement_'+id+'">'+price+'</span>\
			<input type="hidden" class="eachitemprice" id="eachitemprice_'+id+'" value="'+price+'">\
			</h6>\
			</div>\
		  </div>\
                            <div class="product-qty row">\
                                <div class="option-label col-md-3 pr-1">Qty</div>\
                                <div class="qty qty-changer col-md-7 pr-1">\
                                    <fieldset>\
                                        <button type="button" class="decrease"  onclick="orderdecrement('+id+','+price+')"></button>\
                                        <input type="text" class="qty-input" value="1" name="quantity" id="quantity_'+id+'" data-min="1"  readonly="true">\
                                        <input type="hidden" value="'+id+'" name="product[]" id="product_'+id+'"   readonly="true">\
<input type="hidden" value="1" name="order_quantity[]" id="order_quantity_'+id+'"   readonly="true">\
<input type="hidden" value="'+price+'" name="order_price[]" id="order_price_'+id+'"   readonly="true">\
<input type="hidden" value="'+tableId+'" name="tableid"    readonly="true">\
				   <button type="button" class="increase" id="increment_'+id+'" onclick="orderincrement('+id+','+price+','+type+')"></button>\
                                    </fieldset>\
                                </div>\			<div class="col-md-2 pl-0 pr-1 pt-1"><a><i class="fa fa-trash text-red border-red" onclick="deleteprodorder('+id+','+type+')"></i></a></div>\
                            </div>\
                        </div>'); 


	  }
	  var eachitemprice = $('.eachitemprice').map((_,el) => el.value).get();
    var totalbill =0;
    for(var e=0;e<eachitemprice.length;e++){
        
            totalbill = totalbill + parseFloat(eachitemprice[e]);
    }
	  $("#totalbill").html(totalbill);
	 return arr}

      	   




      
  })();

function number_format(val, decimals){
    //Parse the value as a float value
    val = parseFloat(val);
    //Format the value w/ the specified number
    //of decimal places and return it.
    return val.toFixed(decimals);
}

                function displayResult(item) {
				var couponArr = 	item.value.split("-");			
				var coupontype = (couponArr[1]);
				if(coupontype == 'percent'){
					var popamt = parseInt($("#popupamount").html())
					var discountamt = number_format(((popamt * couponArr[0])/100),2);
					
					$("#popupcouponamt").html(discountamt);
					$("#couponamounthidden").val(discountamt);

				}else{
					$("#popupcouponamt").html(couponArr[0]);
					$("#couponamounthidden").val(couponArr[0]);

				}
		
		 
				commontrigger();
                }
                $('#merchant_coupon').typeahead({
                    ajax: 'applycouponautocomplete',

					displayField: 'code',
                    valueField: 'price',
                    onSelect: displayResult
                });
				

function placeOrder(id,name,current_order_id)
{
	        var form=document.createElement('form');
        form.setAttribute('method','post');
        form.setAttribute('action','placeorder');
        //form.setAttribute('target','_blank');

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "tableid");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", id);
    form.appendChild(hiddenField);

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "tableName");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", name);
    form.appendChild(hiddenField);

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "current_order_id");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", current_order_id);
    form.appendChild(hiddenField);

    document.body.appendChild(form);
    form.submit();   
}
function runningOrder(orderType)
{
if(orderType == 'PARCEL'){
	var url = 'parcels';
	
}else{
		var url = 'currentorders';
}
	    var form=document.createElement('form');
        form.setAttribute('method','post');
        form.setAttribute('action',url);
        //form.setAttribute('target','_blank');

   

    document.body.appendChild(form);
    form.submit();   
}


</script>
<script>
$(document).ready(function(){
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $(".filtersearch").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
  

});

function statusPopUp(popuptype,orderId){
    if(popuptype == '0'){
        swal(
				'Warning!',
				'Please Make The Order',
				'warning'
			);
		return false;
    }else {
      
        	$('#updateorderstatuschange').modal('show');
            $("#curr_Order_id").val(orderId);
    }
    	 
}
  function selectiemproduts(categry){
      $("#myInput").val(categry);
      $("#myInput").trigger("keyup");
  }
$("#closeOrderStatus").click(function(){
var orderorigin = $("#orderorigin").val();
var orderpaymethod = $("#orderpaymethod").val();
var curr_order_id = $("#curr_Order_id").val();
    var request = $.ajax({
  url: "updateorderstatus",
  type: "POST",
  data: {id : curr_order_id,orderorigin:orderorigin,orderpaymethod:orderpaymethod},
}).done(function(msg) {
	  swal(
				'Success!',
				'Order Status Updated Successfully',
				'success'
			);
		var res = JSON.parse(msg);
		window.location.replace("placeorder?tableid="+res['table_id']+"&tableName="+res['table_name']+"&current_order_id=0");
});		
});

function tablesection(sectionId){
         var form=document.createElement('form');
        form.setAttribute('method','post');
        form.setAttribute('action','placeorder');
        //form.setAttribute('target','_blank');

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "sectionId");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", sectionId);
    form.appendChild(hiddenField);

    
    document.body.appendChild(form);
    form.submit();
}

//Caliculate amount by discount
function getTotalAmount(){
    var discount_mode = $('#discount_mode').val();
    var merchant_discount = $('#merchant_discount').val();
    var total_amount = $('#popupamounthidden').val();
    //alert('discount_mode:'+discount_mode+', merchant_discount: '+merchant_discount+', total_amount:'+total_amount);
    if(discount_mode > 0 ){
        if(discount_mode == '1'){
            var discountValue = parseFloat(merchant_discount);
            var calAmount = parseFloat(total_amount) - discountValue;
            
        } else if(discount_mode == '2'){
            var discountValue = (parseFloat(total_amount) * parseFloat(merchant_discount)) / 100 ;
            var calAmount = parseFloat(total_amount) - discountValue;
        }
        
    } else {
       var calAmount =  parseFloat(total_amount);
    }
    //$('#popupcouponamt').text(discountValue);  
    $('#popuptotalamt').text(calAmount);
    $('#popupdiscountnum').val(discountValue);
}
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
</script>