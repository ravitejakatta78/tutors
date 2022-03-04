
<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use app\helpers\MyConst;

$actionId = Yii::$app->controller->action->id;
?>
<header class="page-header">

          </header>
          <section>
              <?= \Yii::$app->view->renderFile('@app/views/merchant/_orders.php',['actionId'=>$actionId]); ?>
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header d-flex align-items-center pt-0 pb-0">
                <h3 class="h4 col-md-6 pl-0 tab-title">Order List</h3>
				<div class="col-md-6 text-right pr-0">
			
				</div>
              </div>


              <div class="card-body">
			  <form class="form-inline" method="POST" action="orders">
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
									<th>Date</th> 
									<th>Order Id</th>
									<th>Table / Seat No</th>
									<th>Order Type</th>
									<th>Pay Type</th>
									<th>User Name</th>
									<th>Mobile No</th>
									<th>Order Process</th> 
									<th>Amount</th> 
									<th>Tax</th> 
									<th>Tip</th> 
							<!--		<th>Subscription</th> -->
									<th>Total amount</th> 
									<th>Action</th>
								  </tr>
                    </thead>
		    <tbody>
								<?php $x=1; 
									foreach($orderModel as $orderModel){
								?>
                                  <tr>
                                 <td><?php echo $x;?></td>
									<td><?php echo $orderModel['reg_date'];?></td> 
									<td><?php echo $orderModel['order_id'];?></td>
									<td><?php echo Utility::table_details($orderModel['tablename'],'name');?></td>
									<td><?php if($orderModel['ordertype'] == 2 ) { echo "Offline" ; }else{ echo "Online"; }?></td>	
									<td><?php !empty($orderModel['paymenttype']) ? MyConst::PAYMENT_METHODS[$orderModel['paymenttype']] : null ; ?></td>
									<td><?php  echo  Utility::user_details($orderModel['user_id'],'name');   ?></td>
									<td><?php  echo Utility::user_details($orderModel['user_id'],'mobile');  ?></td>
									<td>
                                        <span class="pending">
                                            <?php
                                            if($orderModel['orderprocess'] == 1 && !empty($orderModel['preparetime']) && empty($orderModel['preparedate'])){
                                                echo 'Preparing';
                                            }
                                            else if($orderModel['orderprocess'] == 1 && !empty($orderModel['preparetime']) && !empty($orderModel['preparedate'])){
                                                echo 'Prepared';
                                            }
                                            else{
                                                echo Utility::orderstatus_details($orderModel['orderprocess']);
                                            }
                                            ?></span>
                                    </td>
									<td><?php echo $orderModel['amount'];?></td>
									<td><?php echo $orderModel['tax'];?></td>
									<td><?php echo $orderModel['tips'];?></td>
								<!--	<td><?php // $orderModel['subscription'];?></td> -->
									<td><?php echo round($orderModel['totalamount'],2);?></td>
									<td class="icons" ><a  onclick="billview('<?= $orderModel['ID'] ?>')" title="bill"><span class="fa fa-credit-card"></span></a>
									<a  onclick="orderrecipeview('<?= $orderModel['ID'] ?>')" title="Receipe Cost Card"><span class="fa fa-eye"></span></a></td>
								                                   </tr>
									<?php $x++; }?>
                       </tbody>
                  </table>


              </div>
            </div>
          </div>

        </section>
		<?php
$script = <<< JS
    $('#example').DataTable({
  "scrollX": true
});
JS;
$this->registerJs($script);
?>
<script>
$(document).ready(function(){
	

});
function  orderrecipeview(id)
{
	
        var form=document.createElement('form');
        form.setAttribute('method','post');
        form.setAttribute('action','order_recipe_cost_card');
        form.setAttribute('target','_blank');

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "id");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", id);
    form.appendChild(hiddenField);

    document.body.appendChild(form);
    form.submit();    

}
</script>
