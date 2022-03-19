<?php

use yii\helpers\Html;
use yii\helpers\Url;
?>
<style>
/*.selection{min-width:200px !important;}

        g[class^='raphael-group-'][class$='-creditgroup'] {
             display:none !important;
        }
        .circle {
  background: grey;
  clip-path: circle(50%);
  height: 10em;
  width: 10em;
  margin:auto;
}
.total {
  position: relative;
  top:20px;
  left: 150px;
  text-align:center;
  margin-top: -100px;
}
.dot {
    position:absolute;
    top:70px;
    left:40px;
  height: 25px;
  width: 25px;
  background-color:#F7464A;
  border-radius: 50%;
  display: inline-block;
}
.dot1{
  position:absolute;
  top:70px;
  left:180px;
  height: 25px;
  width: 25px;
  background-color:#46BFBD;
  border-radius: 50%;
  display: inline-block;
    
}
.dot2{
    position:absolute;
    top:140px;
    left:40px;
  height: 25px;
  width: 25px;
  background-color:#FDB45C;
  border-radius: 50%;
  display: inline-block;
    
}
.dot3 {
    position:absolute;
    top:70px;
    left:40px;
  height: 25px;
  width: 25px;
  background-color:#46BFBD;
  border-radius: 50%;
  display: inline-block;
}
.dot4{
  position:absolute;
  top:70px;
  left:180px;
  height: 25px;
  width: 25px;
  background-color:#949FB1;
  border-radius: 50%;
  display: inline-block;
    
}
.dot5{
    position:absolute;
    top:140px;
    left:40px;
  height: 25px;
  width: 25px;
  background-color:#FDB45C;
  border-radius: 50%;
  display: inline-block;
    
}
#align{
    margin-top:15px;
    padding: 5px;
    margin-left: -21px;
    margin-right: -21px;
    margin-bottom: -42px;
}
.mom {
    width: 100%;
    display: table;
}
.child {
    display: table-cell;
}
.childinner {
    margin-left: 250px;
    min-height: 40px;
}
.child:first-child .childinner {
    margin-left: 0;
}*/
.checked{color:orange;}
.container-fluid{padding: 0 15px;}
.trndamount{font-size:18px;font-weight:bold;}
.header{position: fixed; width: 100%; z-index: 99;}
.page-content{padding-top: 60px;}
</style>
 <!-- Page Header-->
          <header class="page-header">
            <div class="container-fluid col-md-12">
              <div class="row">
              <h2 class="no-margin-bottom float-left col-md-4">Dashboard</h2>
              <div class="col-md-6 text-right">
                <span>User Rating:</span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star"></span>
                <span class="fa fa-star"></span>
              </div>

			  <div class="float-right col-md-2">
			  <select class="form-control">
			  <option>Select</option>
			  </select>
			  </div>
