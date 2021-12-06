<header class="page-header">
</header>
<section>
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header d-flex align-items-center pt-0 pb-0">
                <h3 class="h4 col-md-6 pl-0 tab-title">Inventory List</h3>
				<div class="col-md-6 text-right pr-0">
				<button type="button" class="btn btn-add btn-xs" id="myBtn" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus mr-1"></i> Add Inventory</button>

				</div>
              </div>


              <div class="card-body">
			  <div class="col-md-4 offset-md-4 stock">
			  <div class="form-group row">
                    <label class="control-label col-md-4 pt-2">Start Date:</label>
					<div class="col-md-8">
                  <div class="input-group mb-3 mr-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                  </div>
                  <input type="text" class="form-control datepicker2" name="edate" placeholder="End Date" value="<?= $sdate ; ?>">
                </div>
				</div>
               </div>
			   <div class="form-group row">
                    <label class="control-label col-md-4 pt-2">End Date:</label>
					<div class="col-md-8">
                  <div class="input-group mb-3 mr-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                  </div>
                  <input type="text" class="form-control datepicker2" name="edate" placeholder="End Date" value="<?= $edate ; ?>">
                </div>
				</div>
               </div>
			   <div class="form-group row">
                    <label class="control-label col-md-4 pt-2">End Date:</label>
					<div class="col-md-8">
					<select>
					<option></option>
					</select>
				</div>
               </div>
			   <div class="form-group text-center">
				<button type="button" onclick="billview()" class="btn btn-add btn-xs"><i class="fa fa-print"></i></button>
			   </div>
			  </div>
			  </div>
			 </div>
			</div>
		</section>
		
		
		<script>
		function billview(){


        var form=document.createElement('form');
        form.setAttribute('method','post');
        form.setAttribute('action','tablebill');
        form.setAttribute('target','_blank');



    document.body.appendChild(form);
    form.submit();    


}
		</script>