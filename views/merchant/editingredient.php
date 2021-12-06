<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
?>
   		<?php	$form = ActiveForm::begin([
    		'id' => 'update-ingredient-form',
			'action'=>'updateingredients',
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
	   <label class="control-label col-md-4">Ingredient Name</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'item_name')->textinput(['class' => 'form-control','autocomplete'=>'off','placeholder'=>'Ingredient Name'])->label(false); ?>
				  			      <?= $form->field($model, 'ID')->hiddeninput(['class' => 'form-control','autocomplete'=>'off'])->label(false); ?>
	   </div>
	   </div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Ingredient Type</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'item_type')->textinput(['class' => 'form-control','autocomplete'=>'off','placeholder'=>'Ingredient Type'])->label(false); ?>
	   </div></div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Ingredient Price</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'item_price')->textinput(['class' => 'form-control','autocomplete'=>'off','placeholder'=>'Ingredient Price'])->label(false); ?>
	   </div></div>
	   
</div>
<div class="col-md-6">
	   <div class="form-group row">
	   <label class="control-label col-md-4">Photo</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'photo')->fileinput(['class' => 'form-control'])->label(false); ?>
	   </div></div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Stock Alert</label>
	   <div class="col-md-8">
	   <?= $form->field($model, 'stock_alert')->textinput(['class' => 'form-control','autocomplete'=>'off','placeholder'=>'Stock Alert'])->label(false); ?>
	   </div></div>
	   </div>
	   </div>
	   </div>
	   <div class="modal-footer">
		<?= Html::submitButton('Edit Ingredient', ['class'=> 'btn btn-add']); ?>

      </div> 


<?php ActiveForm::end() ?>
        
<?php
$script = <<< JS
    $('#tblAddRow1').DataTable();
JS;
$this->registerJs($script);
?>
   
<script>

</script>    
        
        