</div>
            </div>
			<div class="clearfix"></div>
          </header>
          <!-- Dashboard Counts Section-->
		  <?php 
		  $statsCntKeys = array_values(array_keys($ordStatusCount));
		  
		  ?>
		  <div class="main">
		  
      <section class="dashboard-counts no-padding-bottom">
              <!-- <div class="row pt-0 pb-0">
                 <div class="statistics col-lg-3 col-12">
                  <div class="statistic  d-flex align-items-center">
                    <div class="icon ml-2" style="background:#2CA612;"><i class="fa fa-users"></i></div>
                    <div class="title ml-2"><span>Total Customers</span>
                     
                      <div class="number" ><strong class="counter"><a style="cursor: pointer;color:black;text-decoration:none;" onclick="orderView(0)" >
					<?php if(in_array('0',$statsCntKeys)){
					echo 	$ordStatusCount['0'] ;
					}else echo 0;?>
					</a></strong></div>
                    </div>
                    
                  </div>
                </div>
                <div class="statistics col-lg-3 col-12">
                  <div class="statistic d-flex align-items-center">
                    <div class="icon ml-2" style="background:#cb2020;"><i class="fa fa-users"></i></div>
                    <div class="title ml-2"><span>Repeat Customers</span>
                     
                      <div class="number"><strong class="counter"><a style="cursor: pointer;color:black;text-decoration:none;" onclick="orderView(1)" >
					<?php if(in_array('1',$statsCntKeys)){
					echo 	$ordStatusCount['1'] ;
					}else echo 0;?></a></strong></div>
                    </div>
                    
                  </div>
                </div>
                
              </div> -->
              <div class="row p-0">
                <div class="col-md-8">
            <div class="row p-0">
                <!-- Item -->
                 <div class="statistics col-lg-4 col-12">
                  <div class="statistic  d-flex align-items-center">
                    <div class="icon bg-violet ml-2"><i class="fa fa-plus"></i></div>
                    <div class="title ml-2"><span>New Orders</span>
                      <!-- <div class="progress">
                        <div role="progressbar" style="width: 25%; height: 4px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-violet"></div>
                      </div> -->
                      <div class="number" ><strong class="counter"><a style="cursor: pointer;color:black;text-decoration:none;" onclick="orderView(0)" >
					<?php if(in_array('0',$statsCntKeys)){
					echo 	$ordStatusCount['0'] ;
					}else echo 0;?>
					</a></strong></div>
                    </div>
                    
                  </div>
                </div>
                <!-- Item -->
                <div class="statistics col-lg-4 col-12">
                  <div class="statistic  d-flex align-items-center">
                    <div class="icon bg-red ml-2"><i class="fa fa-clock-o"></i></div>
                    <div class="title ml-2"><span>Pending Orders</span>
                      <!-- <div class="progress">
                        <div role="progressbar" style="width: 70%; height: 4px;" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-red"></div>
                      </div> -->
                      <div class="number"><strong class="counter"><a style="cursor: pointer;color:black;text-decoration:none;" onclick="orderView(1)" >
					<?php if(in_array('1',$statsCntKeys)){
					echo 	$ordStatusCount['1'] ;
					}else echo 0;?></a></strong></div>
                    </div>
                    
                  </div>
                </div>
                <div class="statistics col-lg-4 col-12">
                  <div class="statistic  d-flex align-items-center">
                    <div class="icon bg-secondary ml-2"><i class="fa fa-server"></i></div>
                    <div class="title ml-2"><span>Served Orders</span>
                      <!-- <div class="progress">
                        <div role="progressbar" style="width: 70%; height: 4px;" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-red"></div>
                      </div> -->
                      <div class="number"><strong class="counter"><a style="cursor: pointer;color:black;text-decoration:none;" onclick="orderView(2)" >
					<?php if(in_array('2',$statsCntKeys)){
					echo 	$ordStatusCount['2'] ;
					}else echo 0;?></a></strong></div>
                    </div>
                    
                  </div>
                </div>
        </div>
        <div class="row p-0">
                <div class="statistics col-lg-4 col-12">
                  <div class="statistic  d-flex align-items-center">
                    <div class="icon bg-orange ml-2"><i class="fa fa-ban"></i></div>
                    <div class="title ml-2"><span>Cancelled Orders</span>
                      <!-- <div class="progress">
                        <div role="progressbar" style="width: 50%; height: 4px;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-orange"></div>
                      </div> -->
                      <div class="number"><strong class="counter"><a style="cursor: pointer;color:black;text-decoration:none;" onclick="orderView(3)" >
					<?php if(in_array('3',$statsCntKeys)){
					echo 	$ordStatusCount['3'] ;
					}else echo 0;?>
					</a></strong></div> 
                    </div>
                    
                  </div>
                </div>
                <div class="statistics col-lg-4 col-12">
                  <div class="statistic d-flex align-items-center">
                    <div class="icon ml-2" style="background: #9f0e66;"><i class="fa fa-table"></i></div>
                    <div class="title ml-2"><span>Table Reservation</span>
                      <!-- <div class="progress">
                        <div role="progressbar" style="width: 70%; height: 4px;" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-red"></div>
                      </div> -->
                      <div class="number"><strong class="counter"><a style="cursor: pointer;color:black;text-decoration:none;" onclick="orderView(1)" >
					<?php  echo 0;?></a></strong></div>
                    </div>
                    
                  </div>
                </div>
                <div class="statistics col-lg-4 col-12">
                  <div class="statistic  d-flex align-items-center">
                    <div class="icon ml-2" style="background: #a4a90a;"><i class="fa fa-line-chart"></i></div>
                    <div class="title ml-2"><span>Gross Revenue</span>
                      <!-- <div class="progress">
                        <div role="progressbar" style="width: 50%; height: 4px;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-orange"></div>
                      </div> -->
                      <div class="number"><strong class="counter"><a style="cursor: pointer;color:black;text-decoration:none;" onclick="orderView(3)" >
					<?php  echo round($resPaidCOunt['totalamount'],2);?>
					</a></strong></div> 
                    </div>
                    
                  </div>
                </div>
              </div>
              <div class="clearfix"></div>
              <div class="">
                <div class="card">
                  <div class="card-header">
                <div class="row p-0">
                <div class="col-md-12 pl-0 pr-0">
                  <div class="">
                      <h3 class="float-left">
                        <span>TOTAL SALES - </span>
                        <i class="fa fa-inr ml-3" aria-hidden="true"></i> <?php  echo round($resPaidCOunt['totalamount'],2);?>
        </h3>
                   <!-- <div class="dropdown no-arrow float-right">
                    <select class="form-control" >
                      <option value="1">Today</option>
                      <option value="2">Week</option>
                      <option value="3">Month</option>
                      <option value="3">Customize</option>
                    </select>
                  </div> -->
                  <div class="clearfix"></div>
        </div>
        </div>
        </div>
        </div>
        <div class="card-body">
                  <div class="col-md-12 pt-2">
                  <div class="row">
                  <div class="col-md-6">
                     <div id="chart-containert"></div>
                     </div>
                     <div class="col-md-6">
                     <div id="chart-containerr"></div>
                     </div>
                     </div>
        </div>
        </div>
                    </div>
                    </div>
    <div class="col-md-12 pl-0 pr-0">
		<div class="card">
          <div class="card-header">
				<div class="row p-0">
					<div class="col-md-2">
						<h3 class="h4 pl-0 tab-title">Sale Report</h3>
					</div>
					<div class="col-md-10">
						<div class="row p-0">
							<div class="col-md-4">
								<input type="text" class="form-control datepicker3" id="datepicker3" value="<?= date('Y-m-d'); ?>" 
								style = "display:none">
							</div>
							<div class="col-md-4">
								<input type="text" class="form-control datepicker4" id="datepicker4" value="<?= date('Y-m-d'); ?>"
								style = "display:none">
							</div>
							<div class="col-md-3">
								<div>
									<select class="form-control" id="saleselect" name="saleselect" >
										<option value="1">Today</option>
										<option value="2">Month</option>
										<option value="3">Date</option>
										<option value="4">Date Range</option>
									</select>
								</div>
							</div>
						
							<div class="col-md-1">
								<button class="btn btn-primary" id="searchsale">Go</button>
							</div>
						</div>
					</div>
				</div>
			</div>
            <div class="card-body">
				<div class="row">
					<!-- Statistics -->
				   
					<!-- Line Chart            -->
					<div class="chart col-lg-12 col-12">
						<div class="clearfix"></div>
						<div id="chart-container"></div>
					</div>	
                </div>
              </div>
        </div>
    </div>
    <div class="col-md-12 pl-0 pr-0">
        <div class="card">
			<div class="card-header d-flex">
                <h3 class="col-md-2">Sales Report</h3>
                <div class="col-md-10">
						<div class="row p-0">
							<div class="col-md-4">
								<input type="text" class="form-control datepicker5" id="datepicker5" value="<?= date('Y-m-d'); ?>" 
								style = "display:none">
							</div>
							<div class="col-md-4">
								<input type="text" class="form-control datepicker6" id="datepicker6" value="<?= date('Y-m-d'); ?>"
								style = "display:none">
							</div>
							<div class="col-md-3">
								<div>
									<select class="form-control" id="select1" name="select1" >
										<option value="1">Today</option>
										<option value="2">Month</option>
										<option value="3">Date</option>
										<option value="4">Date Range</option>
									</select>
								</div>
							</div>
						
							<div class="col-md-1">
								<button class="btn btn-primary" id="search1">Go</button>
							</div>
						</div>
					</div>
            </div>
            <div class="card-body">
                <div id="chart-customermap"></div>
            </div>
        </div>
    </div>
              <div class="col-md-12 pl-0 pr-0">
                <div class="card">
                <div class="card-header d-flex">
                  <div class="col-md-2">
					<select id="chartType">
						<option value="1">Quantity</option>
						<option value="2">Amount</option>
					</select>
				  </div>
                  <div class="col-md-10">
						<div class="row p-0">
							<div class="col-md-4">
								<input type="text" class="form-control datepicker7" id="datepicker7" value="<?= date('Y-m-d'); ?>" 
								style = "display:none">
							</div>
							<div class="col-md-4">
								<input type="text" class="form-control datepicker8" id="datepicker8" value="<?= date('Y-m-d'); ?>"
								style = "display:none">
							</div>
							<div class="col-md-3">
								<div>
									<select class="form-control" id="select3" name="select3" >
										<option value="1">Today</option>
										<option value="2">Month</option>
										<option value="3">Date</option>
										<option value="4">Date Range</option>
									</select>
								</div>
							</div>
						
							<div class="col-md-1">
								<button class="btn btn-primary" id="search2">Go</button>
							</div>
						</div>
					</div>
                </div>
                <div class="card-body">
                <div id="chart-expenses"></div>
                </div>
                </div>
              </div>



        </div>
        <div class="col-md-4">
          <div class="card">
          <div class="card-header d-flex">
            <h3 class="col-md-8 pl-0">Order Performance</h3>
            <div class="col-md-4 pr-0">
              <select class="form-control">
                <option>Select</option>
                <option>Today</option>
              </select>
        </div>
        </div>
        <div class="card-body">
		<div id="chart-dailytarget"></div>
        </div>
        </div>
        
        <div class="col-md-12 pl-0 pr-0">
                <div class="card">
                <div class="card-body text-center">
                  <div class="row">
                    <div class="col-md-6 statistic mb-0" style="border-right: 1px solid #e8e8e8;">
                    <div class="text"><strong class="counter"><?= round($resPaidCOunt['paidByCash'])?? 0; ?></strong><br><small style="color:grey;">Paid By Cash</small></div>
                    </div>
                    <div class="col-md-6 statistic mb-0">
                    <div class="text"><strong class="counter"><?= round($resPaidCOunt['paidOnline']) ?? 0; ?></strong><br><small style="color:grey;">Online Payment</small></div>
                    </div>
                    </div>
                    <hr class="mt-0 mb-0">
                    <div class="row">
                    <div class="col-md-6 statistic mb-0" style="border-right: 1px solid #e8e8e8;">
                    <div class="text"><strong class="counter"><?= round($resPaidCOunt['runningOrders']) ?? 0; ?></strong><br><small style="color:grey;">Running Orders</small></div>
                    </div>
                    <div class="col-md-6 statistic mb-0">
                    <div class="text"><strong class="counter"><?= ($productCount) ?? 0; ?></strong><br><small style="color:grey;">Total Items</small></div>
                    </div>
                  </div>
                  </div>
                  </div>
                  <div class="chart col-lg-12 col-12 pl-0 pr-0">
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
                    <div class="icon bg-success"><i class="fa fa-houzz"></i></div>
                    <div class="text"><strong><a style="cursor: pointer;color:black;text-decoration:none;" href="<?= Url::to(['merchant/ingredientstock']); ?>">Stock Updates</a></strong></div>
                  </div>
                </div>
                </div>
                <div class="chart col-lg-12 pl-0 pr-0 mt-3">
                 <div class="card">
                 <div class="card-header d-flex">
                   <h3 class="col-md-8">Trending Items</h3>
                   <div class="col-md-4">
                     <select class="form-control">
                       <option>Select</option>
                     </select>
                   </div>
                 </div>
                 <div class="card-body">
                 <div class="col-xs-12 item-shdw p-2 mb-2">
                    <div class="row">
                      <div class="col-md-4">
                        <img src="/tutors/web/img/food-q.png" width="100%">
                      </div>
                      <div class="col-md-8">
                        <h6>Egg Biriyani</h6>
                        <div class="trndamount"><i class="fa fa-inr"></i> 250</div>
                      </div>
                    </div>
                  </div>
                  <div class="col-xs-12 item-shdw p-2 mb-2">
                    <div class="row">
                      <div class="col-md-4">
                        <img src="/tutors/web/img/food-q.png" width="100%">
                      </div>
                      <div class="col-md-8">
                        <h6>Egg Biriyani</h6>
                        <div class="trndamount"><i class="fa fa-inr"></i> 250</div>
                      </div>
                    </div>
                  </div>
                  <div class="col-xs-12 item-shdw p-2 mb-2">
                    <div class="row">
                      <div class="col-md-4">
                        <img src="/tutors/web/img/food-q.png" width="100%">
                      </div>
                      <div class="col-md-8">
                        <h6>Egg Biriyani</h6>
                        <div class="trndamount"><i class="fa fa-inr"></i> 250</div>
                      </div>
                    </div>
                  </div>

                 </div>
                 
                 </div>
                  
                </div>
                <div class="col-md-12 pl-0 pr-0">
              <div class="card">
                <div class="card-header d-flex">
                  <h3 class="col-md-12">Customer Retension Rate</h3>
                  
                </div>
                <div class="card-body">
                  <div id="chart-retension"></div>
                </div>
                </div>
              </div>

              <div class="col-md-12 pl-0 pr-0">
              <div class="card">
                <div class="card-header d-flex">
                  <h3 class="col-md-12">Restuarant Rating</h3>
                  
                </div>
                <div class="card-body">
                  <div id="chart-rating"></div>
                </div>
                </div>
              </div>
        </div>
        </div>
          </section>
           
         
          
          
          <section class="pt-0 pb-0">
          <div class="col-lg-12">
            <div class="card">
              
              <div class="card-header d-flex align-items-center pt-0 pb-0">
                <h3 class="h4 col-md-10 pl-0 tab-title">Order History</h3>
				<div class="dropdown no-arrow float-right col-md-2">
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
                    <thead class="thead-light">
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
        <section class="pb-0 pt-0">
          <div class="col-lg-12">
            <div class="card">
              
              <div class="card-header d-flex align-items-center pt-0 pb-0">
                <h3 class="h4 col-md-10 pl-0 tab-title">Pilot Report</h3>
				    <div class="dropdown no-arrow col-md-2 float-right">
					<select class="form-control">
						<option value="1">Today</option>
						<option value="2">Week</option>
						<option value="3">Month</option>
						<option value="3">Customize</option>
					</select>
				</div>
              </div>
              <div class="card-body">
                  <table id="example" class="table table-striped table-bordered" style="border-radius: 4px 4px 0 0;">
                    <thead class="thead-light">
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
                                 	<td><i class="fa fa-inr mr-2"></i><?= round($pilotdet['totalamount']);?></td>
									</tr>
									
                        <?php $i++;}?> 
                    </tbody> 
                    
                  </table>
		  

              </div>
            </div>
          </div>


        </section>
        
        <!-- <div class="statistics col-lg-6 col-12">
                  <div class="statistic align-items-center bg-white has-shadow">
                    <div id="chart-container2"></div>
                  </div>
                  </div>
         
    
        </div> -->
        


          
		  <script src='https://cdn.fusioncharts.com/fusioncharts/latest/fusioncharts.js'></script>
