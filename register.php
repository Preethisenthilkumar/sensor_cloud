<?php

	include("DBConnect.php");
	session_start();

	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$first_name = mysqli_real_escape_string($conn,$_POST['first_name']);
		$last_name = mysqli_real_escape_string($conn,$_POST['last_name']);
		$email = mysqli_real_escape_string($conn,$_POST['email']);
		$password = mysqli_real_escape_string($conn,$_POST['password']);
		$street = mysqli_real_escape_string($conn,$_POST['street']);
		$city = mysqli_real_escape_string($conn,$_POST['city']);
		$state = mysqli_real_escape_string($conn,$_POST['state']);
		$zip = mysqli_real_escape_string($conn,$_POST['zip']);
		$phone = mysqli_real_escape_string($conn,$_POST['phone']);
		$credit_card = mysqli_real_escape_string($conn,$_POST['credit_card']);
		$expiry_date = mysqli_real_escape_string($conn,$_POST['expiry_date']);
		$cardHolderName = mysqli_real_escape_string($conn,$_POST['cardHolderName']);
		$support_plan = mysqli_real_escape_string($conn,$_POST['optionsRadios']);
		$user_type = mysqli_real_escape_string($conn,$_POST['user_type']);



		$sql = "INSERT INTO USERS(first_name, last_name, email, password, street, city, state, zip, phone_no, credit_card_no, expiry_date, card_holder_name, support_plan, user_type) VALUES ('$first_name', '$last_name', '$email', '$password', '$street', '$city', '$state', '$zip', '$phone', '$credit_card', '$expiry_date', '$cardHolderName','$support_plan','$user_type')";

		$res = mysqli_query($conn,$sql);


		if ($res) 
		{
    		//echo "New record created successfully";
    		header("location: index.php");
		} else {
    		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}

	}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Registration page</title>
	</head>
	<body>
		<form method="post" action="">
			<h2> Login Details </h2>
			<h3> Basic Infromation </h3>
			<label required=""> First Name : * </label>
			<input type="text" name="first_name" required=""/>
			</br>
			<label required=""> Last Name : * </label>
			<input type="text" name="last_name" required="" />
			</br>
			<label required=""> Email Address : * </label>
			<input type="text" name="email" required="" />
			</br>
			<label required=""> Retype Email Address : * </label>
			<input type="email" required="" />
			</br>
			<label required=""> New Password : * </label>
			<input type="password" name="password" required="" />
			</br>
			<label required=""> Retype Password : * </label>
			<input type="password" required="" />
			</br>
			</br>
			</br>
			<h3> Contact Information </h3>

			<label required=""> Full Name : * </label>
			<input type="text" required="" />
			</br>
			<label required=""> Street: * </label>
			<input type="text" name="street" required="" />
			</br>
			<label required=""> City: * </label>
			<input type="text" name="city" required="" />
			</br>
			<label required=""> State: * </label>
			<input type="text" name="state" required="" />
			</br>
			<label required=""> Zip: * </label>
			<input type="number" name="zip" required="" />
			</br>
			<label required="" > Phone Number: * </label>
			<input type="text" name="phone" required="" data-inputmask="'mask' : '(999) 999-9999'"/>
			</br>
			</br>
			</br>
			<h3> Payment Information </h3>

			<label required="" > Credit Card Number: * </label>
			<input type="text" name="credit_card" required="" data-inputmask="'mask' : '9999-9999-9999-9999'" />
			</br>
			<label required="" > Expiry Date: * </label>
			<input type="text" name="expiry_date" required="" data-inputmask="'mask' : '99/99'" />
			</br>
			<label required="" > Cardholder's Name: * </label>
			<input type="text" name="cardHolderName" required="" />
			</br>
			</br>
			</br>
			<h3> Support Plan </h3>

			<a href = "support_plan.php">Support Plan</a>
			<div>
			<label>
			<input type="radio" checked="" value="Basic Plan" name="optionsRadios" /> Basic Plan 
			</label>
			</br>
			<label>
			<input type="radio" checked="" value="Premium Plan" name="optionsRadios" /> Premium Plan
			</label>
			</br>
			<label>
			<input type="radio" checked="" value="Pro" name="optionsRadios" /> PRO
			</label>
			</div>
			</br>
			<h3> User Type </h3>

			<div>
			<label>
			<input type="radio" checked="" value="user" name="user_type" /> User 
			</label>
			</br>
			<label>
			<input type="radio" checked="" value="admin" name="user_type" /> Admin
			</label>
			</br>
			</div>
			</br>


			<button type="submit" name="submit"> Submit </button>
			<button type="submit" name="cancel"> Cancel </button>
			
		</form>
	</body>
</html>

<?php

mysqli_close($conn);
?>