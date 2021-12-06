<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
<header class="page-header">
<script src="<?= Yii::$app->request->baseUrl.'/js/jquery2.2.4.min.js';?>"></script>
<script src="<?= Yii::$app->request->baseUrl.'/js/jquery.table2excel.js'?>"></script>
    
</header>
<section>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex align-items-center pt-0 pb-0">
                <h3 class="h4 col-md-6 pl-0 tab-title">Category Wise Report</h3>
		<div class="col-md-6 text-right pr-0">
			
		</div>
            </div>
            <div class="card-body">
               <form class="form-horizontal" method="POST" action="reportcategory"> 
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
                    <label class="control-label col-md-4 pt-1"> Category Type:</label>
                    <div class="col-md-8">
					<div class="input-group">
					<select name="fc_id" id="fc_id"> 
					<option value="">Select</option>
					<?php  foreach($foodcategories as $fc){ ?>
					<option value="<?php echo $fc['ID']; ?>" <?php if($fcid == $fc['ID']){ ?> selected <?php }?>><?php echo $fc['food_category']; ?></option>
					<?php } ?>
					</select>
				</div>
               </div>
               </div>
               </div>
                    <div class="col">
                    <div class="form-group text-center pt-3">
                        <button type="button" onclick="pilotviews()" class="btn btn-add btn-sm btn-search ml-0">Search</button>
                    </div>
                    </div>
                    <div class="col">
                    <div class="form-group text-center">
                        <button class="exportToExcel btn btn-add btn-sm" >Download</button>
                    </div>
                    </div>
                    </div>
                    </form>
                    <div class="container">
              <div class="row">
                <!-- Statistics -->
                <?php 
	$i=0;
	$unintarr[] = array();
	$discountarr[] = array();
	$roundarr[] = array();
	$catcountarr[] = array();
	foreach($categories as $category){
	    	$unintarr[] = $category['orytotal_sale_qty'];
	        $roundarr[] = $category['totalamount'];
	        $discountarr[] = $category['discount'];
	        $catcountarr[] = $category['food_category'];
	?><?php } ?>
                <div class="statistics col-xl-4 col-sm-6 ">
                  <div class="statistic d-flex align-items-center bg-white has-shadow">
                      <div class="icon bg-red"><i class="fa fa-list-alt"></i></div>
                    <div class="text"><small style="color:black;font-size:20px;">Total Units</small><strong class="ml-5"><?= array_sum($unintarr)??0 ?></strong>
                    <div class="progress" style="height:10px">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" style="width:50%;height:10px"></div>
                            </div>
                       </div>
                  </div>
                </div>
                <div class="statistics col-xl-4 col-sm-6">
                  <div class="statistic d-flex align-items-center bg-white has-shadow">
                      <div class="icon bg-violet"><i class="fa fa-database"></i></div>
                    <div class="text"><small style="color:black;font-size:20px;">Total Sale</small><strong class="ml-5"><i class="fa fa-inr" aria-hidden="true"></i>&nbsp;<?= array_sum($roundarr)??0 ?></strong>
                    <div class="progress" style="height:10px">
                            <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" style="width:60%;height:20px"></div>
                    </div>
                    </div>
                  </div>
                </div>
                <div class="statistics col-xl-4 col-sm-6">
                  <div class="statistic d-flex align-items-center bg-white has-shadow">
                      <div class="icon bg-secondary"><i class="fa fa-picture-o"></i></div>
                    <div class="text"><small style="color:black;font-size:18px;">Categories Sold</small><strong >&nbsp;<?= array_sum($catcountarr)??0 ?></strong>
                    <div class="progress" style="height:10px">
                            <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" style="width:60%;height:20px"></div>
                    </div>
                    </div>
                  </div>
                </div>
                </div>
                </div>
                    <table id="example" class="table table-striped table-bordered table2excel" >
	<thead>
	<tr align="center">
            <th>S.No</th>
            <th>Category Name</th>
            <th>Product Name</th>
            <th>Units</th>
            <th>Discount</th>
            <th>Total Price</th>
	</tr>
	</thead>
	<tbody>
	<?php 
	$i=0;
	$unintarr[] = array();
	$discountarr[] = array();
	$roundarr[] = array();
	foreach($categories as $category){
            
	?>
	<tr>
		<td align="center"><?= $i+1; ?></td>
		<td align="center"><?= $category['food_category']; ?></td>
		<td align="center"><?= $category['title'] ."  ". $category['food_type_name']  ; ?></td>
                <td align="center"><?= $category['orytotal_sale_qty']; ?></td>
                <td align="center"><?= $category['discount']; ?></td>
                <td align="center"><?= $category['totalamount']; ?></td>
                
	</tr>

	
	
	<?php $i++; } ?>
		<tr>
	<td colspan="3" align="center" style="font-size:25px;color:black;"><strong>Grand Total</strong></td>
		<td align="center" style="font-size:20px;color:black;"><strong><?= array_sum($unintarr)??0 ?></strong></td>
		<td align="center" style="font-size:20px;color:black;"><strong><?= array_sum($discountarr)??0 ?></strong></td>
		<td align="center" style="font-size:20px;color:black;"><strong><?= array_sum($roundarr)??0 ?></strong></td>
	</tr>
	</tbody>
	</table>
	</div>
	</div>
	</div>
		</section>
		
		
		<script>
		function pilotviews(){
		    var type = $("#fc_id").val();
			if(type == ''){
				swal(
				'Warning!',
				'Please Select Type',
				'warning'
			);
return false;			
			}
		    
		var sdate = $("#sdate").val();
		var edate = $("#edate").val();
		var fc_id = $("#fc_id").val();
                
        var form=document.createElement('form');
        form.setAttribute('method','post');
        form.setAttribute('action','reportcategory');
        form.setAttribute('target','_self');


    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "sdate");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", sdate);
    form.appendChild(hiddenField);


    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "edate");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", edate);
    form.appendChild(hiddenField);
    
     var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "fc_id");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", fc_id);
    form.appendChild(hiddenField);

    document.body.appendChild(form);
    form.submit();    


}
		</script>
		<script>
$(function() {
    $(".exportToExcel").click(function(e){
	var tablelength = $('.table2excel tr').length;
	if(tablelength){
            var preserveColors = ($('.table2excel').hasClass('table2excel_with_colors') ? true : false);
            $('.table2excel').table2excel({
		exclude: ".noExl",
		name: "Excel Document Name",
		filename: "Category Wise Report" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
		fileext: ".xls",
		exclude_img: true,
		exclude_links: true,
		exclude_inputs: true,
		preserveColors: preserveColors
            });
	}
    });
});
</script>
			<?php
$script = <<< JS
    $('#example').DataTable();
JS;
$this->registerJs($script);
?>
