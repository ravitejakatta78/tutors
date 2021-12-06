<?php

use yii\helpers\Html;
use yii\helpers\Url;
$merchant_id = Yii::$app->user->identity->merchant_id;
$merchantdetails = selectQuery("merchant","ID = '".$merchant_id."'"); 
?>
<style>
.selection{min-width:200px !important;}

        g[class^='raphael-group-'][class$='-creditgroup'] {
             display:none !important;
        }
        .gradient{
           background: linear-gradient(to bottom, #66ffff 0%, #ff5050 100%);
        }
        .gradient1{
          background: linear-gradient(to bottom, #0000ff 0%, #ff3399 100%);
        }
        .gradient2{
           background: linear-gradient(to bottom, #00ffff 0%, #ff66ff 100%);
        }
        .gradient3{
           background: linear-gradient(to bottom, #00cc00 0%, #ffff00 100%);
        }
        .gradient4{
           background: linear-gradient(to bottom, #ff0066 0%, #ff6600 100%);
        }
        .circle {
  background: grey;
  clip-path: circle(50%);
  height: 10em;
  width: 10em;
  margin:auto;
}
#align{
    margin-top:15px;
    padding: 5px;
    margin-left: -21px;
    margin-right: -21px;
    margin-bottom: -42px;
}
</style>
 <!-- Page Header-->
          <header class="page-header">
            <div class="container-fluid col-md-12">
              <h2 class="no-margin-bottom float-left">Dashboard | <?php echo $merchantdetails['storename'];?></h2>
			  <div class="float-right">
			  <select class="form-control">
			  <option>Select</option>
			  </select>
			  </div>
            </div>
			<div class="clearfix"></div>
          </header>
          <!-- Dashboard Counts Section-->
		  <?php 
		  $statsCntKeys = array_values(array_keys($ordStatusCount));
		  
		  ?>
		  <div class="main" style="background:lightblue;">
		  
          <section class="dashboard-counts no-padding-bottom">
            <div class="container-fluid">
              <div class="row bg-white has-shadow">
                <!-- Item -->
                 <div class="statistics col-lg-3 col-12">
                  <div class="statistic  d-flex align-items-center gradient3 has-shadow">
                    <div class="icon bg-violet ml-2"><i class="fa fa-plus"></i></div>
                    <div class="title ml-2 text-white"><span>New<br>Orders</span>
                      <div class="progress">
                        <div role="progressbar" style="width: 25%; height: 4px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-violet"></div>
                      </div>
                    </div>
                    <div class="number" ><strong class="counter"><a style="cursor: pointer;color:black;text-decoration:none;" onclick="orderView(0)" >
					<?php if(in_array('0',$statsCntKeys)){
					echo 	$ordStatusCount['0'] ;
					}else echo 0;?>
					</a></strong></div>
                  </div>
                </div>
                <!-- Item -->
                <div class="statistics col-lg-3 col-12">
                  <div class="statistic  d-flex align-items-center gradient1 has-shadow">
                    <div class="icon bg-red ml-2"><i class="fa fa-clock-o"></i></div>
                    <div class="title ml-2 text-white"><span>Pending<br>Orders</span>
                      <div class="progress">
                        <div role="progressbar" style="width: 70%; height: 4px;" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-red"></div>
                      </div>
                    </div>
                    <div class="number"><strong class="counter"><a style="cursor: pointer;color:black;text-decoration:none;" onclick="orderView(1)" >
					<?php if(in_array('1',$statsCntKeys)){
					echo 	$ordStatusCount['1'] ;
					}else echo 0;?></a></strong></div>
                  </div>
                </div>
                <div class="statistics col-lg-3 col-12">
                  <div class="statistic  d-flex align-items-center gradient2 has-shadow">
                    <div class="icon bg-secondary ml-2"><i class="fa fa-server"></i></div>
                    <div class="title ml-2 text-white"><span>Served<br>Orders</span>
                      <div class="progress">
                        <div role="progressbar" style="width: 70%; height: 4px;" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-red"></div>
                      </div>
                    </div>
                    <div class="number"><strong class="counter"><a style="cursor: pointer;color:black;text-decoration:none;" onclick="orderView(1)" >
					<?php if(in_array('1',$statsCntKeys)){
					echo 	$ordStatusCount['1'] ;
					}else echo 0;?></a></strong></div>
                  </div>
                </div>
                <!--<div class="col-xl-2 col-sm-6 ml-5">-->
                <!--  <div class="item d-flex align-items-center gradient4 has-shadow">-->
                <div class="statistics col-lg-3 col-12">
                  <div class="statistic  d-flex align-items-center gradient4 has-shadow">
                    <div class="icon bg-orange ml-2"><i class="fa fa-ban"></i></div>
                    <div class="title ml-2 text-white"><span>Cancelled<br>Orders</span>
                      <div class="progress">
                        <div role="progressbar" style="width: 50%; height: 4px;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-orange"></div>
                      </div>
                    </div>
                    <div class="number"><strong class="counter"><a style="cursor: pointer;color:black;text-decoration:none;" onclick="orderView(3)" >
					<?php if(in_array('3',$statsCntKeys)){
					echo 	$ordStatusCount['3'] ;
					}else echo 0;?>
					</a></strong></div>
                  </div>
                </div>
              </div>
            </div>
          </section>
           <section class="dashboard-header">
            <div class="container-fluid">
              <div class="row">
                <!-- Statistics -->
                <div class="statistics col-lg-4 col-12">
                  <div class="statistic align-items-center bg-white has-shadow">
                    <div class="text-left"><small style="color:black;font-weight:bold;font-family: Source Sans Pro;font-size:40px;">Total Sales</small><br>
                      <i class="fa fa-inr fa-2x ml-3" aria-hidden="true">&nbsp;3000.00</i><span class="text-center float-right" style="font-size:20px;">Orders:45</span><br>
                      <div class="card-body" id="align">
                  <table id="example" class="table table-striped table-bordered">
                    <thead>
                      <tr>
						<th>Section</th>
                        <th>Amount</th>
                        <th>Orders</th>
                       </tr>
                    </thead>
					<tbody>
                                  <tr>
                                 	<td>AC</td>
                                 	<td><i class="fa fa-inr fa-1x" aria-hidden="true">1000.00</td> 
                                 	<td>15</td> 
									</tr>
									<tr>
                                 	<td> NON AC</td>
                                 	<td><i class="fa fa-inr fa-1x" aria-hidden="true">1000.00</td> 
                                 	<td>15</td> 
									</tr>
									<tr>
                                 	<td>MANDI</td>
                                 	<td><i class="fa fa-inr fa-1x" aria-hidden="true">1000.00</td> 
                                 	<td>15</td> 
									</tr
									

                    </tbody>
                  </table>
		  

              </div>
                    </div>
                  </div>
                  </div>
                  <div class="statistics col-lg-4 col-12">
                  <div class="statistic align-items-center bg-white has-shadow">
                    <div class="text-left"><small style="color:black;font-weight:bold;font-family: Source Sans Pro;font-size:40px;">Running Sales</small><br>
                      <i class="fa fa-inr fa-2x ml-3" aria-hidden="true">&nbsp;4500.00</i><span class="text-center float-right" style="font-size:20px;">Orders:45</span><br>
                      <div class="card-body" id="align">
                  <table id="example" class="table table-striped table-bordered">
                    <thead>
                      <tr>
						<th>Section</th>
                        <th>Amount</th>
                        <th>Orders</th>
                       </tr>
                    </thead>
					<tbody>
                                  <tr>
                                 	<td>AC</td>
                                 	<td><i class="fa fa-inr fa-1x" aria-hidden="true">5000.00</td> 
                                 	<td>15</td> 
									</tr>
									<tr>
                                 	<td> NON AC</td>
                                 	<td><i class="fa fa-inr fa-1x" aria-hidden="true">1000.00</td> 
                                 	<td>15</td> 
									</tr>
									<tr>
                                 	<td>MANDI</td>
                                 	<td><i class="fa fa-inr fa-1x" aria-hidden="true">3000.00</td> 
                                 	<td>15</td> 
									</tr>

                    </tbody>
                  </table>
		  

              </div>
                    </div>
                  </div>
                  </div>
                  <div class="statistics col-lg-4 col-12">
                  <div class="statistic align-items-center bg-white has-shadow">
                    <div id="chart-container2"></div>
                  </div>
                  </div>
                  </div>
                  </div>
                  </section
          <section class="dashboard-header">
            <div class="container-fluid">
              <div class="row">
                <!-- Statistics -->
                <div class="statistics col-lg-3 col-12">
                  <div class="statistic d-flex align-items-center bg-white has-shadow">
                    <div class="icon bg-red"><i class="fa fa-money"></i></div>
                    <div class="text"><strong class="counter"><?= $resPaidCOunt['paidByCash']?? 0; ?></strong><br><small style="color:grey;">Paid By Cash</small></div>
                  </div>
                  <div class="statistic d-flex align-items-center bg-white has-shadow">
                    <div class="icon bg-green"><i class="fa fa-globe"></i></div>
                    <div class="text"><strong class="counter"><?= $resPaidCOunt['paidOnline'] ?? 0; ?></strong><br><small style="color:grey;">Online Payment</small></div>
                  </div>
                  <div class="statistic d-flex align-items-center bg-white has-shadow">
                    <div class="icon bg-orange"><i class="fa fa-times"></i></div>
                    <div class="text"><strong class="counter"><?= $resPaidCOunt['notPaid'] ?? 0; ?></strong><br><small style="color:grey;">Running Orders</small></div>
                  </div>
                  <div class="statistic d-flex align-items-center bg-white has-shadow">
                    <div class="icon bg-violet"><i class="fa fa-check"></i></div>
                    <div class="text"><strong class="counter"></strong><br><small style="color:grey;">Total Items</small></div>
                  </div>
                </div>
                <!-- Line Chart            -->
                <div class="chart col-lg-6 col-12">
                  <div id="chart-container"></div>
                </div>
                <div class="chart col-lg-3 col-12">
                  <!-- Bar Chart   -->
                  
                  <!-- Numbers-->
                  <div class="statistic d-flex align-items-center bg-white has-shadow">
                    <div class="icon bg-green"><i class="fa fa-user-secret"></i></div>
                    <div class="text"><strong><a style="cursor: pointer;color:black;text-decoration:none;" href="<?= Url::to(['merchant/pilot']); ?>">Pilot Data</a></strong></div>
                  </div>
                  <!--<div class="statistic d-flex align-items-center bg-white has-shadow">-->
                  <!--  <div class="icon bg-orange"><i class="fa fa-users"></i></div>-->
                  <!--  <div class="text"><strong><a style="cursor: pointer;color:black;text-decoration:none;" href="#">Users Data</a></strong></div>-->
                  <!--</div>-->
                  <div class="statistic d-flex align-items-center bg-white has-shadow">
                    <div class="icon bg-violet"><i class="fa fa-commenting-o"></i></div>
                    <div class="text"><strong><a style="cursor: pointer;color:black;text-decoration:none;" href="<?= Url::to(['merchant/rating']); ?>">Feedback</a></strong></div>
                  </div>
                  <div class="statistic d-flex align-items-center bg-white has-shadow">
                    <div class="icon bg-info"><i class="fa fa-table"></i></div>
                    <div class="text"><strong><a style="cursor: pointer;color:black;text-decoration:none;" href="<?= Url::to(['merchant/tablereservation']); ?>">Table Reservation</a></strong></div>
                  </div>
                  <div class="statistic d-flex align-items-center bg-white has-shadow">
                    <div class="icon bg-success"><i class="fa fa-houzz"></i></div>
                    <div class="text"><strong><a style="cursor: pointer;color:black;text-decoration:none;" href="<?= Url::to(['merchant/ingredientstock']); ?>">Stock Updates</a></strong></div>
                  </div>
                </div>
              </div>
            </div>
          </section>
              <section class="dashboard-header">
            <div class="container-fluid">
              <div class="row">
              <div class="chart col-lg-6 col-12">
                  <div id="chart-container1"></div>
                </div>
                </div>
                </div>
          </section>
          <section>
          <div class="col-lg-12">
            <div class="card">
              
              <div class="card-header d-flex align-items-center pt-0 pb-0">
                <h3 class="h4 col-md-6 pl-0 tab-title">Recent Orders</h3>
				<div class="dropdown no-arrow">
					<select class="form-control">
						<option value="1">Today</option>
						<option value="2">Week</option>
						<option value="3">Month</option>
						<option value="3">Customize</option>
					</select>
					</div>
              </div>
              <div class="card-body">
                  <table id="example" class="table table-striped table-bordered ">
                    <thead>
                      <tr>
						<th>S.No</th>
                        <th>Section</th>
                        <th>Table No</th>
                        <th>Order Status</th> 
                        <th>Order Type</th> 
					    <th>Bill</th> 
						<th>Amount</th> 
						<th>Pay Type</th>
						<th>Action</th> 
                       </tr>
                    </thead>
					<tbody>
                                  <tr>
                                 	<td></td>
                                 	<td></td> 
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


        </section>
    
<section>
          <div class="col-lg-12">
            <div class="card">
              
              <div class="card-header d-flex align-items-center pt-0 pb-0">
                <h3 class="h4 col-md-6 pl-0 tab-title">Pilot Report</h3>
				    <div class="dropdown no-arrow">
					<select class="form-control">
						<option value="1">Today</option>
						<option value="2">Week</option>
						<option value="3">Month</option>
						<option value="3">Customize</option>
					</select>
				</div>
              </div>
              <div class="card-body">
                  <table id="example" class="table table-striped table-bordered ">
                    <thead>
                      <tr>
						<th>S.NO</th>
                        <th>Pilot Name</th>
                        <th>Completed Orders</th>
                        <th>Running Orders</th> 
                        <th>Cancelled Orders</th> 
                        <th>Amount</th>
                       </tr>
                    </thead>
					<tbody>
					    <?php 
					     $i=0;
					     foreach($pilotdet as $pilotdet){ ?>
                                  <tr>
                                 	<td><?= $i+1;?></td>
                                 	<td><?= $pilotdet['name'];?></td> 
                                 	<td><?= $pilotdet['completed_orders'];?></td> 
                                 	<td><?= $pilotdet['total_served_orders'];?></td>  
                                 	<td><?= $pilotdet['rejected_order'];?></td>  
                                 	<td><i class="fa fa-inr mr-2"></i><?= $pilotdet['totalamount'];?></td>
									</tr>
									
                        <?php $i++;}?>
                    </tbody>
                  </table>
		  

              </div>
            </div>
          </div>


        </section>
        </div>


          
		  <script src='https://cdn.fusioncharts.com/fusioncharts/latest/fusioncharts.js'></script>
<script src='https://cdn.fusioncharts.com/fusioncharts/latest/themes/fusioncharts.theme.fusion.js'></script>
<script src='https://unpkg.com/jquery-fusioncharts@1.1.0/dist/fusioncharts.jqueryplugin.js'></script>
			<script src='http://cdnjs.cloudflare.com/ajax/libs/waypoints/2.0.3/waypoints.min.js'></script>
			<script src='https://cdn.jsdelivr.net/jquery.counterup/1.0/jquery.counterup.min.js'></script>	
			<script>
				$(document).ready(function(){
					$('.counter').counterUp({
  delay: 10,
  time: 2000
});

				})
			
			</script>
		  <script>
	$(document).ready(function(){
	
	$('select').select2();
  $("#chart-container").insertFusionCharts({
  type: "spline",
  width: "100%",
  height: "100%",
  dataFormat: "xml",
  dataSource: '<?= $str;?>'
});
});
$(document).ready(function(){
	
	$('select').select2();
$("#chart-container2").insertFusionCharts({
  type: "bar2d",
  width: "100%",
  height: "85%",
  dataFormat: "json",
  dataSource: {
    chart: {
      caption: "Most Selling Items",
      yaxisname: "Revenue",
      aligncaptionwithcanvas: "0",
      numbersuffix: "K",
      plottooltext: "<b>$dataValue</b> leads received",
      theme: "fusion"
    },
    data: [
      {
        label: "CHICKEN BIRYANI",
        value: "41"
      },
      {
        label: "VEG BIRYANI",
        value: "39"
      },
      {
        label: "PIZZA",
        value: "38"
      },
      {
        label: "PASTA",
        value: "32"
      },
      {
        label: "ROTI",
        value: "26"
      }
    ]
  }
});
});

// $(document).ready(function(){
	
// 	$('select').select2();
//   $("#chart-container1").insertFusionCharts({
//   type: "column2d",
//   width: "80%",
//   height: "50%",
//   dataFormat: "json",
//   dataSource: {
//     chart: {
//       caption: "Expenses Bars",
//       subcaption: "Top Five Categories",
//       xaxisname: "Categories",
//       yaxisname: "Earnings",
//       numbersuffix: "K",
//       theme: "fusion"
//     },
//     data: [
//       {
//         label: "Pizza",
//         value: "290"
//       },
//       {
//         label: "Biryani",
//         value: "260"
//       },
//       {
//         label: "NON VEG",
//         value: "180"
//       },
//       {
//         label: "Pasta",
//         value: "130"
//       },
//       {
//         label: "Another",
//         value: "120"
//       },
      
//     ]
//   }
// });

// });
function orderView(orderProcess)
{
	    var form=document.createElement('form');
        form.setAttribute('method','post');
        form.setAttribute('action','orders');


    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "orderProcess");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", orderProcess);
    form.appendChild(hiddenField);

    document.body.appendChild(form);
    form.submit();    

}
	</script>