
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
                <h3 class="h4 col-md-6 pl-0 tab-title">Current Stock</h3>
				<div class="col-md-6 text-right pr-0">
			
				</div>
              </div>
              <div class="card-body">
			  <form class="form-horizontal" method="POST" action="ingredientstock">
			  <div class="row stock">
			  <div class="col-md-3">
                  <div class="form-group row">
                    <label class="control-label col-md-4 pt-2">Start Date:</label>
					<div class="col-md-8">
                  <div class="input-group mb-3 mr-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                  </div>
                  <input type="text" class="form-control datepicker1" name="sdate" placeholder="Start Date" value="<?= $sdate ; ?>">
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
                  <input type="text" class="form-control datepicker2" name="edate" placeholder="End Date" value="<?= $edate ; ?>">
                </div>
				</div>
                  </div>
				  </div>
				<div class="col-md-2">
                  <div class="form-group row">
                    <label class="control-label col-md-4 pt-1">Type:</label>
					<div class="col-md-8">
                  <div class="input-group mb-3 mr-3">
                 <select name="type">
				
				 <option value="detail" <?php if($type == 'detail') { echo 'selected';}?>>Detail</option>
				 <option value="abstract" <?php if($type == 'abstract') { echo 'selected';}?>>Abstract</option>
				 </select>
                 
				 </div>
                </div>
                  </div> 
				  </div>
				  <div class="col-md-3">
			   <div class="form-group row">
                    <label class="control-label col-md-4 pt-1"> Category:</label>
                    <div class="col-md-8">
					<div class="input-group mb-3 mr-3">
					<select name="fc_id" id="fc_id"> 
					<option value="">Select</option>
					<?php  foreach($resStock as $fc){ ?>
					<option value="<?php echo $fc['ID']; ?>" <?php if($fcid == $fc['ID']){ ?> selected <?php }?>><?php echo $fc['ingredient_name']; ?></option>
					<?php } ?>
					</select>
				</div>
               </div>
               </div>
               </div>
				  <div class="col-md-1">
                  <div class="form-group pt-3">
                    <input type="submit" value="Search" class="btn btn-add btn-sm btn-search"/>
                  </div>
                  </div>
				  </div>
                </form>
                <div class="table-responsive">   
                  <table id="example" class="table table-striped table-bordered ">
                    <thead>
                      <tr>
                        <th>S No</th>
						<?php if($type == 'detail'){ ?>
						<th> Last Updated Date</th>							
						<?php } ?>

						<th>Ingredient Name</th>
						<th>Opening Stock</th>
						<th>Stock In</th>
						<th>Stock Out</th>
						<th>Wastage</th>
						<th>Closing Stock</th>
										   
                      </tr>
                    </thead>
					<tbody>
						<?php for($i=0;$i<count($resStock);$i++) { ?>
						<tr>
						<td><?= $i+1;?></td>
												<?php if($type == 'detail'){ ?>
						<td><?= $resStock[$i]['created_on'];?></td>
												<?php } ?>
						<td><?= $resStock[$i]['ingredient_name'];?></td>
						<td><?= round($resStock[$i]['opening_stock']/1000,2);?></td>
						<td><?= round($resStock[$i]['stock_in']/1000,2);?></td>
						<td><?= round($resStock[$i]['stock_out']/1000,2);?></td>
						<td><?= round($resStock[$i]['wastage']/1000,2);?></td>
						<td><?= round($resStock[$i]['closing_stock']/1000,2);?></td>
						</tr>
						<?php } ?>
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
          <h4 class="modal-title">Add Pilot</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
		
    

        
      </div>
    </div>
  </div>
  
    <div id="updatepilot" class="modal fade" role="dialog">
<div class="modal-dialog modal-xl" >
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Edit Pilot</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
	    <div class="modal-body" id="pilotbody">
		
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
