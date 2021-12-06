<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
?>
			<?php	$form = ActiveForm::begin([
    		'id' => 'update-fc-form',
			'action'=>'editfoodsection',
			'options' => ['class' => 'form-horizontal','wrapper' => 'col-xs-12',],
        	'layout' => 'horizontal',
			 'fieldConfig' => [
			 'horizontalCssClasses' => [
				'wrapper' => 'col-sm-12 pl-0 pr-0',
			 ],
		    ]
		    ]) ?>
<div class="row">	
 <td>
                               
<div class="col-md-6">	
		<div class="form-group row">
		<label class="control-label col-md-4">Menu Section</label>
		<div class="col-md-8">
		 <?= $form->field($model, 'food_section_name')->textinput(['class' => 'form-control','autocomplete'=>'off','placeholder'=>'Food Section'])->label(false); ?>
		 <?= $form->field($model, 'ID')->hiddeninput(['class' => 'form-control','autocomplete'=>'off','placeholder'=>'Food Section'])->label(false); ?>

		</div>
		</div>   
	   
</div>

	   </div>
	   </div>
	   <div class="modal-footer">
		<?= Html::submitButton('Update Menu Section', ['class'=> 'btn btn-add']); ?>

      </div> 


<?php ActiveForm::end() ?>        
<?php
$script = <<< JS

JS;
$this->registerJs($script);
?>
   
<script>

</script>    
        
        