<?php
    include("C:/xampp/htdocs/restaurantProject/Models/database.php");

    $result = false;
    if(isset($_POST["uid"]) && isset($_POST["review"]) 
        && isset($_POST["rating"]) && isset($_POST["date"])){
            $uid = (int) $_POST['uid'];
            $review = $_POST['review'];
            $rating = (int)$_POST['rating'];
            $date = (int) $_POST['date'];
            $mysqlDate = date('Y-m-d H:i:s',$date);
            $result = addReview($uid,$review,$rating,$mysqlDate);
            
        }
    echo json_encode($result);

    
?>