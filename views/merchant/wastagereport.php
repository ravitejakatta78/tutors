
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
                <h3 class="h4 col-md-6 pl-0 tab-title">Wastage Report</h3>
				<div class="col-md-6 text-right pr-0">
			
				</div>
              </div>
              <div class="card-body">
			  <form class="form-horizontal" method="POST" action="consumption">
			  <div class="row stock">
			  <div class="col-md-3">
			  <div class="form-group row">
                    <label class="control-label col-md-4 pt-2">Start Date:</label>
                    	<div class="col-md-8">
                  <div class="input-group mb-3 mr-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                  </div>
                  <input type="text" class="form-control datepicker1" id="sdate" name="edate" placeholder="End Date" value="<?= $sdate ; ?>">
                </div>
				</div>
				</div>
				</div>
				 <div class="col-md-3">
			   <div class="form-group row">
                    <label class="control-label col-md-4 pt-2">End Date:</label>
                    <div class="col-md-8">
                  <div class="input-group mb-3 mr-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                  </div>
                  <input type="text" class="form-control datepicker2" id="edate" name="edate" placeholder="End Date" value="<?= $edate ; ?>">
                </div>
				</div>
				</div>
				</div>
				<div class="col-md-3">
			   <div class="form-group row">
                    <label class="control-label col-md-4 pt-1">Type:</label>
                    <div class="col-md-8">
					<div class="input-group-prepend mb-3 mr-3">
					<select name="type" id="type">
					<option value="">Select Any</option>
					<option value="1">Ingredient wise</option>
					<option value="2">Category Wise</option>
					</select>
				</div>
               </div>
               </div>
               </div>
                <div class="col mt-3">
                  <div class="form-group">
                    <button type="button" onclick="billviews()" class="btn btn-add btn-sm btn-search">Search</button>
                  </div>
                  </div>
                  <div class="col">
                    <div class="form-group text-center">
                        <button class="exportToExcel btn btn-add btn-sm" >Download</button>
                    </div>
                    </div>
                </form>
                <div class="table-responsive">   
                  <table id="example" class="table table-striped table-bordered ">
                    <thead>
                     <tr>
                        <tr>
                        <th rowspan="2">Item</th>
                        <th rowspan="2">Category</th>
                        <th colspan="2" class="text-center">Today</th>
                        <th colspan="2" class="text-center">Yesterday</th>
                        <th colspan="2" class="text-center">Last 7 days</th>
                        <th colspan="2" class="text-center">Last 30 days</th>
                    </tr>
                    <tr>
                        <td>Qnty</td>
                        <td>AvgPrice</td>
                        <td>Qnty</td>
                        <td>AvgPrice</td>
                        <td>Qnty</td>
                        <td>AvgPrice</td>
                        <td>Qnty</td>
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
