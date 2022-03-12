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
                <h3 class="h4 col-md-6 pl-0 tab-title">Section Wise Sale Report</h3>
				<div class="col-md-6 text-right pr-0">
			
				</div>
              </div>


              <div class="card-body">
			  <form class="form-inline" method="POST" action="section-wise-sale">
			  <div class="form-group">
                    <label class="control-label">Start Date:</label>
                  <div class="input-group mb-3 mr-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                  </div>
                  <input type="text" class="form-control datepicker1" id="sdate" name="edate" placeholder="End Date" value="<?= $sdate ; ?>">
                </div>
				</div>
			   <div class="form-group">
                    <label class="control-label">End Date:</label>
                  <div class="input-group mb-3 mr-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                  </div>
                  <input type="text" class="form-control datepicker2" id="edate" name="edate" placeholder="End Date" value="<?= $edate ; ?>">
                </div>
				</div>
			   
			   <div class="col">
                  <div class="form-group">
				<button type="button" onclick="billviews()" class="btn btn-add btn-sm btn-search">Search</button>
			   </div></div>
			   <div class="col">
                    <div class="form-group text-center">
                        <button class="exportToExcel btn btn-add btn-sm" >Download</button>
                    </div>
                    </div>
			 </form>
			 <table class="table table-striped table-bordered  table2excel" id="myTable" cellspacing="0">
	<thead>
	<tr>
            <th>S.No</th>
            <th>Section Name</th>
            <th>Amount</th>
            <th>Tax</th>
            <th>Tip</th>
            <th>Coupon Amount</th>
            <th>Discount</th>
            <th>Total Amount</th>
	</tr>
	</thead>
	<tbody>
        <?php
        $totalamount = $tax = $amount =  [];
        $couponamount = $tips = $discount_number = [];
        for($i=0;$i<count($res);$i++) { ?>
            <tr>
                <td><?= $i+1; ?></td>
                <td><?= $res[$i]['section_name']; ?></td>
                <td><?= $amount[] = round($res[$i]['amount'],2); ?></td>
                <td><?= $tax[] = round($res[$i]['tax'],2); ?></td>
                <td><?= $tips[] = round($res[$i]['tips'],2); ?></td>
                <td><?= $couponamount[] = round($res[$i]['couponamount'],2); ?></td>
                <td><?= $discount_number[] = round($res[$i]['discount_number'],2); ?></td>
                <td><?= $totalamount[] = round($res[$i]['totalamount'],2); ?></td>
            </tr>
        <?php } ?>
            <tr>
                <td colspan="2" align="center"><b>Total</b></td>
                <td><b><?= array_sum($amount); ?></b></td>
                <td><b><?= array_sum($tax); ?></b></td>
                <td><b><?= array_sum($tips); ?></b></td>
                <td><b><?= array_sum($couponamount); ?></b></td>
                <td><b><?= array_sum($discount_number); ?></b></td>
                <td><b><?= array_sum($totalamount); ?></b></td>
            </tr>
	</tbody>
	</table>
			
</div>
</div>
</div>
		</section>
		
		
		<script>
		function billviews(){
	
		var sdate = $("#sdate").val();
		var edate = $("#edate").val();
        var form=document.createElement('form');
        form.setAttribute('method','post');
        form.setAttribute('action','section-wise-sale');
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
		filename: "Section Wise Sale Report" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
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
		