
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
                <h3 class="h4 col-md-6 pl-0 tab-title">Ingredients List</h3>
				<div class="col-md-6 text-right pr-0">
				<button type="button" class="btn btn-add btn-xs" id="myBtn" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus mr-1"></i> Add Ingredients</button>

				</div>
              </div>


              <div class="card-body">
                <div class="table-responsive">   
                  <table id="example" class="table table-striped table-bordered ">
                    <thead>
                      <tr>
                        <th>S No</th>
						<th>Ingredient Name</th>
						<th>Ingredient Type</th>
			<!--			<th>Ingredient Price</th> -->
						<th>Photo</th>
						<th>Stock Alert</th>
						<th>Status</th>
						<th>Action</th>
                      </tr>
                    </thead>
		    <tbody>
								<?php 
								if(count($ingredientsModel) > 0){
								$x=1; 
									foreach($ingredientsModel as $ingredientsModel){
								?>
                                  <tr>
                                 <td>
                                                    <?php echo $x; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $ingredientsModel['item_name']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $ingredientsModel['item_type']; ?>
                                                    </td>
                                        <!--            <td>
                                                        <?php //echo $ingredientsModel['item_price']; ?>
                                                    </td> -->
                                                    <td>
														<img  src="<?= Yii::$app->request->baseUrl.'/uploads/ingredients/'.$ingredientsModel['photo'];?>" style="height:50px" />
                                                    </td>
                                                    <td>
                                                        <?php echo $ingredientsModel['stock_alert']; ?>
                                                    <td>
                                                        <label class="switch">
                                                            <input type="checkbox" <?php   if($ingredientsModel[ 'status']=='1' ){ echo 'checked';}?> 
															onChange="changestatus('ingredients',
                                                            <?php echo $ingredientsModel['ID'];?>);"> <span class="slider round"></span> </label>
                                                    </td>
                                                    <td class="icons"><a onclick="updateingredient('<?= $ingredientsModel['ID'];?>')"><span class="fa fa-pencil"></span>
													</a></td>
										</tr>			
								<?php $x++; } }?>
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
          <h4 class="modal-title">Add Ingredient</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
			<?php	$form = ActiveForm::begin([
    		'id' => 'ingrdeient-form',
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
	   <label class="control-label col-md-4">Ingredient Name</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'item_name', ['enableAjaxValidation' => true])->textinput(['class' => 'form-control','autocomplete'=>'off','placeholder'=>'Ingredient Name'])->label(false); ?>
	   </div>
	   </div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Ingredient Type</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'item_type')->textinput(['class' => 'form-control','autocomplete'=>'off','placeholder'=>'Ingredient Type'])->label(false); ?>
	   </div></div>
<!--	   <div class="form-group row">
	   <label class="control-label col-md-4">Ingredient Price</label>
	   <div class="col-md-8">
			      <?php // $form->field($model, 'item_price')->textinput(['class' => 'form-control','autocomplete'=>'off','placeholder'=>'Ingredient Price'])->label(false); ?>
	   </div></div> -->
	   
</div>
<div class="col-md-6">
	   <div class="form-group row">
	   <label class="control-label col-md-4">Photo</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'photo')->fileinput(['class' => 'form-control'])->label(false); ?>
	   </div></div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Stock Alert</label>
	   <div class="col-md-8">
	   <?= $form->field($model, 'stock_alert')->textinput(['class' => 'form-control','autocomplete'=>'off','placeholder'=>'Stock Alert'])->label(false); ?>
	   </div></div>
	   </div>
	   </div>
	   </div>
	   <div class="modal-footer">
		<?= Html::submitButton('Add Ingredient', ['class'=> 'btn btn-add']); ?>

      </div> 


<?php ActiveForm::end() ?>
        
    

        
      </div>
    </div>
  </div>
  
    <div id="updateingredient" class="modal fade" role="dialog">
<div class="modal-dialog modal-xl" >
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Edit Ingredient</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
	    <div class="modal-body" id="ingredientbody">
		
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
</script>
