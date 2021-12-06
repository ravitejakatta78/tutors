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
                <h3 class="h4 col-md-6 pl-0 tab-title">Gallery</h3>
				<div class="col-md-6 text-right pr-0">
				<button type="button" class="btn btn-add btn-xs" id="myBtn" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus mr-1"></i> Add Gallery</button>
				</div>
              </div>
              <div class="card-body">
                <div class="table-responsive">   
                  <table id="example" class="table table-striped table-bordered ">
                    <thead>
                      <tr>
									<th>S No</th> 
									<th>Image</th>  
									<th>Status</th>  									
									<th>Action</th>
                      </tr>
                    </thead>
		    <tbody>
								<?php $x=1; 
									foreach($galleryModel as $galleryModel){
								?>
                                  <tr>
                                 		<td><?php echo $x;?></td>                      
									<td><img src="<?= Yii::$app->request->baseUrl.'/uploads/merchantgallery/'.$galleryModel['image'];?>" style="width:100px;height:100px;" /></td>   
														<td>
<label class="switch">
					<input type="checkbox" <?php if($galleryModel['status']=='1'){ echo 'checked';}?> onChange="changestatus('gallery',<?php echo $galleryModel['ID'];?>);">
					<span class="slider round"></span> 

					</label>
					</td>
                                    <td class="icons"><a onClick="deletegallery('<?=$galleryModel['ID'] ?>')"   ><span class="fa fa-trash"></span></a></td>

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
          <h4 class="modal-title">Add Gallery</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
			
<?php	$form = ActiveForm::begin([
    		'id' => 'food-categery-form',
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
	   <label class="control-label col-md-4">Upload Image</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'image')->fileinput(['class' => 'form-control'])->label(false); ?>
	   </div>
	   </div>
</div>

	   </div>
	   </div>
	   <div class="modal-footer">
		<?= Html::submitButton('Add Gallery', ['class'=> 'btn btn-add btn-hide']); ?>

      </div> 


<?php ActiveForm::end() ?>

        </section>
		<?php
$script = <<< JS
    $('#example').DataTable();
JS;
$this->registerJs($script);




?>
<script>
function deletegallery(id){
	var res = confirm('Are you sure want to delete??')
	if(res == true)
	{
var request = $.ajax({
  url: "deletegallery",
  type: "POST",
  data: {id : id},
}).done(function(msg) {
	
	location.reload();
});	
		
	}
}
</script>