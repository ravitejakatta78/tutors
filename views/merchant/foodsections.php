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
            //'timer' => (!empty($message['timer'])) ? $message['timer'] : 4000,
            'showConfirmButton' =>  (!empty($message['showConfirmButton'])) ? $message['showConfirmButton'] : true
        ]
    ]);
}
?>
          </header>
          <section>
              <?= \Yii::$app->view->renderFile('@app/views/merchant/_manageitems.php',['actionId'=>$actionId]); ?>
          <div class="col-lg-12">
            <div class="card">
              
              <div class="card-header d-flex align-items-center pt-0 pb-0">
                <h3 class="h4 col-md-6 pl-0 tab-title">Manage Menu Sections</h3>
				<div class="col-md-6 text-right pr-0">
				<button type="button" class="btn btn-add btn-xs" id="myBtn" <?php if(count($foodsections) < 3 ) { ?> data-toggle="modal" data-target="#myModal" <?php } ?>>
				    <i class="fa fa-plus mr-1"></i> Add Menu Section</button>
				</div>
              </div>
              <div class="card-body">
                <div class="table-responsive">   
                  <table id="example" class="table table-striped table-bordered ">
                    <thead>
                      <tr>
						<th>S No</th>
						<th>Section Name</th> 
						<th>Action</th>
                      </tr>
                    </thead>
		    <tbody>
								<?php $x=1; 
									foreach($foodsections as $foodsections){
								?>
                                  <tr>
                                 	<td><?php echo $x;?></td>                     
									<td><?php echo $foodsections['food_section_name'];?></td>  
									<td  class="icons">
									<a onclick="updatefc('<?php echo $foodsections['ID']; ?>')"  ><span class="fa fa-pencil"></span></a>
									</td>
                                                                  </tr>
									<?php $x++; }?>
                       </tbody>
                  </table>
		  
                </div>
              </div>
            </div>
          </div>

<div class="modal fade" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Add Menu Section</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
				<?php	$form = ActiveForm::begin([
    		'id' => 'add-table-form',
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
		<label class="control-label col-md-4">Menu Section</label>
		<div class="col-md-8">
		 <?= $form->field($model, 'food_section_name')->textinput(['class' => 'form-control','autocomplete'=>'off','placeholder'=>'Menu Section'])->label(false); ?>
		</div>
		</div>
		
		
   <div class="modal-footer">
		<?= Html::submitButton('Add Menu Section', ['class'=> 'btn btn-add btn-hide']); ?>
      </div> 
		<?php ActiveForm::end() ?>
		
	    </div>
        
        
        
      </div>
    </div>
  </div>
  
    <div id="editfc" class="modal fade" role="dialog">
<div class="modal-dialog " >
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Edit Menu Section</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
	    <div class="modal-body" id="editfcbody">
		
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
function deletetable(id){
//	var res = confirm('Are you sure want to delete??')
		    swal({
				title: "Are you sure want to delete??", 
				type: "warning",
				showCancelButton: true
		    })
		    	.then((result) => {
					if (result.value) {
					    var request = $.ajax({
						  url: "deletetable",
						  type: "POST",
						  data: {id : id},
						}).done(function(msg) {
							
							location.reload();
						});
					}
				});

}
function qrdownload(tableid,tablename,userid,qrlogo){


 var request = $.ajax({
						  url: "qrdownload",
						  type: "POST",
						  data: {tableid : tableid,userid:userid},
						}).done(function(msg) {
							alert(msg);
							location.reload();
						});


}
function qrpreview()
{
	
}

function qrpreview(tableid,tablename,userid,qrlogo,qrpath){


        var form=document.createElement('form');
        form.setAttribute('method','post');
        form.setAttribute('action','testqr');
        form.setAttribute('target','_blank');

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "tableid");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", tableid);
    form.appendChild(hiddenField);
	
    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "tablename");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", tablename);
    form.appendChild(hiddenField);	

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "userid");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", userid);
    form.appendChild(hiddenField);	
	
    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "qrlogo");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", qrlogo);
    form.appendChild(hiddenField);		

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "qrpath");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", qrpath);
    form.appendChild(hiddenField);		
	
    document.body.appendChild(form);
    form.submit();    


}
</script>