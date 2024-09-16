<?php
    include("C:/xampp/htdocs/restaurantProject/models/database.php");

    if(isset($_POST["uid"]) && isset($_POST["date"])  && isset($_POST["total"]) 
    && isset($_POST["latitude"]) && isset($_POST["longitude"]) && isset($_POST["arrMeals"])){
        $uid =  (int) $_POST["uid"];
        $date = (int) $_POST["date"];
        $total = (float) $_POST["total"];
        $latitude = (float) $_POST["latitude"];
        $longitude = (float) $_POST["longitude"];
        $sqlDate = date('Y-m-d H:i:s',$date);
        $arrMeals = json_decode($_POST["arrMeals"]);
        $result = makeOnlineOrder($uid,$sqlDate,$total,$latitude,$longitude,$arrMeals);
        echo json_encode($result);
    }
?>