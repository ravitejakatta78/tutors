<?php
use yii\helpers\Html;
use aryelds\sweetalert\SweetAlert;
$actionId = Yii::$app->controller->action->id;
$merchant_det = \app\models\Merchant::findOne(Yii::$app->user->identity->merchant_id);
$merchant_tip_percent = !empty($merchant_det) ? $merchant_det['tip'] : 0;
$paytypearray = array('1'=>'Cash','2'=>'Online','3'=>'UPI','4'=>'Card');

?>

   <link rel="stylesheet" href="<?= Yii::$app->request->baseUrl.'/css/css/newpos.css';?>">
   <script src="<?= Yii::$app->request->baseUrl.'/js/bootstrap-typeahead.js'?>"></script>

   <?php 
foreach (Yii::$app->session->getAllFlashes() as $message) {
    echo SweetAlert::widget([
        'options' => [
            'title' => (!empty($message['title'])) ? Html::encode($message['title']) : 'Title Not Set!',
            'text' => (!empty($message['text'])) ? Html::encode($message['text']) : 'Text Not Set!',
            'type' => (!empty($message['type'])) ? $message['type'] : SweetAlert::TYPE_INFO,
            'timer' => (!empty($message['timer'])) ? $message['timer'] : 4000,
            'showConfirmButton' =>  (!empty($message['showConfirmButton'])) ? $message['showConfirmButton'] : true
        ]
    ]);
}
?>

