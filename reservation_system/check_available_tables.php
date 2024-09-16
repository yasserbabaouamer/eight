<?php

include("C:/xampp/htdocs/restaurantProject/models/database.php");

if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST["date"]) && isset($_POST["startReserv"]) &&
    isset($_POST["endReserv"]) && isset($_POST["size"])){
    $date = $_POST["date"];
    $size = $_POST["size"];
    $startReserv = $_POST["startReserv"];
    $endReserv = $_POST["endReserv"];

    $availableTables = getAvailableTables($size,$startReserv,$endReserv,$date);
    echo json_encode($availableTables);
}


?>