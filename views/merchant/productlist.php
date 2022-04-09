
<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use aryelds\sweetalert\SweetAlert;
$actionId = Yii::$app->controller->action->id;

?>
<script src="<?= Yii::$app->request->baseUrl.'/js/typeahead.js'?>"></script>
<header class="page-header">
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
          </header>
    <div class="loading" style="display:none">
  		<img src="<?php echo Url::base(); ?>/img/load.gif" >
  	</div>
          <section>
            <?= \Yii::$app->view->renderFile('@app/views/merchant/_manageitems.php',['actionId'=>$actionId]); ?>  
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header d-flex align-items-center pt-0 pb-0">
                <h3 class="h4 col-md-6 pl-0 tab-title">Item List</h3>
				<div class="col-md-6 text-right pr-0">
				<button type="button" class="btn btn-add btn-xs" id="myBtn" data-toggle="modal" data-target="#myModalExcel"
				><i class="fa fa-plus mr-1"></i> Add Item Excel</button>
				
					<button type="button" class="btn btn-add btn-xs" id="myBtn" data-toggle="modal" data-target="#myModal"
				><i class="fa fa-plus mr-1"></i> Add Item</button>

				</div>
              </div>


              <div class="card-body">
                <div class="table-responsive">   
                  <table id="example" class="table table-striped table-bordered ">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Item Name</th>
                        <th>Item Code</th>
                        <th>Item Icon</th>
                        <th>Item Tag</th>
                        <th>Item Size</th>
						<th>Serve for</th>
						<th>Item Category</th>
						<th>Upselling</th>
						<th>Availabilty</th>
						<th>Status</th>
						<th>Action</th>
                      </tr>
                    </thead>
		    <tbody>
								<?php $x=1; 
									foreach($productModel as $productlist){
								?>
                                  <tr>
                                 	<td><?php echo $x;?></td>
                                 	<td><?php echo $productlist['title'];?></td>
                                 	<td><?php echo $productlist['unique_id'];?></td>
                                 	<td><img src="<?php echo  Yii::$app->request->baseUrl.'/uploads/productimages/'. $productlist['image'];?>" alt="" class="img-table dash-icon" style="height:50px"/></td> 
                                 	<td><?php echo $productlist['labeltag'];?></td>
									<td><?php echo Utility::foodcategory_type($productlist['food_category_quantity']);?></td>                                 	
                                 	<td><?php echo $productlist['serveline'];?></td>
                                 	<td><?php echo Utility::foodtype_value($productlist['foodtype']);?></td>
                                 	<!--<td><?php echo $productlist['upselling'] == '2' ? 'No' : 'Yes' ;?></td> -->
                                 	<td>
                                 	    <label class="switch">
					  <input type="checkbox" <?php if($productlist['upselling']=='1'){ echo 'checked';}?> onChange="changeupsellingstatus('product',<?php echo $productlist['ID'];?>);">
					  <span class="slider round"></span>
					</label></td>
					<td><label class="switch">
					  <input type="checkbox" <?php if($productlist['availabilty']=='1'){ echo 'checked';}?> onChange="changeavailabilty('product',<?php echo $productlist['ID'];?>);">
					  <span class="slider round"></span>
					</label>
					</td>
					<td>
<label class="switch">
					<input type="checkbox" <?php if($productlist['status']=='1'){ echo 'checked';}?> onChange="changestatus('product',<?php echo $productlist['ID'];?>);">
					<span class="slider round"></span> 

					</label>
					</td>
                    <td class="icons"><a onclick="editproductpopup('<?= $productlist['ID'];?>')"><span class="fa fa-pencil"></span></a> 
                    					<a title="Product - Delete" onClick="deleteproduct('<?=$productlist['ID'] ?>')"   ><span class="fa fa-trash"></span></a>
                    </td>
                                  </td>                                  </tr>
									<?php $x++; }?>
                       </tbody>
                  </table>

                </div>
              </div>
            </div>
          </div>

