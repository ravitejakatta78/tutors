<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use aryelds\sweetalert\SweetAlert;
$actionId = Yii::$app->controller->action->id;

?>
<script src="<?= Yii::$app->request->baseUrl.'/js/typeahead.js'?>"></script>
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
	<div class="loading" style="display:none">
  		<img src="<?php echo Url::base(); ?>/img/load.gif" >
  	</div>
 <section>
 <?= \Yii::$app->view->renderFile('@app/views/merchant/_manageitems.php',['actionId'=>$actionId]); ?>  

          <div class="col-lg-12">
            <div class="card">
              <div class="card-header d-flex align-items-center pt-0 pb-0">
                <h3 class="h4 col-md-6 pl-0 tab-title">Banner List</h3>
				<?php if(@$bannerStatusCount['1'] < 3 ) { ?>
				<div class="col-md-6 text-right pr-0">
	<button type="button" class="btn btn-add btn-xs" id="myBtn" data-toggle="modal" ><i class="fa fa-plus mr-1"></i> Add Banner</button>			
				</div>
				<?php } ?>
              </div>


              <div class="card-body">
                <div class="table-responsive">   
                  <table id="example" class="table table-striped table-bordered ">
                    <thead>
                      <tr>
                        <th>S No</th> 
                        <th>banner image</th>  
                        <th>Status</th>            
						<th>Action</th>
                      </tr>
                    </thead>
		    <tbody>
								<?php $x=1; 
									foreach($bannerdet as $banner){
								?>
                                  <tr>
                                 	<td><?php echo $x ;?></td>   
									<td><img  width="100" height="100"  src="<?= '../../../bannerimage/'.$banner['image'];?>" /></td>
									<td><label class="switch">										  
											<input type="checkbox" <?php if($banner['status'] == '1'){ echo 'checked';}?> onChange="changeBannerStatus('banners',<?php echo $banner['ID'];?>);">										  
											<span class="slider round"></span>										
										</label>									
									</td>
                                    
									   <td class="icons">
									    <!--   <a onClick="editbanner('<?=$banner['ID'] ?>')"   ><span class="fa fa-edit"></span></a> -->
									       <a onClick="deletebanner('<?=$banner['ID'] ?>')"   ><span class="fa fa-trash"></span></a>
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
          <h4 class="modal-title">Add Banner</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
			
<?php	$form = ActiveForm::begin([
    		'id' => 'banner-form',
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
		<?= Html::submitButton('Add Banner', ['class'=> 'btn btn-add btn-hide']); ?>

      </div> 


<?php ActiveForm::end() ?>
</div>
</div>
</div>	

    <div id="editbanner" class="modal fade" role="dialog">
<div class="modal-dialog modal-lg" >
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Edit Banner</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
	    <div class="modal-body" id="editbannerbody">
		
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
function deletebanner(id){
//	var res = confirm('Are you sure want to delete??')
		    swal({
				title: "Are you sure want to delete??", 
				type: "warning",
				showCancelButton: true
		    })
		    	.then((result) => {
					if (result.value) {
					    var request = $.ajax({
						  url: "deletebanner",
						  type: "POST",
						  data: {id : id},
						}).done(function(msg) {
							
							location.reload();
						});
					}
				});

}

$("#myBtn").click(function(){
	var bannerStatusCountJson = '<?= json_encode($bannerStatusCount); ?>';
	var bannerStatusCount = JSON.parse(bannerStatusCountJson);

	if(bannerStatusCount['1'] < 3){
		$('#myModal').modal('toggle');
	}
	else{
		swal(
				'Warning!',
				'More than 3 Active Banners are restricted!!',  
				'warning'
			);
	}
	
});

function changeBannerStatus(tablename,tableid){
			 $.ajax({
				 type: 'post',
				 url: 'changeproductstatus',
				 data: {
				 tablename:tablename,
				 tableid:tableid
				 },		
				 success: function (response) {
					/* silence is golden */ 
					window.location.href = 'bannerdetails';
				 }	 
				 });
}
</script>
