<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>
<style>
.placeorder{background:#FD8B02;border:1px solid #FD8B02;color:#fff;}
.placeorder:hover{bacground:#28a745;border:1px solid #28a745;}
</style>
<link class="jsbin" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />
<script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/jquery-ui.min.js"></script>

    <header class="page-header">
            
          </header>
		            <section>
          <div class="col-lg-12">
            <div class="card">
              
              <div class="card-header d-flex align-items-center pt-0 pb-0">
                <h3 class="h4 col-md-6 pl-0 tab-title">Profile</h3>
				<div class="col-md-6 text-right pr-0">
				</div>
              </div>
              <div class="card-body">
			<?php	$form = ActiveForm::begin([
    		'id' => 'merchantupdate-form',
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
	   <label class="control-label col-md-4">Owner Name</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'name')->textinput(['class' => 'form-control','placeholder'=>'Enter Merchant Name'])->label(false); ?>
	   </div>
	   </div>

	   <div class="form-group row">
	   <label class="control-label col-md-4">Enter Mail id</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'email')->textinput(['class' => 'form-control','placeholder'=>'Enter Mail id','readonly'=>'readonly'])->label(false); ?>
	   </div></div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Merchant Type</label>	 
		<div class="col-md-8">	   
	   			      <?= $form->field($model, 'storetype')
				   ->dropdownlist(\yii\helpers\ArrayHelper::map(\app\models\Storetypes::find()
				  ->where(['type_status'=>'1'])
				  ->all(), 'ID', 'storetypename')
		,['prompt'=>'Select'])->label(false); ?>
	   </div></div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">State</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'state')->textinput(['class' => 'form-control','placeholder'=>'Enter State'])->label(false); ?>
	   </div></div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Location</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'location')->textinput(['class' => 'form-control','placeholder'=>'Enter Location'])->label(false); ?>
	   </div></div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Latitude</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'latitude')->textinput(['class' => 'form-control','placeholder'=>'Enter Latitude'])->label(false); ?>
	   </div></div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Verification Status:</label>
	   <div class="col-md-8">

				<p><?php echo $model['verify']==1 ? '<span style="color:green">Verified</span>' : 'Pending';?></p>
	   </div></div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">QR Scan Range (In Meters)</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'scan_range')->textinput(['class' => 'form-control','placeholder'=>'Enter Scan Range (In meters)'])->label(false); ?>
	   </div></div>
</div>
<div class="col-md-6">
	   	   <div class="form-group row">
	   <label class="control-label col-md-4">Mobile Number</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'mobile')->textinput(['class' => 'form-control','placeholder'=>'Enter Mobile Number'])->label(false); ?>
	   </div></div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Merchant Name</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'storename')->textinput(['class' => 'form-control','placeholder'=>'Enter Merchant Name'])->label(false); ?>
	   </div></div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Address</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'address')->textinput(['class' => 'form-control','placeholder'=>'Enter Address'])->label(false); ?>
	   </div></div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">City</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'city')->textinput(['class' => 'form-control','placeholder'=>'Enter City'])->label(false); ?>
	   </div></div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Serving Type</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'servingtype')->textinput(['class' => 'form-control','placeholder'=>'Ex: Fastfood,North indian'])->label(false); ?>
	   </div></div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Longitude</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'longitude')->textinput(['class' => 'form-control','placeholder'=>'Enter Longitude'])->label(false); ?>
	   </div></div>	   
	   <div class="form-group row">
	   <label class="control-label col-md-4">Description</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'description')->textarea(['class' => 'form-control','placeholder'=>'Enter Description'])->label(false); ?>
			       <input type="hidden" id="hiddenchkpwd" >
			      
	   </div></div>	
