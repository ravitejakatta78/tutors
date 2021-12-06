<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
?>
   		<?php	$form = ActiveForm::begin([
    		'id' => 'update-tile-name-form',
			'action'=>'updateprofiletitle',
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
 <td>
                               
<div class="col-md-6">	
	   <div class="form-group row">
	   <label class="control-label col-md-4">Profile Title</label>
	   <div class="col-md-8">
	   <?= $form->field($model, 'title_name')->textinput(['class' => 'form-control title','autocomplete'=>'off','placeholder'=>'Profile Title'])->label(false); ?>

				  <?= $form->field($model, 'ID')->hiddeninput(['class' => 'form-control'])->label(false); ?>
	   </div>
	   </div>
	   
</div>

	   </div>
	   </div>
	   <div class="modal-footer">
		<?= Html::submitButton('Update Title', ['class'=> 'btn btn-add']); ?>

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
        
        