<script src='https://cdn.fusioncharts.com/fusioncharts/latest/themes/fusioncharts.theme.fusion.js'></script>
<script src='https://unpkg.com/jquery-fusioncharts@1.1.0/dist/fusioncharts.jqueryplugin.js'></script>
			
<script>
	$(document).ready(function(){
		var str =  '<?= $str;?>';
		var orderMainStatusStr =  '<?= $strOrderMainStatus;?>';
		var topSaleChartDet = '<?= $topSaleChartDet; ?>';
		saleChart(str);
		orderMainStatus(orderMainStatusStr);
	topOrderAmountChart(topSaleChartDet);
	});
	
	function topOrderAmountChart(topSaleChartDet){
		var topSaleChart = JSON.parse(topSaleChartDet); 
		$("#chart-expenses").insertFusionCharts({
		  type: "bar2d",
		  width: "100%",
		  height: "550",
		  dataFormat: "json",
		  dataSource: {
			chart: {
			  caption: "Trending Items",
			  xaxisname: "Items",
			  yaxisname: "Top Sale",
			  theme: "fusion"
			},
			data: topSaleChart
		  }
		});
	}	



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
  <script>
  $(document).ready(function(){
  
//	$('select').select2();
  $("#chart-containerr").insertFusionCharts({
  type: "doughnut2d",
  width: "100%",
  height: "450",
  dataFormat: "json",
  dataSource: {
    "chart": {
"caption": "Running Orders",
//            "subCaption": "Last year",
            "numberPrefix": "₹",
            "bgColor": "#ffffff",
            "startingAngle": "310",
            "showLegend": "1",
            "defaultCenterLabel": "Total revenue: <?= "₹ ".array_sum(array_column($runningPie,'value')); ?>",
            "centerLabel": "Revenue from $label: $value",
            "centerLabelBold": "1",
            "showTooltip": "0",
            "decimals": "0",
            "theme": "fusion",

            "showLabels" : "0",
            "showValues" : "0"
                        //showLegend:"0",
             },
    data: 
      <?= json_encode($runningPie); ?>
    
  }
});

FusionCharts.ready(function(){
			var chartObj = new FusionCharts({
    type: 'doughnut2d',
    renderAt: 'chart-containert',
    width: '100%',
    height: '450',
    dataFormat: 'json',
    dataSource: {
        "chart": {
"caption": "Completed Orders",
  //          "subCaption": "Last year",
            "numberPrefix": "₹",
            "bgColor": "#ffffff",
            "startingAngle": "310",
            "showLegend": "1",
            "defaultCenterLabel": "Total revenue: <?= "₹ ".array_sum(array_column($completedPie,'value')); ?>",
            "centerLabel": "Revenue from $label: $value",
            "centerLabelBold": "1",
            "showTooltip": "0",
            "decimals": "0",
            "theme": "fusion",
            "showLabels" : "0",
            "showValues" : "0"
           // showLegend:"0"
        },
        "data": <?= json_encode($completedPie); ?>
    }
}
);
			chartObj.render();
		});
		
		
		
	
		
});
$("#searchsale").click(function(){
var date1 = $("#datepicker3").val();
var date2 = $("#datepicker4").val();
var saleselect = $("#saleselect").val();

var request = $.ajax({
  url: "ajaxsalechart",
  type: "POST",
  data: {date1:date1,
          date2:date2,
          saleselect : saleselect },
}).done(function(msg) {
  saleChart(msg);
});


});

