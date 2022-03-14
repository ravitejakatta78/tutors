<?php
use app\helpers\Utility;
?>
<!DOCTYPE html>
<!-- saved from url=(0054)https://superpilot.in/dev/tutors/web/merchant/tablekot -->
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>KOT</title>
    <link href="./KOT_files/css2" rel="stylesheet">
    <style>
        * {
            font-size: 12px;
            font-family: 'Roboto', sans-serif;

        }

        .qty {
            float: right;
            margin-right: 130px;
        }

        body,
        html {
            margin: 0px;
            padding: 0px;
        }

        .merchant-header {
            font-size: 18px;
            font-weight: bold;
        }

        .merchant-subheader {
            font-size: 16px;
        }

        .merchant-address {
            font-size: 12px;
        }

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

        table thead tr th {
            font-weight: bold;
        }

        .ticket {
            width: 1045px;
            max-width: 100%;
        }

        tfoot tr.divider th {
            border-top: 1px solid #333;
            font-size: 16px;
        }

        tfoot tr.divider td {
            border-top: 1px solid #333;
            font-size: 14px;
        }

        .thanks-text {
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
        }

        .thanks-text img {
            width: 25px !important;
        }

        .container {
            width: 100%;
            background: #fff;
            font-family: sans-Serif;
        }

        .bill-left {
            width: 60%;
            float: left;
        }

        .bill-sign {
            width: 40%;
            float: right;
            text-align: right;
        }

        .clear {
            clear: both;
        }

        @media print {

            .hidden-print,
            .hidden-print * {
                display: none !important;
            }

            td,
            th,
            tr,
            table {
                border: none;
            }

            .container {
                width: 100%;
                padding-top: 40px !important;
            }

            .ticket{
                width: 100%;
            }

            body {
                height: 300px !important;
            }

            body,
            html {
                margin: 0px;
                padding: 0.75rem;
            }
        }

        .submenu {
            font-size: 20px;
        }

        .right {
            float: right;
        }

        .d-flex{
            display: flex;
        }

        .text-align-left{
            text-align: left;
        }

        .text-align-right{
            text-align: right;
        }

        .text-align-center{
            text-align: center;
        }

        .w-50{
            width: 50%;
        }

        .w-100{
            width: 100%;
        }

        .pt-3{
            padding-top: 0.75rem;
        }

        .pb-3{
            padding-top:  0.75rem;
        }

        .p-3{
            padding: 0.75rem;
        }

        .justify-content-center{
            justify-content: center
        }

        .justify-content-left{
            justify-content: left;
        }

        .justify-content-right{
            justify-content: right;
        }

        .justify-content-between{
            justify-content: space-between;
        }

        .pl-1{
            padding-left: 0.5rem;
        }

        .w-70{
            width:70%
        }

        .w-30{
            width:30%
        }

    </style>

</head>

<body onload="window.print()">
    <div class="container w-100">
        <div class="ticket ">
            <?php $merchantDet = \app\models\Merchant::findone(Yii::$app->user->identity->merchant_id);
            $user_det = \app\models\Users::findOne($orderDet['user_id']);
            $section_det = \app\models\Sections::findOne($tableDet['section_id']);
            $timestamp=strtotime($orderDet['reg_date']);
            $order_date = date('d/m/Y', $timestamp); // d.m.YYYY
            $order_time = date('h:i  A', $timestamp); // HH:ss
            ?>
            <div class="d-flex w-100 justify-content-between">
                <div class="w-50" style="padding-right: 9px;">
                    Date:&nbsp;<?= $order_date ?>
                </div>
                <div class="w-50 pl-1 text-align-center">
                    Time:&nbsp;<?= $order_time ?>
                </div>
            </div>

            <div class="d-flex w-100 pt-3">
                <div class="w-50" style="padding-right: 9px;">
                    Proceessed By:&nbsp;<?= $merchantDet['name']; ?>
                </div>
                <div class="w-50 pl-1 text-align-center">
                    Bill No:&nbsp;<?= $orderDet['order_id']; ?>
                </div>
            </div>

            <div class="d-flex w-100 pt-3">
                <div class="w-50" style="padding-right: 9px;">
                    Customer Info:&nbsp;<?= !empty($user_det['name']) ? $user_det['name'] : 'Guest' ; ?>
                </div>
                <div class="w-50 pl-1 text-align-center">
                    <?= $section_det['section_name']; ?> - <?= $tableDet['name']; ?>
                </div>
            </div>

            
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
                $amt=[];
                $qty=[];
                for($i=0;$i<count($orderProdDet);$i++){
                    $amt[]=  $orderProdDet[$i]['price'] * $orderProdDet[$i]['count'];
                    $qty[]=  $orderProdDet[$i]['count'];
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
                        <td colspan="4">
                            <div class="w-100 d-flex ">
                                <div class="w-70">
                                    <span>Total items:<?= count($orderProdDet);?></span>
                                </div>
                                <div class="w-30">
                                     <span >Total qty:<?= array_sum($qty);?></span>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="divider">
                        <td colspan="2">Sub Total</td>
                        <td colspan="2" align="right"><?= array_sum($amt);?></td>
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

        </div>
    </div>
    <!--    <button id="btnPrint" class="hidden-print" >Print</button> -->
    <script>
        /*const $btnPrint = document.querySelector("#btnPrint");
$btnPrint.addEventListener("click", () => {
    window.print();
});*/
    </script>

</body>

</html>