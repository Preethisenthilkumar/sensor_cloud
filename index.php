<?php
	$error = "";
   include("DBConnect.php");
   session_start();
   
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      
      $username = mysqli_real_escape_string($conn,$_POST['username']);
      $password = mysqli_real_escape_string($conn,$_POST['password']); 

      
      $sql = "SELECT user_id, user_type FROM users WHERE email = '$username' and password = '$password'";
      $result = mysqli_query($conn,$sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

      $userStatus = $row['user_type'];
      
      $count = mysqli_num_rows($result);
    
      if($count == 1) 
      {
         $_SESSION['user_log_in'] = $username;
         $_SESSION['userStatus'] = $userStatus;

         if ($row['user_type']=="admin") 
         {
           header("location: admin-page.php");
         } 
         else 
         {
           header("location: user-page.php");
         }  
      }
      else 
      { 
         $error = "Your Username or Password is invalid";   
      }
   }
?>

<!DOCTYPE HTML>
<html>
	<head>
		<title> First Page! </title>
	</head>
	<body>
		<div>
			<form action = "" method = "post">
				<h3> USER LOGIN </h3>
				<?php 
				echo $error;
				?>
				<div>
				User:<input type = "text" name="username" required=""/>
				</div>
				<div>
				Password:<input type = "password" name="password" required="" />
				</div>
				<div>
				<input type = "submit" name = "submit" value = "Submit" />
                <p>New to site?
                  <a href="register.php"> Create Account </a>
                </p>
            	</div>
			</form>	
		</div>
	</body>
</html>


