<?php
namespace app\helpers;

use yii;
class MyConst 
{
	/* ROLE CONSTANTS */
	const ROLE_SUPER_ADMIN = '1';
	const ROLE_COACH = '2';
	const ROLE_USER = '3';
	const ROLE_INSTITUTION = '4';
	
	/* STATUS CONSTANTS */
	const _ACTIVE = 'ACTIVE';
	const _INACTIVE = 'INACTIVE';
	const TYPE_ACTIVE = '1';
	const TYPE_INACTIVE = '2';
	
	const ROOM_RESERVATION_TYPES = ['4' => 'Hotels','6' => 'Service Apartments' , '7' => 'P.G' ,'8' => 'Resorts'];
	const TASTE_CATEGORIES = ['1' => 'Spicy','2' => 'Sweet','3' => 'Normal'];
	const PAYMENT_METHODS = ['1'=>'Cash On Dine','2'=>'Online Payment','3'=>'UPI Scanner','4'=>'Card Swipe'];
}

?>
