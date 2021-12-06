
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
	$purchaseTypeArr = Utility::purchase_quantity_type();
?>
          </header>
		  
          <section>
		<?= \Yii::$app->view->renderFile('@app/views/merchant/_stockhorizontaltab.php',['actionId'=>$actionId]); ?>
		  <div class="clearfix"></div>
          <div class="col-lg-12">
		  
            <div class="card">
              <div class="card-header d-flex align-items-center pt-0 pb-0">
                <h3 class="h4 col-md-6 pl-0 tab-title">Inventory List</h3>
				<div class="col-md-6 text-right pr-0">
				<button type="button" class="btn btn-add btn-xs" id="myBtn" data-toggle="modal" data-target="#myModal">
				<i class="fa fa-plus mr-1"></i> Add Stock</button>

				</div>
              </div>


              <div class="card-body">
			  <form class="form-inline" method="POST" action="addinventory">
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
                <div class="table-responsive">   
                  <table id="example" class="table table-striped table-bordered ">
                    <thead>
                      <tr>
                        <th>S No</th>
						<th>Vendor Name</th>
						<th>Purchase Date</th>
						<th>Purchase Number</th>
						<th>Purchase Amount</th>
						<th>Purchase Detail</th>
						
                      </tr>
                    </thead>
					<tbody>
					
					<?php for($i=0;$i<count($resPurchaseDetail);$i++){?>
						<tr>
						<td><?= $i+1;?></td>
						<td><?= $resPurchaseDetail[$i]['vendor_name'];?></td>
						<td><?= $resPurchaseDetail[$i]['reg_date'];?></td>
						<td><?= $resPurchaseDetail[$i]['purchase_number'];?></td>
						<td><?= $resPurchaseDetail[$i]['purchase_amount'];?></td>
						<td  class="icons"><a onclick="viewpurchasedetail('<?php echo $resPurchaseDetail[$i]['ID']; ?>')"  ><span class="fa fa-eye"></span></a></td>

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
          <h4 class="modal-title">Add Stock</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
		<form action="saveinventory" id="addinventory" method="POST">
		        <div class="col-md-12 text-right pr-0">
		            <a href="../merchant/vendorlist" class="btn btn-info" role="button"><i class="fa fa-plus mr-1"></i>Add Vendor</a>
		        </div><br>
		        <div class="form-group row">
		            <label class="control-label col-md-2">Vendor Type</label>
		            <div class="col-md-2">
		            <select id="vendortype" name="vendortype" class="form-control" onchange="getVendorName()">
		                <option value="">Select</option>
						<?php 
						$vendorTypeArr = array_unique(array_column($vendorModel,'vendor_type'));
						for($vt = 0;$vt <count($vendorTypeArr);$vt++) { ?>
		                <option value="<?= $vendorTypeArr[$vt]; ?>"><?= $vendorTypeArr[$vt]; ?></option>
		                <?php } ?>
		</select>
		            <span id="err_vendortype" style="display:none;color:red">Please Select Vendor Type</span>
		       </div>
		            <label class="control-label col-md-2">Vendor Name</label>
		            <div class="col-md-2">
		            <select id="vendorname" name="vendorname" class="form-control">
		                <option value="">Select</option>
		                
		</select>
		            <span id="err_vendorname" style="display:none;color:red">Please Select Vendor Name</span>
		       </div>
		        <label class="control-label col-md-2">Vendor Bill No</label>
		        <div class="col-md-2">
		        <input name="billno" id="billno" autocomplete="off" class="form-control numbers">
				<span id="err_billno" style="display:none;color:red">Please Add Bill No</span>
		        </div>
		        </div>
		        <table id="tblAddRow1"  class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Amount</th>
			<th>Paid</th>
			<th>Balance</th>
			<th>TAX/GST</th>
			<th>Payment Type</th>
			<th>Transaction ID</th>
        </tr>
    </thead>
    <tbody>
        <tr>
			<td style="position: relative;">
                <input name="amount" id="amount" autocomplete="off" class="form-control numbers">
				<span id="err_amount" style="display:none;color:red">Please Add Amount</span>
			
            </td>
			<td style="position: relative;">
                <input name="paid" id="paid" autocomplete="off" class="form-control numbers">
				<span id="err_paid" style="display:none;color:red">Please Add Paid</span>
			
            </td>
			<td style="position: relative;">
                <input name="balance" id="balance" autocomplete="off" class="form-control numbers">
				<span id="err_balance" style="display:none;color:red">Please Add Balance</span>
			
            </td>
			<td>
                <input name="TAX" id="TAX" autocomplete="off" class="form-control numbers">
				<span id="err_TAX" style="display:none;color:red">Please Add TAX/GST</span>
            </td>
            <td>
                <input name="payment_type" id="payment_type" autocomplete="off" class="form-control ">
				<span id="payment_type" style="display:none;color:red">Please Add Payment Type</span>
            </td>
            <td>
                <input name="transaction_id" id="transaction_id" autocomplete="off" class="form-control ">
				<span id="transaction_id" style="display:none;color:red">Please Add Transaction ID</span>
            </td>
            
        </tr>
       
    </tbody>
</table>
	<table id="tblAddRow"  class="table table-bordered table-striped">
    <thead>
        <tr>
            <!--<th><input type="checkbox" id="checkedAll"/></th>-->
            <th rowspan="2">Purchased Item</th>
            <th colspan="2" class="text-center">Bulk</th>
            <!--<th rowspan="2">Qnty</th>-->
			<th rowspan="2">Quantity</th>
			<th rowspan="2">Price</th>
			<th rowspan="2">SUM</th>
			<th rowspan="2">Volume</th>
			<th rowspan="2">Effective Price</th>
			<th rowspan="2">Action</th>
        </tr>
        <tr>
                        <td>Quntity</td>
                        <td>Units</td>
        </tr>                
    </thead>
    <tbody>
        <tr id="1">
            <!--<td>-->
                <!--<input name="ckcDel[]"  type="checkbox" />-->
            <!--</td>-->
            
			<td style="position: relative;">
                <input type="text" name="ingredients[]" autocomplete="off" id="ingredient1" class="form-control ingredients">
				<span id="err_ingredient1" style="display:none;color:red">Please Add Ingredient</span>
				<span id="err_dup_ingredient1" style="display:none;color:red">Duplicate Ingredient Added</span>
				<span id="err_non_ingredient1" style="display:none;color:red">Not An Ingredient</span>
			
            </td>
            <td>
                <input name="bulk[]" id="bulk1" onchange="calQtyPrice('1','1')" autocomplete="off" class="form-control numbers">
				<span id="err_bulk1" style="display:none;color:red">Please Add Bulk</span>
            </td>
            <td>
                <select id="qty_type1" name="qtytype[]"class="form-control qtytype">
				<option value="">Select</option>
				<?php foreach($purchaseTypeArr as $typeVal => $typeKey)
				{ ?>
					<option value="<?= $typeVal; ?>"><?= $typeKey; ?></option>
				<?php } ?>
				</select>
				<span id="err_qty_type1" style="display:none;color:red">Please Add Quantity Type</span>
            </td>
			<td>
                <input name="quantity[]" id="quantity1" onchange="calQtyPrice('1','1')" autocomplete="off" class="form-control numbers">
				<span id="err_quantity1" style="display:none;color:red">Please Add Quantity</span>
            </td>
			
			<td>
                <input name="price[]" id="price1" onchange="calQtyPrice('1','1')" autocomplete="off" class="form-control numbers fa fa-inr">
				<span id="err_price1" style="display:none;color:red">Please Add Price</span>
            </td>
            <td>
                <input name="sum[]" id="sum1" onchange="calQtyPrice('1','2')" autocomplete="off" class="form-control numbers">
				<span id="err_sum1" style="display:none;color:red">Please Add Sum</span>
            </td>
            <td>
                <input name="volume[]" id="volume1" autocomplete="off" class="form-control numbers">
				<span id="err_volume1" style="display:none;color:red">Please Add Volume</span>
            </td>
            <td>
                <input name="effective_price[]" id="effective_price1" autocomplete="off" class="form-control numbers">
				<span id="err_effective_price1" style="display:none;color:red">Please Add Effective Price</span>
            </td>
            
        </tr>
       
    </tbody>
</table>

	   <div class="modal-footer">
	   <button id="btnAddRow" class="btn btn-add btn-xs" type="button">
    Add Row
</button>
		
		<button id="btnAddRec" class="btn btn-add btn-xs" type="button">
    Add Inventory
</button>
	
      </div>
		</form>
		</div>

        
      </div>
    </div>
  </div>
  
    <div id="updatepurchasedetail" class="modal fade" role="dialog">
<div class="modal-dialog modal-xl" >
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Purchase Details</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
	    <div class="modal-body" id="purchasedetailbody">
					
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
$('.numbers').on('keypress',function (event) {
    if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
        event.preventDefault();
    }
    var input = $(this).val();
    if ((input.indexOf('.') != -1) && (input.substring(input.indexOf('.')).length > 2)) {
        event.preventDefault();
    }
});

