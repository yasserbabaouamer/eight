<?php 
    //session_start();
    include("models/Meal.php");
    include("models/Review.php");
    $db_server = $_ENV['DB_HOST'];
    $db_user = $_ENV['DB_USER'];
    $db_pass = $_ENV['DB_PASSWORD'];
    $db_name = $_ENV['DB_NAME'];
    $db = mysqli_connect($db_server,$db_user,$db_pass,$db_name);    
    

    function getOneMeal(int $mid):Meal{
        global $db;
        $query = "SELECT * from meals where mid = {$mid}";
        $result = mysqli_query($db,$query);
        $meal = new Meal();
        if($result && mysqli_num_rows($result)>0){
            $row = mysqli_fetch_assoc($result);
            $meal->mid = $row["mid"];
            $meal->name = $row["name"];
            $meal->category = $row["category"];
            $meal->photo = $row["photo"];
            $meal->description = $row["description"];
            $meal->price = $row["price"];
        }
        return $meal;
    }

    function getSpecialMeals():array{
        global $db;
        $query = "SELECT * FROM meals INNER JOIN special_meals ON special_meals.smid = meals.mid";
        $result = mysqli_query($db,$query);
        $arrSpecials = array();
        if($result && mysqli_num_rows($result)>0){
            while($row = mysqli_fetch_assoc($result)){
                $meal = new Meal();
                $meal->mid = $row["mid"];
                $meal->name = $row["name"];
                $meal->category = $row["category"];
                $meal->photo = $row["photo"];
                $meal->description = $row["description"];
                $meal->price = $row["price"];
                $arrSpecials[] = $meal;
            }
        }
        return $arrSpecials;
    }

    function getPopulaireMeals():array{
        global $db;
        $query = "SELECT * FROM meals INNER JOIN populaire_meals ON populaire_meals.pmid = meals.mid";
        $result = mysqli_query($db,$query);
        $arrPopulaires = array();
        if($result && mysqli_num_rows($result)>0){
            while($row = mysqli_fetch_assoc($result)){
                $meal = new Meal();
                $meal->mid = $row["mid"];
                $meal->name = $row["name"];
                $meal->category = $row["category"];
                $meal->photo = $row["photo"];
                $meal->description = $row["description"];
                $meal->price = $row["price"];
                $arrPopulaires[] = $meal;
            }
        }
        return $arrPopulaires;
    }

    function getOneUser(string $email):array{
        global $db;
        $query = "SELECT * FROM users where email = '{$email}'";
        $result = mysqli_query($db,$query);
        $arr = array();
        if($result && mysqli_num_rows($result)>0){
            $row = mysqli_fetch_assoc($result);
            $arr["uid"] = $row["uid"];
            $arr["firstName"] = $row["firstName"];
            $arr["lastName"] = $row["lastName"];
            $arr["email"] = $row["email"];
            $arr["password"] = $row["password"];
            $arr["phone"] = $row["phone"];
        }
        return $arr;
    }

    function checkIfUserIsLoggedIn():bool{
        return isset($_SESSION["uid"]) && isset($_SESSION["firstName"]) && isset($_SESSION["lastName"])
                && isset($_SESSION["email"]) && isset($_SESSION["phone"]);
    }

    function getMealsOfCategory(string $category):array{
        global $db;
        $sql = $db->prepare("SELECT * FROM meals WHERE category like ?");
        $likeParam = "%".$category."%";
        $sql->bind_param("s",$likeParam);
        $sql->execute();
        $result = $sql->get_result();
        $arr = array();
        if($result && mysqli_num_rows($result) > 0){
            while ($row = mysqli_fetch_assoc($result)){
                $meal = new Meal();
                $meal->mid = $row["mid"];
                $meal->name = $row["name"];
                $meal->category = $row["category"];
                $meal->price = $row["price"];
                $meal->photo = $row["photo"];
                $meal->description = $row["description"];
                $arr[] = $meal;
            }   
        }
        $sql->close();
        return $arr;
    }
    function checkEmailExistence(string $email):bool{
        global $db;
        $sql = $db->prepare("SELECT email from users where email = ?");
        $sql->bind_param("s",$email);
        $sql->execute();
        $result = $sql->get_result();
        return mysqli_num_rows($result) > 0;
    }

    function accountVerification(string $email ,string $password) : bool{
        global $db;
        $sql = $db->prepare("SELECT * FROM users WHERE email = ?");
        $sql->bind_param("s",$email);
        $sql->execute();
        $result = $sql->get_result();
        if($result && mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);
            $savedHashedPass = $row["password"];
            return password_verify($password,$savedHashedPass);
        }else{
            $sql->close();
            return false;
        }       
    }

    function signupNewUser($firstName,$lastName,$email,$password,$phone):bool{
        global $db;
        $sql = $db->prepare("INSERT INTO users (firstName,lastName,email,
                                    password,phone) 
                                    VALUES (?,?,?,?,?)");
        $hashedPassword = password_hash($password,PASSWORD_DEFAULT);
        $sql->bind_param("sssss",$firstName,$lastName,$email,$hashedPassword,$phone);
        $result = $sql->execute();

        $id = mysqli_insert_id($db);
        $sql->close();
        return $result;
    }

    function getReviews():array{
        global $db;
        $sql = "SELECT reviews.revid,users.firstName,users.lastName,reviews.review,reviews.rating,reviews.date from reviews INNER JOIN users on reviews.uid = users.uid where revid IN (
            SELECT MAX(revid) as revid
            FROM reviews
            GROUP BY uid
             )";
        $arrReviews = array();
        $result = mysqli_query($db,$sql);
        if($result && mysqli_num_rows($result)>0){
            while($row = mysqli_fetch_assoc($result)){
                $review = new Review();
                $review->revid = $row["revid"];
                $review->firstName = $row["firstName"];
                $review->lastName = $row["lastName"];
                $review->review = $row["review"];
                $review->rating = $row["rating"];
                $review->date = $row["date"];
                $arrReviews[] = $review;
            }
        }
        return $arrReviews;  
        }

    

        function getAvailableTables(int $size,int $start_reserv,int $end_reserv,string $date):array{
            global $db;
            $start_res_tstmp = $start_reserv;
            $end_res_tstmp = $end_reserv;
            $arrTables = array();
            $sql = "SELECT tid FROM tables where size = {$size} AND tid NOT IN (SELECT tid FROM reservations WHERE 
            reservations.date  = '{$date}' AND (
            UNIX_TIMESTAMP(reservations.start_reserv)>{$start_res_tstmp} AND   
            UNIX_TIMESTAMP(reservations.start_reserv)<{$end_res_tstmp}
            OR
            UNIX_TIMESTAMP(reservations.start_reserv)<={$start_res_tstmp} AND
            UNIX_TIMESTAMP(reservations.end_reserv)>={$end_res_tstmp}
            OR
            UNIX_TIMESTAMP(reservations.end_reserv)>{$start_res_tstmp} AND
            UNIX_TIMESTAMP(reservations.end_reserv)<{$end_res_tstmp}))";
            $result = mysqli_query($db,$sql);
            if($result && mysqli_num_rows($result)>0){
                while($row = mysqli_fetch_assoc($result)){
                    $arrTables[] = $row["tid"];
                }
            }
            return $arrTables;
        }

    function makeTableReservation(int $uid,int $tid,string $date,int $start_reserv,int $end_reserv):bool{
        global $db;
        $mysqlStartReservation = date('Y-m-d H:i:s', $start_reserv);
        $mysqlEndReservation = date('Y-m-d H:i:s', $end_reserv);
        $sql = $db->prepare("INSERT INTO reservations (uid,tid,date,start_reserv,end_reserv) VALUES (?,?,?,?,?)");
        $sql->bind_param("iisss",$uid,$tid,$date,$mysqlStartReservation,$mysqlEndReservation);
        return $sql->execute();
    }

    function makeOnlineOrder(int $uid,string $date,float $total,float $latitude,float $longitude,array $oderedFoods):bool{
        global $db;
        $sql = $db->prepare("INSERT INTO orders (uid,date,total,latitude,longitude) values (?,?,?,?,?)");
        $sql->bind_param("isddd",$uid,$date,$total,$latitude,$longitude);
        if($sql->execute()){
            $order_id = mysqli_insert_id($db);
            foreach($oderedFoods as $food){
                $insertionSql = $db->prepare("INSERT INTO ordered_items values (?,?,?)");
                $insertionSql->bind_param("iii",$order_id,$food->mid,$food->qte);
                $insertionSql->execute();
            }
            return true;
        }else{
            return false;
        }
    }
    function addReview(int $uid , string $review,int $rating,string $date):bool{
        global $db;
        $sql = $db->prepare("INSERT INTO reviews (uid,review,rating,date) VALUES (?,?,?,?)");
        $sql->bind_param("isis",$uid,$review,$rating,$date);
        return $sql->execute();
    }
?>