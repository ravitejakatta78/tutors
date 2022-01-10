<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>
    

		
	<div class="form-horizontal">
		<div class="form-group row">
		<label class="control-label col-md-4">Item Category</label>
		<div class="col-md-8">
		<input type="text" id="update_food_category" name="update_food_category" value="<?= $categorytypes[0]['food_category'];?>" class="form-control">
		<input type="hidden" id="update_food_id" name="update_food_id" value="<?= $id;?>">
		</div>
		</div>
			<div class="form-group row">
		<label class="control-label col-md-4">Menu Section</label>
		<div class="col-md-8">
		<select id="update_food_section" name="update_food_section" class="form-control">
		    <option value="">Select Section</option>
		    <?php for($fs = 0;$fs <count($foodSections);$fs++) { ?>
		    <option value="<?= $foodSections[$fs]['ID']; ?>" <?php if( $categorytypes[0]['food_section_id'] ==  $foodSections[$fs]['ID']) { ?> selected <?php } ?>><?= $foodSections[$fs]['food_section_name']; ?></option>
		    <?php } ?>
		</select>     
		<span id="err_food_section" style="display:none;color:red">Please Enter Menu Section</span>
		</div>
		</div>
		
		<div class="form-group row">
		<label class="control-label col-md-4">Upselling</label>
		<div class="col-md-8">
		<select id="update_upselling" name="update_upselling" class="form-control">
		    <option value="2" <?php if($categorytypes[0]['upselling'] == '2') { ?> selected <?php } ?>>No</option>
		    <option value="1" <?php if($categorytypes[0]['upselling'] == '1') { ?> selected <?php } ?>>Yes</option>		    
		</select>    

		</div>
		</div>

		<div class="form-group row">
		<label class="control-label col-md-4">Category Image</label>
		<div class="col-md-8">
          <input type="file" class="form-control" name="update_category_label" >
		</div>
		</div>
		
		
		</div>
	<?php if(count($categorytypes)>0 &&  !empty($categorytypes[0]['fcid'])   ) {?>	
	<table id="tblAddRowsupdate" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Quantity</th>
        </tr>
    </thead>
    <tbody>
	<?php for($i=0;$i<count($categorytypes);$i++){ ?>
        <tr>
			<td>
                <input name="categorytypes_<?=$categorytypes[$i]['fcid']; ?>" value="<?= $categorytypes[$i]['food_type_name']?>" class="form-control">
            </td>
        </tr>
    <?php } ?>   
    </tbody>
</table>
	<?php } ?>
	<table id="tblAddRows" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Quantity</th>
			<th>Action</th>
        </tr>
    </thead>
    <tbody>
        <tr>
			<td>
                <input name="categorytypes[]" class="form-control">
            </td>
        </tr>
       
    </tbody>
</table>
	
		<div class="modal-footer">
	   <button id="btnAddRow" onclick="addrow()" class="btn btn-add btn-xs" type="button">
    Add Row
</button>
		<?= Html::submitButton('Update Category', ['class'=> 'btn btn-add']); ?>

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
        
        