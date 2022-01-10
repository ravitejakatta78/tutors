
<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use aryelds\sweetalert\SweetAlert;
$actionId =  Yii::$app->controller->action->id;
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
		  <?= \Yii::$app->view->renderFile('@app/views/merchant/_emphorizontaltab.php',['actionId'=>$actionId]); ?>
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header d-flex align-items-center pt-0 pb-0">
                <h3 class="h4 col-md-6 pl-0 tab-title">Employee List</h3>
				<div class="col-md-6 text-right pr-0">
				<button type="button" class="btn btn-add btn-xs" id="myBtn" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus mr-1"></i> Add Employee</button>

				</div>
              </div>


              <div class="card-body">
                <div class="table-responsive">   
                  <table id="example" class="table table-striped table-bordered ">
                    <thead>
                      <tr>
                        <th>S No</th>
						<th>Employee ID</th>
						<th>Employee Name</th>
						<th>Employee Role</th>
						<th>Employee Phone</th>
						<th>Employee Email</th>
						<th>Experience</th>
						<th>Date of Joining</th>
						<th>Salary</th>
						<th>Designation</th>
						<th>Specialities</th>
						<th>Status</th>
						<th>Action</th>
				   
                      </tr>
                    </thead>
					<tbody>
						<?php $x=1; 
							foreach($empModel as $empModel){
						?>
                            <tr>
                                <td><?php echo $x; ?></td>
                                <td><?php echo $empModel['emp_id']; ?></td>
								<td><?php echo $empModel['emp_name']; ?></td>
                                <td><?php echo $empRoleIdNameArr[$empModel['emp_role']] ?? 'OWNER'; ?></td>
                                <td><?php echo $empModel['emp_phone']; ?></td>
                                <td><?php echo $empModel['emp_email']; ?></td>
								<td><?php echo $empModel['emp_exp']; ?></td>
								<td><?php echo $empModel['date_of_join']; ?></td>
								<td><?php echo $empModel['emp_salary']; ?></td>
								<td><?php echo $empModel['emp_designation']; ?></td>
								<td><?php echo $empModel['emp_specialities']; ?></td>
                                <td>
                                    <label class="switch">
                                    <input type="checkbox" <?php   if($empModel[ 'emp_status']=='1' ){ echo 'checked';}?> 
									onChange="changestatus('employee',
                                    <?php echo $empModel['ID'];?>);"> <span class="slider round"></span> </label>
                                </td>
								<td class="icons">
									<a onclick="updateemployee('<?= $empModel['ID'];?>')"><span class="fa fa-pencil"></span></a>
									<a onclick="deleteemployee('<?= $empModel['ID'];?>')"><span class="fa fa-trash"></span></a>
								</td>
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
          <h4 class="modal-title">Add Emplpoyee</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
			<?php	$form = ActiveForm::begin([
    		'id' => 'employee-form',
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
				  ->andWhere(['<>','role_name', 'OWNER'])
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
		<input type="hidden" id="update_merchant_id" name="update_merchant_id" value="<?= $id;?>">
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
  
    <div id="updateemployee" class="modal fade" role="dialog">
<div class="modal-dialog modal-lg" >
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Edit Employee</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
	    <div class="modal-body" id="employeebody">
		
		</div>	
		
		  
		
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
function deleteemployee(id){
//	var res = confirm('Are you sure want to delete??')
		    swal({
				title: "Are you sure want to delete??", 
				type: "warning",
				showCancelButton: true
		    })
		    	.then((result) => {
					if (result.value) {
					    var request = $.ajax({
						  url: "deleteemployee",
						  type: "POST",
						  data: {id : id},
						}).done(function(msg) {
							
							location.reload();
						});
					}
				});

}

jQuery('body').on('click', '[data-toggle=dropdown]', function() {
    var opened = $(this).parent().hasClass("open");
    if (! opened) {
        $('.btn-group').addClass('open');
        $("button.multiselect").attr('aria-expanded', 'true');
    } else {
        $('.btn-group').removeClass('open');
        $("button.multiselect").attr('aria-expanded', 'false');
    }
});
</script>