$("#saleselect").change(function(){
  var selectedvalue = this.value;
  if(selectedvalue == '3' || selectedvalue == '4' ){
    $("#datepicker4").show(); 
  }
  else{
    $("#datepicker4").hide();
  } 

  if( selectedvalue == '4' ){
    $("#datepicker3").show(); 
  }
  else{
    $("#datepicker3").hide();
  } 
});

$("#select1").change(function(){
  var selectedvalue = this.value;
  if(selectedvalue == '3' || selectedvalue == '4' ){
    $("#datepicker6").show(); 
  }
  else{
    $("#datepicker6").hide();
  } 

  if( selectedvalue == '4' ){
    $("#datepicker5").show(); 
  }
  else{
    $("#datepicker5").hide();
  } 
});

$("#select3").change(function(){
  var selectedvalue = this.value;
  if(selectedvalue == '3' || selectedvalue == '4' ){
    $("#datepicker8").show(); 
  }
  else{
    $("#datepicker8").hide();
  } 

  if( selectedvalue == '4' ){
    $("#datepicker7").show(); 
  }
  else{
    $("#datepicker7").hide();
  } 
});

function saleChart(str){

$("#chart-container").insertFusionCharts({
type: "spline",
width: "100%",
height: "300",
dataFormat: "xml",
dataSource: str
});
}