<section class="col-md-12" style="padding-top:0px !important;">
          <div class="row">
            <div class="col-md-5 pr-0 pl-0">
              <div class="userdiv">
                <div class="row">
                <div class="col-md-3 ">
                  <span class="usricn p-1"><i class="fa fa-user"></i></span>
                  <span id="change-user-name"><?php echo $userDet['name'] ?? 'User Name'; ?></span>
                  <input type="hidden" id="submitorder" value="0">
                </div>
                <div class="col-md-4">
                   <?php 
                   $sectionNameArray = array_column($tableDetails,'sectionname','ID');
                   echo $tableName." (".$sectionNameArray[$tableid].")"; ?></i>
                </div>
                <div class="col-md-5 text-right">
                  <div class="usertyps">
                    <div class="kds">
                      <a style="text-decoration: none;" href="<?= \yii\helpers\Url::to('../merchant/viewkdsone'); ?>"><span style="color:#72a140">KDS</span></a>
                    </div>
                    <div class="kds">
                      <a style="text-decoration: none;" href="<?= \yii\helpers\Url::to('../merchant/orders'); ?>"><span style="color:#72a140">H</span></a>
                    </div>
                    <div class="kds">
                      Draft
                    </div>
                    <div class="kds">
                       <a style="text-decoration: none;" href="<?= \yii\helpers\Url::to('../merchant/pilot'); ?>"><span style="color:#72a140">P</span></a>
                    </div>
                    <div class="kds p-1">
                      <a style="text-decoration: none;" href="<?= \yii\helpers\Url::to('../merchant/tableplaceorder'); ?>"><span style="color:#72a140"><i class="fa fa-home"></i></span></a>
                    </div>
                  </div>
                </div>
              </div>
              </div>
              <div class="clearfix"></div>
              <div class="row">
            <div class="col-md-4 pr-0">
              <div class="rung-ordrs handcursor" >
                <a style="text-decoration: none;color:white" href="<?= \yii\helpers\Url::to('../merchant/currentorders'); ?>"><span>Running Orders</span></a>
                <span style="cursor:pointer;" id="runningorder"><a><i class="fa fa-refresh"></i></a></span>
              </div>
              <div class="card mb-1">
                
                <div class="card-body p-2 rung-order-body">
                  <div class="runngorders">
                  <div>
                  <input type="text" class="form-control mb-2" id="myInput2"  placeholder="Search">
                  </div>
                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                  
                  <?php if(count($runningOrders) > 0) {
                    for($r=0;$r<count($runningOrders);$r++) { ?>
                      <div class="panel panel-default filtersearch2">
                        <div class="panel-heading" role="tab" id="headingOne">
                          <h4 class="panel-title">
                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?= ($r+1) ?>" aria-expanded="true" aria-controls="collapseOne">
                              <i class="more-less fa fa-plus" id="<?= ($r+1); ?>"></i>
                              <?php echo   $runningOrders[$r]['table_name'] ?? $runningOrders[$r]['order_id']; ?> 
                            </a>
                          </h4>
                        </div>
                        <div id="collapse<?= ($r+1) ?>"   class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                          <div class="panel-body" style="cursor:pointer" onclick="changetable('<?= $runningOrders[$r]['tablename']; ?>',null,'<?= $runningOrders[$r]['ID']; ?>')">
                              <p class="ordr-dtls">Pilot: <?= $runningOrders[$r]['pilot_name'] ?? "Not Assigned"; ?></p>
                              <p class="ordr-dtls">Order: <?= $runningOrders[$r]['order_id']; ?></p>
                              <p class="ordr-dtls"><?php 
                              if($runningOrders[$r]['orderprocess'] == '1' && $runningOrders[$r]['preparetime'] > 0 && empty($runningOrders[$r]['preparedate'])){
                                  echo 'Preparing';
                              }
                              else if($runningOrders[$r]['orderprocess'] == '1' && $runningOrders[$r]['preparetime'] > 0 && !empty($runningOrders[$r]['preparedate'])){
                                  echo 'Prepared';
                              }
                              else{
                                echo \app\helpers\Utility::orderstatus_details($runningOrders[$r]['orderprocess']);    
                              }
                               ?></p>
                              <p class="ordr-dtls">Table No: <?= $runningOrders[$r]['table_name']; ?></p>
                              <p class="ordr-dtls">Bill Amount: <?= $runningOrders[$r]['totalamount']; ?></p>
                          </div>
                        </div>
                      </div>
                  <?php } }?>    
                  

                  


                  
                    </div><!-- panel-group -->
                    
                    
                  </div><!-- container -->
                  
                </div>
              </div>
              <div class="card">
                <div class="card-body p-2">
                    <div class="col-md-12 text-center">
                      <!--<div class="modify mb-1">
                        <a>Modify Orders</a>
                      </div>
                      <div class="modify">
                        <a>Order Details</a>
                      </div> -->
                      
                    </div>
                </div>
              </div>
            </div>
            <div class="col-md-8 pl-0">
              <div class="type-details" >
                <div class="type ordertype <?php if($tableName != 'PARCEL' ) {?> active <?php } ?>" data-toggle="modal" data-target="#dinein">
                  <i class="fa fa-picture-o"></i> Dine In
                </div>
                <div class="type ordertype <?php if($tableName == 'PARCEL' ) {?> active <?php } ?>" onclick="changetable('PARCEL','','')">
                  <i class="fa fa-picture-o"></i> Take Away
                </div>
                <div class="type ordertype">
                  <i class="fa fa-picture-o"></i> Delivery
                </div>
                <!-- <div class="type">
                  <i class="fa fa-picture-o"></i> Table
                </div> -->
              </div>
              <div class="row">
              <div class="col-md-6 text-center pr-0">
              <div class="pilot" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModalPilot">Pilot Assign <span class="ml-2"><i class="fa fa-edit"></i></span></div>
              </div>
              <div class="col-md-6 text-center pl-0">
              <div class="custmer" data-toggle="modal" data-target="#myModalCustomer">Customer <span class="ml-2"><i class="fa fa-edit"></i></span></div>
              </div>
              </div>
            <div class="card">
              <div class="card-body p-0">
              <form id="mainorderdiv" class="ordrlist" id="saveorder" method="POST" action="saveneworder" onsubmit="return validatesubmitorder()">
              <table class="table table-striped table-bordered dataTable no-footer mt-0" id="itemtable" style="margin-top: 0px !important">
                <thead>
                  <tr>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <?php if(isset($prevFullSingleOrderDet['ID'])){ ?> 
                    <th><i class="fa fa-times" style="cursor:pointer" onclick="cancelreject(<?= $prevFullSingleOrderDet['ID']; ?>,<?= $prevFullSingleOrderDet['orderprocess']; ?>)"></i></th>
                    <?php }else { ?>
                    <th></th>
                    <?php } ?>
                    
                  </tr>
                </thead>
                <tbody id="appenditems">
                <?php
                $itemidarr = []; 
                $totalorderprice = [];
                $titlewithqty = array_column($productDetails,'titlewithqty','ID');
                
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
                

                for($p=0;$p<count($prevOrderDetails);$p++) {
                  $itemidarr[] = $prevOrderDetails[$p]['product_id'];
                  ?>
                  <tr id="rowid_<?= $prevOrderDetails[$p]['product_id']; ?>" class="itemrowclass">
                    <td><?= $titlewithqty[$prevOrderDetails[$p]['product_id']]; ?>
                    <input type="hidden" name="itemid[]" id="item_id_<?= $prevOrderDetails[$p]['product_id']; ?>" value="<?= $prevOrderDetails[$p]['product_id']; ?>">
                    <input type="hidden" name="priceind[]" id="priceind_<?= $prevOrderDetails[$p]['product_id']; ?>" value="<?= $prevOrderDetails[$p]['price']; ?>">
                    <input type="hidden" name="pricetot[]" id="pricetot_<?= $prevOrderDetails[$p]['product_id']; ?>" value="<?= $prevOrderDetails[$p]['totalprice']; ?>">
                    <input type="hidden" name="qtyitem[]" id="qtyind_<?= $prevOrderDetails[$p]['product_id']; ?>" value="<?= $prevOrderDetails[$p]['count']; ?>">
                    </td>
                    <td><?= $prevOrderDetails[$p]['price']; ?></td>
                    <td>
                      <div class="number-input">
                        <button onclick="this.parentNode.querySelector('input[type=number]').stepDown();plusorminusagain('<?= $prevOrderDetails[$p]['product_id']; ?>','<?= $prevOrderDetails[$p]['price']; ?>')" ></button>
                        <input class="quantity" min="0" name="quantity" id="real_quant_<?= $prevOrderDetails[$p]['product_id']; ?>" value="<?= $prevOrderDetails[$p]['count']; ?>" type="number">
                        <input type="hidden" class="tableid" name="tableid" value="<?= $tableid;?>">
                        <button onclick="this.parentNode.querySelector('input[type=number]').stepUp();plusorminusagain('<?= $prevOrderDetails[$p]['product_id']; ?>','<?= $prevOrderDetails[$p]['price']; ?>')" class="plus"></button>
                      </div>
                    </td>
                    <td><span class="realitemtotalprice" id="realitemtotalprice_<?= $prevOrderDetails[$p]['product_id']; ?>"><?php echo   $prevOrderDetails[$p]['totalprice']; ?></td>
                    <td><i class="fa fa-trash" style="cursor:pointer" onclick="checktoadd('<?= $prevOrderDetails[$p]['product_id']; ?>','<?= $prevOrderDetails[$p]['price']; ?>','','2')"></i></td>
                                      </tr>
                <?php } ?>  
                </tbody>
              </table>
              <input type="hidden" id="ttl_tax" name="ttl_tax" >
              <input type="hidden" id="ttl_discount" name="ttl_discount" value="<?= $prevFullSingleOrderDet['discount_number'] ?? '' ; ?>">
              <input type="hidden" id="ttl_discount_type" name="ttl_discount_type"  >
              <input type="hidden" id="ttl_cpn_amt" name="ttl_cpn_amt" >
              <input type="hidden" id="ttl_sub_amt" name="ttl_sub_amt" >
              <input type="hidden" id="ttl_amt" name="ttl_amt" >
              <input type="hidden" id="ttl_tip" name="ttl_tip" >
              <input type="hidden" id="pilotid" name="pilotid" value="" >
              <input type="hidden" id="merchantcpn" name="merchantcpn" value="" >
              <input type="hidden" id="user_name" name="user_name" value="<?= $userDet['name'] ?? '' ; ?>" >
              <input type="hidden" id="user_mobile" name="user_mobile" value="<?= $userDet['mobile'] ?? '' ; ?>" >

              </form>
              <hr>
              <div class="col-md-12">
              
              <div class="row">
                <div class="col-md-4">
                  <span class="ttl-item">Total Item :</span> 
                  <span class="item-count" id="item-count"><?= isset($prevOrderDetails) ? array_sum(array_column($prevOrderDetails,'count')) : 0 ?></span>
                </div>
                <div class="col-md-4">
                  <span class="ttl-item">Tax : ₹</span> 
                  <span class="ttl-tax" id="ttl_tax">  <?php echo ($ttltax = !empty($prevFullSingleOrderDet['tax']) ? $prevFullSingleOrderDet['tax'] : 0) ?></span>
                </div>
                <div class="col-md-4 handcursor">
                  <span class="ttl-item handcursor" data-toggle="modal" data-target="#myModalDiscount">Discount : ₹ </span> 
                  <span class="ttl-discount"> <?php echo $ttl_discount =  (!empty($prevFullSingleOrderDet['discount_number']) ? $prevFullSingleOrderDet['discount_number'] : 0) ?></span>
                </div>
              </div>
              <div class="row mt-2">
                <div class="col-md-4">
                  <span class="ttl-item">Coupon : ₹</span> 
                  <span class="item-count ttl-cpn-amt" id="item-count"> <?php echo ($ttl_cpn_amt =  !empty($prevFullSingleOrderDet['couponamount']) ? $prevFullSingleOrderDet['couponamount'] : 0) ?></span>
                </div>
                <div class="col-md-4">
                  <span class="ttl-item">Sub Total : ₹</span> 
                  <span class="ttl-sub-amt"> <?php echo $ttl_sub_amt = (!empty($prevFullSingleOrderDet['amount']) ? $prevFullSingleOrderDet['amount'] : 0) ?></span>
                </div>
                <div class="col-md-4">
                  <span class="ttl-tip">Tip : ₹</span> 
                  <input type="text" id="ttl-tip-amt" class="ttl-tip-amt" value="<?php echo ($prevFullSingleOrderDet['tips'] ?? 0); ?>" style="width:50px" onchange="totlrealprice(1)"> 
                </div>
                <!-- <div class="col-md-4">
                  <span class="ttl-item">Discount :</span> 
                  <span >10</span>
                </div> -->
              </div>
            </div>
            <div class="col-md-12">
              <div class="row">
                <div class="ttl-payble" onclick="saveorder()" style="cursor:pointer">
                  Place Order :₹ <span id="ttl_payble"> <?php echo $ttl_amt =  round(@$prevFullSingleOrderDet['totalamount'],2) ?? 0; ?></span>
                </div>
                <div class="buts mt-2 text-center col-md-12">
                <?php if(!empty($prevFullSingleOrderDet) && @$prevFullSingleOrderDet['orderprocess'] == 0){ ?>
                  <button class="btn btn-Info mb-2 fnt" data-toggle="modal" onclick="acceptandserveorder('<?= !empty($prevOrderDetails) ? $prevOrderDetails[0]['order_id'] : "" ; ?>','1')">Accept</button>    
                <?php }else if(!empty($prevFullSingleOrderDet) && @$prevFullSingleOrderDet['orderprocess'] == 1 &&  @$prevFullSingleOrderDet['preparetime'] == 0){ ?>    
                  <button class="btn btn-Info mb-2 fnt" data-toggle="modal" onclick="openprepmodal('<?= !empty($prevOrderDetails) ? $prevOrderDetails[0]['order_id'] : "" ; ?>')">Prepare</button>    
                <?php } else if(!empty($prevFullSingleOrderDet) && @$prevFullSingleOrderDet['orderprocess'] == 1 &&  @$prevFullSingleOrderDet['preparetime'] > 0 &&  empty($prevFullSingleOrderDet['preparedate'])){ ?>    
                  <button class="btn btn-Info mb-2 fnt"  onclick="isFoodPrepared('<?= !empty($prevOrderDetails) ? $prevOrderDetails[0]['order_id'] : "" ; ?>')">Prepared ?</button>    
                <?php }else if(!empty($prevFullSingleOrderDet) && @$prevFullSingleOrderDet['orderprocess'] == 1 &&  @$prevFullSingleOrderDet['preparetime'] > 0 &&  !empty($prevFullSingleOrderDet['preparedate'])){ ?>    
                  <button class="btn btn-Info mb-2 fnt"  onclick="acceptandserveorder('<?= !empty($prevOrderDetails) ? $prevOrderDetails[0]['order_id'] : "" ; ?>','2')">Serve </button>    
                <?php } ?>
                  <button class="btn btn-danger mb-2 fnt" onclick = "statusPopUp('<?= $toShowPopUp; ?>','<?= !empty($prevOrderDetails) ? $prevOrderDetails[0]['order_id'] : "" ; ?>')">Checkout</button>
                  <button class="btn btn-warning mb-2 fnt" onclick="return bckot('<?= $prevFullSingleOrderDet['ID'] ?? ''; ?>');">KOT</button>
                  <button class="btn btn-Info mb-2 fnt">BOT</button>
                  <button class="btn btn-Success mb-2 fnt" onclick="billview('<?= $prevFullSingleOrderDet['ID'] ?? '' ;?>');">Bill</button>
                  <button class="btn btn-primary mb-2 fnt" data-toggle="modal" data-target="#myModalDiscount">Discount</button>
                  <button class="btn btn-primary mb-2 fnt" data-toggle="modal" data-target="#myModalCoupon">Coupon</button>
                  

                </div>
              </div>
          </div>

              </div>
              </div>
            </div>
            </div>
            </div>
            <?php
            $refcount = count($resfc);
            if($refcount == '2'){
              $classtype = 'type1';
            }else{
              $classtype = 'type5';
            }
            ?>
            <div class="col-md-7 pl-0 pr-0">
              <div class="type-details1 pl-1">
                
                <div class="<?= $classtype; ?> btn-food-types active  dropdown dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-picture-o"></i> <span>Category</span>
                  <ul class="dropdown-menu">
  
                  <li><a onclick="getFoodTypeProducts('','1')">All</a></li>
                <?php $food_cat_id_arr = array_keys($result);
  
                  for( $fc = 0; $fc < count($food_cat_id_arr) ; $fc++ ) {
                  ?>
                    <li><a onclick="getFoodTypeProducts('<?= $food_cat_id_arr[$fc]; ?>','1')"><?php echo $result[$food_cat_id_arr[$fc]][0]['food_category'] ; ?></a></li>
                  <?php } ?> 

                  </ul>
                </div>
                <?php 
                for($fs=0;$fs<count($resfc);$fs++) {
                ?>
                
                <div class="<?= $classtype; ?> btn-food-types" id="fc_<?= $resfc[$fs]['ID']; ?>" onclick="getFoodTypeProducts('<?= $resfc[$fs]['ID']; ?>','2')">
                  <i class="fa fa-picture-o"></i> <a ><?= $resfc[$fs]['food_section_name'] ;?></a>
                </div>
                                <?php } ?>
                <div class="<?= $classtype; ?>">
                <input type="text" class="form-control" id="myInput1" placeholder="Search">
                </div>
              </div>
              <div class="card itmlist ">
                <div class="card-body pt-0 pl-2 ml-1">
              <div class="row" id="mainproductsdisplay">
                <?php 
                $imgArr = array_column($productDetails,'image','modified_title');

                foreach($result as $key => $value) {
                    for($i=0 ; $i < count($value); $i++) {
                      if($allProductDetails[$value[$i]['modified_title']][0]['price']){
                    ?>
                <div class="col-lg-2 col-md-2 pl-1 pr-1 filtersearch">
                  <div class="nd_options_section nd_options_position_relative mb-2 min-hgt"  onclick="foodquantitypopup('<?php echo $value[$i]['modified_title']; ?>')">
          
                    <img class="nd_options_section" alt="" src="<?= Yii::$app->request->baseUrl.'/uploads/productimages/'.$imgArr[$value[$i]['modified_title']];?>">
          
                    <!--start filter-->
                    <div class="nd_options_bg_greydark_alpha_gradient_6 nd_options_position_absolute nd_options_left_0 nd_options_height_100_percentage nd_options_width_100_percentage nd_options_padding_30 nd_options_box_sizing_border_box">
                    
          
                      <a class="price-color nd_options_position_absolute nd_options_top_30 nd_options_right_30 nd_options_padding_5_10 nd_options_border_radius_3 nd_options_line_height_14 nd_options_text_transform_uppercase nd_options_color_white nd_options_second_font">₹ <?= $allProductDetails[$value[$i]['modified_title']][0]['price'] ; ?></a>
          
                      <a class="nd_options_color_white nd_options_position_absolute nd_options_left_0 nd_options_bottom_30 nd_options_section nd_options_text_align_center" >
                        <h3 class="nd_options_margin_0_important nd_options_color_white nd_options_second_font">
                        <?= $value[$i]['title'] ; ?>
                        </h3>
                      </a>
          
                    </div>
                    <!--END filter-->
                  </div>
                </div>
                <?php } } } ?>
            
              </div>
                </div>
                
              </div>
            </div>
          </div>
          </section>
          <div id="dinein" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        
        <h4 class="modal-title">Table Selection</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row mb-2">
          <div class="col-md-8">
            <h4>Section Type</h4>
          </div>
          <div class="col-md-4 text-right">
            <input type="text" class="form-control" id="myInput3" placeholder="Search Table">
          </div>
        </div>
      <div class="tab">
  <?php $sectionMainArr = array_column($tableDetails,'sectionname','section_id');
  foreach($sectionMainArr as $section_id => $section_name) {
  ?>    
  <button class="tablinks" onclick="openCity(event, '<?= "sec_".$section_id; ?>')" id="defaultOpen"><?= $section_name;?></button>
  <?php } ?>
