
<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use aryelds\sweetalert\SweetAlert;
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
?>
          </header>
          <section>
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header d-flex align-items-center pt-0 pb-0">
                <h3 class="h4 col-md-6 pl-0 tab-title">Recipe Product List</h3>
				<div class="col-md-6 text-right pr-0">
			<!--	<button type="button" class="btn btn-add btn-xs" id="myBtn" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus mr-1"></i> Add Ingredients</button> -->

				</div>
              </div>


              <div class="card-body">
                <div class="table-responsive">   
                  <table id="example" class="table table-striped table-bordered ">
                    <thead>
                      <tr>
                        <th>S No</th>
						<th>Product Name</th>
						<th>Image</th>
						<th>Recipe Description</th>
						<th>Action</th>
                      </tr>
                    </thead>
		    <tbody>
								<?php 
								if(count($productModel) > 0){
								$x=1; 
								function cvf_convert_object_to_array($data) {

    if (is_object($data)) {
        $data = get_object_vars($data);
    }

    if (is_array($data)) {
        return array_map(__FUNCTION__, $data);
    }
    else {
        return $data;
    }
}
								$added_ingred = (cvf_convert_object_to_array(json_decode(($prevRecipeing))));
									foreach($productModel as $productModel){
								?>
                                  <tr>
                                 <td>
                                                    <?php echo $x; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $productModel['title_quantity']; ?>
                                                    </td>
                                                    <td>
														<img  src="<?= Yii::$app->request->baseUrl.'/uploads/productimages/'.$productModel['image'];?>" style="height:50px" />
                                                    </td>

													<td class="icons"><?php if(isset($added_ingred[$productModel['ID']] )){ ?>
														<a onclick="viewrecipe('<?= $productModel['ID'];?>')"><span class="fa fa-eye"></span></a>
														<?php }else { ?>
														Please Add Recpie
														<?php } ?>
													</td>
                                                    <td class="icons"><a onclick="updaterecipe('<?= $productModel['ID'];?>')"><span class="fa fa-pencil"></span>
													</a></td>
										</tr>			
								<?php $x++; } }?>
                       </tbody>
                  </table>

                </div>
              </div>
            </div>
          </div>

<div class="modal" id="myModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      
 <div class="modal-header">
          <h4 class="modal-title">Add Recipe</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
		
		<form method="post" action="saverecipe" id="receipeform">
        <div class="modal-body">

<div id="addedrecipe">

</div>		

		<input type="hidden" id="productid" name="productid" class="form-control">
	<table id="tblAddRow"  class="table table-bordered table-striped">
    <thead>
        <tr>
            <th><input type="checkbox" id="checkedAll"/></th>
            <th>Ingredient</th>
			<th>Quantity</th>
		<!--	<th>Price</th>  -->
			<th>Units</th>
			<th>Action</th>
        </tr>
    </thead>
    <tbody>
        <tr id="1">
            <td>
                <input name="ckcDel[]"  type="checkbox" />
            </td>
            
			<td style="position: relative;">
                <input type="text" name="ingredients[]"  autocomplete="off" id="ingredient1" class="form-control ingredients">
				<span id="err_ingredient1" style="display:none;color:red">Please Add Ingredient</span>
				<span id="err_dup_ingredient1" style="display:none;color:red">Duplicate Ingredient Added</span>
				<span id="err_non_ingredient1" style="display:none;color:red">Not An Ingredient</span>
			
            </td>
			<td>
                <input name="quantity[]" id="quantity1" autocomplete="off" class="form-control numbers">
				<span id="err_quantity1" style="display:none;color:red">Please Add Quantity</span>
            </td>
			<td>
			<select id="qty_units1" name="qty_units[]">
			<option value="">Select Units</option>
			<option value="1">Kg</option>
			<option value="2">Litre</option>
			<option value="3">Pieces</option>
			<option value="4">Packets</option>
			<option value="5">Grams</option>
			<option value="6">Milli Litre</option>
			</select>
			<span id="err_qty_units1" style="display:none;color:red">Please Add Units</span>
			</td>
		<!--	<td>
                <input name="price[]" id="price1" autocomplete="off" class="form-control numbers">
				<span id="err_price1" style="display:none;color:red">Please Add Price</span>
            </td>			-->
            
        </tr>
       
    </tbody>
</table>

		</div>
	   <div class="modal-footer">
	   <button id="btnAddRow" class="btn btn-add btn-xs" type="button">
    Add Row
</button>
		
		<button id="btnAddRec" class="btn btn-add btn-xs btn-hide" type="button">
    Add Recipe
</button>
	
      </div> 
</form>        
       
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

