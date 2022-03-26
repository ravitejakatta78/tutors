<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use aryelds\sweetalert\SweetAlert;
?>
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
          <section>
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header d-flex align-items-center pt-0 pb-0">
                <h3 class="h4 col-md-6 pl-0 tab-title">Pilot List</h3>
				<div class="col-md-6 text-right pr-0">
			<button type="button" class="btn btn-add btn-xs" id="myBtn" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus mr-1"></i> Add Pilot</button> 

				</div>
              </div>


              <div class="card-body">
                <div class="table-responsive">   
                  <table id="example" class="table table-striped table-bordered ">
                    <thead>
                      <tr>
                         <th>S No</th>
						<th>Pilot Id</th>
						<th>Pilot Name</th>
						<th>Mobile Number</th>
						<th>Email</th>
						<th>Logged In</th>
						<th>Online Status</th>
						<th>Status</th>
						<th>Buzz</th> 
				   
                      </tr>
                    </thead>
		    <tbody>
								<?php $x=1; 
									foreach($pilotModel as $pilotModel){
								?>
                                  <tr>
                                 <td>
                                                        <?php echo $x; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $pilotModel['unique_id']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $pilotModel['name']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $pilotModel['mobile']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $pilotModel['email']; ?>
                                                    </td>
                                                    <td>
                                                        <label class="switch">
                                                            <input type="checkbox" <?php if($pilotModel[ 'loginaccess']=='1' ){ echo 'checked';}?> 
															onChange="changeloginaccess('serviceboy',
                                                            <?php echo $pilotModel['ID'];?>);"> <span class="slider round"></span> </label> 
                                                    </td>    
                                                    <td>
                                                        <label class="switch">
                                                            <input type="checkbox" <?php if($pilotModel[ 'loginstatus']=='1' ){ echo 'checked';}?> 
															onChange="changeloginstatus('serviceboy',
                                                            <?php echo $pilotModel['ID'];?>);"> <span class="slider round"></span> </label>
                                                    </td>
                                                    <td>
                                                        <label class="switch">
                                                            <input type="checkbox" <?php   if($pilotModel[ 'status']=='1' ){ echo 'checked';}?> 
															onChange="changestatus('serviceboy',
                                                            <?php echo $pilotModel['ID'];?>);"> <span class="slider round"></span> </label>
                                                    </td>
                                                    <td><button class="btn btn-success" onclick="buzzPilot('<?= $pilotModel['ID'];?>')">Buzz
													</button></td> 
										</tr>			
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
          <h4 class="modal-title">Add Pilot</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
			<?php	$form = ActiveForm::begin([
    		'id' => 'pilot-form',
        'action' => 'employeelist',
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
				  ->dropdownlist(\yii\helpers\ArrayHelper::map(\app\models\EmployeeRole::find()
				  ->where(['merchant_id'=>Yii::$app->user->identity->merchant_id])
				  ->andWhere(['=','role_name', 'PILOT'])
				  ->all(), 'ID', 'role_name'),['prompt'=>'Select']) 
				  ->label(false); ?>
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
		<select name="sectiongroup[]" class="test" multiple="multiple"> 
					<option value="">Select Section Name</option>
					<?php  foreach($categorytypes as $ca){ ?>
					<option value="<?php echo $ca['ID']; ?>"><?php echo $ca['section_name']; ?></option>
					<?php } ?>
					</select>
          <input type="hidden" value="1" name="pilotrender">
	   </div>
	   </div>
	   
	   </div>
	   </div>
	   </div>
	   <div class="modal-footer">
		<?= Html::submitButton('Add Employee', ['class'=> 'btn btn-add btn-hide']); ?>

      </div> 


<?php ActiveForm::end() ?>
        
    

        
      </div>
    </div>
  </div>
  
    <div id="updatepilot" class="modal fade" role="dialog">
<div class="modal-dialog modal-xl" >
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Edit Pilot</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
	    <div class="modal-body" id="pilotbody">
		
		</div>	
		
		  
		
	</div>
	</div>
</div>
        </section>
		<?php
$script = <<< JS
    $('#example').DataTable();
JS;
$this->registerJs($script);
?>
<script>
$(document).ready(function(){
	$('#example').DataTable();
});

function buzzPilot(empId){
	var request = $.ajax({
		url: "pilotbuzz",
		type: "POST",
		data: {empId : empId},
	}).done(function(msg) {
		alert("Buzzed")
		
	});
}
</script>
