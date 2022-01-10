<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
?>
			<?php	$form = ActiveForm::begin([
    		'id' => 'update-employee-form',
			'action'=>'updateemployee',
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
	   <label class="control-label col-md-4">Employee Name</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'emp_name')->textinput(['class' => 'form-control','autocomplete'=>'off'
				  ,'placeholder'=>'Employee Name'])->label(false); ?>
	   </div>
	   </div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Employee Role</label>
	   <div class="col-md-8">
			   	   			      <?= $form->field($model, 'emp_role')
				  ->dropdownlist(\yii\helpers\ArrayHelper::map(\app\models\EmployeeRole::find()->all(), 'ID', 'role_name'),['prompt'=>'Select']) 
				  ->label(false); ?>
			<?= $form->field($model, 'ID')->hiddeninput(['class' => 'form-control'])->label(false); ?>
	   </div></div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Employee Phone</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'emp_phone',['enableAjaxValidation' => true])->textinput(['class' => 'form-control','maxlength'=>'10','autocomplete'=>'off'
				  ,'placeholder'=>'Phone Number'])->label(false); ?>
	   </div></div>
	  	<div class="form-group row">
	   <label class="control-label col-md-4">Employee Salary</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'emp_salary')->textinput(['class' => 'form-control','autocomplete'=>'off','placeholder'=>'Salary'])->label(false); ?>
	   </div></div>
	  	<div class="form-group row">
	   <label class="control-label col-md-4">Specialities</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'emp_specialities')->textarea(['class' => 'form-control','autocomplete'=>'off','placeholder'=>'Specialities'])->label(false); ?>
	   </div></div>	   
	   
</div>
<div class="col-md-6">
  <div class="form-group row">
	   <label class="control-label col-md-4">Employee Password</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'emp_password')->passwordinput(['class' => 'form-control','autocomplete'=>'off','placeholder'=>'Password'])->label(false); ?>
	   </div></div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Employee Email</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'emp_email',['enableAjaxValidation' => true])->textinput(['class' => 'form-control','autocomplete'=>'off','placeholder'=>'Email'])->label(false); ?>
	   </div></div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Employee Experience</label>
	   <div class="col-md-8">
	   <?= $form->field($model, 'emp_exp')->textinput(['class' => 'form-control','autocomplete'=>'off','placeholder'=>'Experience'])->label(false); ?>
	   </div></div>
	   	<div class="form-group row">
	   <label class="control-label col-md-4">Joined On</label>
	   <div class="col-md-8">
	   <?= $form->field($model, 'date_of_join')->textinput(['class' => 'form-control datepicker1','value'=>date('Y-m-d')])->label(false); ?>
	   </div></div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Employee Designation</label>
	   <div class="col-md-8">
	   <?= $form->field($model, 'emp_designation')->textinput(['class' => 'form-control','autocomplete'=>'off','placeholder'=>'Designation'])->label(false); ?>
	   </div></div>
	   	   <div class="form-group row">
	   <label class="control-label col-md-4">Sections</label>  
	   <div class="col-md-8">
		<select name="sectiongroup[]" class="select" id="groupmultiselect" multiple="multiple"> 
					<option value="">Select Section Name</option>
					<?php  foreach($categorytypes as $ca){ ?>
					<option value="<?php echo $ca['ID']; ?>" <?php if(in_array($ca['ID'],$pilotTableId)){ echo 'selected'; } ?>><?php echo $ca['section_name']; ?></option>
					<?php } ?>
					</select>
	   </div>
	   </div>
	   </div>
	   </div>
	   </div>
	   <div class="modal-footer">
		<?= Html::submitButton('Update Employee', ['class'=> 'btn btn-add']); ?>

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
        