</div>

<?php 

$secTableIndexArr = yii\helpers\ArrayHelper::index($tableDetails, null, 'section_id');

foreach($secTableIndexArr as $sec_id => $tableDetails ) {

?>
<div id="<?= "sec_".$sec_id; ?>" class="tabcontent">
<div class="row">
			  
        <?php 
        for($tn=0; $tn < count($tableDetails);$tn++ ) {
        ?>         				
         <div class="col-sm-4 filtersearch3" id="nopadding" style="cursor:pointer">
           <!-- normal -->
           <div class="ih-item circle effect10 top_to_bottom"  >
             <a onclick="changetable('<?= $tableDetails[$tn]['ID']?>','<?= $tableDetails[$tn]['name']?>','<?= $tableDetails[$tn]['current_order_id']?>')">
               <div class="img" width="20px" height="20px">
                 <?php 
                     $table_status = ($tableDetails[$i]['table_status'] ?? 5);
                     if($table_status == '1' || $table_status == '2') { ?>
                     <img  src="<?= Yii::$app->request->baseUrl.'/img/table/servingtable.png' ?>" alt="img">
                     <?php } else {
                     ?>
                       <img  src="<?= Yii::$app->request->baseUrl.'/img/table/emptytable.png' ?>" alt="img">
                       
                 <?php }?>
               </div>
               <p>
                 <?php $tableStatus = $tableDetails[$tn]['table_status'] ?? 5 ;
                   if($tableStatus == '5' || $tableStatus == '' ){ ?>
                   <div class="info Available">
                     <h3><?= $tableDetails[$tn]['name']?></h3>
                     <span class="badge badge-success">Available</span>
                   </div>
                   <?php }
                   else if($tableStatus == '2'){ ?>
                   <div class="info Serving"> 
                     <h3><?= $tableDetails[$tn]['name']?></h3>
                     <span class="badge badge-warning">Serving</span>
                   </div>
                   <?php }else { ?>
                   <div class="info occupied"> 
                     <h3><?= $tableDetails[$tn]['name']?></h3>
                     <span class="badge badge-danger">Occupied</span>
                   </div>
                 <?php } ?>
               </p>
            </a>
           </div>
           <!-- end normal -->
           </div>
        <?php } ?>   
       </div>
</div>
<?php } ?>

      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
     <!-- Modal -->
     <div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" ><span id="modal_title_header"></span></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        
      </div>
      <div class="modal-body">
        <div class="col-md-12 pop-head">
        <div class="row">
          <div class="col-md-2">
            S.No
          </div>
          <div class="col-md-2">
            Units
          </div>
          <div class="col-md-2">
            Quantity
          </div>
          <div class="col-md-2">
            Item Price
          </div>
          <div class="col-md-2">
            Price
          </div>
        </div>
        </div>
        <div  id="modal_body">
          
        </div>
        
        <div class="col-md-12 pop-head">
          <div class="row">
            <div class="col-md-6">
              Total
            </div>
            <div class="col-md-6 text-right">
              <i class="fa fa-inr"></i> <span id="product_total">0</span>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
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
			     <?php for($p=0;$p<count($merchant_pay_types);$p++) {?>
			          <option value="<?= $merchant_pay_types[$p]; ?>"><?= $paytypearray[$merchant_pay_types[$p]]; ?></option>
			     <?php } ?>     
			     </select>     
	   </div>
	   </div>
	   
	    <div class="form-group row">
	   <label class="control-label col-md-4">Company</label>
	   <div class="col-md-8">
			      <select id="orderorigin">
			          <option value="Self">Self</option>
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


