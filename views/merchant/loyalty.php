
<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use aryelds\sweetalert\SweetAlert;
$actionId =  Yii::$app->controller->action->id;
$months = ['1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4',
           '5'=>'5', '6'=>'6', '7'=>'7', '8'=>'8',
           '9'=>'9','10'=>'10','11'=>'11','12'=>'12'
          ];
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
                <h3 class="h4 col-md-6 pl-0 tab-title">Loyalty</h3>
				<div class="col-md-6 text-right pr-0">
				<button type="button" class="btn btn-add btn-xs" id="myBtn" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus mr-1"></i> Add Loyalty</button>

				</div>
              </div>


              <div class="card-body">
                <div class="table-responsive">   
                  <table id="example" class="table table-striped table-bordered " width="100%">
                    <thead>
                      <tr>
                        <th>S No</th>
						<th>Days</th>
						<th>Time Period</th>
						<th>Repetition No</th>
						<th>Title</th>
						<th>View Details</th>
						<th>Action</th>
				   
                      </tr>
                    </thead>
					<tbody>
						<?php $x=1; 
							foreach($loyaltyDet as $loyalty){
						?>
                            <tr>
                                <td><?php echo $x; ?></td>
                                <td><?php echo $loyalty['days']; ?></td>
								<td><?php echo $loyalty['time_period'].' (Months)'; ?></td>
                                <td><?php echo $loyalty['repetition_no']; ?></td>
                                <td><?php echo $loyalty['title']; ?></td>
								<!--<td class="icons">-->
								<!--	<a onclick="updateemployee('<?= $empModel['ID'];?>')"><span class="fa fa-pencil"></span></a>-->
								<!--	<a onclick="deleteemployee('<?= $empModel['ID'];?>')"><span class="fa fa-trash"></span></a>-->
								<!--</td>-->
							    <td class="icons">
							        <a onclick="merchantLoyaltyDetails('<?php echo $loyalty['ID']; ?>')"><span class="fa fa-eye"></span></a> 
                                </td>
								<td><button class="btn btn-info btn-xs" onclick="merchantLoyalty('<?php echo $loyalty['ID']; ?>')">Run</button></td>
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
          <h4 class="modal-title">Add Loyalty</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
			<?php	$form = ActiveForm::begin([
    		    'id' => 'loyalty-form',
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
	                <label class="control-label col-md-4">Days</label>
	                <div class="col-md-8">
			            <?= $form->field($model, 'days')->textinput(['class' => 'form-control','autocomplete'=>'off'
				        ,'placeholder'=>'Days'])->label(false); ?>
	                </div>
	            </div>
	            <div class="form-group row">
	                <label class="control-label col-md-4">Time Period (Months)</label>
	                <div class="col-md-8">
	   	   			      <?= $form->field($model, 'time_period')
				            ->dropdownlist($months,['prompt'=>'Select']) 
				            ->label(false); ?>
			        </div>
			    </div>
	         <div class="form-group row">
	                <label class="control-label col-md-4">Repetition No</label>
	                <div class="col-md-8">
	   	   			      <?= $form->field($model, 'repetition_no')->textinput(['class' => 'form-control','autocomplete'=>'off'
				        ,'placeholder'=>'Repetition No'])->label(false); ?>
			        </div>
			    </div>
	        <div class="form-group row">
	                <label class="control-label col-md-4">Title</label>
	                <div class="col-md-8">
	   	   			       <?= $form->field($model, 'title')->textinput(['class' => 'form-control','autocomplete'=>'off'
				        ,'placeholder'=>'Title'])->label(false); ?>
			        </div>
			    </div>
			     <div class="form-group row">
	                <label class="control-label col-md-4">Message</label>
	                <div class="col-md-8">
	   	   			       <?= $form->field($model, 'message')->textinput(['class' => 'form-control','autocomplete'=>'off'
				        ,'placeholder'=>'Message'])->label(false); ?>
			        </div>
			    </div>
	        <div class="modal-footer">
		        <?= Html::submitButton('Add Employee', ['class'=> 'btn btn-add btn-hide']); ?>
            </div> 
            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>
</div>  


<div id="merchant_loyalty_details" class="modal fade" role="dialog">
<div class="modal-dialog modal-xl" >
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Loyalty Detials</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
	    <div class="modal-body" id="loyaltydetialsbody">
		
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
function merchantLoyalty(id){
   
  $.ajax({
      url: "calculateloyalty", 
      data:{
          id:id
      },
      method: "POST",
      success: function(result){
        alert(result);
  }
      
  });
}
function merchantLoyaltyDetails(id){
    $.ajax({
      url: "merchantloyaltydetails",
      type: "POST",
      data:{
          id:id
      },
      success: function(result){
        $('#loyaltydetialsbody').html('');  
        $('#loyaltydetialsbody').html(result);
        $('#merchant_loyalty_details').modal('show');
    }
  });
}

</script>
