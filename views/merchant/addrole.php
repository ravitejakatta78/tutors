
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
				<button type="button" class="btn btn-add btn-xs" id="myBtn" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus mr-1"></i> Add Role</button>

				</div>
              </div>


              <div class="card-body">
                <div class="table-responsive">   
                  <table id="example" class="table table-striped table-bordered ">
                    <thead>
                      <tr>
                        <th>S No</th>
						<th>Role</th>
						<th>Status</th>
						<th>Action</th>
				   
                      </tr>
                    </thead>
					<tbody>
						<?php $x=1; 
							foreach($roleModel as $roleModel){
						?>
                            <tr>
                                <td><?php echo $x; ?></td>
                                <td><?php echo $roleModel['role_name']; ?></td>
								<td>
                                    <label class="switch">
                                    <input type="checkbox" <?php   if($roleModel[ 'role_status']=='1' ){ echo 'checked';}?> 
									onChange="changestatus('role',
                                    <?php echo $roleModel['ID'];?>);"> <span class="slider round"></span> </label>
                                </td>
								<td class="icons">
									<a onclick="updaterole('<?= $roleModel['ID'];?>')"><span class="fa fa-pencil"></span></a>
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
          <h4 class="modal-title">Add Role</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
			<?php	$form = ActiveForm::begin([
    		'id' => 'role-form',
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
	   <label class="control-label col-md-4">Role Name</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'role_name')->textinput(['class' => 'form-control','autocomplete'=>'off'
				  ,'placeholder'=>'Role'])->label(false); ?>
	   </div>
	   </div>
	   
</div>

	   </div>
	   </div>
	   <div class="modal-footer">
		<?= Html::submitButton('Add Role', ['class'=> 'btn btn-add']); ?>

      </div> 
	<?php ActiveForm::end() ?>
      </div>
    </div>
  </div>
  
    <div id="updaterole" class="modal fade" role="dialog">
<div class="modal-dialog modal-lg" >
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Edit Role</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
	    <div class="modal-body" id="rolebody">
		
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