<!-- Modal -->
<div id="myModalPilot" class="modal fade" role="dialog">
<div class="modal-dialog">
  <!-- Modal content-->
  <div class="modal-content">
  <div class="modal-header">
    <h4 class="modal-title" >Pilot Assign</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
  </div>
      <div class="modal-body">
      <select id="pilotselection">
        <option value="">Select Pilot</option>
        <?php $pilotArr = array_column($resServiceBoy,'name','ID');
        $serviceboy_id = !empty($prevFullSingleOrderDet['serviceboy_id']) ? $prevFullSingleOrderDet['serviceboy_id'] : ''; 
        foreach($pilotArr as $id => $name){ ?>
        <option value="<?= $id ;?>" <?php if($serviceboy_id == $id) { ?>selected <?php } ?>><?= $name; ?></option>
        <?php }
        ?>
      </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Save</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="myModalCustomer" class="modal fade" role="dialog">
<div class="modal-dialog">
  <!-- Modal content-->
  <div class="modal-content">
  <div class="modal-header">
    <h4 class="modal-title" >Customer Details</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
  </div>

  <div class="modal-body" >

					<div class="form-group row">
					<label class="control-label col-md-3">User Name</label>
					<div class="col-md-9">
          <input type="text" id="username" autocomplete="off" class="form-control" value="<?= $userDet['name'] ?? '' ; ?>">
					</div>
					</div>

	   
					<div class="form-group row">
					<label class="control-label col-md-3">User Mobile</label>
					<div class="col-md-9">
          <input type="text" id="usermobile" autocomplete="off" class="form-control" value="<?= $userDet['mobile'] ?? '' ; ?>">
					</div>
					</div>
			  
		</div>


      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" >Save</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal -->
<div id="myModalDiscount" class="modal fade" role="dialog">
<div class="modal-dialog">
  <!-- Modal content-->
  <div class="modal-content">
  <div class="modal-header">
    <h4 class="modal-title" >Discount Details</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
  </div>

  <div class="modal-body" >
			<div class="row">	
				<div class="col-md-12">	
					<div class="form-group row">
					<label class="control-label col-md-4">Discount Type</label>
					<div class="col-md-8">
            <select id="discounttype" name="discounttype" >
          <option value="">Discount Type</option>
                    <option value="1" <?php if(@$prevFullSingleOrderDet['discount_type'] == '1') { echo 'selected';}?>>Overall</option>
                     <option value="2" <?php if(@$prevFullSingleOrderDet['discount_type'] == '2') { echo 'selected'; } ?>>Percentage</option>
        </select>
        <span id="discounttype_err" style="color:red;display:none" >Please Select Discount Type</span>
                    </div>
					</div>
	   
					<div class="form-group row">
					<label class="control-label col-md-4">Discount Value</label>
					<div class="col-md-8">
          <input type="text" id="discountvalue" value="<?= $prevFullSingleOrderDet['discount_number'] ?? ''; ?>"  autocomplete="off" >
					</div>
					</div>
			   </div>
	   		</div>
		</div>


      <div class="modal-footer">
        <button type="button" class="btn btn-success" onclick="discountchange()" >Apply</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal -->
<div id="myModalCoupon" class="modal fade" role="dialog">
<div class="modal-dialog">
  <!-- Modal content-->
  <div class="modal-content">
  <div class="modal-header">
    <h4 class="modal-title" >Coupon Details</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
  </div>

  <div class="modal-body" >
			<div class="row">	
				<div class="col-md-12">	
				
	   
					<div class="form-group row">
					<label class="control-label col-md-4">Coupon</label>
					<div class="col-md-8">
          <input type="text" id="merchant_coupon"  placeholder="Apply Coupon" class="form-control" value="<?= $prevFullSingleOrderDet['coupon'] ?? ''; ?>" <?php if(!empty($prevFullSingleOrderDet['coupon'])) { echo 'readonly'; } ?>>
					</div>
					</div>
			   </div>
	   		</div>
		</div>


      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="merchant_coupon_save" data-dismiss="modal">Save</button>
      </div>
    </div>
  </div>
</div>


<!--Set Prepartion  Modal -->
<div id="myModalPrep" class="modal fade" role="dialog">
<div class="modal-dialog">
  <!-- Modal content-->
  <div class="modal-content">
  <div class="modal-header">
    <h4 class="modal-title" >Preparation Time</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
  </div>

  <div class="modal-body" >
			<div class="row">	
				<div class="col-md-12">	
				
	   
					<div class="form-group row">
					<label class="control-label col-md-4">Preparation Time( in minutes)</label>
					<div class="col-md-8">
                        <input type="text" id="preparationtime"  placeholder="Preparation Time" class="form-control" >
                        <input type="hidden" id="prepareorderid" >
					</div>
					</div>
			   </div>
	   		</div>
		</div>


      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="savepreptime">Save</button>
      </div>
    </div>
  </div>
</div>


<!--Cancel  Modal -->
<div id="myModalCanc" class="modal fade" role="dialog">
<div class="modal-dialog">
  <!-- Modal content-->
  <div class="modal-content">
  <div class="modal-header">
    <h4 class="modal-title" >Cancel</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
  </div>

  <div class="modal-body" >
			<div class="row">	
				<div class="col-md-12">	
				
	   
					<div class="form-group row">
					<label class="control-label cr-label col-md-4">Cancel Reason</label>
					<div class="col-md-8">
                        <textarea  id="crid"   class="form-control" ></textarea>
                        <input type="hidden" id="crorderid" >
                        <input type="hidden" id="crorderstatus" >
					</div>
					</div>
			   </div>
	   		</div>
		</div>


      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="savecr">Save</button>
      </div>
    </div>
  </div>
</div>

<script>
  function openCity(evt, cityName) {
      
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display =  "block"; ;
  
  evt.currentTarget.className += " active";

      
  }
