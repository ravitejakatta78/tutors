
<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use aryelds\sweetalert\SweetAlert;
$actionId = Yii::$app->controller->action->id;
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
		  <?= \Yii::$app->view->renderFile('@app/views/merchant/_vendorhorizontal.php',['actionId'=>$actionId]); ?>
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header d-flex align-items-center pt-0 pb-0">
                <h3 class="h4 col-md-6 pl-0 tab-title">Vendor List</h3>
				<div class="col-md-6 text-right pr-0">
				<button type="button" class="btn btn-add btn-xs" id="myBtn" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus mr-1"></i> Add Vendor</button>

				</div>
              </div>


              <div class="card-body">
                <div class="table-responsive">   
                  <table id="example" class="table table-striped table-bordered ">
                    <thead>
                      <tr>
                        <th>S No</th>
						<th>Store Name</th>
						<th>Vendor Type</th>
						<th>Owner Name</th>
						<th>Mobile Number</th>
						<th>Manager Name</th>
						<th>Manager Mobile Number</th>
						<th>Location</th>
						<th>City</th>
						<th>Range</th>
						<th>Settlement</th>
						<th>Status</th>
						<th>Action</th>
				   
                      </tr>
                    </thead>
		    <tbody>
								<?php $x=1; 
									foreach($vendorModel as $vendorModel){
								?>
                                  <tr>
                                 <td>
                                                        <?php echo $x; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $vendorModel['store_name']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $vendorModel['vendor_type']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $vendorModel['owner_name']; ?>
                                                    </td>

													<td>
                                                        <?php echo $vendorModel['owner_mobile']; ?>
                                                    </td>
													<td>
                                                        <?php echo $vendorModel['manager_name']; ?>
                                                    </td>
													<td>
                                                        <?php echo $vendorModel['manager_mobile']; ?>
                                                    </td>
													<td>
                                                        <?php echo $vendorModel['vendor_location']; ?>
                                                    </td>
													<td>
                                                        <?php echo $vendorModel['vendor_city']; ?>
                                                    </td>		
													<td>
                                                        <?php echo $vendorModel['vendor_range']; ?>
                                                    </td>
                                                    <td></td>
                                                    <td>
                                                        <label class="switch">
                                                            <input type="checkbox" <?php   if($vendorModel[ 'status']=='1' ){ echo 'checked';}?> 
															onChange="changestatus('vendor',
                                                            <?php echo $vendorModel['ID'];?>);"> <span class="slider round"></span> </label>
                                                    </td>
                                                    <td class="icons"><a onclick="updatevendor('<?= $vendorModel['ID'];?>')">
													<span class="fa fa-pencil"></span>
													</a>
									<a onclick="deletevendor('<?= $vendorModel['ID'];?>')"><span class="fa fa-trash"></span></a>

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
          <h4 class="modal-title">Add Vendor</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
			<?php	$form = ActiveForm::begin([
    		'id' => 'vendor-form',
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
	   <label class="control-label col-md-4">Store Name</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'store_name')->textinput(['class' => 'form-control','autocomplete'=>'off','placeholder'=>'Store Name'])->label(false); ?>
	   </div>
	   </div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Vendor Type</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'vendor_type')->textinput(['class' => 'form-control','placeholder'=>'Vendor Type'])->label(false); ?>
	   </div></div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Owner Name</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'owner_name')->textinput(['class' => 'form-control','placeholder'=>'Owner Name'])->label(false); ?>
	   </div></div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Mobile Number</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'owner_mobile')->textinput(['class' => 'form-control','maxlength'=>'10','placeholder'=>'Mobile Number'])->label(false); ?>
	   </div></div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Range</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'vendor_range')->textinput(['class' => 'form-control','placeholder'=>'Distance(KM)'])->label(false); ?>
	   </div></div>
	   
</div>
<div class="col-md-6">
	   <div class="form-group row">
	   <label class="control-label col-md-4">Manager Name</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'manager_name')->textinput(['class' => 'form-control','autocomplete'=>'off','placeholder'=>'Manager Name'])->label(false); ?>
	   </div></div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Manager Mobile Number</label>
	   <div class="col-md-8">
	   <?= $form->field($model, 'manager_mobile')->textinput(['class' => 'form-control','autocomplete'=>'off','maxlength'=>'10','placeholder'=>'Manager Mobile Number'])->label(false); ?>
	   </div></div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Location</label>
	   <div class="col-md-8">
	   <?= $form->field($model, 'vendor_location')->textinput(['class' => 'form-control','autocomplete'=>'off','placeholder'=>'Location'])->label(false); ?>
	   </div></div>
	   	   <div class="form-group row">
	   <label class="control-label col-md-4">City</label>
	   <div class="col-md-8">
	   <?= $form->field($model, 'vendor_city')->textinput(['class' => 'form-control','autocomplete'=>'off','placeholder'=>'City'])->label(false); ?>
	   </div></div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">GSTIN</label>
	   <div class="col-md-8">
	   <?= $form->field($model, 'vendor_city')->textinput(['class' => 'form-control','autocomplete'=>'off','placeholder'=>'GSTIN'])->label(false); ?>
	   </div></div>
	   </div>
	   </div>
	   </div>
	   <div class="modal-footer">
		<?= Html::submitButton('Add Vendor', ['class'=> 'btn btn-add']); ?>

      </div> 


<?php ActiveForm::end() ?>
        
    

        
      </div>
    </div>
  </div>
  
    <div id="updatevendor" class="modal fade" role="dialog">
<div class="modal-dialog modal-xl" >
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Edit Vendor</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
	    <div class="modal-body" id="vendorbody">
		
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
function deletevendor(id){
//	var res = confirm('Are you sure want to delete??')
		    swal({
				title: "Are you sure want to delete??", 
				type: "warning",
				showCancelButton: true
		    })
		    	.then((result) => {
					if (result.value) {
					    var request = $.ajax({
						  url: "deletevendor",
						  type: "POST",
						  data: {id : id},
						}).done(function(msg) {
							
							location.reload();
						});
					}
				});

}
</script>