$("#btnAddRec").click(function(){
		chkArr = [];
	var errDisplayStop = 0; 
		var ingredIdNameArr = JSON.parse( '<?php echo $ingredIdNameArr ?>' );	
	 $('#tblAddRow tr').not("thead tr").each(function() {
    if($("#ingredient"+this.id).val() == ''){
		$("#err_ingredient"+this.id).show();
		errDisplayStop = errDisplayStop + 1;
	}else{
		$("#err_ingredient"+this.id).hide();
	if (jQuery.inArray($("#ingredient"+this.id).val(), ingredIdNameArr)!='-1') {
					$("#err_non_ingredient"+this.id).hide();		
		}else{
			$("#err_non_ingredient"+this.id).show();
			errDisplayStop = errDisplayStop + 1;
		}
	}
	if($("#quantity"+this.id).val() == ''){
		$("#err_quantity"+this.id).show();
		errDisplayStop = errDisplayStop + 1;
	}else{
		$("#err_quantity"+this.id).hide();
	}
	if($("#qty_units"+this.id).val() == ''){
		$("#err_qty_units"+this.id).show();
		errDisplayStop = errDisplayStop + 1;
	}else{
		$("#err_qty_units"+this.id).hide();
	}
	/*if($("#price"+this.id).val() == ''){
		$("#err_price"+this.id).show();
		errDisplayStop = errDisplayStop + 1;
	}else{
		$("#err_price"+this.id).hide();
	} */
		if (jQuery.inArray($("#ingredient"+this.id).val(), chkArr)!='-1') {
			$("#err_dup_ingredient"+this.id).show();
			errDisplayStop = errDisplayStop + 1;				
		}else{
			$("#err_dup_ingredient"+this.id).hide();
		}
		chkArr.push($("#ingredient"+this.id).val());
  
  

  
  });
	if(errDisplayStop == 0 )
	{
	    		$('.btn-hide').hide();
		$("#receipeform").submit();
	}else{

		return false;
	}
	

});
function updaterecipe(id){
	$("#productid").val(id);
	var ar = JSON.parse( '<?php echo $prevRecipeing ?>' );
	//alert(ar['78'][0]['ID']);
	$("#addedrecipe").html('');
	var strAppend = '';
	
	if(typeof ar[id] === 'undefined') {
    // does not exist
}
else {
	if(ar[id].length>0)
	{
		var ingredUnits = {"1":"Kg","2":"Litre","3":"Pieces","4":"Packets","5":"Grams","6":"Milli Litre"};
		var strAppend = '<table id="tblAddRowsupdate" class="table table-bordered table-striped">\
    <thead>\
        <tr>\
            <th>Ingredient</th>\
			<th>Quantity</th>\
			<th>Action</th>\
        </tr>\
    </thead>';
	for(var p=0,r=1;p<ar[id].length;p++,r++){
		strAppend += '<tr id="rowid'+r+'"><td>'+ar[id][p]['item_name']+'</td><td>'+ar[id][p]['ingred_quantity']+' ('+ingredUnits[ar[id][p]['ingred_units']]+')'+'</td>\
		<td><a onclick="deleteaddedingredient('+ar[id][p]['ID']+',\''+ar[id][p]['item_name']+'\','+r+')" onchange="toggleOption(this)" class="delrow"><i class="fa fa-trash border-red text-red"></i></a></td></tr>';
	}
    $("#addedrecipe").append(strAppend);
	}
}
	

	
	$('#myModal').modal('show');
}


function deleteaddedingredient(id,item_name,trid){
	
	swal({
				title: "Are you sure?", 
				text: "You are going to delete ingredient "+item_name, 
				type: "warning",
				confirmButtonText: "Yes, Delete!",
				showCancelButton: true
		    })
		    	.then((result) => {
					if (result.value) {
						var request = $.ajax({
						url: "deleteingredientfromrecipe",
						type: "POST",
						data: {id : id},
						}).done(function(msg) {
	$("#rowid"+trid).hide();					
						});
					} else if (result.dismiss === 'cancel') {
					    swal(
					      'Cancelled',
					      'Your stay here :)',
					      'error'
					    )
					}
				});
}
// Add row the table
$('#btnAddRow').on('click', function() {
    var lastRow = $('#tblAddRow tbody tr:last').html();
var lastid = $('#tblAddRow tr:last').attr('id');
var currentId = parseInt(lastid)+1;   
  lastRow =  '<td>\
                <input name="ckcDel[]" type="checkbox">\
            </td>\
            <td style="position: relative;">\
                <input type="text" name="ingredients[]" autocomplete="off" id="ingredient'+currentId+'" class="form-control ingredients">\
				<span id="err_ingredient'+currentId+'" style="display:none;color:red">Please Add Ingredient</span>\
								<span id="err_dup_ingredient'+currentId+'" style="display:none;color:red">Duplicate Ingredient Added</span>\
								<span id="err_non_ingredient'+currentId+'" style="display:none;color:red">Not An Ingredient</span>\
				<ul class="typeahead dropdown-menu"></ul>\
            </td>\
			<td>\
                <input name="quantity[]" autocomplete="off" id="quantity'+currentId+'" class="form-control numbers">\
				<span id="err_quantity'+currentId+'" style="display:none;color:red">Please Add Quantity</span>\
            </td>\
						<td>\
			<select id="qty_units'+currentId+'" name="qty_units[]">\
			<option value="">Select Units</option>\
			<option value="1">Kg</option>\
			<option value="2">Litre</option>\
			<option value="3">Pieces</option>\
			<option value="4">Packets</option>\
			<option value="5">Grams</option>\
			<option value="6">Milli Litre</option>\
			</select>\
			<span id="err_qty_units'+currentId+'" style="display:none;color:red">Please Add Units</span>\
			</td>';
			//<td>
              //  <input name="price[]" autocomplete="off" id="price'+currentId+'" class="form-control numbers">\
				//<span id="err_price'+currentId+'" style="display:none;color:red">Please Add Price</span>\
            //</td>		\
lastRow +=  '<td><a href="#" class="delrow"><i class="fa fa-trash border-red text-red"></i></a></td>			';
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



// Add button Delete in row

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
function displayResult(item)
{
	var addedIngreds = JSON.parse( '<?php echo $addedIngreds ?>' );	
		  if (jQuery.inArray(item.value, addedIngreds)!='-1') {
		  alert("Not It Is");		
	  }else{
		  alert("It Is");
	  }
}
$('#ingredient1').typeahead({
       ajax: 'autocompleteingredient',

					displayField: 'item_name',
					valueField: 'ID',
					
 
    });
	function viewrecipe(id)
	{
		        var form=document.createElement('form');
        form.setAttribute('method','post');
        form.setAttribute('action','viewrecpie');
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