(function(f){jQuery.fn.extend({slimScroll:function(h){var a=f.extend({width:"auto",height:"250px",size:"7px",color:"#000",position:"right",distance:"1px",start:"top",opacity:0.4,alwaysVisible:!1,disableFadeOut:!1,railVisible:!1,railColor:"#333",railOpacity:0.2,railDraggable:!0,railClass:"slimScrollRail",barClass:"slimScrollBar",wrapperClass:"slimScrollDiv",allowPageScroll:!1,wheelStep:20,touchScrollStep:200,borderRadius:"7px",railBorderRadius:"7px"},h);this.each(function(){function r(d){if(s){d=d||
window.event;var c=0;d.wheelDelta&&(c=-d.wheelDelta/120);d.detail&&(c=d.detail/3);f(d.target||d.srcTarget||d.srcElement).closest("."+a.wrapperClass).is(b.parent())&&m(c,!0);d.preventDefault&&!k&&d.preventDefault();k||(d.returnValue=!1)}}function m(d,f,h){k=!1;var e=d,g=b.outerHeight()-c.outerHeight();f&&(e=parseInt(c.css("top"))+d*parseInt(a.wheelStep)/100*c.outerHeight(),e=Math.min(Math.max(e,0),g),e=0<d?Math.ceil(e):Math.floor(e),c.css({top:e+"px"}));l=parseInt(c.css("top"))/(b.outerHeight()-c.outerHeight());
e=l*(b[0].scrollHeight-b.outerHeight());h&&(e=d,d=e/b[0].scrollHeight*b.outerHeight(),d=Math.min(Math.max(d,0),g),c.css({top:d+"px"}));b.scrollTop(e);b.trigger("slimscrolling",~~e);v();p()}function C(){window.addEventListener?(this.addEventListener("DOMMouseScroll",r,!1),this.addEventListener("mousewheel",r,!1),this.addEventListener("MozMousePixelScroll",r,!1)):document.attachEvent("onmousewheel",r)}function w(){u=Math.max(b.outerHeight()/b[0].scrollHeight*b.outerHeight(),D);c.css({height:u+"px"});
var a=u==b.outerHeight()?"none":"block";c.css({display:a})}function v(){w();clearTimeout(A);l==~~l?(k=a.allowPageScroll,B!=l&&b.trigger("slimscroll",0==~~l?"top":"bottom")):k=!1;B=l;u>=b.outerHeight()?k=!0:(c.stop(!0,!0).fadeIn("fast"),a.railVisible&&g.stop(!0,!0).fadeIn("fast"))}function p(){a.alwaysVisible||(A=setTimeout(function(){a.disableFadeOut&&s||(x||y)||(c.fadeOut("slow"),g.fadeOut("slow"))},1E3))}var s,x,y,A,z,u,l,B,D=30,k=!1,b=f(this);if(b.parent().hasClass(a.wrapperClass)){var n=b.scrollTop(),
c=b.parent().find("."+a.barClass),g=b.parent().find("."+a.railClass);w();if(f.isPlainObject(h)){if("height"in h&&"auto"==h.height){b.parent().css("height","auto");b.css("height","auto");var q=b.parent().parent().height();b.parent().css("height",q);b.css("height",q)}if("scrollTo"in h)n=parseInt(a.scrollTo);else if("scrollBy"in h)n+=parseInt(a.scrollBy);else if("destroy"in h){c.remove();g.remove();b.unwrap();return}m(n,!1,!0)}}else{a.height="auto"==a.height?b.parent().height():a.height;n=f("<div></div>").addClass(a.wrapperClass).css({position:"relative",
overflow:"hidden",width:a.width,height:a.height});b.css({overflow:"hidden",width:a.width,height:a.height});var g=f("<div></div>").addClass(a.railClass).css({width:a.size,height:"100%",position:"absolute",top:0,display:a.alwaysVisible&&a.railVisible?"block":"none","border-radius":a.railBorderRadius,background:a.railColor,opacity:a.railOpacity,zIndex:90}),c=f("<div></div>").addClass(a.barClass).css({background:a.color,width:a.size,position:"absolute",top:0,opacity:a.opacity,display:a.alwaysVisible?
"block":"none","border-radius":a.borderRadius,BorderRadius:a.borderRadius,MozBorderRadius:a.borderRadius,WebkitBorderRadius:a.borderRadius,zIndex:99}),q="right"==a.position?{right:a.distance}:{left:a.distance};g.css(q);c.css(q);b.wrap(n);b.parent().append(c);b.parent().append(g);a.railDraggable&&c.bind("mousedown",function(a){var b=f(document);y=!0;t=parseFloat(c.css("top"));pageY=a.pageY;b.bind("mousemove.slimscroll",function(a){currTop=t+a.pageY-pageY;c.css("top",currTop);m(0,c.position().top,!1)});
b.bind("mouseup.slimscroll",function(a){y=!1;p();b.unbind(".slimscroll")});return!1}).bind("selectstart.slimscroll",function(a){a.stopPropagation();a.preventDefault();return!1});g.hover(function(){v()},function(){p()});c.hover(function(){x=!0},function(){x=!1});b.hover(function(){s=!0;v();p()},function(){s=!1;p()});b.bind("touchstart",function(a,b){a.originalEvent.touches.length&&(z=a.originalEvent.touches[0].pageY)});b.bind("touchmove",function(b){k||b.originalEvent.preventDefault();b.originalEvent.touches.length&&
(m((z-b.originalEvent.touches[0].pageY)/a.touchScrollStep,!0),z=b.originalEvent.touches[0].pageY)});w();"bottom"===a.start?(c.css({top:b.outerHeight()-c.outerHeight()}),m(0,!0)):"top"!==a.start&&(m(f(a.start).position().top,null,!0),a.alwaysVisible||c.hide());C()}});return this}});jQuery.fn.extend({slimscroll:jQuery.fn.slimScroll})})(jQuery);

// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();
$(document).ready(function() {
    $('.ordrlist').slimScroll({
        height: '380px'
    });
    $('.runngorders').slimScroll({
        height: '400px'
    });
    $('.itmlist').slimScroll({
        height: '700px'
    });

    $("#myInput1").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $(".filtersearch").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    });
  });

  $("#myInput2").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $(".filtersearch2").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    });
  });

  $("#myInput3").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $(".filtersearch3").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    });
  });

  $('.btn-food-types').on("click", function() {
  $(this).addClass('active') //add class to clicked button
         .siblings(".active") //look for any other button at the same level
         .removeClass('active'); //remove the class from the found button
});

$('.ordertype').on("click", function() {
  $(this).addClass('active') //add class to clicked button
         .siblings(".active") //look for any other button at the same level
         .removeClass('active'); //remove the class from the found button
});

});

$("#pilotselection").change(function(){
  $("#pilotid").val(this.value);
});

$("#username").change(function(){
    if(this.value == ''){
        $("#change-user-name").html("User Name");
    }
    else{
        $("#change-user-name").html(this.value);
    }
  $("#user_name").val(this.value);
});

$("#usermobile").change(function(){
  $("#user_mobile").val(this.value);
});

function discountchange(){
  var discounttype = $("#discounttype").val();
  var discountvalue = $("#discountvalue").val();

  if(!discounttype){
      $("#discounttype_err").show();
      return false;
  }else{
  $("#ttl_discount_type").val(discounttype);
  $("#ttl_discount").val(discountvalue);
  totlrealprice(1);    
  $("#discounttype_err").hide();
     $('#myModalDiscount').modal('toggle');
  }
  


}

itemaddarr = [];
itemaddphparr = '<?= json_encode($itemidarr) ; ?>';
if(itemaddphparr.length > 1){
  itemaddarr =  JSON.parse(itemaddphparr);

}
console.log(itemaddarr);