$("#search1").click(function(){
var date1 = $("#datepicker5").val();
var date2 = $("#datepicker6").val();
var selected = $("#select1").val();
var request = $.ajax({
  url: "ajax-sale-order-report-chart",
  type: "POST",
  data: {sdate:date1,
          edate:date2,
          selected : selected },
}).done(function(msg) {
  orderMainStatus(msg);
});


});

$("#search2").click(function(){
var date1 = $("#datepicker7").val();
var date2 = $("#datepicker8").val();
var selected = $("#select3").val();
var chartType = $("#chartType").val();
var request = $.ajax({
  url: "ajax-top-amount-chart",
  type: "POST",
  data: {sdate:date1,
          edate:date2,
          selected : selected,
		  chartType : chartType
		  },
}).done(function(msg) {
  topOrderAmountChart(msg);
});


});

function orderMainStatus(orderMainStatusJson)
{
	var orderMainStatusArr = JSON.parse(orderMainStatusJson);
	

$("#chart-customermap").insertFusionCharts({
  type: "mscolumn2d",
  width: "100%",
  height: "520",
  dataFormat: "json",
  dataSource: {
    chart: {
      caption: "Sales Report (Completed , Cancelled Orders)",
      xaxisname: "Date",
      yaxisname: "Orders",
      formatnumberscale: "1",
      plottooltext:
        "<b>$dataValue</b> <b>$seriesName</b> orders on $label",
      theme: "fusion",
      drawcrossline: "1"
    },
    categories: [
      {
        category: orderMainStatusArr['category']
      }
    ],
    dataset: orderMainStatusArr['dataSeries']
  }
});
}

