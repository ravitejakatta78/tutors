
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
                <h3 class="h4 col-md-6 pl-0 tab-title">Raise Request</h3>
				<div class="col-md-6 text-right pr-0">
				<button type="button" class="btn btn-add btn-xs" id="myBtn" data-toggle="modal" data-target="#myModal">
				<i class="fa fa-plus mr-1"></i> Request</button>

				</div>
              </div>


              <div class="card-body">
			  <form class="form-inline" method="POST" action="raiserequest">
			      <div class="form-group">
		                <label class="control-label">Vendor type</label>
		                <div class="input-group mb-3 mr-3">
		                    <input type="text" id="vendorname" name="vendorname" autocomplete="off" class="form-control">
		                    <span id="err_vendorname1" style="display:none;color:red">Please Enter Vendor Type</span>
		                </div>
		           </div>
		           <div class="form-group">
		                <label class="control-label">Store Name</label>
		                <div class="input-group mb-3 mr-3">
		                    <input type="text" id="vendorname" name="vendorname" autocomplete="off" class="form-control">
		                    <span id="err_vendorname" style="display:none;color:red">Please Enter Store Name</span>
		                </div>
		           </div>
                  <div class="form-group">
                    <label class="control-label">Request Date:</label>
                  <div class="input-group mb-3 mr-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                  </div>
                  <input type="text" class="form-control datepicker1" name="sdate" placeholder="Start Date" value="<?= $sdate ; ?>">
                </div>
                  </div>
                  <div class="form-group">
                    <input type="submit" value="Search" class="btn btn-add btn-sm btn-search"/>
                  </div>
                </form>
                <div class="table-responsive">   
                  
                </div>
              </div>
            </div>
          </div>

<div class="modal" id="myModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Raise Request</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
		<form action="saveinventory" id="addinventory" method="POST">
		<!--<div class="form-group row">-->
		<!--<label class="control-label col-md-4">Search Items</label>-->
		<!--<div class="col-md-8">-->
		<!--<input type="text" name="ingredients[]"  autocomplete="off" id="ingredient1" class="form-control ingredients">-->
		<!--<span id="err_vendorname" style="display:none;color:red">Search items</span>-->
		<!--</div>-->
		<!--</div>-->
		<div class="form-group row">
		            <label class="control-label col-md-2">Vendor Type</label>
		            <div class="col-md-4">
		            <select id="vendortype" name="vendortype" class="form-control">
		                <option value="">Select</option>
		                <?php for($vt = 0;$vt <count($vendorModel);$vt++) { ?>
		                <option value="<?= $vendorModel[$vt]['ID']; ?>"><?= $vendorModel[$vt]['vendor_type']; ?></option>
		                <?php } ?>
		</select>
		            <span id="err_vendorname" style="display:none;color:red">Please Select Vendor Type</span>
		       </div>
		            <label class="control-label col-md-2">Vendor Name</label>
		            <div class="col-md-4">
		            <select id="vendorname" name="vendorname" class="form-control">
		                <option value="">Select</option>
		                <?php for($vt = 0;$vt <count($resPurchaseDetail);$vt++) { ?>
		                <option value="<?= $resPurchaseDetail[$vt]['ID']; ?>"><?= $resPurchaseDetail[$vt]['vendor_name']; ?></option>
		                <?php } ?>
		</select>
		            <span id="err_vendorname" style="display:none;color:red">Please Select Vendor Type</span>
		       </div>
		       </div>
		       
	<table id="tblAddRow"  class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>S.NO</th>
            <th>Item Name</th>
			<th>Quantity</th>
			<th>Units</th>
			<th>Suggestions</th>
			<th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php $x=1; ?>
        <tr id="1">
            <td>
               <?php echo $x; ?>
            </td>
            
			<td style="position: relative;">
                <input type="text" name="ingredients[]"  autocomplete="off" id="ingredient1" class="form-control ingredients" placeholder="Enter Item Name">
				<span id="err_ingredient1" style="display:none;color:red">Please Enter Item Name</span>
				<span id="err_dup_ingredient1" style="display:none;color:red">Duplicate Item Name Added</span>
				<span id="err_non_ingredient1" style="display:none;color:red">Not An Item</span>
			
            </td>
			<td>
                <input name="quantity[]" id="quantity1" autocomplete="off" class="form-control numbers" placeholder="Enter Quantity">
				<span id="err_quantity1" style="display:none;color:red">Please Add Quantity</span>
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
                <input name="price[]" id="price1" autocomplete="off" class="form-control numbers" placeholder="Enter Suggestions">
				<span id="err_price1" style="display:none;color:red">Please Add suggestions</span>
            </td>			
            
        </tr>
       
    </tbody>
</table>

	   <div class="modal-footer">
	   <button id="btnAddRow" class="btn btn-add btn-xs" type="button">
    Add Item
</button>
		
		<button id="btnAddRec" class="btn btn-add btn-xs" type="button">
    Raise Request
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
  lastRow =  '<td>\
                <?php $x=1;?>
                 <?php echo ++$x; ?>\
            </td>\
            <td style="position: relative;">\
                <input type="text" name="ingredients[]" autocomplete="off" id="ingredient'+currentId+'" class="form-control ingredients" placeholder="Enter Item Name">\
				<span id="err_ingredient'+currentId+'" style="display:none;color:red">Please Add Enter Item Name</span>\
				<span id="err_dup_ingredient'+currentId+'" style="display:none;color:red">Duplicate Item Name Added</span>\
				<span id="err_non_ingredient'+currentId+'" style="display:none;color:red">Not An Item</span>\
				<ul class="typeahead dropdown-menu"></ul>\
            </td>\
			<td>\
                <input name="quantity[]" autocomplete="off" id="quantity'+currentId+'" class="form-control numbers" placeholder="Enter Quantity">\
				<span id="err_quantity'+currentId+'" style="display:none;color:red">Please Add Quantity</span>\
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
                <input name="price[]" autocomplete="off" id="price'+currentId+'" class="form-control numbers" placeholder="Enter Suggestions">\
				<span id="err_price'+currentId+'" style="display:none;color:red">Please Add Suggestions</span>\
            </td>		\
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
	var purchaseBody = '<table id="example" class="table table-striped table-bordered ">\
                    <thead>\
                      <tr>\
                        <th>S No</th>\
						<th>Ingredient Name</th>\
						<th>Ingredient Quantity</th>\
						<th>Ingredient Price</th>\
												                 </tr>\
                    </thead><tbody>';
					var totalprice = 0;
	if(purchaseDet.length > 0){
		for(var pb = 0,sn=1;pb<purchaseDet.length;pb++,sn++){
			
			purchaseBody+='<tr><td>'+sn+'</td><td>'+purchaseDet[pb]['ingredient_name']+'</td>\
			<td>'+purchaseDet[pb]['purchase_quantity']+' ('+purchaseQtyArr[purchaseDet[pb]['purchase_qty_type']]+')'+'</td>\
			<td><i class="fa fa-inr"></i>\
			'+purchaseDet[pb]['purchase_price']+'</td></tr>';
		totalprice = totalprice + parseFloat(purchaseDet[pb]['purchase_price']);
		}
	}
	purchaseBody +='</tbody>\
	<tfoot>\
			<tr>\
			<td colspan="3" class="text-right"><b>Total Amount</b></td>\
			<td><i class="fa fa-inr"></i> <b>'+totalprice+'</b></td>\
			</tr>\
			</tfoot>\
	</table>';
	$('#purchasedetailbody').html(purchaseBody);
	$('#updatepurchasedetail').modal('show');
});
	}
</script>