<div class="modal" id="myModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Add Item</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
		<?php	$form = ActiveForm::begin([
    		'id' => 'food-categery-form',
		'options' => ['class' => 'form-horizontal','wrapper' => 'col-xs-12',],
        	'layout' => 'horizontal',
			 'fieldConfig' => [
        'horizontalCssClasses' => [
            
            'offset' => 'col-sm-offset-0',
            'wrapper' => 'col-sm-12 pl-0 pr-0',
        ],
		]
		]) ?>
<div class="row">	
<div class="col-md-6">	

	   <div class="form-group row">
	   <label class="control-label col-md-4">Item Name</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'title', ['enableAjaxValidation' => true])->textinput(['class' => 'form-control title','autocomplete'=>'off','placeholder'=>'Item Name'])->label(false); ?>
	   </div>
	   </div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Item Tag</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'labeltag')->textinput(['class' => 'form-control labeltag','placeholder'=>'Item Tag'])->label(false); ?>
	   </div></div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Serve Line</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'serveline')->textinput(['class' => 'form-control','placeholder'=>'Serve Line'])->label(false); ?>
	   </div></div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Upselling</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'upselling')
				  ->dropdownlist(['2' => 'No','1' => 'Yes']
				  )->label(false); ?>	  
		</div></div>
		<div class="form-group row">
	   <label class="control-label col-md-4">Item Type</label>
	   <div class="col-md-8">
  		<?= $form->field($model, 'item_type')
				  ->dropdownlist(['1' => 'Veg','2' => 'Non Veg']
				  )->label(false); ?>	   </div></div>
		<div class="form-group row">
	   <label class="control-label col-md-4">Taste Range</label>
	   <div class="col-md-8">
  		<?= $form->field($model, 'taste_range')
				  ->dropdownlist(['1' => '1','2' => '2','3' => '3','4' => '4','5' => '5']
				  )->label(false); ?>	   </div></div>
</div>
<div class="col-md-6">
	   <div class="form-group row">
	   <label class="control-label col-md-4">Category</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'foodtype')
				  ->dropdownlist(\yii\helpers\ArrayHelper::map(\app\models\FoodCategeries::find()
				  ->where(['merchant_id'=>Yii::$app->user->identity->merchant_id])->all(), 'ID', 'food_category')
				  ,['prompt'=>'Select',
                    'onchange'=>'
                        $.get( "'.Url::toRoute('/merchant/quantitylist').'", { id: $(this).val() } )
                            .done(function( data ) {
                                $( "#'.Html::getInputId($model, 'food_category_quantity').'" ).html( data );
                            }
                        );
                    '])->label(false); ?>
	   </div></div>
	   	   <div class="form-group row">
	   <label class="control-label col-md-4">Category Unit</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'food_category_quantity', ['enableAjaxValidation' => true])
				  ->dropdownlist([]
				  ,['prompt'=>'Select'])->label(false); ?>
	   </div></div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Upload</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'image')->fileinput(['class' => 'form-control'])->label(false); ?>
	   </div></div>
	   	   <div class="form-group row">
	   <label class="control-label col-md-4">Item Code</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'unique_id', ['enableAjaxValidation' => true])->textinput(['class' => 'form-control'])->label(false); ?>
	   </div></div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Taste Category</label>
	   <div class="col-md-8">
	   <?= $form->field($model, 'taste_category')
				  ->dropdownlist(\app\helpers\MyConst::TASTE_CATEGORIES
				  )->label(false); ?>	   </div></div>
	   	
	   </div>
	   </div>
	   </div>
	   	<table id="tblAddRow" class="table table-bordered table-striped">
    <thead>
        <tr>
			<th>Section</th>
			<th>Price</th>
			<th>Sale Price</th>
        </tr>
    </thead>
    <tbody>
	<?php for($i=0;$i<count($sections);$i++){
		if(isset($itemSectionPricesArr[$sections[$i]['ID'] . '-'. $model->ID])){
	$price_item = is_numeric($itemSectionPricesArr[$sections[$i]['ID'] . '-'. $model->ID]) ?  $itemSectionPricesArr[$sections[$i]['ID'] . '-'. $model->ID] : 0; 
	
}else{
	$price_item = 0;
}
?>
        <tr>
			<td>
                <p><?= $sections[$i]['section_name']; ?></p>
				<input type="hidden" name="sectionid[]"  value="<?= $sections[$i]['ID']; ?>" >
            </td>
			<td>
                <input name="prices[]" autocomplete="off"  class="form-control allow_decimal_one" >
            </td>  
            <td>
                <input name="sale_prices[]" autocomplete="off"  class="form-control allow_decimal_one" >
            </td> 
        </tr>
	<?php } ?> 
    </tbody>
