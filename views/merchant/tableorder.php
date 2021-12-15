
<?php

use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
$actionId = Yii::$app->controller->action->id;
$merchant_det = \app\models\Merchant::findOne(Yii::$app->user->identity->merchant_id);
?>
<style>
.placeorder{background:#FD8B02;border:1px solid #FD8B02;color:#fff;border-radius:24px;}
.placeorder:hover{bacground:#28a745;border:1px solid #28a745;}
#nopadding {
   margin-left: 90px !important;
}
.ih-item.circle {
  position: relative;
  width: 150px;
  height: 150px;
  border-radius: 50%;
  margin-bottom:30px;
}
.ih-item.circle .img {
  position: relative;
  width: 147px;
  height: 130px;
  border-radius: 50%;
}
.ih-item.circle.effect10.top_to_bottom .info h3 {
  margin: 0 30px;
  padding: 12px 0 5px 0;
  /*height: 78px;*/
}
</style>
 <header class="page-header">
            
          </header>
          
          <section class="col-md-12">
            <?= \Yii::$app->view->renderFile('@app/views/merchant/_orders.php',['actionId'=>$actionId]); ?>
              <div class="card">
             <!--   <div class="card-header d-flex align-items-center">
                  <h3 class="h4"></h3>
                </div>-->
				
			<!--	<div class="card-header d-flex align-items-center pt-0 pb-0">
                <h3 class="h4 col-md-6 pl-0 tab-title">Table View</h3>
				<div class="col-md-6 text-right pr-0">
				<button type="button" class="btn btn-add btn-xs placeorder" id="myBtn" data-toggle="modal" data-target="#myModal"> Take Away</button>
				</div>
              </div> -->
			  <div class="card-header d-flex align-items-center pt-0 pb-0">
                <h3 class="h4 col-md-3 pl-0 tab-title">Table View</h3>
                <?php 
		  $sectionsArr = array_column($tableSections,'section_name','section_id');
		  $se = 1 ;
		  foreach($sectionsArr as $col => $val){ 
		     // echo "$col";exit;
		  ?>
		  
		  <button type="button" class="btn btn-add btn-xs ml-4" onclick="tablesection('<?= $col; ?>')" ><?= $val; ?></button>

		  <?php 
if($se % 2 == 0){
    echo '<br><br><br>';
}
		  $se++;
		  } ?>
				<div class="col-md-6 text-right pr-0">
				<button type="button" class="btn btn-add btn-xs placeorder" onclick="placeOrder('PARCEL','','')" ><i class="fa fa-plus mr-1"></i>Takeaway</button>
				</div>
              </div>
                <!-- Statistics -->
                <div class="card-body">
              <!-- Top to bottom-->
              <div class="row">
			  
                <?php  if(count($tableDetails) > 0) {
					for($i=0;$i<count($tableDetails);$i++) {
					?>
				
				<div class="col-sm-1" id="nopadding">
                  <!-- normal -->
                  <div class="ih-item circle effect10 top_to_bottom"   onclick="placeOrder('<?= $tableDetails[$i]['ID']?>','<?= $tableDetails[$i]['name']?>','<?= ($merchant_det['table_occupy_status'] == 1) ? $tableDetails[$i]['current_order_id'] : '0'; ?>')"><a href="#">
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
                                              <p><?php $tableStatus = $tableDetails[$i]['table_status'] ?? 5 ;
						if($tableStatus == '5' || $tableStatus == '' ){ ?>
<div class="info Available">
                        <h3><?= $tableDetails[$i]['name']?></h3>
						
						<span class="badge badge-success">Available</span>
						</div>
						<?php }
						else if($tableStatus == '2'){ ?>
<div class="info Serving"> 
                        <h3><?= $tableDetails[$i]['name']?></h3>

							 <span class="badge badge-warning">Serving</span>
						</div>
						<?php }else { ?>
<div class="info occupied"> 
                        <h3><?= $tableDetails[$i]['name']?></h3>

							 <span class="badge badge-danger">Occupied</span>
						</div>

						<?php } ?> </p>
                      </a></div>
                  <!-- end normal -->
                </div>
					<?php } } else { //occupied?>
				No tables to display
				<?php } ?>
                
              </div>
              <!-- end Top to bottom-->
              
            </div>
              </div>
          </section>
		
<script>
function placeOrder(id,name,current_order_id)
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
function tablesection(sectionId){
         var form=document.createElement('form');
        form.setAttribute('method','post');
        form.setAttribute('action','tableplaceorder');
        //form.setAttribute('target','_blank');

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "sectionId");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", sectionId);
    form.appendChild(hiddenField);

    
    document.body.appendChild(form);
    form.submit();
}

</script>