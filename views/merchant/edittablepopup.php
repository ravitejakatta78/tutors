<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
?>
    <div class="modal-body">
				<?php	$form = ActiveForm::begin([
    		'id' => 'edit-table-form',
			'action'=>'edittable',
		'options' => ['class' => 'form-horizontal','wrapper' => 'col-xs-12',],
        	'layout' => 'horizontal',
			 'fieldConfig' => [
        'horizontalCssClasses' => [
            
            'offset' => 'col-sm-offset-0',
            'wrapper' => 'col-sm-12 pl-0 pr-0',
        ],
		]
		]) ?>
		<div class="form-group row">
		<label class="control-label col-md-4">Spot Name</label>
		<div class="col-md-8">
		 <?= $form->field($model, 'name',['enableAjaxValidation' => true])->textinput(['class' => 'form-control','style'=> 'text-transform: uppercase','placeholder'=>'Spot Name'])->label(false); ?>
		 <?= $form->field($model, 'ID')->hiddeninput(['class' => 'form-control','placeholder'=>'Spot Name'])->label(false); ?>
		</div>
		</div>
		
		<div class="form-group row">
		<label class="control-label col-md-4">Capacity</label>
		<div class="col-md-8">
		 <?= $form->field($model, 'capacity')->textinput(['class' => 'form-control','placeholder'=>'Capacity'])->label(false); ?>
		</div>
		</div>
		<div class="form-group row">
	   <label class="control-label col-md-4">Section Name</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'section_id')
				  ->dropdownlist(\yii\helpers\ArrayHelper::map(\app\models\Sections::find()
				  ->where(['merchant_id'=>Yii::$app->user->identity->merchant_id])->all(), 'ID', 'section_name')
				  ,['prompt'=>'Select'])->label(false); ?>
	   </div></div>
		
   <div class="modal-footer">
		<?= Html::submitButton('Edit Spot', ['class'=> 'btn btn-add']); ?>
      </div> 
		<?php ActiveForm::end() ?>
        
        