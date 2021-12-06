<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<script src="<?= Yii::$app->request->baseUrl.'/js/typeahead.js'?>"></script>
   		<?php	$form = ActiveForm::begin([
    		'id' => 'update-product-form',
			'action'=>'updateroom',
		'options' => ['class' => 'form-horizontal','wrapper' => 'col-xs-12',],
        	'layout' => 'horizontal',
			 'fieldConfig' => [
        'horizontalCssClasses' => [
            
            'offset' => 'col-sm-offset-0',
            'wrapper' => 'col-sm-12 pl-0 pr-0',
        ],
		]
		]) ?>
<div class="row">	
<div class="col-md-6">	
        <div class="form-group row">
	   <label class="control-label col-md-4">Category Name</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'category_name')->textinput(['class' => 'form-control titleedit','autocomplete'=>'autocompleteroom()','placeholder'=>'Category Name'])->label(false); ?>
	   </div>
	   </div>
	 <input type="hidden" name="ID" value="<?php echo $id; ?>">
	   <div class="form-group row">
	   <label class="control-label col-md-4">Price</label>
	   <div class="col-md-8">
	   <?= $form->field($model, 'price')->textinput(['class' => 'form-control titleedit','placeholder'=>'Price'])->label(false); ?>
	   </div></div>
</div>
<div class="col-md-6">
        <div class="form-group row">
	   <label class="control-label col-md-4">Availability</label>
	   <div class="col-md-8">
	   <?= $form->field($model, 'availability')->textinput(['class' => 'form-control titleedit','placeholder'=>'availability'])->label(false); ?>
	   </div></div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Upload</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'category_pic')->fileinput(['class' => 'form-control titleedit'])->label(false); ?>
	   </div></div>

	   <div class="form-group row">
	   <label class="control-label col-md-4">Availability Alert</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'availability_alert')->textinput(['class' => 'form-control '])->label(false); ?>
	   </div></div>
	   </div>
	   </div>
	   <?php if(count($roomnames)>0 &&  !empty($roomnames)   ) {?>	
	<table id="tblAddRowsupdate" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Room Names</th>
        </tr>
    </thead>
    <tbody>
	<?php for($i=0;$i<count($roomnames);$i++){ ?>
        <tr>
			<td>
                <input name="roomnames_<?=$roomnames[$i]['ID']; ?>" value="<?= $roomnames[$i]['room_name']?>" class="form-control">
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
                <input name="roomnamesupdate[]" class="form-control">
            </td>
			<td><a href="#" class="delrow" ><i class="fa fa-trash border-red text-red deleterow" name="deleterow" ></i></a></td>
        </tr>
       
    </tbody>
</table>
	   </div>
	   </div>
	   <div class="modal-footer">
	   <button id="btnAddRow" onclick="addrow()" class="btn btn-add btn-xs" type="button">
    Add Row
</button>

		<?= Html::submitButton('Update Room', ['class'=> 'btn btn-add']); ?>

      </div> 


<?php ActiveForm::end() ?>
<?php
$script = <<< JS
    $('#tblAddRow1').DataTable();
	$('select').select2();
JS;
$this->registerJs($script);
?>
   
<script>

$(document).on('keydown', '.titleedit,.labeltagedit', function(){


            source: function (query, result) {
                $.ajax({
                    url: "roomautocomplete",
					data: 'query=' + query,            
                    dataType: "json",
                    type: "POST",
                    success: function (data) {
						result($.map(data, function (item) {
							return item;
                        }));
                    }
                });
            }
});
$(document).ready(function(){
// Add button Delete in row
$('#tblAddRows tbody tr')
    .find('td')
    //.append('<input type="button" value="Delete" class="del"/>')
    .parent() //traversing to 'tr' Element
    .append('<td><a href="#" class="delrow" ><i class="fa fa-trash border-red text-red deleterow" name="deleterow" ></i></a></td>');
	

	
});
</script>    
        
        