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
                <h3 class="h4 col-md-6 pl-0 tab-title">Payment Method Section Wise Sale Report</h3>
				<div class="col-md-6 text-right pr-0">
			
				</div>
              </div>


              <div class="card-body">
			  <form class="form-inline" method="POST" action="payment-method-section-sale">
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
            <th>Cash On Dine</th>
            <th>Online Payment</th>
            <th>UPI Scanner</th>
            <th>Card Swipe</th>
	</tr>
	</thead>
	<tbody>
        <?php
        $cash = $online = $upi = $card =[];
        for($i=0;$i<count($res);$i++) { ?>
            <tr>
                <td><?= $i+1; ?></td>
                <td><?= $res[$i]['section_name']; ?></td>
                <td><?= $cash[] = $res[$i]['cash']; ?></td>
                <td><?= $online[] = $res[$i]['online']; ?></td>
                <td><?= $upi[] = $res[$i]['upi']; ?></td>
                <td><?= $card[] = $res[$i]['card']; ?></td>
            </tr>
        <?php } ?>
            <tr>
                <td colspan="2" align="center"><b>Total</b></td>
                <td><b><?= array_sum($cash); ?></b></td>
                <td><b><?= array_sum($online); ?></b></td>
                <td><b><?= array_sum($upi); ?></b></td>
                <td><b><?= array_sum($card); ?></b></td>
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
        form.setAttribute('action','payment-method-section-sale');
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
		filename: "Payment Method Sale Report" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
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
		