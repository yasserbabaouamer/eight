<?php
    session_start();
    if(isset($_POST["mid"])){
        if(isset($_SESSION['cart'])){
            if(!in_array($_POST["mid"],$_SESSION['cart'])){
                $_SESSION['cart'][] = $_POST["mid"];
            }
        }else{
            $_SESSION['cart'] = array();
            $_SESSION['cart'][] = $_POST["mid"];
        }
        
    }
?>