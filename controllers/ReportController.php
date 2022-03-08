<?php

namespace app\controllers;
use yii;

class ReportController extends GoController
{

    public function actionIndex()
    {
				$sdate = $_POST['sdate'] ?? date('Y-m-d'); 
		$edate = $_POST['edate'] ?? date('Y-m-d');
        return $this->render('index',['sdate'=>$sdate,'edate'=>$edate]);
    }
	public function actionReportpurchase()
	{
	    extract($_POST);
		$sdate = $_POST['sdate'] ?? date('Y-m-d'); 
		$edate = $_POST['edate'] ?? date('Y-m-d');
		$daterowSpanArr = [];
		$purchase_number_rowSpanArr = [];
		$merchant_id = Yii::$app->user->identity->merchant_id;
$merchantDet = \app\models\Merchant::findOne($merchant_id);
$storename = $merchantDet['storename'];
		if($type == '1')
		{
			$sql = 'select ip.purchase_number,date(ip.reg_date) reg_date,i.item_name,ipd.purchase_quantity,purchase_price from ingredient_purchase ip 
			inner join ingredient_purchase_detail ipd on ip.ID = ipd.purchase_id 
			inner join ingredients i on i.ID = ipd.ingredient_id 
			where ip.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' 
			and date(ip.reg_date) between \''.$sdate.'\' and \''.$edate.'\' order by date(reg_date),purchase_number,item_name ';
			
			$res = Yii::$app->db->createCommand($sql)->queryAll();
			//print_r($res);exit;
			
		$sqlJson = 'select * from ( select * from ( select ip.purchase_number,date(ip.reg_date) reg_date,i.item_name,ipd.purchase_quantity
,purchase_price from ingredient_purchase ip inner join ingredient_purchase_detail ipd on ip.ID = ipd.purchase_id
inner join ingredients i on i.ID = ipd.ingredient_id where ip.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\'
and date(ip.reg_date) between \''.$sdate.'\' and \''.$edate.'\' order by date(ip.reg_date),purchase_number,item_name ) A
union select concat(date(ip.reg_date),\' Total\'),concat(date(ip.reg_date),\' Total\') reg_date,null,sum(ipd.purchase_quantity) purchase_quantity,sum(purchase_price) purchase_price from ingredient_purchase ip inner join ingredient_purchase_detail ipd on ip.ID = ipd.purchase_id inner join ingredients i on i.ID = ipd.ingredient_id
where ip.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' and date(ip.reg_date) between \''.$sdate.'\' and \''.$edate.'\' group by date(ip.reg_date) ) B
order by reg_date,purchase_number';
			$resjson = Yii::$app->db->createCommand($sqlJson)->queryAll();
			
			$dateArr = array_column($res,'reg_date');
		    $daterowSpanArr = array_count_values($dateArr);
			$purchase_number_Arr = array_column($res,'purchase_number');
		    $purchase_number_rowSpanArr = array_count_values($purchase_number_Arr);			
		}
		else if($type == '2'){
			$sql = 'select date(ip.reg_date) reg_date,sum(coalesce(purchase_amount,0)) purchase_amount from ingredient_purchase ip
			where ip.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' 
			and date(ip.reg_date) between \''.$sdate.'\' and \''.$edate.'\' group by date(reg_date) order by date(reg_date) ';
			$res = Yii::$app->db->createCommand($sql)->queryAll();
			$resjson = [];
		}
        return $this->render('reportpurchase',['sdate'=>$sdate,'edate'=>$edate,'res'=>$res
		,'daterowSpanArr'=>$daterowSpanArr,'sdate'=>$sdate,'edate'=>$edate
,'purchase_number_rowSpanArr'=>$purchase_number_rowSpanArr,'type'=>$type
,'resjson'=>json_encode($resjson),'orgres'=>json_encode($res),'storename'=>$storename]);
		
	}
	public function actionTablebill(){
		return $this->renderPartial('reportprint');
	}
	public function actionPurchaseprint(){
		extract($_POST);
		$daterowSpanArr = [];
		$purchase_number_rowSpanArr = [];
		$merchant_id = Yii::$app->user->identity->merchant_id;
$merchantDet = \app\models\Merchant::findOne($merchant_id);
$storename = $merchantDet['storename'];
		if($type == '1')
		{
			$sql = 'select ip.purchase_number,date(ip.reg_date) reg_date,i.item_name,ipd.purchase_quantity,purchase_price from ingredient_purchase ip 
			inner join ingredient_purchase_detail ipd on ip.ID = ipd.purchase_id 
			inner join ingredients i on i.ID = ipd.ingredient_id 
			where ip.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' 
			and date(ip.reg_date) between \''.$sdate.'\' and \''.$edate.'\' order by date(reg_date),purchase_number,item_name ';
			
			$res = Yii::$app->db->createCommand($sql)->queryAll();
			
		$sqlJson = 'select * from ( select * from ( select ip.purchase_number,date(ip.reg_date) reg_date,i.item_name,ipd.purchase_quantity
,purchase_price from ingredient_purchase ip inner join ingredient_purchase_detail ipd on ip.ID = ipd.purchase_id
inner join ingredients i on i.ID = ipd.ingredient_id where ip.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\'
and date(ip.reg_date) between \''.$sdate.'\' and \''.$edate.'\' order by date(ip.reg_date),purchase_number,item_name ) A
union select concat(date(ip.reg_date),\' Total\'),concat(date(ip.reg_date),\' Total\') reg_date,null,sum(ipd.purchase_quantity) purchase_quantity,sum(purchase_price) purchase_price from ingredient_purchase ip inner join ingredient_purchase_detail ipd on ip.ID = ipd.purchase_id inner join ingredients i on i.ID = ipd.ingredient_id
where ip.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' and date(ip.reg_date) between \''.$sdate.'\' and \''.$edate.'\' group by date(ip.reg_date) ) B
order by reg_date,purchase_number';
			$resjson = Yii::$app->db->createCommand($sqlJson)->queryAll();
			
			$dateArr = array_column($res,'reg_date');
		    $daterowSpanArr = array_count_values($dateArr);
			$purchase_number_Arr = array_column($res,'purchase_number');
		    $purchase_number_rowSpanArr = array_count_values($purchase_number_Arr);			
		}
		else if($type == '2'){
			$sql = 'select date(ip.reg_date) reg_date,sum(coalesce(purchase_amount,0)) purchase_amount from ingredient_purchase ip
			where ip.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' 
			and date(ip.reg_date) between \''.$sdate.'\' and \''.$edate.'\' group by date(reg_date) order by date(reg_date) ';
			$res = Yii::$app->db->createCommand($sql)->queryAll();
			$resjson = [];
		}
//echo "<pre>";print_r($resjson);exit;

		return $this->renderPartial('purchaseprint',['res'=>$res
		,'daterowSpanArr'=>$daterowSpanArr,'sdate'=>$sdate,'edate'=>$edate
,'purchase_number_rowSpanArr'=>$purchase_number_rowSpanArr,'type'=>$type
,'resjson'=>json_encode($resjson),'orgres'=>json_encode($res),'storename'=>$storename]);
	}
	public function actionSampleprint()
	{		$sdate = $_POST['sdate'] ?? date('Y-m-d'); 
		$edate = $_POST['edate'] ?? date('Y-m-d');
		$sql = 'select ip.purchase_number,date(ip.reg_date) reg_date,i.item_name,ipd.purchase_quantity,purchase_price from ingredient_purchase ip 
			inner join ingredient_purchase_detail ipd on ip.ID = ipd.purchase_id 
			inner join ingredients i on i.ID = ipd.ingredient_id 
			where ip.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' 
			and date(ip.reg_date) between \'2020-04-01\' and \''.$edate.'\' order by date(reg_date),purchase_number,item_name ';
			
			$res = Yii::$app->db->createCommand($sql)->queryAll();
		
		return $this->renderPartial('..\..\PHPExcel\exceldownload',['res'=>$res]);
	}
     public function actionReportfeedback(){
            extract($_POST);
            $sdate = $_POST['sdate'] ?? date('Y-m-d'); 
            $edate = $_POST['edate'] ?? date('Y-m-d');
            $merchant_id = Yii::$app->user->identity->merchant_id;
            $sqlfeedback = 'select f.rating, f.message, u.name,o.order_id order_num,f.reg_date from feedback f
                            inner join users u on u.ID=f.user_id
                            inner join orders o on o.ID=f.order_id
                            where f.merchant_id=\''.$merchant_id.'\' and date(f.reg_date) between \''.$sdate.'\' and \''.$edate.'\'';
            
            $feedbackdet = Yii::$app->db->createCommand($sqlfeedback)->queryAll();
            return $this->render('reportfeedback',['sdate'=>$sdate,'edate'=>$edate,'feedbackdet'=>$feedbackdet]);
        }
        public function actionFeedbackprint(){
            $sdate = $_POST['sdate'] ?? date('Y-m-d'); 
            $edate = $_POST['edate'] ?? date('Y-m-d');
            extract($_POST);
            $merchant_id = Yii::$app->user->identity->merchant_id;
            $sqlfeedback = 'select f.rating, f.message, u.name,o.order_id order_num,f.reg_date from feedback f
                            inner join users u on u.ID=f.user_id
                            inner join orders o on o.ID=f.order_id
                            where f.merchant_id=\''.$merchant_id.'\'';
            
            $feedbackdet = Yii::$app->db->createCommand($sqlfeedback)->queryAll();
            return $this->renderPartial('feedbackprint',['sdate'=>$sdate,'edate'=>$edate,'feedbackdet'=>$feedbackdet]);
	
        }
        public function actionReportemployee(){
             extract($_POST);
            $sdate = $_POST['sdate'] ?? date('Y-m-d'); 
            $edate = $_POST['edate'] ?? date('Y-m-d');
           
            $merchant_id = Yii::$app->user->identity->merchant_id;
          /*select D.*,total_dates,present_dates from (
 select me.*,er.role_name from merchant_employee me 
    inner join employee_role er on er.ID=me.emp_role where me.merchant_id = '8' ) D left join (
 select * from (
select * from (
select count(distinct(created_on)) total_dates,1 join_con from employee_attendance where merchant_id = '8' and created_on between '2020-05-01' and '2020-09-14'
) A left join (
select distinct(count(created_on)) present_dates,employee_id,1 con_join from employee_attendance where merchant_id = '8' and created_on between '2020-05-01' and '2020-09-14' group by employee_id
) B on A.join_con = B.con_join
) C
) E on D.ID = E.employee_id*/
            $sqlmerchantemployee = 'select me.*,er.role_name,ea.attendent_status from merchant_employee me
                            inner join employee_role er on er.ID=me.emp_role
                            left join employee_attendance ea on ea.employee_id=me.ID
                            where me.merchant_id=\''.$merchant_id.'\' and date(me.reg_date) between \''.$sdate.'\' and \''.$edate.'\'';
            $employeedet = Yii::$app->db->createCommand($sqlmerchantemployee)->queryAll();
            return $this->render('reportemployee',['sdate'=>$sdate,'edate'=>$edate,'employeedet'=>$employeedet]);
        }
        public function actionEmployeeprint(){
            $sdate = $_POST['sdate'] ?? date('Y-m-d'); 
            $edate = $_POST['edate'] ?? date('Y-m-d');
            extract($_POST);
            $merchant_id = Yii::$app->user->identity->merchant_id;
          /*select D.*,total_dates,present_dates from (
 select me.*,er.role_name from merchant_employee me 
    inner join employee_role er on er.ID=me.emp_role where me.merchant_id = '8' ) D left join (
 select * from (
select * from (
select count(distinct(created_on)) total_dates,1 join_con from employee_attendance where merchant_id = '8' and created_on between '2020-05-01' and '2020-09-14'
) A left join (
select distinct(count(created_on)) present_dates,employee_id,1 con_join from employee_attendance where merchant_id = '8' and created_on between '2020-05-01' and '2020-09-14' group by employee_id
) B on A.join_con = B.con_join
) C
) E on D.ID = E.employee_id*/
            $sqlmerchantemployee = 'select me.*,er.role_name,ea.attendent_status from merchant_employee me
                            inner join employee_role er on er.ID=me.emp_role
                            left join employee_attendance ea on ea.employee_id=me.ID
                            where me.merchant_id=\''.$merchant_id.'\'';
            $employeedet = Yii::$app->db->createCommand($sqlmerchantemployee)->queryAll();
            return $this->renderPartial('employeeprint',['sdate'=>$sdate,'edate'=>$edate,'employeedet'=>$employeedet]);
	
        }
        public function actionReportpilot(){
            $sdate = $_POST['sdate'] ?? date('Y-m-d'); 
            $edate = $_POST['edate'] ?? date('Y-m-d');
            extract($_POST);
            $merchant_id = Yii::$app->user->identity->merchant_id;
            if($type == '2'){
                        $sqlpilot = 'select sb.name serviceboyname,u.name username,date(o.reg_date) reg_date,
                            case when orderprocess = \'0\' then \'pending\'
                             when orderprocess = \'1\' then \'Accepted\'
                             when orderprocess = \'2\' then \'Served\'
                             when orderprocess = \'3\' then \'Cancelled\'
                             when orderprocess = \'4\' then \'Completed\' end status,o.order_id,o.totalamount
                             from orders o
                             inner join serviceboy sb on o.serviceboy_id = sb.ID
                             left join users u on o.user_id = u.ID
                             where date(o.reg_date) between \''.$sdate.'\' and \''.$edate.'\'
                             and o.merchant_id = \''.$merchant_id.'\'';
                $pilotdet = Yii::$app->db->createCommand($sqlpilot)->queryAll();
                return $this->renderPartial('pilotabstractprint',['sdate'=>$sdate,'edate'=>$edate,'pilotdet'=>$pilotdet]);
	
        
            } else {
                $sqlpilot = 'SELECT sb.name,count(o.ID) total_served_orders,
                         sum(case when o.orderprocess = \'4\' then 1 else 0 end) completed_orders,
                         sum(case when o.orderprocess = \'3\' then 1 else 0 end) rejected_order,sum(o.totalamount) totalamount 
                         FROM orders o inner join serviceboy sb on o.serviceboy_id = sb.ID 
                         where date(o.reg_date) between \''.$sdate.'\' and  \''.$edate.'\'  
                         and o.merchant_id = \''.$merchant_id.'\' group by sb.name';
                $pilotdet = Yii::$app->db->createCommand($sqlpilot)->queryAll();
            }
            return $this->render('reportpilot',['sdate'=>$sdate,'edate'=>$edate,'pilotdet'=>$pilotdet]);
        }
        public function actionPilotprint(){
            $sdate = $_POST['sdate'] ?? date('Y-m-d'); 
            $edate = $_POST['edate'] ?? date('Y-m-d');
            extract($_POST);
            $merchant_id = Yii::$app->user->identity->merchant_id;
            if($type == '2'){
                        $sqlpilot = 'select sb.name serviceboyname,u.name username,date(o.reg_date) reg_date,
                            case when orderprocess = \'0\' then \'pending\'
                             when orderprocess = \'1\' then \'Accepted\'
                             when orderprocess = \'2\' then \'Served\'
                             when orderprocess = \'3\' then \'Cancelled\'
                             when orderprocess = \'4\' then \'Completed\' end status,o.order_id,o.totalamount
                             from orders o
                             inner join serviceboy sb on o.serviceboy_id = sb.ID
                             left join users u on o.user_id = u.ID
                             where date(o.reg_date) between \''.$sdate.'\' and \''.$edate.'\'
                             and o.merchant_id = \''.$merchant_id.'\'';
                $pilotdet = Yii::$app->db->createCommand($sqlpilot)->queryAll();
                return $this->renderPartial('pilotabstractprint',['sdate'=>$sdate,'edate'=>$edate,'pilotdet'=>$pilotdet]);
	
        
            } else {
                $sqlpilot = 'SELECT sb.name,count(o.ID) total_served_orders,
                         sum(case when o.orderprocess = \'4\' then 1 else 0 end) completed_orders,
                         sum(case when o.orderprocess = \'3\' then 1 else 0 end) rejected_order,sum(o.totalamount) totalamount 
                         FROM orders o inner join serviceboy sb on o.serviceboy_id = sb.ID 
                         where date(o.reg_date) between \''.$sdate.'\' and  \''.$edate.'\'  
                         and o.merchant_id = \''.$merchant_id.'\' group by sb.name';
                $pilotdet = Yii::$app->db->createCommand($sqlpilot)->queryAll();
                return $this->renderPartial('pilotprint',['sdate'=>$sdate,'edate'=>$edate,'pilotdet'=>$pilotdet]);
            }
        }
        public function actionReportcategory(){
            $sdate = $_POST['sdate'] ?? date('Y-m-d'); 
            $edate = $_POST['edate'] ?? date('Y-m-d');
            $fc_id = $_POST['fc_id'] ?? '';
            extract($_POST);
            $merchant_id = Yii::$app->user->identity->merchant_id;
            if(!empty($_POST) && $fc_id !=''){
                $fcid=' and fc.ID=\''.$fc_id.'\'';
            }
            $sqlcategory = 'SELECT food_category,p.title,food_type_name,sum(count) orytotal_sale_qty,sum(couponamount) discount,sum(op.price) totalamount FROM orders o 
                        inner join order_products op on o.ID = op.order_id
                        inner join product p on op.product_id = p.ID
                        inner join  food_categeries fc on fc.ID = p.foodtype'.$fcid.'
                        left join food_category_types fct on p.food_category_quantity = fct.ID
                        WHERE date(o.reg_date) between \''.$sdate.'\' and \''.$edate.'\' 
                        and o.merchant_id = \''.$merchant_id.'\'
                        group by food_category,p.title,food_type_name';
                       
            $category = Yii::$app->db->createCommand($sqlcategory)->queryAll();
            $sqlfoodcategory = 'SELECT * from food_categeries WHERE merchant_id = \''.$merchant_id.'\'';
            $foodcategories= Yii::$app->db->createCommand($sqlfoodcategory)->queryAll();
    
            return $this->render('reportcategory',['sdate'=>$sdate,'edate'=>$edate,'categories'=>$category,'foodcategories'=>$foodcategories,'fcid'=>$fc_id]);
        }
        public function actionCategoryprint(){
            $sdate = $_POST['sdate'] ?? date('Y-m-d'); 
            $edate = $_POST['edate'] ?? date('Y-m-d');
            extract($_POST);
            $merchant_id = Yii::$app->user->identity->merchant_id;
            $sqlcategory = 'SELECT food_category,p.title,sum(count) orytotal_sale_qty,sum(couponamount) discount,sum(totalamount) totalamount FROM orders o 
                        inner join order_products op on o.ID = op.order_id
                        inner join product p on op.product_id = p.ID
                        inner join  food_categeries fc on fc.ID = p.foodtype
                        WHERE date(o.reg_date) between \''.$sdate.'\' and \''.$edate.'\' 
                        and o.merchant_id = \''.$merchant_id.'\'
                        group by food_category,p.title';
            $category = Yii::$app->db->createCommand($sqlcategory)->queryAll();
            return $this->renderPartial('categoryprint',['sdate'=>$sdate,'edate'=>$edate,'categories'=>$category]);
        }
        public function actionReporttablereservation(){
            $sdate = $_POST['sdate'] ?? date('Y-m-d'); 
            $edate = $_POST['edate'] ?? date('Y-m-d');
             extract($_POST);
            $merchant_id = Yii::$app->user->identity->merchant_id;
            if($type == '2'){
                $sqltablereserv = 'select u.name,tr.bookdate,tr.booktime,tn.name table_name 
                             from table_reservations tr 
                             inner join users u on tr.user_id = u.ID
                             inner join tablename tn on tr.table_id = tn.ID
                             where tr.merchant_id = \''.$merchant_id.'\' 
                             and date(tr.reg_date) between \''.$sdate.'\' and \''.$edate.'\'';
                            $tablereserv = Yii::$app->db->createCommand($sqltablereserv)->queryAll();
            }else {
                $sqltablereserv = 'select count(ID) reservationCount,bookdate
                        from table_reservations tr where date(reg_date) between \''.$sdate.'\' and \''.$edate.'\' and merchant_id = \''.$merchant_id.'\' 
                        group by bookdate';
                $tablereserv = Yii::$app->db->createCommand($sqltablereserv)->queryAll();   
            return $this->render('reportTableReservation',['sdate'=>$sdate,'edate'=>$edate,'tablereserv'=>$tablereserv]);
            }
        }
        public function actionTablereservationprint(){
            $sdate = $_POST['sdate'] ?? date('Y-m-d'); 
            $edate = $_POST['edate'] ?? date('Y-m-d');
            extract($_POST);
            $merchant_id = Yii::$app->user->identity->merchant_id;
            if($type == '2'){
                $sqltablereserv = 'select u.name,tr.bookdate,tr.booktime,tn.name table_name 
                             from table_reservations tr 
                             inner join users u on tr.user_id = u.ID
                             inner join tablename tn on tr.table_id = tn.ID
                             where tr.merchant_id = \''.$merchant_id.'\' 
                             and date(tr.reg_date) between \''.$sdate.'\' and \''.$edate.'\'';
                            $tablereserv = Yii::$app->db->createCommand($sqltablereserv)->queryAll();
                return $this->renderPartial('tableabstractprint',['sdate'=>$sdate,'edate'=>$edate,'tablereserv'=>$tablereserv]);
	
        
            } else {
                $sqltablereserv = 'select count(ID) reservationCount,bookdate
                        from table_reservations tr where date(reg_date) between \''.$sdate.'\' and \''.$edate.'\' and merchant_id = \''.$merchant_id.'\' 
                        group by bookdate';
                $tablereserv = Yii::$app->db->createCommand($sqltablereserv)->queryAll();
                return $this->renderPartial('tableprint',['sdate'=>$sdate,'edate'=>$edate,'tablereserv'=>$tablereserv]);
            }
        }

        public function actionSectionWiseSale()
        {
            extract($_POST);

            $sdate = $_POST['sdate'] ?? date('Y-m-d');
            $edate = $_POST['edate'] ?? date('Y-m-d');
            $merchant_id = Yii::$app->user->identity->merchant_id;
            $sql = 'select s.section_name,sum(amount) amount,sum(tax) tax,sum(totalamount) totalamount from orders o 
                    inner join tablename tb on o.tablename = tb.ID
                    inner join sections s on s.ID = tb.section_id
                    where date(o.reg_date) between \''.$sdate.'\' and \''.$edate.'\' 
            and orderprocess in (\'4\') group by s.section_name';
            $res = Yii::$app->db->createCommand($sql)->queryAll();
            return $this->render('section-sale-report',['sdate'=>$sdate
                ,'edate'=>$edate, 'res' => $res]);

        }
        
   	

}