function getFoodTypeProducts(foodtype,foodsecvalue)
{



    var result = '<?= json_encode($result)?>';
    var mainArr = JSON.parse(result);

    var res = '<?= json_encode($res)?>';
    var mainArrRes = JSON.parse(res);

    var fsresult = '<?= json_encode($fsresult)?>';
    var mainfsresult = JSON.parse(fsresult);

    var imgArrjs = '<?= json_encode($imgArr)?>';
    var imgArr = JSON.parse(imgArrjs);
  
    
    var title_products_quantity = '<?= json_encode($allProductDetails); ?>';
    var title_products_quantity_arr = JSON.parse(title_products_quantity);

    var baseUrl = '<?= Yii::$app->request->baseUrl."/uploads/productimages/" ?>';

    $("#mainproductsdisplay").html('');

  if(foodsecvalue == '1'){
    if(foodtype == ''){
        var productArr = mainArrRes;
    }else{
        var productArr = mainArr[foodtype];
    }
  }else{

    var productArr = mainfsresult[foodtype];

  }
    for(var p=0; p < productArr.length; p++){
      if(title_products_quantity_arr[productArr[p]['modified_title']][0]['price'] > 0){
    $("#mainproductsdisplay").append('<div class="col-lg-2 col-md-2 pl-1 pr-1 filtersearch">\
                  <div class="nd_options_section nd_options_position_relative mb-2 min-hgt"  onclick="foodquantitypopup(\''+productArr[p]['modified_title']+'\')">\
                    <img class="nd_options_section" alt="" src="'+baseUrl+imgArr[productArr[p]['modified_title']]+'">\
                    <div class="nd_options_bg_greydark_alpha_gradient_6 nd_options_position_absolute nd_options_left_0 nd_options_height_100_percentage nd_options_width_100_percentage nd_options_padding_30 nd_options_box_sizing_border_box">\
                     <a class="price-color nd_options_position_absolute nd_options_top_30 nd_options_right_30 nd_options_padding_5_10 nd_options_border_radius_3 nd_options_line_height_14 nd_options_text_transform_uppercase nd_options_color_white nd_options_second_font">₹ '+title_products_quantity_arr[productArr[p]['modified_title']][0]['price']+'</a>\
                     <a class="nd_options_color_white nd_options_position_absolute nd_options_left_0 nd_options_bottom_30 nd_options_section nd_options_text_align_center" >\
                        <h3 class="nd_options_margin_0_important nd_options_color_white nd_options_second_font">'+productArr[p]['title']+'\
                        </h3>\
                      </a>\
                    </div>\
                  </div>\
                </div>');
              }
            }
}

var foodquantitypopup = (function() {
  return function (total_modified_name)
  {
    var title_name = total_modified_name.replaceAll("_", " ");  
    $("#modal_title_header").html(title_name);
    var title_products_quantity = '<?= json_encode($allProductDetails); ?>';
    var title_products_quantity_arr = JSON.parse(title_products_quantity);
        var fcqarr = '<?= json_encode($fcqarr); ?>';
    var fcq_arr = JSON.parse(fcqarr);
    var quantity_arr = title_products_quantity_arr[total_modified_name];
    $("#modal_body").html('');
        for(var i=0; i < (quantity_arr.length) ; i++) {
          if(quantity_arr[i]['price'] > 0) {  
          if(quantity_arr[i]['food_type_name'] == null) {  
           var food_type_name = title_name;
          }else{
            var food_type_name = quantity_arr[i]['food_type_name'];
          }

            $("#modal_body").append('<div class="row"><div class="col-md-2">'+(i+1)+'\
                  </div>\
                  <div class="col-md-2">'+fcq_arr[quantity_arr[i]['ID']]+'\
                  </div>\
                  <div class="col-md-2">\
                    <div class="number-input">\
                      <button onclick="this.parentNode.querySelector(\'input[type=number]\').stepDown();plusorminus(\''+quantity_arr[i]['ID']+'\',\''+quantity_arr[i]['price']+'\',\'\',\''+quantity_arr[i]['food_type_name']+'\')" ></button>\
                      <input class="quantity" min="0" name="quantity" value="0" id="quant_id_'+quantity_arr[i]['ID']+'" type="number">\
                      <button onclick="this.parentNode.querySelector(\'input[type=number]\').stepUp();plusorminus(\''+quantity_arr[i]['ID']+'\',\''+quantity_arr[i]['price']+'\',\'\',\''+quantity_arr[i]['food_type_name']+'\')"  class="plus"></button>\
                    </div>\
                  </div>\
                  <div class="col-md-2">\
                    <div class="pop-price">\
                      <i class="fa fa-inr"></i> '+quantity_arr[i]['price']+'\
                    </div>\
                  </div>\
                  <div class="col-md-2 text-right">\
                    <div class="pop-price">\
                      <i class="fa fa-inr"></i> <span class="added_price" id="added_price_'+quantity_arr[i]['ID']+'">0\
                    <input type="hidden" id="single_qty_'+quantity_arr[i]['ID']+'" value = "0">\
                    </div>\
                  </div>\
                  </div>');
                  if (jQuery.inArray(quantity_arr[i]['ID'], itemaddarr)!='-1') {
                    $("#quant_id_"+quantity_arr[i]['ID']).val($("#qtyind_"+quantity_arr[i]['ID']).val());
                    $("#added_price_"+quantity_arr[i]['ID']).html($("#pricetot_"+quantity_arr[i]['ID']).val());
                    totalPrice();
              }
           
        }
        }
        if(quantity_arr.length >= 2){
          $("#myModal").modal('show');
        }
        else{
          if($("#quant_id_"+quantity_arr[0]['ID']).val() == 0){
            $("#quant_id_"+quantity_arr[0]['ID']).val(1); 
            checktoadd(quantity_arr[0]['ID'],quantity_arr[0]['price']
          ,quantity_arr[0]['food_type_name'] || '',3);
          }
          else{
            plusorminus(quantity_arr[0]['ID'],quantity_arr[0]['price'],'inc',quantity_arr[0]['food_type_name']);
            plusorminusagain(quantity_arr[0]['ID'],quantity_arr[0]['price'],'inc');
          }

        }
        
    
    

  }
  })();
          
  


var checktoadd = (function() {
  var arr = [];
  

  return function (id,price,foodtypename,decision){
    
    var quant_id = $("#quant_id_"+id).val();
    
    var quant_price = parseFloat(price) * parseInt(quant_id);
    $("#added_price_"+id).html(quant_price);
    if(quant_id == 0){
      $("#added_price_"+id).html('0');
    }
    var tableid = '<?php echo $tableid; ?>';
    totalPrice();
    
     if(quant_id == 0 || decision == '2'){
      $("#rowid_"+id).remove();
      var index = itemaddarr.indexOf(id);
      if (index > -1) {
        itemaddarr.splice(index, 1);
    }      var item_count =parseInt($("#item-count").html())  - 1
     }else{
       
      if(!itemaddarr.includes(id)){  
      $("#appenditems").append('<tr id="rowid_'+id+'" class="itemrowclass">\
                    <td>'+  $("#modal_title_header").html()+' '+ foodtypename  +'\
                    <input type="hidden" name="itemid[]" class="itemid" id="item_id_'+id+'" value="'+id+'">\
                    <input type="hidden" name="priceind[]" id="priceind_'+id+'" value="'+price+'">\
                    <input type="hidden" name="pricetot[]" id="pricetot_'+id+'" value="'+quant_price+'">\
                    <input type="hidden" name="qtyitem[]" id="qtyind_'+id+'" value="'+quant_id+'">\
      </td>\
                    <td>'+price+'</td>\
                    <td>\
                      <div class="number-input">\
                        <button onclick="this.parentNode.querySelector(\'input[type=number]\').stepDown();plusorminusagain(\''+id+'\',\''+price+'\')" ></button>\
                        <input class="quantity" min="0" name="quantity" id="real_quant_'+id+'" value="'+quant_id+'" type="number">\
                        <input type="hidden" class="tableid" name="tableid" value="'+tableid+'">\
                        <button onclick="this.parentNode.querySelector(\'input[type=number]\').stepUp();plusorminusagain(\''+id+'\',\''+price+'\')" class="plus"></button>\
                      </div>\
                    </td>\
                    <td><span class="realitemtotalprice" id="realitemtotalprice_'+id+'">'+quant_price+'</td>\
                    <td><i class="fa fa-trash" style="cursor:pointer" onclick="checktoadd(\''+id+'\',\''+price+'\',\''+foodtypename+'\',\'2\')"></i></td>\
                                      </tr>\
                  ');
                  var item_count =       parseInt($("#item-count").html()) + 1;
                  itemaddarr.push(id);
      }
     }
     $('#item-count').html(item_count);
     totlrealprice(1);   
  }
})();

