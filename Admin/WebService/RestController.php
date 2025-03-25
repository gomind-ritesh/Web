<?php

//Adapted from https://phppot.com/php/php-restful-web-service/
require_once("ReservationRestHandler.php");
$method = $_SERVER['REQUEST_METHOD'];
$view = "";

if(isset($_GET["resource"]))
	$resource = $_GET["resource"];
// if(isset($_GET["page_key"]))
// 	$page_key = $_GET["page_key"];
/*
controls the RESTful services
URL mapping
*/


switch($resource){
	case "food":	
		switch($method){

			case "GET":
				$reservationRestHandler = new ReservationRestHandler();
				$result = $reservationRestHandler->getAllReservations();
			break;
	
			case "POST":
				$reservationRestHandler = new ReservationRestHandler();
				$reservationRestHandler->add();
			break;
		
			case "DELETE":
				$reservationRestHandler = new ReservationRestHandler();
				$result = $reservationRestHandler->deleteReservationById();
			break;
		
			case "PUT":
				$reservationRestHandler = new ReservationRestHandler();
				$reservationRestHandler->editReservationById();
			break;
		}
	break;	
}	
?>
