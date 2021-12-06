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
              <?= \Yii::$app->view->renderFile('@app/views/merchant/_managetable.php',['actionId'=>$actionId]); ?>
          <div class="col-lg-12">
            <div class="card">
              
              <div class="card-header d-flex align-items-center pt-0 pb-0">
                <h3 class="h4 col-md-6 pl-0 tab-title">Manage Table</h3>
				<div class="col-md-6 text-right pr-0">
			
				</div>
              </div>
              <div class="card-body">
                <div class="table-responsive">   
                  <table id="example" class="table table-striped table-bordered ">
                    <thead>
                      <tr>
						<th>S No</th>
						<th>Section Name</th>
						<th>Download Menu QR Code</th>
						
                      </tr>
                    </thead>
		    <tbody>
								<?php $x=1; 
									foreach($food_sections as $food_sections){
									$onetabledet = \app\models\Tablename::find()->where(['section_id' => $food_sections['ID']])->one();
									
									if(!empty($onetabledet)){
									
								?>
                                  <tr>
                                 	<td><?php echo $x;?></td>                     
									<td><?php echo $food_sections['section_name'];?></td>		
<td class="icons"><a class="label label-xs label-warning "   
									href="../../../test.php?table=<?= $onetabledet['ID']; ?>&tablename=<?= $onetabledet['name'] ?>&userid=<?= $merchantdetails['ID']; ?>&qrlogo=<?= $merchantdetails['qrlogo']; ?>&qrtype=menuqr"	target="_blank" ><span class="fa fa-download"></span></a>
</td>									


                                                                  </tr>
                                <?php $x++; } }?>
									
                       </tbody>
                  </table>
		  
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

</script>