function totalPrice(){
  var price = $('.added_price');
  var prices = new Array();
  var totalprice = 0;
  for(var i=0;i<price.length;i++){
    prices.push(price[i].innerHTML);
    totalprice = parseFloat(totalprice) + parseFloat(price[i].innerHTML); 
  }
  $("#product_total").html(totalprice);
}
function totlrealprice(dynamictip = '')
{
  var realprices = $('.realitemtotalprice');
  var itid = $('.itemid');
  var totalprice = 0;
  //var itid = $('.itemid').map((_,el) => el.value).get();
  var merchantfoodTaxJsn =   '<?= json_encode($MerchantfoodTaxArr); ?>';
  var merchantfoodTaxArr = JSON.parse(merchantfoodTaxJsn);
  var prodvsfcidJsn =   '<?= json_encode($prodvsfcidarr); ?>';
  var prodvsfcidarr = JSON.parse(prodvsfcidJsn);
  var calFoodTax   = 0;
  var taxamt = 0;
  var cpnamt = 0;
  var foodTaxArr = [];
  var priceTotArr = [];
  

  
    for(var i=0;i<realprices.length;i++){
      priceTotArr.push(realprices[i].innerHTML); 
      totalprice = parseFloat(totalprice) + parseFloat(realprices[i].innerHTML); 
    }
    $('#itemtable tr').each(function() {
      if(this.id != ''){
        var prodriwid = 	this.id.split("_");	

        foodTaxArr = (merchantfoodTaxArr[prodvsfcidarr[prodriwid[1]]]) || [];

        if(foodTaxArr.length > 0){
              for(var f =0;f< foodTaxArr.length ; f++){
                  foodTaxValue = parseFloat(foodTaxArr[f]['tax_value']);
                  calFoodTax = calFoodTax + (parseFloat($("#realitemtotalprice_"+prodriwid[1]).html()) * parseFloat(foodTaxValue/100));
                 }
                    taxamt = parseFloat(( calFoodTax).toFixed(2)); 
        }
      }
    });
    $(".ttl-sub-amt").html(totalprice);
    $(".ttl-tax").html(taxamt); 
    cpnamt = parseFloat($(".ttl-cpn-amt").html()); 
    var ttl_discount = $("#ttl_discount").val();
    var ttl_discount_type = $("#ttl_discount_type").val();
    var calDiscountAmount = 0;
    var actualTotalAmt = totalprice + taxamt;
    if(ttl_discount_type && ttl_discount > 0){
      if(ttl_discount_type == '1' ){
          var calDiscountAmount = parseFloat(ttl_discount).toFixed(2);
          if(calDiscountAmount >= actualTotalAmt){
              $("#discountvalue").val('');
              $("#discounttype").val('');
              
                    swal(
				'Warning!',
				'Discount Can not be greater than the bill amount',  
				'warning'
			);
              return false;
          }
	        			$(".ttl-discount").html(calDiscountAmount);
			
        } else if(ttl_discount_type == '2'){
            var calDiscountAmount = (parseFloat(actualTotalAmt) * parseFloat(ttl_discount)) / 100 ;
            if(calDiscountAmount >= actualTotalAmt){
              $("#discountvalue").val('');
              $("#discounttype").val('');
              
              swal(
				'Warning!',
				'Discount Can not be greater than the bill amount',  
				'warning'
			);
              return false;
          }
            $(".ttl-discount").html(parseFloat(calDiscountAmount).toFixed(2));

        }
    }
    else if(ttl_discount >= 0){
        if(!isNaN(ttl_discount)){
            ttl_discount = 0;
        }
			$(".ttl-discount").html(parseFloat(ttl_discount).toFixed(2));
            var calDiscountAmount = parseFloat(ttl_discount);
    }
    totalprice = parseFloat(totalprice + taxamt - cpnamt - calDiscountAmount).toFixed(2); 


    var merchant_tip_percent = '<?= $merchant_tip_percent; ?>';
    merchant_tip_percent = parseFloat(merchant_tip_percent);
    if(dynamictip != '1'){
    var cal_tip = parseFloat(((totalprice * merchant_tip_percent)/100).toFixed(2));    
    }
    else{
    var cal_tip = $("#ttl-tip-amt").val()   ; 
    }
    
    $("#ttl-tip-amt").val(cal_tip);
    totalprice = (parseFloat(totalprice) + parseFloat(cal_tip)).toFixed(2);
    $("#ttl_payble").html(totalprice);
   
   }

function plusorminus(id,price,typeinc = '',foodtypename)
{
  var quant_id = $("#quant_id_"+id).val();
  if(quant_id < 0){
    $("#quant_id_"+id).val(0);
    swal(
				'Warning!',
				'Please Add Atleast one quantity',  
				'warning'
			);
		return false;
  }
  if(quant_id == 1 || quant_id == 0){
    checktoadd(id,price,foodtypename);
  }

  if(typeinc == 'inc'){
    $("#quant_id_"+id).val(parseInt(quant_id) + 1);
    var quant_id = parseInt(quant_id) + 1;
  }

  var itemPrice = parseInt(quant_id) * parseFloat(price);
  $("#added_price_"+id).html(itemPrice);
  $("#real_quant_"+id).val(quant_id);
  $("#qtyind_"+id).val(quant_id);
  $("#realitemtotalprice_"+id).html(itemPrice);
  totalPrice();
  totlrealprice(1);

}

function plusorminusagain(id,price)
{
  var quant_id = $("#real_quant_"+id).val();
  if(quant_id < 1){
    $("#real_quant_"+id).val(1);
    swal(
				'Warning!',
				'Please Add Atleast one quantity',  
				'warning'
			);
		return false;
  }
  var itemPrice = parseInt(quant_id) * parseFloat(price);
  $("#realitemtotalprice_"+id).html(itemPrice);
  totalPrice();
  totlrealprice(1);
         
  $("#qtyind_"+id).val(quant_id);
  $("#pricetot_"+id).val(itemPrice);
  $("#priceind_"+id).val(price);


}

function saveorder()
{

              $("#ttl_tax").val($('.ttl-tax').html());
              $("#ttl_discount").val($('.ttl-discount').html());
              $("#ttl_cpn_amt").val($('.ttl-cpn-amt').html());
              $("#ttl_sub_amt").val($('.ttl-sub-amt').html());
              $("#ttl_tip").val($('#ttl-tip-amt').val());
              $("#ttl_amt").val($('#ttl_payble').html());
              
              var ttl_sub_amt = $("#ttl_sub_amt").val();
              if(isNaN(ttl_sub_amt)){
                   swal(
            				'Warning!',
            				'Please Chaeck The Order !!',
            				'warning'
            			);
                      return false;   
              }
  
  $("#submitorder").val(1);
  $("#mainorderdiv").submit();
  
}

