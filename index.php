<?php
    // Autoload Composer packages
    require __DIR__ . '/vendor/autoload.php';
    // Load the .env file
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
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
    <script>
        var isUserLoggedIn = <?php echo checkIfUserIsLoggedIn() ? 1 : 0 ; ?>;
        var uid=-1
        var firstName=""
        var lastName=""
        var email=""
        var phone=""
        if(isUserLoggedIn === 1){
            
            uid = <?php if(isset($_SESSION["uid"])) echo json_encode((int)$_SESSION["uid"]); 
                                else echo json_encode(-1); ?>;
            firstName = <?php  if(isset($_SESSION["firstName"])) echo json_encode($_SESSION["firstName"]);
                                else echo json_encode("na"); ?>;
            lastName = <?php if(isset($_SESSION["lastName"])) echo json_encode($_SESSION['lastName']);
                                else echo json_encode("na"); ?>;
            email = <?php if(isset($_SESSION["email"])) echo json_encode($_SESSION['email']);
                                else echo json_encode("na"); ?>;
            phone = <?php if(isset($_SESSION["phone"])) echo json_encode($_SESSION['phone']);
                                else echo json_encode("na"); ?>;
        }
        </script>
    <title>Eight</title>
</head>
<body>

    <!--header section starts!-->
    <header>

        <a href="#" class="logo"><i class="fas fa-utensils"></i>Eight.</a>
        <nav class="navbar">
            <a href="#home" class="active">home</a>
            <a href="#dishes">dishes</a>
            <a href="#menu">menu</a>
            <a href="#tableReserv">reservation</a>
            <a href="#about">about</a>
            <a href="#review">review</a>
            
        </nav>
        <div class="icons">
            <i class="fas fa-bars" id="menu-bars"></i>
            <i class="fas fa-search" id="search-icon"></i>
            <a href="shoppingCart.php" class="fas fa-shopping-cart">
            <span class="notification-dot" id="notification"></span>
            </a>
            <i class="fas fa-user" id="user-icon"></i>
            <?php
                if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])){
                    echo "<script> console.log('notifi displayed');
                     document.getElementById('notification').style.display = 'flex' </script>";
                }else{
                    echo "<script> document.getElementById('notification').style.display = 'none' </script>";
                }
            ?>
        </div>
    </header>
    <!--header section ends --> 
    <!--user card-->
    <div class="user-card" id="user-card">
        <div id="loged">
            <div class="user">
                <img src="assets/Customers/john mike.png" alt="">
                <div class="user-info">
                    <h3 id="username">Johnny depp</h3>
                    <div id="user-email" class="user-email">johnnydepp@gmail.com</div>            
                </div>
            </div>
            <button  id="logout-button">log out</button>
        </div>
        <div id="not-loged">
            <button id="login-button">Log in</button>
            <div>don't have an account?</div>
            <button id="signup-button">sign up</button>
        </div>
    </div>
    <!--search form-->
    <form action="" id="search-form">
        <input type="search" name="" placeholder="search here.." id="search-box">
        <label for="search" class="fas fa-search"></label>
        <i class="fas fa-times" id="close"></i>
    </form>
    <!--to top-->
    <a href="#" class="to-top">
        <i class="fas fa-chevron-up"></i>
    </a>
    <!--home section starts-->
    <section class="home" id="home">
        <div class="swiper swiper-container home-slider">
            <div class="swiper-wrapper wrapper">
                <?php 
                    $specialMeals = getSpecialMeals();
                    if(!empty($specialMeals)){
                        foreach($specialMeals as $special){
                            echo "
                            <div class='swiper-slide slide'>
                                <div class='image'>
                                    <img src='{$special->photo}' alt=''>
                                </div>
                                <div class='content'>
                                    <span>our special dish</span>
                                    <h3>{$special->name}</h3>
                                    <p>{$special->description}</p>
                                    <button class='btn' meal-id = {$special->mid}>order now!</button>
                                </div>
                                
                            </div>
                            ";
                        }
                    }
                ?>   
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </section>
    <!--home section ends-->
    
    <!--dishes section starts-->
    <section class="dishes" id="dishes">
        <h3 class="sub-heading">our dishes</h3>
        <h1 class="heading">Populaire Dishes</h1>
        <div class="box-container">
            <?php 
                $populaireMeals = getPopulaireMeals();
                if(!empty($populaireMeals)){
                    foreach($populaireMeals as $populaire){
                        echo "
                        <div class='box'>
                            <a href='#' class='fas fa-heart'></a>
                            <a href='#' class='fas fa-eye'></a>
                            <img src='{$populaire->photo}' alt=''>
                            <h3>Grilled steak</h3>
                            <div class='stars'>
                                <i class='fas fa-star'></i>
                                <i class='fas fa-star'></i>
                                <i class='fas fa-star'></i>
                                <i class='fas fa-star'></i>
                                <i class='fas fa-star-half-alt'></i>
                            </div>
                            <span class='price'>{$populaire->price}<b class='curency'>$</b></span>
                            <a  class='btn' meal-id={$populaire->mid}>add to the card</a>
                        </div>
            
                        ";
                    }
                }
            ?>
            
        </div>

    </section>
    <!--dishes section ends-->
    <!--menu section starts-->

    <section class="menu" id="menu">
        <h3 class="sub-heading">our menu</h3>
        <h1 class="heading">today's speciality</h1>
        
        <div class ="category" id="Meats">
            <div class="category-name">Meats</div>
            <div class="swiper-container swiper menu-slider">
                <div class="swiper-wrapper wrapper">
                    <?php 
                        $meats = getMealsOfCategory("meat");
                        if(!empty($meats)){
                            foreach($meats as $meat){
                                echo "<div class='swiper-slide slide'>
                                <div class='image'>
                                    <img src='{$meat->photo}'>
                                </div>
                                <div class='content'>
                                    <div class='stars'>
                                        <i class='fas fa-star'></i>
                                        <i class='fas fa-star'></i>
                                        <i class='fas fa-star'></i>
                                        <i class='fas fa-star'></i>
                                        <i class='fas fa-star-half-alt'></i>
                                    </div>
                                    <h3>{$meat->name}</h3>
                                    <p>{$meat->description}</p>
                                    <span class='price'>{$meat->price}<b class='currency'>$</b></span>
                                    <a class='btn' meal-id = {$meat->mid}>add to card</a>
                                </div>
                            </div>";
                            }
                        }
                    ?>
                    
                </div>
            </div>
        </div><div class ="category" id="Soups">
            <div class="category-name">Soups</div>
            <div class="swiper-container swiper menu-slider">
                <div class="swiper-wrapper wrapper">
                    <?php 
                        $soups = getMealsOfCategory("soup");
                        foreach($soups as $soup){
                            echo "<div class='swiper-slide slide'>
                            <div class='image'>
                                <img src='{$soup->photo}'>
                            </div>
                            <div class='content'>
                                <div class='stars'>
                                    <i class='fas fa-star'></i>
                                    <i class='fas fa-star'></i>
                                    <i class='fas fa-star'></i>
                                    <i class='fas fa-star'></i>
                                    <i class='fas fa-star-half-alt'></i>
                                </div>
                                <h3>{$soup->name}</h3>
                                <p>{$soup->description}</p>
                                <span class='price'>{$soup->price}<b class='currency'>$</b></span>
                                <a class='btn' meal-id = {$soup->mid}>add to card</a>
                            </div>
                        </div>";
                        }
                    ?>
                    

                    
                </div>
            </div>
        </div>
        <div class ="category" id="Desserts">
            <div class="category-name">Desserts</div>
            <div class="swiper-container swiper menu-slider">
                <div class="swiper-wrapper wrapper">
                <?php   
                        $desserts = getMealsOfCategory("dessert");
                        if(!empty($meats)){
                            foreach($desserts as $dessert){
                                echo "<div class='swiper-slide slide'>
                                <div class='image'>
                                    <img src='{$dessert->photo}'>
                                </div>
                                <div class='content'>
                                    <div class='stars'>
                                        <i class='fas fa-star'></i>
                                        <i class='fas fa-star'></i>
                                        <i class='fas fa-star'></i>
                                        <i class='fas fa-star'></i>
                                        <i class='fas fa-star-half-alt'></i>
                                    </div>
                                    <h3>{$dessert->name}</h3>
                                    <p>{$dessert->description}</p>
                                    <span class='price'>{$dessert->price}<b class='currency'> $</b></span>
                                    <a class='btn' meal-id = {$dessert->mid}>add to card</a>
                                </div>
                            </div>";
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
    </section>
    <!--menu section ends-->
    <!-- Table booking section starts -->
    <section class="table" id="tableReserv">
        <h3 class="sub-heading">Table booking</h3>
        <h1 class="heading">it's a pleasure having you!</h1>
        <div class="holder">
            <div class="container">
                <div class="dropdown">
                    <select id="dropdown-table">
                        <option value="2 people" selected>2 people</option>
                        <option value="4 people">4 people</option>
                        <option value="6 people">6 people</option>
                        <option value="8 people">8 people</option>
                        <option value="10 people">10 people</option>
                    </select>
                </div>
                <div class="dropdown">
                    <input type="date" id="dropdown-date">
                </div>
                <div class="dropdown">
                    <select id="dropdown-time">
                        <!--<option value="09:00" selected>09:00</option>-->
                        <script>
                            var select = document.getElementById('dropdown-time');
                            var currentTime = new Date();
                            var roundedMinutes = Math.ceil(currentTime.getMinutes() / 30) * 30;
                            currentTime.setMinutes(roundedMinutes);
                            console.log("hours :"+ currentTime.getHours()+ "minutes : "+currentTime.getMinutes());
            
                        </script>
                    
                        <option value="09:30">09:30</option> 
                        <option value="10:00">10:00</option>
                        <option value="10:30">10:30</option>
                        <option value="11:00">11:00</option>
                        <option value="11:30">11:30</option>
                        <option value="12:00">12:00</option>
                        <option value="12:30">12:30</option>
                        <option value="13:00">13:00</option>
                        <option value="13:30">13:30</option>
                        <option value="14:00">14:00</option>
                        <option value="14:30">14:30</option>
                        <option value="15:00">15:00</option>
                        <option value="15:30">15:30</option>
                        <option value="16:00">16:00</option>
                        <option value="16:30">16:30</option>
                        <option value="17:00">17:00</option>
                        <option value="17:30">17:30</option>
                        <option value="18:00">18:00</option>
                        <option value="18:30">18:30</option>
                        <option value="19:00">19:00</option>
                        <option value="19:30">19:30</option>
                        <option value="20:00">20:00</option>
                        <option value="20:30">20:30</option>
                        <option value="21:00">21:00</option>
                        <option value="21:30">21:30</option>
                        <option value="22:00">22:00</option>
                        <option value="22:30">22:30</option>
                        <option value="23:00">23:00</option>
                    </select>
                </div>
                <div class="dropdown">
                    <select id="dropdown-duration">
                        <option value="30 min">30 min</option>
                        <option value="01 h">01 h</option>
                        <option value="01h 30min">01h 30min</option>
                        <option value="02 h">02 h</option>
                    </select>
                </div>
                <button id="reserveBtn" >reserve</button>
            </div>
            <div id="timeOptions" class="time-options">
                <button class="time-button" id="time-opt1">option 1</button>
                <button class="time-button" id="time-opt2">option 2</button>
                <button class="time-button" id="time-opt3">option 3</button>
                <button class="time-button" id="time-opt4">option 4</button>
                <button class="time-button" id="time-opt5">option 5</button>
            </div>
            <div id="no-options" style="display: none;
                        font-size: 1.7em;
                        text-align: center;
                        margin: 15px 0px;
                        color: red;">
                At the moment, there's no online availability within 2 hours of your chosen time 
            </div>
        </div>
        <div class="modal-container">
            <div class="modal">
                <button id="close-button"><i class="fas fa-times"></i></button>
                <div class="reservation-info">
                    <h3>Reservation info</h3>
                    <div class="text"><i class="fas fa-chair"></i><div id="modal-table">table</div></div>
                    <div class="text" ><i class="fas fa-clock"></i><div id="modal-time">time</div></div>
                    <div class="text" ><i class="fas fa-calendar-alt"></i><div id="modal-date">calander</div></div>
                    <div class="text" ><i class="fas fa-hourglass-2"></i><div id="modal-duration">duration</div></div>
                    <button id="confirm-button">confirm</button>    
                </div>
                <div class="reservation-confirmed">
                    <h3>Reservation confirmed!</h3>
                    <div class="text">We hope you'll have a wonderful time at our restaurant!</div>
                </div>
            </div>
        </div>
     </section>
     <!-- Table booking section ends -->
    <!--about section starts-->
    <section class="about" id="about">
        <h3 class="sub-heading">about us</h3>
        <h1 class="heading">why choose us?</h1>
        <div class="row">
            <div class="image">
                <img src="assets/About/about02.jpg" alt="">
            </div>
            <div class="content">
                <h3>A Taste of Timeless Elegance</h3>
                <p>Experience sophisticated dining at our restaurant,
                    where ancient culinary arts are blended with the finest ingredients. Our chefs craft each dish with care and attention to detail,
                    from delicate seafood to hearty roasts.
                    <br>Indulge in a culinary journey like no other,
                    with a nod to the past and an eye to the future.
                </p>
                <div class="icons-container">
                    <div class="icon">
                        <i class="fa fa-shipping-fast"></i>
                        <p>fast delevery</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-dollar-sign"></i>
                        <p>easy payment</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-headset"></i>
                        <p>24/7 service</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--about section ends-->
    <!-- review section starts -->
    <section class="review" id="review">
        <h3 class="sub-heading">customer's review</h3>
        <h1 class="heading">what they say!</h1>
        <div class="reviews">
            <div class="swiper-container swiper review-slider">
                <div class="swiper-wrapper wrapper">
                    <?php 
                        $reviews = getReviews();
                        if(!empty($reviews)){
                            foreach($reviews as $review){
                                echo "
                                <div class='swiper-slide slide'>
                                    <i class='fas fa-quote-right'></i>
                                    <div class='user'>
                                        <img src='assets/Customers/john mike.png' alt=''>
                                        <div class='user-info'>
                                            <h3>{$review->firstName} {$review->lastName}</h3>
                                            <div class='stars'>
                                                <i class='fas fa-star'></i>
                                                <i class='fas fa-star'></i>
                                                <i class='fas fa-star'></i>
                                                <i class='fas fa-star-half-alt'></i>
                                                <i class='far fa-star'></i>
                                            </div>            
                                        </div>
                                    </div>
                                    <p>{$review->review}</p>
                                </div>           
                                ";
                            }
                        }
                    ?>
                    
                </div>
            </div>
            <div class="user-review">
                    <div id="add-review" style="display: flex;">
                        <i class="fas fa-plus-circle"></i>
                        <div>Add review</div>   
                    </div>
                    <div id="write-review" >
                        <textarea name="textarea" id="textarea" class="textarea"></textarea>
                        <i class="fas fa-paper-plane" id="send-review"></i>
                    </div>
            </div>
        </div>
    </section>
    <!-- review section ends -->
    <div class="snackbar">
        <img src="assets/check-svgrepo-com (1).svg" alt="">
        <div class="snackbar-text">
            Your review has been sent
        </div>
        
    </div>
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
    



    
    <!--custom js file link-->
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script src="js/script.js"></script>

</body>
</html>
