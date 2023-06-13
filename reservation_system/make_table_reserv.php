<?php
    include("C:/xampp/htdocs/restaurantProject/Models/database.php");


if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["uid"]) && isset($_POST["tid"] )
        && isset($_POST["date"]) && isset($_POST["startReserv"]) && isset($_POST["endReserv"]))
{
    $uid = (int)$_POST["uid"];
    $tid = (int)$_POST["tid"];
    $date =  $_POST["date"];
    $startReserv = (int)$_POST["startReserv"];
    $endReserv = (int)$_POST["endReserv"];
    $result = makeTableReservation($uid,$tid,$date,$startReserv,$endReserv);

    echo json_encode($result);
}



?>