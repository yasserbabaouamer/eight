<?php 
    session_start(); 
    include("models/database.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Eight-CheckOut</title>
    <script>
        uid = <?php if(isset($_SESSION["uid"])) echo json_encode((int)$_SESSION["uid"]); 
                                else echo json_encode(-1); ?>;
    </script>
</head>
<body>

    <!--header section starts!-->
    <header>

        <a href="#" class="logo"><i class="fas fa-utensils"></i>Eight.</a>
        <nav class="navbar">
            <a href="#home" class="active">Shopping Card</a>
            <a href="index.php">home</a>
        </nav>
        <div class="icons">
            <i class="fas fa-bars" id="menu-bars"></i>
            <i class="fas fa-search" id="search-icon"></i>
            <a href="#" class="fas fa-shopping-cart"></a>
            <i class="fas fa-user" id="user-icon"></i>
        </div>
    </header>
    <!--header section ends-->
    <div class="user-card" id="user-card">
        <div id="loged">
            <div class="user">
                <img src="assets/Customers/john mike.png" alt="">
                <div class="user-info">
                    <h3>Johnny depp</h3>
                    <div class="user-email">johnnydepp@gmail.com</div>            
                </div>
            </div>
            <button  id="logout-button">log out</button>
        </div>
        <div id="not-loged">
            <button id="login-button">Log in</button>
            <div>don't have an account?</div>
            <button id="singup-button">sing up</button>
        </div>
    </div>
    
    <!-- cart section starts-->
    <section class="card">
        <div class="items-container">
            <h3 class="title">Shopping card total</h3>

            <?php 
                if(isset($_SESSION['cart'])){
                    foreach($_SESSION['cart'] as $id){
                        $meal = getOneMeal($id);
                        echo "
                        <div class='item'>
                            <button id='close-button'><i class='fas fa-times'></i></button>
                            <div class='info'>
                                <div class='item-content'>
                                    <div class='image'>
                                        <img src='{$meal->photo}' alt=''>    
                                    </div>
                                    <div class='text'>
                                        <h3 id='name'>{$meal->name}</h3>
                                        <div id='category'>{$meal->category}</div>
                                    </div>
                                </div>
                                <div class='quantity'>
                                    <a class='plus' meal-price={$meal->price} meal-id = {$meal->mid}><i class='fas fa-plus'></i></a>
                                    <div class='display'>1</div>
                                    <a class='minus'><i class='fas fa-minus'></i></a>
                                </div>
                            </div>
                            <div class='pricing'>
                                <div class='price'  price-id = {$meal->mid}>{$meal->price}<b class='currency'>$</b></div>
                            </div>

                        </div>
                        ";
                    }
                }
                            
            ?>
            
            
        <div class="checkout-container">
            <h3>Checkout</h3>
            <div class="checkout-total">
                <div class="text">Total</div>
                <div id="total">000.00 $</div>
            </div>
            <hr>
            <div class="checkout-location">
                <div class="text">choose your location</div>
                <div id="map"></div>
            </div>
            <hr>
            <button id="checkout-button">Check out</button>
        </div>
        <div class="modal-checkout-container">
            <div class="modal-checkout">
                <h3>Order confirmed!</h3>
                <div class="text">Your order has been catched !, It will be delivered to you within 30 minutes </div>
                <button id="modal-confirm-button">OK</button> 
            </div>
        </div>
    </section>
    <!-- cart section ends-->
     <!-- footer section starts -->
     <section class="footer">
        <div class="box-container">
            <div class="box">
                <h3>locations</h3>
                <a href="#">location01</a>
                <a href="#">location02</a>
                <a href="#">location03</a>
                <a href="#">location04</a>
                <a href="#">location05</a>
            </div>
            <div class="box">
                <h3>quick links</h3>
                <a href="#home">home</a>
                <a href="#dishes">dishes</a>
                <a href="#about">about</a>
                <a href="#menu">menu</a>
                <a href="#review">review</a>
                <a href="#order">order</a>
            </div>
            <div class="box">
                <h3>contact info</h3>
                <a href="#">(+213)0 000 000</a>
                <a href="#">(+213)0 000 000</a>
                <a href="#">mail@eight.com</a>
                <a href="#">mail@eight.com</a>
                <a href="#">mail@eight.com</a>
            </div>
            <div class="box">
                <h3>follow us</h3>
                <a href="#">facebook</a>
                <a href="#">instagram</a>
                <a href="#">twitter</a>
                <a href="#">linkdein</a>
            </div>
        </div>
        <div class="credit">copyright @ 2023 <span>Eight </span>&<span> devTeam</span></div>
     </section>
     <!-- footer section ends -->
     


    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script src="js/shoppingScript.js"></script>
</body>
</html>