// Add row the table
$('#btnAddRow').on('click', function() {
    var lastRow = $('#tblAddRow tbody tr:last').html();
var lastid = $('#tblAddRow tr:last').attr('id');
//var purchaseTypeArr = {"1":"Kg","2":"Litre","3":"Pieces":"4"=>"Packets"];


var currentId = parseInt(lastid)+1;   
  lastRow =  '<td style="position: relative;">\
                <input type="text" name="ingredients[]" autocomplete="off" id="ingredient'+currentId+'" class="form-control ingredients">\
				<span id="err_ingredient'+currentId+'" style="display:none;color:red">Please Add Ingredient</span>\
				<span id="err_dup_ingredient'+currentId+'" style="display:none;color:red">Duplicate Ingredient Added</span>\
				<span id="err_non_ingredient'+currentId+'" style="display:none;color:red">Not An Ingredient</span>\
				<ul class="typeahead dropdown-menu"></ul>\
            </td>\
			<td>\
                <input name="bulk[]" autocomplete="off" id="bulk'+currentId+'" onchange=calQtyPrice(\''+currentId+'\',\'1\') class="form-control numbers">\
				<span id="err_bulk'+currentId+'" style="display:none;color:red">Please Add Bulk</span>\
            </td>\
						<td>\
                <select id="qty_type'+currentId+'" name="qtytype[]"class="form-control qtytype">\
				<option value="">Select</option>\
				<option value="1">Kg</option>\
				<option value="2">Litre</option>\
				<option value="3">Pieces</option>\
				<option value="4">Packets</option>\
								<option value="5">Grams</option>\
												<option value="6">Milli Litre</option>\
				</select>\
				<span id="err_qty_type'+currentId+'" style="display:none;color:red">Please Add Quantity Type</span>\
            </td>\
            <td>\
                <input name="quantity[]" autocomplete="off" id="quantity'+currentId+'" onchange=calQtyPrice(\''+currentId+'\',\'1\') class="form-control numbers">\
				<span id="err_quantity'+currentId+'" style="display:none;color:red">Please Add Quantity</span>\
            </td>\
			<td>\
                <input name="price[]" autocomplete="off" id="price'+currentId+'" onchange=calQtyPrice(\''+currentId+'\',\'1\') class="form-control numbers">\
				<span id="err_price'+currentId+'" style="display:none;color:red">Please Add Price</span>\
            </td>\
            <td>\
                <input name="sum[]" autocomplete="off" id="sum'+currentId+'" onchange=calQtyPrice(\''+currentId+'\',\'2\') class="form-control numbers">\
				<span id="err_sum'+currentId+'" style="display:none;color:red">Please Add Price</span>\
            </td>\
            <td>\
                <input name="volume[]" autocomplete="off" id="volume'+currentId+'" class="form-control numbers">\
				<span id="err_volume'+currentId+'" style="display:none;color:red">Please Add Price</span>\
            </td>\
            <td>\
                <input name="effective_price[]" autocomplete="off" id="effective_price'+currentId+'" class="form-control numbers">\
				<span id="err_effective_price'+currentId+'" style="display:none;color:red">Please Add Price</span>\
            </td>\
<td><a href="#" class="delrow"><i class="fa fa-trash border-red text-red"></i></a></td>			';
    $('#tblAddRow tbody').append('<tr id='+currentId+'>' + lastRow + '</tr>');



	$('select').select2();

$('#ingredient'+currentId+'').typeahead({
       ajax: 'autocompleteingredient',

					displayField: 'item_name',
					valueField: 'ID',
					
    });

 //   $('#tblAddRow tbody').append('<tr><input type="text" id="in2"></tr>');  
  $('#tblAddRow tbody tr:last input').val('');

	$('.numbers').on('keypress',function (event) {
		if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
			event.preventDefault();
		}
		var input = $(this).val();
		if ((input.indexOf('.') != -1) && (input.substring(input.indexOf('.')).length > 2)) {
			event.preventDefault();
		}
	});
});

