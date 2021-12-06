<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
?>
   		<?php	$form = ActiveForm::begin([
    		'id' => 'update-title-image-form',
			'action'=>'updatetitleimage',
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
	   <label class="control-label col-md-4">Title Name</label>
	   <div class="col-md-8">
				  <?= $form->field($model, 'title_id')
				  ->dropdownlist(\yii\helpers\ArrayHelper::map(\app\models\RoomProfileTitles::find()
				  ->where(['merchant_id'=>Yii::$app->user->identity->merchant_id])->all(), 'ID', 'title_name')
				  ,['prompt'=>'Select'])->label(false); ?>
	  				  <?= $form->field($model, 'ID')->hiddeninput(['class' => 'form-control'])->label(false); ?>

	  
	   </div>
	   </div>

	   <div class="form-group row">
	   <label class="control-label col-md-4">Upload</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'title_pic')->fileinput(['class' => 'form-control'])->label(false); ?>
	   </div></div>

	   
</div>

	   </div>
	   </div>
	   <div class="modal-footer">
		<?= Html::submitButton('Update Title Image', ['class'=> 'btn btn-add']); ?>

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
        
        