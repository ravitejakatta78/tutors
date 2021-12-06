<?php
$actionId = Yii::$app->controller->action->id;

?>
<style>

.square {display: inline-block;
width: 5rem;
height: 5rem;
line-height: 4.5rem;
margin: .5rem;
background-color: #f5f5f5;
box-shadow : 5px 10px white;
}
</style>

<header class="page-header">
                   
          </header>
<section>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" >
<?= \Yii::$app->view->renderFile('@app/views/merchant/_roomreservations.php',['actionId'=>$actionId]); ?>
<div class="col-lg-12">
<div class="card ml-0.5 mr-0.5">
  <div class="card-header">
    <ul class="nav nav-tabs card-header-tabs" style="pointer:cursor">
    <?php 
    foreach( $categorylist as $categorylist ) { ?>
      <li class="nav-item ">
        <button type="button" class="nav-link <?php if($category_id == $categorylist['ID']) { ?> active <?php } ?>" onclick="roomlist('<?= $categorylist['ID'] ; ?>')"><?= $categorylist['category_name'] ; ?></button>
      </li>
    <?php  } ?>  
    </ul>
  </div>
  <div class="card-body">
    <div class="container">
        <div class="row">
           <?php for($i=0;$i < count($rooms) ; $i++ ) { ?>
            <div class="col-md-2">
                <div class="border square text-center <?php if($rooms[$i]['status'] == '2' ) { ?> bg-red <?php } else { ?> bg-green <?php } ?>   rounded" >
                    <p class=" lead"><?= $rooms[$i]['room_name'];?></p>
                </div>
            </div>
            <?php } ?>
           
        </div>    
    </div>
  </div>
</div>
</div>
</section>
<script>
function roomlist(category_id)
{
    var form=document.createElement('form');
        form.setAttribute('method','post');
        form.setAttribute('action','roomsdisplay');
 

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "category_id");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", category_id);
    form.appendChild(hiddenField);

    document.body.appendChild(form);
    form.submit();    

}
</script>