function validatesubmitorder()
{
  var checksubmit = $("#submitorder").val();
  if(checksubmit != 1){
    return false;
  }
  else{
      var total_bill_amt = parseFloat($("#ttl_payble").html());
      
      if(total_bill_amt != '' && total_bill_amt != 0){
          return true;        
      }
      else{
          swal(
				'Warning!',
				'Please Select An Item To Place An Order',
				'warning'
			);
          return false;
      }
      
  }
}
$( document ).ready(function() {

$('#merchant_coupon').typeahead({
                    ajax: 'applycouponautocomplete',

					displayField: 'code',
                    valueField: 'price',
                    onSelect: displayResult,
                });
              });
              

function displayResult(item) {
				var couponArr = 	item.value.split("-");			
        $("#merchantcpn").val($('#merchant_coupon').val());

				var coupontype = (couponArr[1]);
				if(coupontype == 'percent'){
				    
					var popamt = parseInt($("#ttl_payble").html());
					var discountamt = ((popamt * couponArr[0])/100).toFixed(2);
					
					$(".ttl-cpn-amt").html(discountamt);
				//	$("#couponamounthidden").val(discountamt);

				}else{
					$(".ttl-cpn-amt").html(couponArr[0]);
					//$("#couponamounthidden").val(couponArr[0]);

				}
		
        totlrealprice(1);
			}
			
			$('#merchant_coupon_save').click(function(){
                 var request = $.ajax({
                  url: "checkcouponcode",
                  type: "POST",
                  data: {id : $('#merchant_coupon').val()},
                }).done(function(msg) {
                    if(msg == 2)
                    {
                         swal(
				'Warning!',
				'Invalid Coupon Code',  
				'warning'
			);
			$('#merchant_coupon').val('');
                    }

                });
			});
function changetable(id,name,current_order_id)
{

  var form=document.createElement('form');
        form.setAttribute('method','post');
        form.setAttribute('action','newpos');
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

$("#closeOrderStatus").click(function(){
var orderorigin = $("#orderorigin").val();
var orderpaymethod = $("#orderpaymethod").val();
var curr_order_id = $("#curr_Order_id").val();
var orderpaymethodname = $("#orderpaymethod option:selected").text();
/*if(orderpaymethodname == 'Online'){
var curr_order_id = "ORDS0000"+$("#curr_Order_id").val();    
        var form=document.createElement('form');
        form.setAttribute('method','post');
        form.setAttribute('action','../../../paytmkit/pgRedirect.php');
       

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "ORDER_ID");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", curr_order_id);
    form.appendChild(hiddenField);
    
    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "CUST_ID");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", "CUST001");
    form.appendChild(hiddenField);
    
    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "INDUSTRY_TYPE_ID");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", "Retail");
    form.appendChild(hiddenField);
    
    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "CHANNEL_ID");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", "WEB");
    form.appendChild(hiddenField);
    
    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "TXN_AMOUNT");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", "100");
    form.appendChild(hiddenField);    

    document.body.appendChild(form);
    form.submit();    
    
}*/
//else{
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
		window.location.replace("newpos?tableid="+res['table_id']+"&tableName="+res['table_name']+"&current_order_id=0");
});

    
//}




});

function bckot(id){

if(id == ''){
  swal(
				'Warning!',
				'Please Place An Order',
				'warning'
			);
		return false; 
}

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
function toggleIcon(e) {
    $(e.target)
            .prev('.panel-heading')
            .find(".more-less")
            .toggleClass('fa-plus fa-minus');
    }
    $('.panel-group').on('hidden.bs.collapse', toggleIcon);
    $('.panel-group').on('shown.bs.collapse', toggleIcon);

    $("#runningorder").click(function(){
      location.reload();
    });
    
    function acceptandserveorder(orderid,orderstatus){
        
        var tableid = '<?= $tableid; ?>';
        var $tableName = '<?= $tableName; ?>';
        if(orderstatus == 1){
            var ordermsgtxt = 'Order Accepted Successfully';
        }
        else{
            var ordermsgtxt = 'Order Served Successfully';
        }
        swal({
				title: "Are you sure !!", 
				type: "warning",
				showCancelButton: true
		    })
		    	.then((result) => {
					if (result.value) {
					     var request = $.ajax({
          url: "acceptandserveorder",
          type: "POST",
          data: {orderid : orderid,orderstatus:orderstatus},
        }).done(function(msg) {
        	  swal(
        				'Success!',
        				ordermsgtxt,
        				'success'
        			);
        		var res = JSON.parse(msg);
        		window.location.replace("newpos?tableid="+tableid+"&tableName="+$tableName+"&current_order_id="+orderid);
        }); 
					}
				});
        
                
    }
    
    function openprepmodal(orderid){
        $("#myModalPrep").modal('show');
        $("#prepareorderid").val(orderid);   
    }
    
    $("#savepreptime").click(function(){
        var tableid = '<?= $tableid; ?>';
        var $tableName = '<?= $tableName; ?>';

        var orderid = $("#prepareorderid").val();
        var preptime = $("#preparationtime").val();
    
        swal({
				title: "Are you sure !!", 
				type: "warning",
				showCancelButton: true
		    })
		    	.then((result) => {
					if (result.value) {
					            $("#myModalPrep").modal('toggle');
					     var request = $.ajax({
                            url: "setorderpreparetime",
                            type: "POST",
                            data: {orderid : orderid,preptime:preptime},
                        }).done(function(msg) {
        	                swal(
                				'Success!',
                				'Preparation Time Set Successfully',
                				'success'
                			);
        		window.location.replace("newpos?tableid="+tableid+"&tableName="+$tableName+"&current_order_id="+orderid);
        }); 
					}
				});
    });
    

function isFoodPrepared(orderid){
                    var tableid = '<?= $tableid; ?>';
        var $tableName = '<?= $tableName; ?>';
	
	swal({
				title: "Is food prepared ?", 
				type: "warning",
				confirmButtonText: "Yes!",
				showCancelButton: true
		    })
		    	.then((result) => {
					if (result.value) {

						var request = $.ajax({
                          url: "tableorderstatuschange",
                          type: "POST",
                          data: {tableId : this.id,id:orderid,kdschange:1},
                        }).done(function(msg) {
                    		window.location.replace("newpos?tableid="+tableid+"&tableName="+$tableName+"&current_order_id="+orderid);
                        });
					} else if (result.dismiss === 'cancel') {
					    
					}
				});

    
}    

function cancelreject(orderid,orderstatus){
    $("#crorderid").val(orderid);
    $("#crorderstatus").val(orderstatus);
    $("#myModalCanc").modal('show');
    /* if(orderstatus != 2){
        $(".modal-title").html('Reject Order');
        $(".cr-label").html('Reject Reason');
        
    }
    else{
        $(".modal-title").html('Cancel Order');
        $(".cr-label").html('Cancel Reason');
    } */
}

$("#savecr").click(function(){
        var tableid = '<?= $tableid; ?>';
        var $tableName = '<?= $tableName; ?>';
        var orderstatus = $("#crorderstatus").val();
        var orderid = $("#crorderid").val();
        var cr_reason = $("#crid").val();
    
        swal({
				title: "Are you sure !!", 
				type: "warning",
				showCancelButton: true
		    })
		    	.then((result) => {
					if (result.value) {
					            $("#myModalPrep").modal('toggle');
					     var request = $.ajax({
                            url: "cancelreasonpos",
                            type: "POST",
                            data: {orderid : orderid,cr_reason:cr_reason,orderstatus:orderstatus},
                        }).done(function(msg) {
        	                swal(
                				'Success!',
                				'Reason Updated Successfully',
                				'success'
                			);
        		if(orderstatus == 2){
            		window.location.replace("newpos?tableid="+tableid+"&tableName="+$tableName+"&current_order_id=0");
        		}
        		else{
            		window.location.replace("newpos?tableid="+tableid+"&tableName="+$tableName+"&current_order_id="+orderid);
        		}
        }); 
					}
				});
});
</script>