</div>
	   
				<div class="col-md-4">
					<div class="view view-tenth">
						<img id="logopreview" src="<?= 'http://'.$_SERVER['SERVER_NAME'].'/development/merchantimages/'.$model->logo;?>" />
							<div class="mask col-md-12">
								<div class="row">
									<div class="col-md-12">
									
									  <a class="w-100">
									  <div class="dropify-wrapper has-preview col-lg-12">
									  <div class="dropify-message">
									  <span >Logo Upload</span> 
									  <p>Drag and drop a file here or click</p>
									  <p class="dropify-error">Ooops, something wrong appended.</p>
									  </div>
									  <div class="dropify-errors-container">
									  </div>
									  <?= $form->field($model, 'logo')->fileinput(['class' => 'dropify logopreview',"onchange"=>"readURL(this,'logopreview');"])->label(false); ?>
										<button type="button" class="dropify-clear" onclick="removeImg('<?= $model->ID;?>','logo')">Remove</button>
									  
									  </div>
									  </a>
									</div>
								</div>
							</div>
					</div>
				</div>
				
				<div class="col-md-4">
					<div class="view view-tenth">
						<img id="qrlogopreview" src="<?= 'http://'.$_SERVER['SERVER_NAME'].'/development/merchantimages/'.$model->qrlogo;?>" />
						<div class="mask col-md-12">
							<div class="row">
								<div class="col-md-12">
					
								  <a class="w-100">
								  <div class="dropify-wrapper has-preview col-lg-12">
								  <div class="dropify-message">
								  <span class="">QR Logo Upload </span> 
								  <p>Drag and drop a file here or click</p>
								  
								  <p class="dropify-error">Ooops, something wrong appended.</p>
								  </div>
								  <div class="dropify-errors-container">
								  </div>
									<?= $form->field($model, 'qrlogo')->fileinput(['class' => 'dropify',"onchange"=>"readURL(this,'qrlogopreview');"])->label(false); ?>
								  <button type="button" class="dropify-clear" onclick="removeImg('<?= $model->ID;?>','qrlogo')">Remove</button>
								  
								  </div>
								  </a>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="col-md-4">
                <div class="view view-tenth">
					<img id="coverpicpreview" src="<?= 'http://'.$_SERVER['SERVER_NAME'].'/development/merchantimages/'.$model->coverpic;?>" />
  				    <div class="mask col-md-12">
						<div class="row">
							<div class="col-md-12">
					
							  <a class="w-100">
							  <div class="dropify-wrapper has-preview col-lg-12">
							  <div class="dropify-message">
							  <span class="">Cover Pic Upload </span> 
							  <p>Drag and drop a file here or click</p>
							  
							  <p class="dropify-error">Ooops, something wrong appended.</p>
							  </div>
							  <div class="dropify-errors-container">
							  </div>
								<?= $form->field($model, 'coverpic')->fileinput(['class' => 'coverpic',"onchange"=>"readURL(this,'coverpicpreview');"])->label(false); ?>					
								<button type="button" class="dropify-clear" onclick="removeImg('<?= $model->ID;?>','coverpic')">Remove</button>
							  
							  </div>
							  </a>
                    		</div>
					    </div>
					</div>
                </div>
				</div>
				
	</div> <!-- class row div end -->  
		
				
				
				
	   <div class="modal-footer">
		<?= Html::submitButton('Update Profile', ['class'=> 'btn btn-add']); ?>

      </div> 


<?php ActiveForm::end() ?>	   
	
<?php	$form = ActiveForm::begin([
    		'id' => 'change-password',
			'action' => 'changepassword',
			
		'options' => ['class' => 'form-horizontal','autocomplete'=>'off','wrapper' => 'col-xs-12',],
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
	   <label class="control-label col-md-4">Old Password</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'password', ['enableAjaxValidation' => true])->passwordinput(['class' => 'form-control' ,'autocomplete'=>'new-password','value'=>'','placeholder'=>'Enter Merchant Name'])->label(false); ?>
	   </div>
	   </div>

	   <div class="form-group row">
	   <label class="control-label col-md-4">New Password</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'newpassword')->passwordinput(['class' => 'form-control','autocomplete'=>'off','placeholder'=>'Enter New Password'])->label(false); ?>
	   </div></div>

