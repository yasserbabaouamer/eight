<?php
    
    session_start();
    if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['mid'])){
        $mid = (int) $_POST['mid'];
        $index = array_search($mid,$_SESSION['cart']);
        if($index!==false){
            array_splice($_SESSION['cart'],$index,1);
        }
        echo json_encode($_SESSION['cart']);
    }
?>