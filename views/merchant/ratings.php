<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
<header class="page-header">
            
          </header>
          <section>
          <div class="col-lg-12">
            <div class="card">
              
              <div class="card-header d-flex align-items-center pt-0 pb-0">
                <h3 class="h4 col-md-6 pl-0 tab-title">Feedback Rating</h3>
				<div class="col-md-6 text-right pr-0">
				</div>
              </div>
              <div class="card-body">
			  <form class="form-inline" method="POST" action="rating">
                  <div class="form-group">
                    <label class="control-label">Start Date:</label>
                  <div class="input-group mb-3 mr-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                  </div>
                  <input type="text" class="form-control datepicker1" name="sdate" placeholder="Start Date" value="<?= $sdate ; ?>">
                </div>
                  </div>

                  <div class="form-group">
                    <label class="control-label">End Date:</label>
                  <div class="input-group mb-3 mr-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                  </div>
                  <input type="text" class="form-control datepicker2" name="edate" placeholder="End Date" value="<?= $edate ; ?>">
                </div>
                  </div>
                  <div class="form-group">
                    <input type="submit" value="Search" class="btn btn-add btn-sm btn-search"/>
                  </div>
                  
                </form>
   
                  <table id="example" class="table table-striped table-bordered ">
                    <thead>
                      <tr>
						<th>S No</th>
                        <th>Customer</th>  
						<th>Rating</th>
						<th>Message</th> 
						<th>Date</th> 
                       </tr>
                    </thead>
					<tbody>
										<?php 
								if(!empty($rating)){
								$x=1; 
									foreach($rating as $rating){
												?>
                                  <tr>
                                 	<td><?php echo $x;?></td>
                                 	<td><?php echo $rating['user_name'];?></td> 
                                 	<td><?php echo round($rating['rating'],1);?></td>
                                 	<td><?php echo $rating['feedback'];?></td>  
                                 	<td><?php echo date('d M Y',strtotime($rating['reg_date']));?></td>  
									 
									</tr>
									<?php $x++; }?>
									<?php  }?>

                    </tbody>
                  </table>
		  

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