</table>
	   <div class="modal-footer">
		<?= Html::submitButton('Add Item', ['class'=> 'btn btn-add btn-hide']); ?>

      </div> 


<?php ActiveForm::end() ?>
        
        
        
        
      </div>
    </div>
  </div>
  
    <div id="editproduct" class="modal fade" role="dialog">
<div class="modal-dialog modal-lg" >
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Edit Item</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
	    <div class="modal-body" id="editproductbody">
		
		</div>	
		
		  
		
	</div>
	</div>
</div>

    <div id="myModalExcel" class="modal fade" role="dialog">
<div class="modal-dialog" >
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Add Items Excel</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <form method="POST" enctype= "multipart/form-data" action="upload-items">
	    <div class="modal-body" >
		    	<?php	$form = ActiveForm::begin([
    		'id' => 'item-excel-form',
    		'action' => 'upload-items',
		'options' => ['class' => 'form-horizontal','enctype' => 'multipart/form-data','wrapper' => 'col-xs-12',],
        	'layout' => 'horizontal',
			 'fieldConfig' => [
        'horizontalCssClasses' => [
            
            'offset' => 'col-sm-offset-0',
            'wrapper' => 'col-sm-12 pl-0 pr-0',
        ],
		]
		]) ?>
<div class="row">	
<div class="col-md-12">	

	   <div class="form-group row">
	   <label class="control-label col-md-4">Upload</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'title')->fileInput(['class' => 'form-control '])->label(false); ?>
	   </div>
	   </div>
	   </div>
	   	   </div>
		      
		</div>	
		 <div class="modal-footer">
		<?= Html::submitButton('Upload Excel', ['class'=> 'btn btn-add btn-hide']); ?>

      </div> 

<?php ActiveForm::end() ?>		  
		
	</div>
	</div>
</div>
        </section>
		<?php
$script = <<< JS
   $('#example').DataTable({
  "scrollX": true
});
JS;
$this->registerJs($script);
?>
<script>

function deleteproduct(id){
//	var res = confirm('Are you sure want to delete??')
		    swal({
				title: "Are you sure want to delete??", 
				type: "warning",
				showCancelButton: true
		    })
		    	.then((result) => {
					if (result.value) {
					    var request = $.ajax({
						  url: "deleteproduct",
						  type: "POST",
						  data: {id : id},
						}).done(function(msg) {
							
							location.reload();
						});
					}
				});

}
$('.title').typeahead({
            source: function (query, result) {
                $.ajax({
                    url: "productautocomplete",
					data: 'query=' + query,            
                    dataType: "json",
                    type: "POST",
                    success: function (data) {
						result($.map(data, function (item) {
							return item;
                        }));
                    }
                });
            }
});
function autocompleteproduct(){
$('.titleedit').typeahead({
            source: function (query, result) {
                $.ajax({
                    url: "productautocomplete",
					data: 'query=' + query,            
                    dataType: "json",
                    type: "POST",
                    success: function (data) {
						result($.map(data, function (item) {
							return item;
                        }));
                    }
                });
            }
});	
	}
	
	function allowdecimanls(){
		 $(".allow_decimal").on("input", function(evt) {
	var self = $(this);
   self.val(self.val().replace(/[^0-9\.]/g, ''));
   if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57)) 
   {
     evt.preventDefault();
   }
 });
}
		 $(".allow_decimal_one").on("input", function(evt) {
	var self = $(this);
   self.val(self.val().replace(/[^0-9\.]/g, ''));
   if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57)) 
   {
     evt.preventDefault();
   }
 });
 
 function changeupsellingstatus(tablename,tableid){
			 $.ajax({
				 type: 'post',
				 url: 'changeupsellingstatus',
				 data: {
				 tablename:tablename,
				 tableid:tableid
				 },		
				 success: function (response) {
					/* silence is golden */ 
				 }	 
				 });
}
</script>
