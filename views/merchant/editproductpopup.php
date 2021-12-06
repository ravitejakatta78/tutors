<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<script src="<?= Yii::$app->request->baseUrl.'/js/typeahead.js'?>"></script>
   		<?php	$form = ActiveForm::begin([
    		'id' => 'update-product-form',
			'action'=>'updateproduct',
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
			      <?= $form->field($model, 'title')->textinput(['class' => 'form-control titleedit','onkeydown'=>'autocompleteproduct()' ,'placeholder'=>'Item Name'])->label(false); ?>
	   </div>
	   </div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Item Code</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'unique_id')->textinput(['class' => 'form-control titleedit','onkeydown'=>'autocompleteproduct()' ,'placeholder'=>'Item Code'])->label(false); ?>
	   </div>
	   </div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Item Tag</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'labeltag')->textinput(['class' => 'form-control labeltagedit','placeholder'=>'Item Tag'])->label(false); ?>
	   </div></div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Serve Line</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'serveline')->textinput(['class' => 'form-control','placeholder'=>'Serve Line'])->label(false); ?>
	   </div></div>
	   <!-- <div class="form-group row">
	   <label class="control-label col-md-4">Price</label>
	   <div class="col-md-8">
	   <?= $form->field($model, 'price')->textinput(['class' => 'form-control','placeholder'=>'Price'])->label(false); ?>
	   </div></div> -->
	   

</div>
<div class="col-md-6">
	  <!-- <div class="form-group row">
	   <label class="control-label col-md-4">Sale Price</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'saleprice')->textinput(['class' => 'form-control','placeholder'=>'Sale Price'])->label(false); ?>
	   </div></div> -->
	   <div class="form-group row">
	   <label class="control-label col-md-4">Category</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'foodtype')
				  ->dropdownlist(\yii\helpers\ArrayHelper::map(\app\models\FoodCategeries::find()
				  ->where(['merchant_id'=>Yii::$app->user->identity->merchant_id])
				  ->all(), 'ID', 'food_category')
				  
				  ,['prompt'=>'Select',
                    'class'=>'form-control food_cat',
					'onchange'=>'
                        $.get( "'.Url::toRoute('/merchant/quantitylist').'", { id: $(this).val() } )
                            .done(function( data ) {
                                $(".food_cat_type").html( data );
                            }
                        );
                    '])->label(false); ?>
	   </div></div>
	   	   <div class="form-group row">
	   <label class="control-label col-md-4">Category Unit</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'food_category_quantity')
				  ->dropdownlist([\yii\helpers\ArrayHelper::map(\app\models\FoodCategoryTypes::find()
				  ->where(['merchant_id'=>Yii::$app->user->identity->merchant_id,'food_cat_id'=>$model->foodtype])
				  
				  ->all(), 'ID', 'food_type_name')]
				  ,['prompt'=>'Select','class'=>'form-control food_cat_type'
])->label(false); ?>
	   </div></div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Upload</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'image')->fileinput(['class' => 'form-control'])->label(false); ?>
				  <?= $form->field($model, 'ID')->hiddeninput(['class' => 'form-control','placeholder'=>'Sale Price'])->label(false); ?>

	   </div></div>
	   
	   	   	   <div class="form-group row">
	   <label class="control-label col-md-4">Upselling</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'upselling')
				  ->dropdownlist(['2' => 'No','1' => 'Yes']
				  )->label(false); ?>	   </div></div>
				  
				  
	   	   <div class="form-group row">
	   <label class="control-label col-md-4">Item Type</label>
	   <div class="col-md-8">
  <?= $form->field($model, 'item_type')
				  ->dropdownlist(['1' => 'Veg','2' => 'Non Veg']
				  )->label(false); ?>	   </div></div>
				  
				  	   	   	   <div class="form-group row">
	   <label class="control-label col-md-4">Today Special</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'today_special')
				  ->dropdownlist(['2' => 'No','1' => 'Yes']
				  )->label(false); ?>	   </div></div>
	   </div>
	   </div>
	   </div>
	   	   	<table id="tblAddRow" class="table table-bordered table-striped">
    <thead>
        <tr>
			<th>Section</th>
			<th>Price</th>
			<th>Sale Prices</th>
        </tr>
    </thead>
    <tbody>
	<?php for($i=0;$i<count($sections);$i++){
		if(isset($itemSectionPricesArr[$sections[$i]['ID'] . '-'. $model->ID])){
	$price_item = is_numeric($itemSectionPricesArr[$sections[$i]['ID'] . '-'. $model->ID]) ?  $itemSectionPricesArr[$sections[$i]['ID'] . '-'. $model->ID] : 0; 
	
}else{
	$price_item = 0;
}
	if(isset($itemSectionSalePricesArr[$sections[$i]['ID'] . '-'. $model->ID])){
	$sale_price_item = is_numeric($itemSectionSalePricesArr[$sections[$i]['ID'] . '-'. $model->ID]) ?  $itemSectionSalePricesArr[$sections[$i]['ID'] . '-'. $model->ID] : 0; 
	
}else{
	$sale_price_item = 0;
}
?>
        <tr>
			<td>
                <p><?= $sections[$i]['section_name']; ?></p>
				<input type="hidden" name="updatesectionid[]"  value="<?= $sections[$i]['ID']; ?>" >
            </td>
			<td>
                <input name="updateprices[]" onkeydown="allowdecimanls()" autocomplete="off" value="<?= $price_item; ?>" class="form-control allow_decimal" >
            </td>    
            	<td>
                <input name="updatesaleprices[]" onkeydown="allowdecimanls()" autocomplete="off" value="<?= $sale_price_item; ?>" class="form-control allow_decimal" >
            </td>    
            <!--itemSectionSalePricesArr-->
        </tr>
	<?php } ?> 
    </tbody>
</table>
	   <div class="modal-footer">
		<?= Html::submitButton('Save Item', ['class'=> 'btn btn-add']); ?>

      </div> 


<?php ActiveForm::end() ?>
<?php
$script = <<< JS
    $('#tblAddRow1').DataTable();
	$('select').select2();
JS;
$this->registerJs($script);
?>
   
<script>

$(document).on('keydown', '.titleedit,.labeltagedit', function(){


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

</script>    
        
        