$(document).ready(function(){

FusionCharts.ready(function(){
			var chartObj = new FusionCharts({
    type: 'doughnut2d',
    renderAt: 'chart-dailytarget',
    width: '100%',
    height: '250',
    dataFormat: 'json',
    dataSource: {
        "chart": {
            
            "bgColor": "#ffffff",
            "startingAngle": "310",
            "showLegend": "1",
		"legendNumRows": "2",
        "legendNumColumns": "2",
		"defaultCenterLabel": "",
            "centerLabel": "$value",
            "centerLabelBold": "1",
            "showTooltip": "0",
            "decimals": "0",
            "theme": "fusion",
			"showLabels" : "0",
            "showValues" : "0"
        },
        "data": <?= json_encode($orderPerformanceArray); ?>
    }
}
);
			chartObj.render();
		});
		
		FusionCharts.ready(function(){
			var chartObj = new FusionCharts({
    type: 'doughnut2d',
    renderAt: 'chart-rating',
    width: '100%',
    height: '450',
    dataFormat: 'json',
    dataSource: {
        "chart": {
            "bgColor": "#ffffff",
            "startingAngle": "310",
            "showLegend": "1",
            "defaultCenterLabel": "",
            "centerLabel": "$value",
            "centerLabelBold": "1",
            "showTooltip": "0",
            "decimals": "1",
            "theme": "fusion",
			"showLabels" : "0",
            "showValues" : "0"
        },
        "data": <?= json_encode($merchantRatingArray) ?>
    }
}
);
			chartObj.render();
		});
		

		

$("#chart-retension").insertFusionCharts({
  type: "msarea",
  width: "100%",
  height: "400",
  dataFormat: "json",
  dataSource: {
    chart: {
      caption: "GDP Growth Rate Comparison",
      yaxisname: "Quarterly GDP Growth Rate in %",
      subcaption: "India vs China",
      drawcrossline: "1",
      numbersuffix: "%",
      plottooltext: "$seriesName's GDP grew $dataValue in $label",
      theme: "fusion"
    },
    categories: [
      {
        category: [
          {
            label: "April 2016"
          },
          {
            label: "July 2016"
          },
          {
            label: "Oct 2016"
          },
          {
            label: "Jan 2017"
          },
          {
            label: "April 2017"
          }
        ]
      }
    ],
    dataset: [
      {
        seriesname: "India",
        data: [
          {
            value: "9.2"
          },
          {
            value: "7.9"
          },
          {
            value: "7.5"
          },
          {
            value: "7"
          },
          {
            value: "6.1"
          }
        ]
      },
      {
        seriesname: "China",
        data: [
          {
            value: "6.7"
          },
          {
            value: "6.7"
          },
          {
            value: "6.7"
          },
          {
            value: "6.8"
          },
          {
            value: "6.9"
          }
        ]
      }
    ]
  }
});


});

  </script>
