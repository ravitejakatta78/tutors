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
                <h3 class="h4 col-md-6 pl-0 tab-title">Reservation History</h3>
				<div class="col-md-6 text-right pr-0">
<!--				<button type="button" class="btn btn-add btn-xs" id="myBtn" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus mr-1"></i> Add Gallery</button>
-->				</div>
              </div>
              <div class="card-body">
			  <form class="form-horizontal" method="POST" action="reservationhistory">
                   <div class="row">
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
				  
				  
				  <div class="col-md-3">
                  <div class="form-group row">
                    <label class="control-label col-md-4 pt-1">Status:</label>
					<div class="col-md-8">
                  <div class="input-group mb-3 mr-3">
                  <select id="reservationstatus" name="reservationstatus">
				  <option value="" <?php if($reservationstatus == '') { echo "selected"; } ?>>All</option>
				  <option value="0" <?php if($reservationstatus == '0') { echo "selected"; } ?>>Pending</option>
				  <option value="1" <?php if($reservationstatus == '1') { echo "selected"; } ?>>Approved</option>
				  <option value="2" <?php if($reservationstatus == '2') { echo "selected"; } ?>>Rejected</option>
				  				  
				</select> 
				 </div>
                </div>
                  </div> 
				  </div>
				  
				  
				  
 
				  <div class="col-md-3 ">
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
                                    <th>User</th>
                                    <th>Table</th> 
									<th>Date</th> 
									<th>Time</th> 
									<th>Status</th> 
                      </tr>
                    </thead>
		    <tbody>
								<?php $x=1; 
										foreach($restablereservation as $productlist){
								?>
                                  <tr>
                                 	<td><?php echo $x;?></td>
                                 	<td><?php echo \app\helpers\Utility::user_details($productlist['user_id'],'name');?></td> 
                                 	<td><?php echo \app\helpers\Utility::table_details($productlist['table_id'],'name');?></td>
                                 	<td><?php echo $productlist['bookdate'];?></td>
                                 	<td><?php echo $productlist['booktime'];?></td>  
									 <td> 									 
										<select class="form-control changestatus" name="status" data-orderid="<?php echo $productlist["ID"];?>">
											<option value="">Select</option>
											<option value="0" <?php if($productlist['status']==''||$productlist['status']=='0'){ echo 'selected';}?>>Pending</option>
											<option value="1" <?php if($productlist['status']=='1'){ echo 'selected';}?>>Approved</option>
											<option value="2" <?php if($productlist['status']=='2'){ echo 'selected';}?>>Reject</option>
										</select>
									</td> 
									</tr>
									<?php $x++; }?>                     </tbody>
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