</div>
<div class="col-md-6">	

	   <div class="form-group row">
	   <label class="control-label col-md-4">Confirm Password</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'confirmpassword')->passwordinput(['class' => 'form-control','placeholder'=>'Enter Confirm Password'])->label(false); ?>
	   </div>
	   </div>


</div>
</div>	
	   <div class="modal-footer">
		<?= Html::submitButton('Change Password', ['class'=> 'btn btn-add']); ?>

      </div> 

<?php ActiveForm::end() ?>	   
	
	</div>

		
        
          </div>
            </div>
            
            <div id="checkauth" class="modal fade" role="dialog">
<div class="modal-dialog modal-md" >
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Edit Merchant Profile</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
	    <div class="modal-body" id="checkauthbody">
			<div class="row">	
<div class="col-md-6">	

	   <div class="form-group row">
	   <label class="control-label col-md-4">Password</label>
	   <div class="col-md-8">
			<input type="password" id="authpwd" class="form=control" placeholder="Please Enter Password" autocomplete="off" onchange="focustoupdate()"  autofocus/>      
	   </div>
	   </div>



</div>
		</div>	
		
		  
		
	</div>
		<div class="modal-footer">
<button class="btn btn-danger btn-xs"  data-dismiss="modal">Cancel</button>
		<?= Html::submitButton('Confirm', ['class'=> 'btn btn-add placeorder','onclick'=>'authpassword()' ]); ?>


      </div>
	</div>
</div>
</div>
          
		          </section>
		<?php
$script = <<< JS
$("#merchant-storetype").select2({disabled:'readonly'});   
   $('form#merchantupdate-form').on('beforeSubmit', function(e) {
       
      
       if($("#hiddenchkpwd").val() == '1')
       {
                 
       return true;
           
       }else{

        $("#checkauth").modal('show'); 
        return false;
       }

    
}).on('submit', function(e){    // can be omitted
    
    if($("#hiddenchkpwd").val() == '1')
       {
                 
       return true;
           
       }else{
e.preventDefault();           
       }

 

});
JS;
$this->registerJs($script);
?>				  
				  
				  <script>
				  function focustoupdate(){
				      $(".placeorder").focus();
				  }
				  
				 function authpassword()
				 {

				    var pwd = $("#authpwd").val();
                    var request = $.ajax({
                        url: "chkpwd",
                        type: "POST",
                        data: {pwd : pwd},
                    }).done(function(msg) {
                        if(msg == '1'){
                         $("#hiddenchkpwd").val(1);
                            $("#merchantupdate-form").submit();
                        
                          //  location.reload();
                        }
                        else{
                            swal(
				'Error!',
				'Please Enter Valid Password.',
				'error'
			);
                            return false;
                        }
                    }); 
				 } 
				  
				       function readURL(input,idpreview) {
						   var _validFileExtensions = ["jpg", "jpeg", "gif", "png"];   
						   if(idpreview == 'coverpicpreview'){
							   var ext = $("#merchant-coverpic").val().split('.').pop();
						   }else if(idpreview == 'qrlogopreview'){
								var ext = $("#merchant-qrlogo").val().split('.').pop();
						   }
						   else if(idpreview == 'logopreview'){
								var ext = $("#merchant-logo").val().split('.').pop();				   
						   }
			
			
    if (jQuery.inArray(ext, _validFileExtensions)!='-1') {
		 if (input.files && input.files[0]) {
                
				var reader = new FileReader();

                reader.onload = function (e) {
                    $('#'+idpreview)
                        .attr('src', e.target.result)
                        .width('100%')
						.height('100%');
                        
                };

                reader.readAsDataURL(input.files[0]);
            }	
		}
        else{
		var msg =	swal(
				'Error!',
				'Only files with these extensions are allowed: png, jpg, gif.',
				'error'
			);
	return false;	
 		}
	   

            
        }
		function removeImg(id,col)
		{
				var cnfm = confirm("Are you sure you want to remove!");
				if(cnfm == true)
				{
					var request = $.ajax({
  url: "removeimage",
  type: "POST",
  data: {id : id,col:col},
}).done(function(msg) {
	location.reload();
});	
				}

		}
				  </script>
				  
				  
