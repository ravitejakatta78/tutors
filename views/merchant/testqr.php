<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use aryelds\sweetalert\SweetAlert;
?>
<link href="https://fonts.googleapis.com/css2?family=Kaushan+Script&family=Permanent+Marker&display=swap" rel="stylesheet"> 
<style>
  .qr-dwnld{padding: 5px 10px !important;}
</style>

<header class="page-header">
 
          </header>
          <section>
          <div class="col-lg-12">
            <div class="card">
              
              <div class="card-header d-flex align-items-center pt-0 pb-0">
                <h3 class="h4 col-md-6 pl-0 tab-title">Manage Table</h3>
				<div class="col-md-6 text-right pr-0">
				<button type="button" class="btn btn-add btn-xs" id="myBtn" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus mr-1"></i> Add Table</button>
				</div>
              </div>

              <div class="card-body">
                <div class="qr-card" id="html-content-holder" style="height:672px;">
                  <div class="qr-head">
                    <img src="<?= Yii::$app->request->baseUrl ?>/img/qrimg/food-q1.png" width="150">
                    <h4 style="font-family: 'Kaushan Script', cursive;margin-top:5px; ">Happy dining with FoodQ App</h4>
                  </div>
                  <div class="qr-body">
                      	    <?php $qrlogopath = 'http://foodqonline.com/development/merchantimages/'.$qrlogo; ?>
                    <img src="<?= $qrlogopath ?>" width="200" height="70">
                    <div class="">
                      <label class="scan-label">Scan and Order Your Food</label>
                    </div>
                    <div class="qr-img">
                        <?php $qrpath = 'http://foodqonline.com/development/qrimg/qrcodes/'.$qrpath.'.png'; ?>
                      <img src="<?= Yii::$app->request->baseUrl ?>/img/qrimg/qrcode.png" width="260">
                      <span class="tblenm">
                          <?php $tableExplode = explode(" ",$_REQUEST['tablename']); ?>
                        Table <br><?php echo $tableExplode[1]; ?>
                      </span>
                    </div>
                  </div>
                  <div class="socialmedia">
                    <div>
                      <img src="<?= Yii::$app->request->baseUrl ?>/img/qrimg/fb.png" width="30"> foodqonline
                      <img src="<?= Yii::$app->request->baseUrl ?>/img/qrimg/insta.png" width="30"> foodq_insta
                      <img src="<?= Yii::$app->request->baseUrl ?>/img/qrimg/youtube.png" width="30"> foodq technologies
                    </div>
                  </div>
                  <div class="qr-dwnld">
                    <div class="row">
                      <div class="col-md-2">
                        <img src="<?= Yii::$app->request->baseUrl ?>/img/qrimg/green1.png" width="100%">
                      </div>
                      <div class="col-md-8 text-right">
                        <div class="row">
                          <div class="col-md-8">
                          <h6 style="font-weight: normal;margin-bottom:4px;">Scan Here to <br> Download FoodQ App </h6>
                        </div>
                        <div class="col-md-4 text-left">
                          <img src="<?= Yii::$app->request->baseUrl ?>/img/qrimg/google.png" width="100" style="border-radius: 4px;">
                        </div>
                      </div>
                        <div style="font-size: 12px;">
                          Ph: +91 97048 40417, +91 90329 08096
                        </div>
                      </div>
                      <div class="col-md-2">
                        <img src="<?= Yii::$app->request->baseUrl ?>/img/qrimg/favicon1.png" width="100%">
                      </div>
                    </div>
                  </div>
                  <div class="qr-foot text-center">
                    <h5>Mail: Support@foodqonline.com</h5>
                  </div>
                </div>
                <div class="clearfix"></div>
              </div>
			  
            </div>
          </div>

  <input id="btn-Preview-Image" type="button"
				value="Preview" style="display:none" /> 
		
	 

	<br/> 
	
	<h3 style="display:none">Preview :</h3> 
	
	<div style="display:none" id="previewImage"></div> 

 <a id="btn-Convert-Html2Image" href="#" class="btn btn-primary"> 
		Download Here
	</a>

        </section>
		<?php
$script = <<< JS
    $('#example').DataTable();
JS;
$this->registerJs($script);
?>
<script>

$(document).ready(function() { 

			// Global variable 
			var element = $("#html-content-holder"); 
		
			// Global variable 
			var getCanvas; 

//			$("#btn-Preview-Image").on('click', function() { 
				html2canvas(element, { 
					onrendered: function(canvas) { 
						$("#previewImage").append(canvas); 
						getCanvas = canvas; 
					} 
				}); 
	//		}); 

			$("#btn-Convert-Html2Image").on('click', function() { 
				var imgageData = 
					getCanvas.toDataURL("image/png"); 
			
				// Now browser starts downloading 
				// it instead of just showing it 
				var newData = imgageData.replace( 
				/^data:image\/png/, "data:application/octet-stream"); 
			
				$("#btn-Convert-Html2Image").attr( 
				"download", "GeeksForGeeks.png").attr( 
				"href", newData); 
			});
			
		});

</script>
 	<script src= "https://files.codepedia.info/files/uploads/iScripts/html2canvas.js"> 