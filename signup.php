<?php
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
      <h1>Signup</h1>
      <form action="signup.php" method="post">
        <div class="txt_field">
          <input type="text" name="firstName" placeholder="First name" required>
          <span></span>
        </div>
        <div class="txt_field">
          <input type="text" name="lastName" placeholder="Last name" required>
          <span></span>
        </div>
        <div class="txt_field">
          <input type="email" name="email" placeholder="Email@" required>
          <span></span>
        </div>
        <div class="txt_field">
          <input type="password" name="password" placeholder="Password" required>
          <span></span>
        </div>
        <div class="txt_field">
          <input type="tel" name="phone" placeholder="Phone number" required>
          <span></span>
        </div>
        <div id="emailVerify" style=" display: none;text-align: center;margin: 15px 0px;color: red;">
          This Email is Already token</div>
        <input type="submit" name="signup" value="Create account">
        <div class="signup_link">
            Already have account ? <a href="login.php">Login</a>
        </div>
      </form>
    </div>
    <script>
      
    </script>

  </body>
</html>
<?php
  if(isset($_POST["signup"])){
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $phone = $_POST["phone"];
    echo "exected";
    if(checkEmailExistence($email)){
      echo "<script> 
      document.getElementById('emailVerify').style.display='block';
      </script>";
    }else{
        $result = signupNewUser($firstName,$lastName,$email,$password,$phone);
        if($result){
          echo "<script> 
          alert('You're signed up correctly , go to login page now');
          </script>";
          header("Location:login.php");
        }else{
          echo "<script> 
          alert('Something went wrong, please Try later');
          </script>";
          exit();
        }
    }
  }

?>