$('#tblAddRow tbody tr')
    .find('td')
    //.append('<input type="button" value="Delete" class="del"/>')
    .parent() //traversing to 'tr' Element
    .append('<td><a href="#" class="delrow"><i class="fa fa-trash border-red text-red"></i></a></td>');

// For select all checkbox in table
$('#checkedAll').click(function (e) {
	//e.preventDefault();
    $(this).closest('#tblAddRow').find('td input:checkbox').prop('checked', this.checked);
});
// Delete row on click in the table
$('#tblAddRow').on('click', 'tr a', function(e) {
    var lenRow = $('#tblAddRow tbody tr').length;
    e.preventDefault();
    if (lenRow == 1 || lenRow <= 1) {
        alert("Can't remove all row!");
    } else {
        $(this).parents('tr').remove();
    }
});

$('#ingredient1').typeahead({
       ajax: 'autocompleteingredient',

					displayField: 'item_name',
					valueField: 'ID',
					
 
    });
	$('#vendorname').typeahead({
       ajax: 'autocompletevendor',

					displayField: 'store_name',
					valueField: 'ID',
				//	onSelect : displayResult,
 
    });	
	
	
$("#btnAddRec").click(function(){
	chkArr = [];
	var errDisplayStop = 0; 
	var ingredIdNameArr = JSON.parse( '<?php echo $ingredIdNameArr ?>' );	
	var vendortype = $("#vendortype").val();
	if(vendortype == ''){
		$("#err_vendortype").show();
		errDisplayStop = errDisplayStop + 1;
	}
	else{
		$("#err_vendortype").hide();
	}

	var vendorname = $("#vendorname").val();
	if(vendorname == ''){
		$("#err_vendorname").show();
		errDisplayStop = errDisplayStop + 1;
	}
	else{
		$("#err_vendorname").hide();
	}
	 $('#tblAddRow tr').not("thead tr").each(function() {
    if($("#ingredient"+this.id).val() == ''){
		$("#err_ingredient"+this.id).show();
		errDisplayStop = errDisplayStop + 1;
	}else{
		$("#err_ingredient"+this.id).hide();
	}
	if($("#quantity"+this.id).val() == ''){
		$("#err_quantity"+this.id).show();
		errDisplayStop = errDisplayStop + 1;
	}else{
		$("#err_quantity"+this.id).hide();
	}
	if($("#price"+this.id).val() == ''){
		$("#err_price"+this.id).show();
		errDisplayStop = errDisplayStop + 1;
	}else{
		$("#err_price"+this.id).hide();
	}
	if($("#sum"+this.id).val() == ''){
		$("#err_sum"+this.id).show();
		errDisplayStop = errDisplayStop + 1;
	}else{
		$("#err_sum"+this.id).hide();
	}
	if($("#bulk"+this.id).val() == ''){
		$("#err_bulk"+this.id).show();
		errDisplayStop = errDisplayStop + 1;
	}else{
		$("#err_bulk"+this.id).hide();
	}
	if($("#effective_price"+this.id).val() == ''){
		$("#err_effective_price"+this.id).show();
		errDisplayStop = errDisplayStop + 1;
	}else{
		$("#err_effective_price"+this.id).hide();
	}
	if($("#volume"+this.id).val() == ''){
		$("#err_volume"+this.id).show();
		errDisplayStop = errDisplayStop + 1;
	}else{
		$("#err_volume"+this.id).hide();
	}
	if($("#qty_type"+this.id).val() == ''){
		$("#err_qty_type"+this.id).show();
		errDisplayStop = errDisplayStop + 1;
	}else{
		$("#err_qty_type"+this.id).hide();
	}
	
	if (jQuery.inArray($("#ingredient"+this.id).val(), chkArr)!='-1') {
			$("#err_dup_ingredient"+this.id).show();
			errDisplayStop = errDisplayStop + 1;				
	}
	else{
			$("#err_dup_ingredient"+this.id).hide();
	}
	chkArr.push($("#ingredient"+this.id).val());
    if (jQuery.inArray($("#ingredient"+this.id).val(), ingredIdNameArr)!='-1') {
							$("#err_non_ingredient"+this.id).hide();
		}else{
			$("#err_non_ingredient"+this.id).show();
			errDisplayStop = errDisplayStop + 1;
		}
  });
	 
	if(errDisplayStop == 0 )
	{
		$("#addinventory").submit();
	}else{
		return false;
	}
	

});	
	function viewpurchasedetail(id){
		var request = $.ajax({
  url: "viewpurchasedetail",
  type: "POST",
  data: {id : id},
}).done(function(msg) {
	var purchaseQtyArr = {"1":"Kg","2":"Litre","3":"Pieces","4":"Packets"}  ;
	
	
	var purchaseDet = JSON.parse(msg);
	var purchaseBody = '<div class="form-group row">\
		            <label class="control-label col-md-2">Vendor Name : </label>\
		            <div class="col-md-2">'+purchaseDet[0]['vendor_name']+'\
					</div>\
		        <label class="control-label col-md-2">Vendor Bill No :</label>\
		        <div class="col-md-2">'+purchaseDet[0]['vendor_bill_no']+'\
		        </div>\
				</div>\
				<table id="tblAddRow1"  class="table table-bordered table-striped">\
    <thead><tr>\
            <th>Amount</th>\
			<th>Paid</th>\
			<th>Balance</th>\
			<th>TAX/GST</th>\
			<th>Payment Type</th>\
			<th>Transaction ID</th>\
        </tr>\
    </thead>\
	<tbody>\
	<tr><td>'+purchaseDet[0]['purchase_amount']+'</td>\
	<td>'+purchaseDet[0]['amount_paid']+'</td>\
	<td>'+purchaseDet[0]['balance_amount']+'</td>\
	<td>'+purchaseDet[0]['purchase_gst']+'</td>\
	<td>'+purchaseDet[0]['purchase_payment_type']+'</td>\
	<td>'+purchaseDet[0]['purchase_transcation_id']+'</td>\</tr>\
</tbody></table>\
	<table id="example" class="table table-striped table-bordered ">\
                    <thead>\
					<tr>\
					<th rowspan="2">S.No</th>\
            <th rowspan="2">Purchased Item</th>\
            <th colspan="2" class="text-center">Bulk</th>\
			<th rowspan="2">Quantity</th>\
			<th rowspan="2">Price</th>\
			<th rowspan="2">SUM</th>\
			<th rowspan="2">Volume</th>\
			<th rowspan="2">Effective Price</th>\
        </tr>\
        <tr>\
                        <td>Quntity</td>\
                        <td>Units</td>\
        </tr>    </thead><tbody>';
					var totalprice = 0;
	if(purchaseDet.length > 0){
		for(var pb = 0,sn=1;pb<purchaseDet.length;pb++,sn++){
			
			purchaseBody+='<tr><td>'+sn+'</td><td>'+purchaseDet[pb]['ingredient_name']+'</td>\
			<td>'+purchaseDet[pb]['bag_quantity']+'</td><td>'+purchaseQtyArr[purchaseDet[pb]['purchase_qty_type']]+'</td>\
			<td>'+purchaseDet[pb]['each_quantity']+'</td><td><i class="fa fa-inr"></i>'+purchaseDet[pb]['quantity_price']+'</td>\
			<td><i class="fa fa-inr"></i>\
			'+purchaseDet[pb]['purchase_price']+'</td><td>'+purchaseDet[pb]['purchase_quantity']+'</td><td>'+purchaseDet[pb]['effective_price']+'</td></tr>';
		}
	}
	purchaseBody +='</tbody>\
	</table>';
	$('#purchasedetailbody').html(purchaseBody);
	$('#updatepurchasedetail').modal('show');
});
	}

	function getVendorName(){
		let vendortype = $("#vendortype").val();
		var request = $.ajax({
  url: "getvendornames",
  type: "POST",
  data: {vendortype : vendortype},
}).done(function(msg) {
var res =	JSON.parse(msg);
$("#vendorname").html('');
$("#vendorname").append('<option value="">Select Name</option>');
for(var i=0;i<res.length;i++){
	$("#vendorname").append('<option value=\''+res[i]['ID']+'\'>'+res[i]['store_name']+'</option>');
}
});
	}
	function calQtyPrice(id,caltype){
		var quantity = $("#quantity"+id).val();
		var sumvalue = $("#sum"+id).val();
		var price = $("#price"+id).val();
		var bulk = $("#bulk"+id).val();
	if(quantity > 0 && price > 0 && caltype == '1'){
		var calCulatePrice = parseFloat(quantity) * parseFloat(price); 
		$("#sum"+id).val(calCulatePrice);
	}
	else if(quantity > 0 && price > 0 && caltype == '2'){
		var calCulatePrice = (parseFloat(sumvalue) / parseFloat(quantity)).toFixed(2)  ; 
		$("#price"+id).val(calCulatePrice);
	}

	if(bulk > 0 && quantity > 0){
		var calCulatePrice = parseFloat(quantity) * parseFloat(bulk); 
		$("#volume"+id).val(calCulatePrice);
	}

var volume = $("#volume"+id).val();
var sum = $("#sum"+id).val();

if(volume > 0 &&  sum > 0){
	var calCulatePrice =(parseFloat(volume) / parseFloat(sum)).toFixed(2)  ; 
			$("#effective_price"+id).val(calCulatePrice);

}
	

	}
</script>
