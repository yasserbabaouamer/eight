<?php
    session_start();
    include("models/database.php");
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Login Page</title>
    <link rel="stylesheet" href="css/loginStyles.css">
  </head>
  <body>
    <div class="center">
      <h1>Login</h1>
      <form action="login.php" method="post">
        <div class="txt_field">
          <input type="email" name="email"  placeholder="Email@" required>
          <span></span>
          
        </div>
        <div class="txt_field">
          <input type="password" name ="password" placeholder="Password" required>
          <span></span>
        </div>
        <div class="pass">Forgot Password?</div>
        <div id="verification" class="verification_failed">Invalid Informations !</div>
        <input id="loginBtn" type="submit" name="login" value="Login">
        <div class="signup_link">
          Not a member? <a href="signup.php">Signup</a>
        </div>
      </form>
    </div>
  </body>
</html>
<?php
    if(isset($_POST["login"])){
        if(!empty($_POST["email"]) && !empty($_POST["password"])){
            $email = $_POST["email"];
            $password = $_POST["password"];
            $isCorrect = accountVerification($email,$password);
            if($isCorrect){
                $user = getOneUser($email);
                $_SESSION["uid"] = $user["uid"];
                $_SESSION["firstName"] = $user["firstName"];
                $_SESSION["lastName"] = $user["lastName"];
                $_SESSION["email"] = $user["email"];
                $_SESSION["password"] = $user["password"];
                $_SESSION["phone"] = $user["phone"];
                header('Location:index.php');
            }else{
                echo "<script>var verificationResult = document.getElementById('verification').style.display = 'block'; 
                </script>";
            }
        }
    }
?>
