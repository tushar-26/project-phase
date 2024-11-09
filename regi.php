<?php 

session_start();
if (isset($_SESSION["user"])){
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="favicon.png">
    <link rel="stylesheet" href="register.css">
    <title>Sign Up</title>
</head>
<body>
    <div class="wrapper">
        <nav class="nav">
            <div class="nav-logo">
                <img style="width: 120px;" src="favicon.png" alt="logo">
            </div>
            <div class="nav-menu" id="navMenu">
                <ul>
                    <li><a href="#" class="link active">Home</a></li>
                    <li><a href="#" class="link">About</a></li>
                    <li><a href="members.html" class="link">Members</a></li>
                    <li><a href="#" class="link">Customers</a></li>
                </ul>
            </div>
            <div class="nav-btns">
                
                <a href="login.php"><button class="l-btn" id="loginBtn">Sign In</button></a>
                <button class="s-btn" id="registerBtn">Sign Up</button>

        </div>
        <div class="nav-menu-btn">
            <i class="bx bx-menu" onclick="myMenuFunction()"></i>
        </div>
        </nav>

        <div class="form-box">
            <?php 
            if(isset($_POST["Signup"])){

                $first_name = $_POST["fname"];
                $last_name = $_POST["lname"];
                $email = $_POST["email"];
                $password = $_POST["password"];
                $ConfirmPassword = $_POST["confirm-password"]; 
                $PasswordHash = password_hash($password, PASSWORD_DEFAULT);  //security

                $errors = array();

                if(empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($ConfirmPassword)){
                    array_push($errors, "all fields shouldn't be empty <br>");
                }else{
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                    array_push($errors, "email isn't valid <br>");
                } 
                if(strlen($password) <8){
                    array_push($errors, "password must be 8 characters long <br>");
                }
                if($password !== $ConfirmPassword){
                    array_push($errors, "password doesn't match <br>");
                }
                require_once('database.php');
                $sql = "SELECT * FROM users WHERE email = '$email'";
                $result = mysqli_query($conn, $sql);
                $rowCount = mysqli_num_rows($result);
                if($rowCount>0){
                    array_push($errors, "Email Already Exists");
                }
            }
                if(count($errors)>0){
                    foreach($errors as $error){
                        echo $error;
                    }
                }else{
                    //inserting data to database
                   
                    $sql ="INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)";
                    $stmt = mysqli_stmt_init($conn);
                    $prepareStmt = mysqli_stmt_prepare($stmt,$sql);
                    if($prepareStmt){
                        mysqli_stmt_bind_param($stmt,"ssss",$first_name, $last_name, $email,$PasswordHash);
                        mysqli_stmt_execute($stmt);
                        echo "you are registered successfully." . "<br>" . "now you can login to enter website";
                    }else{
                        die("something went wrong");
                    }
                }
            }
            
            ?>
            <form class="register-container" id="register" action="register.php" method="post">
                <div class="top">
                    <span>Already have an account? <a href="login.php">Login</a></span>
                    <header>Sign Up to Curenet</header>
                </div>
                <div class="two-forms">
                    <div class="input-box">
                        <input type="text" class="input-field" placeholder="Firstname" name="fname">
                        <i class="bx bx-user"></i>
                    </div>
                    <div class="input-box">
                        <input type="text" class="input-field" placeholder="Lastname" name="lname">
                        <i class="bx bx-user"></i>
                    </div>
                </div>
                <div class="input-box">
                    <input type="text" class="input-field" placeholder="Email" name="email">
                    <i class="bx bx-envelope"></i>
                </div>
                <div class="input-box">
                    <input type="password" class="input-field" placeholder="Password" name="password">
                    <i class="bx bx-lock-alt"></i>
                </div>
                <div class="input-box">
                    <input type="password" class="input-field" placeholder="Confirm Password" name="confirm-password">
                    <i class="bx bx-lock-alt"></i>
                </div>
                <div class="input-box">
                    <input type="submit" class="submit" value="Register" name="Signup">
                </div>
            </form>
        </div>
    </div>
    <script>
   
        function myMenuFunction() {
           var i = document.getElementById("navMenu");
       
           if(i.className === "nav-menu") {
               i.className += " responsive";
           } else {
               i.className = "nav-menu";
           }
          }
        
       </script>
</body>
</html>
