<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>
    

		
	<div class="form-horizontal">
		<div class="form-group row">
		<label class="control-label col-md-4">Item Category</label>
		<div class="col-md-8">
		<input type="text" id="update_food_category1" name="update_food_category" value="<?php echo $categeryModel['food_category'];?>" class="form-control" readonly>
		<input type="hidden" id="update_food_id1" name="food_category_id" value="<?php echo $id; ?>">
		</div>
		</div>
			
		
		</div>
<!--child list popup-->
	<?php if(count($categoryTax)>0 &&  !empty($categoryTax[0]['ID'])   ) {?>	
	<table id="tblAddRowsupdate" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Tax Name</th>
            <th>Tax Type</th>
            <th>Value</th>
        </tr>
    </thead>
    <tbody>
	<?php for($i=0;$i<count($categoryTax);$i++){ ?>
        <tr>
            <td>
                <input name="category_tax_id" value="<?php echo $categoryTax[$i]['tax_name']?>" class="form-control">
            </td>
            <td>
                <select name="tax_type_<?php echo $categoryTax[$i]['ID']; ?>" class="form-control">
                    <option value="" <?php echo $categoryTax[$i]['tax_type'] == '' ? 'selected' : ''?> >--Select--</option>
                    <option value="2" <?php echo $categoryTax[$i]['tax_type'] == '2' ? 'selected' : ''?> >Percentage</option>
                </select>
            </td>
            <td>
                <input type="text" name="update_tax_value[]" id="tax_value_<?php echo $categoryTax[$i]['ID']; ?>" value="<?php echo $categoryTax[$i]['tax_value']?>" class="form-control">
        <input type="hidden" name="update_food_cat_tax_id[]" id="tax_food_tax_value_<?php echo $categoryTax[$i]['ID']; ?>" value="<?php echo $categoryTax[$i]['ID']?>" class="form-control">        
            </td>
        </tr>
    <?php } ?>   
    </tbody>
</table>
	<?php } ?>
<!--child list popup-->
	<table id="tblAddRows" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Tax Name</th>
            <th>Tax Type</th>
            <th>Value</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <!--<input name="taxname[]" class="form-control">-->
                <select name="tax_id[]" class="form-control">
                    <option value="">--Select--</option>
                    <?php foreach($merchantTax as $tax){ ?>
                        <option value="<?= $tax['ID'] ?>"><?= $tax['tax_name']; ?></option>
                    <?php } ?>
                </select>
            </td>
            <td>
                <!--<input name="taxtype[]" class="form-control">-->
                <select name="tax_type[]" class="form-control">
                    <option value="">--Select--</option>
                       <option value="2">Percentage</option>
                </select>
            </td>
            <td>
                <input name="tax_value[]" class="form-control">
            </td>
        </tr>
       
    </tbody>
</table>
	
		<div class="modal-footer">
	   <button id="btnAddRow" onclick="addrow()" class="btn btn-add btn-xs" type="button">
    Add Row
</button>
		<?= Html::submitButton('Update Category Tax', ['class'=> 'btn btn-add']); ?>

      </div>
   

   
<script>
$(document).ready(function(){
// Add button Delete in row
$('#tblAddRows tbody tr')
    .find('td')
    //.append('<input type="button" value="Delete" class="del"/>')
    .parent() //traversing to 'tr' Element
    .append('<td><a href="#" class="delrow" ><i class="fa fa-trash border-red text-red deleterow" name="deleterow" ></i></a></td>');
	

	
});

</script>    
        
        