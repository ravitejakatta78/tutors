
<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use aryelds\sweetalert\SweetAlert;
$actionId = Yii::$app->controller->action->id;
?>
<script src="<?= Yii::$app->request->baseUrl.'/js/bootstrap-typeahead.js'?>"></script>
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
              <?= \Yii::$app->view->renderFile('@app/views/merchant/_reporthorizontal.php',['actionId'=>$actionId]); ?>
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header d-flex align-items-center pt-0 pb-0">
                <h3 class="h4 col-md-6 pl-0 tab-title">Consumption Report</h3>
				<div class="col-md-6 text-right pr-0">
			
				</div>
              </div>
              <div class="card-body">
			  <form class="form-horizontal" method="POST" action="consumption">
			  <div class="row stock">
			            <span class="float-left">Select Range: </span>
			            <div class="dropdown no-arrow">
					<select class="form-control">
						<option value="1">Today</option>
						<option value="2">Week</option>
						<option value="3">Month</option>
						<option value="3">Customize</option>
					</select>
				</div>

                  
				  </div>
                </form>
                <div class="table-responsive">   
                  <table id="example" class="table table-striped table-bordered ">
                    <thead>
                     <tr>
                        <tr>
                        <th rowspan="2">Raw Material</th>
                        <th rowspan="2">Unit</th>
                        <th colspan="2">26 Oct 2020</th>
                        <th colspan="2">27 Oct 2020</th>
                        <th colspan="2">28 Oct 2020</th>
                        <th colspan="2">29 Oct 2020</th>
                        <th colspan="2">30 Oct 2020</th>
                    </tr>
                    <tr>
                        <td>Consumption</td>
                        <td>AvgPrice</td>
                        <td>Consumption</td>
                        <td>AvgPrice</td>
                        <td>Consumption</td>
                        <td>AvgPrice</td>
                        <td>Consumption</td>
                        <td>AvgPrice</td>
                        <td>Consumption</td>
                        <td>AvgPrice</td>
                    </tr>	
						
                     
                    </thead>
					<tbody>
					    <tr>
					        <td></td>
					        <td></td>
					        <td></td>
					        <td></td
					        <td></td>
					        <td></td
					        <td></td>
					        <td></td
					        <td></td>
					        <td></td>
					        <td></td>
					        <td></td>
					        <td></td>
					        <td></td>
					        <td></td>
					        
					    </tr>
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
$(document).ready(function(){
	
	$('#example').DataTable();
});
</script>
