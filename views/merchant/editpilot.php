<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
?>
   		<?php	$form = ActiveForm::begin([
    		'id' => 'update-pilot-form',
			'action'=>'updatepilot',
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
	   <label class="control-label col-md-4">Pilot Name</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'name')->textinput(['class' => 'form-control','placeholder'=>'Pilot Name'])->label(false); ?>
	   </div>
	   </div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Mobile Number</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'mobile')->textinput(['class' => 'form-control','placeholder'=>'Mobile Number'])->label(false); ?>
			      <?= $form->field($model, 'ID')->hiddeninput(['class' => 'form-control','placeholder'=>'Mobile Number'])->label(false); ?>				  
	   </div></div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Email</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'email')->textinput(['class' => 'form-control','placeholder'=>'Email'])->label(false); ?>
	   </div></div>
	   
</div>
<div class="col-md-6">
	   <div class="form-group row">
	   <label class="control-label col-md-4">Password</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'password')->passwordinput(['class' => 'form-control','value'=> '','placeholder'=>'Password'])->label(false); ?>
	   </div></div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Joined On</label>
	   <div class="col-md-8">
	   <?= $form->field($model, 'joiningdate')->textinput(['class' => 'form-control datepicker1'])->label(false); ?>
	   </div></div>
	   </div>
	   </div>
	   </div>
	   <div class="modal-footer">
		<?= Html::submitButton('Update Pilot', ['class'=> 'btn btn-add']); ?>

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
        
        