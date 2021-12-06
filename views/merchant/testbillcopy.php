<?php
use app\helpers\Utility;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        
        <title>Bill</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet"> 
    <style>
    * {
    font-size: 12px;
    font-family:  'Roboto', sans-serif;
    
}
body, html{margin:0px; padding:0px;}
.merchant-header{font-size:18px;font-weight:bold;}
.merchant-subheader{font-size:16px;}
.merchant-address{font-size:12px;}
td,
th,
tr,
table {
   border: 1px solid black;
border-collapse: collapse;
text-align: center;
width: 100%;
padding: 4px;
}
.vtop tr td {
    vertical-align: top;
}
td.description,
th.description {
    width: 100px;
    max-width: 100px;
}

td.quantity,
th.quantity {
    width: 15px;
    max-width: 15px;
}

td.price,
th.price {
    width: 40px;
    max-width: 40px;
}

.centered {
    text-align: center;
    align-content: center;
}
table thead tr th{font-weight:bold;}

.ticket {
    width: 100%;
    max-width: 100%;
}
tfoot tr.divider th{border-top:1px solid #333;font-size:16px;}
tfoot tr.divider td{border-top:1px solid #333;font-size:14px;}

.thanks-text{font-size:16px;font-weight:bold;margin-top:20px;}
.thanks-text img{width:25px !important;}
.container{width:1045px;background:#fff;font-family:sans-Serif;}
.bill-left{width:60%;float:left;}
.bill-sign{width:40%;float:right; text-align:right;}
.clear{clear:both;}
@media print {
    .hidden-print,
    .hidden-print * {
        display: none !important;
    }
    td,
th,
tr,
table{border:none;}
.container{width:100%;padding-top:40px !important;}
	 body{height:300px !important;}
	 body, html{margin:0px; padding:0px;}
}

    
    </style>
    
    </head>
    <body onload="window.print()">
        <div class="container">
        <div class="ticket">
            
            <p class="centered"><span class="merchant-header"><?php $merchantDet = \app\models\Merchant::findone(Yii::$app->user->identity->merchant_id);
            echo $merchantDet['storename'];?></span>
            <br>
            <span class="merchant-subheader"><?= $merchantDet['servingtype'] ?></span>
                <br>
                <span class="merchant-address"><?= $merchantDet['address'] ?>, <?=  $merchantDet['city'] ;?>, <?=  $merchantDet['state'] ;?></span>

                </p>
                <hr>
            <table class="vtop">
                <thead>
                    <tr>
                        <th class="description">Item Description</th>
                        <th class="quantity">Qty.</th>
                        <th class="price">Rate</th>
                        <th class="amount">Amount</th>
                        
                    </tr>
                </thead>
                <tbody>
			    <?php
			    for($i=0;$i<count($orderProdDet);$i++){
			        $amt[] =  $orderProdDet[$i]['price'] * $orderProdDet[$i]['count'];
			    ?>
			    <tr>
                        
                        <td class="description"><?= Utility::product_details($orderProdDet[$i]['product_id'],'title') ; ?> <?php 
			$food_cat_type_id = Utility::product_details($orderProdDet[$i]['product_id'],'food_category_quantity');
			if(!empty($food_cat_type_id)) { echo "(".Utility::food_cat_qty_det($food_cat_type_id,'food_type_name').")"; } ?></td>
                        <td class="quantity"><?= $orderProdDet[$i]['count'];?></td>
                        <td class="price"><?= $orderProdDet[$i]['price'];?></td>
                        <td class="amount"><?= $orderProdDet[$i]['price'] * $orderProdDet[$i]['count'];?></td>
                    </tr>
			    <?php } ?>
			       
                </tbody>
                
                <tfoot>
                    <tr class="divider">
                        <td colspan="2">Total</td>
                        <td colspan="2" align="right"><?= array_sum($amt);?></td>
                    </tr>
                     <tr>
                        <td colspan="2">Discount (-)</td>
                        <td colspan="2" align="right"><?= $orderDet['couponamount'] ?? 0;?>  </td>
                    </tr>
                    <tr>
                        <td colspan="2">Service Tax (%)</td>
                        <td colspan="2" align="right"><?= $orderDet['tax'] ?? 0;?></td>
                    </tr>
                    <tr class="divider">
                        <th colspan="2">Grand Total</th>
                        <th colspan="2" align="right"><?= $orderDet['totalamount'] ?? 0;?></th>
                    </tr>
                </tfoot>
                
            </table>
            <hr>
            <div>
            <div class="bill-left">
                Please Check Your<br> Bill Before Paying
            </div>
             <div class="bill-sign">
               Signature
            </div>
            </div>
            <div class="clear"></div>
            <div class="centered thanks-text">Thank You..!! Please Visit Again <img src="https://img.favpng.com/16/10/10/emoticon-smiley-computer-icons-download-online-chat-png-favpng-PBz489aP2fZvPt4jbWF3F7vwt.jpg" width="25"></div>
           
        </div>
        </div>
    <!--    <button id="btnPrint" class="hidden-print" >Print</button> -->
        <script >
            /*const $btnPrint = document.querySelector("#btnPrint");
$btnPrint.addEventListener("click", () => {
    window.print();
});*/


            
        </script>
    </body>
</html>