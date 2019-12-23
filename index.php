<?php session_start(); ?>
<?php require_once('inc/connection.php'); ?>
<?php require_once('inc/functions.php'); ?>
<?php

	$errors = array();
	 

	//check for form submission
	if (isset($_POST['submit'])) {
		//check if the username and password has been entered
		if(!isset($_POST['email']) || strlen(trim($_POST['email'])) < 1) {
			$errors[] = 'username is Missing / Invalid';
		}

		if(!isset($_POST['password']) || strlen(trim($_POST['password'])) < 1) {
			$errors[] = 'Password is Missing / Invalid';
		}
		
		//check if there are any errors in the form
		if(empty($errors)){
			//save username and password into variables
			$email = mysqli_real_escape_string($connection, $_POST['email']);

			$password = mysqli_real_escape_string($connection, $_POST['password']);

			$hashed_password = sha1($password);

			//prepare database query
			$query = "SELECT * FROM user 
						WHERE email = '{$email}'
						AND password = '{$hashed_password}'
						LIMIT 1";

			$reslut_set = mysqli_query($connection,$query);



			verify_query($reslut_set);
				//query successful


				//check if the user is valid
				if(mysqli_num_rows($reslut_set) == 1){
					//valid user found
					$user = mysqli_fetch_assoc($reslut_set);
					$_SESSION['user_id'] = $user['id'];
					$_SESSION['first_name'] = $user['first_name'];

					//updating last login
					$query = "UPDATE user SET last_login = NOW()";
					$query .= "WHERE id={$_SESSION['user_id']} LIMIT 1";

					$result_set = mysqli_query($connection,$query);

					verify_query($result_set);
					

					//redirect to users.php
					header("Location: users.php");
				}else{
					$errors[] = "Invalid Username / Passwrod";
				}
			
			//if not,display error

		}

		
	}

 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Log In - User Management System</title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>

	<div class="login">
		
		<form action="index.php" method="post">
			
			<fieldset>
				
				<legend><h1>Log In</h1></legend>

				<?php 
					if(isset($errors) && !empty($errors)){

						echo '<p class="error">Invalied Username / Password</p>';
					}


				 ?>

				 <?php 
					if(isset($_GET['logout'])){

						echo '<p class="info">You have Successfully logged out from the system</p>';
					}


				 ?>
				
				<p>
					<label>Username:</label>
					<input type="text" name="email" placeholder="Email Address">
				</p>

				<p>
					<label>Password:</label>
					<input type="Password" name="password" placeholder="Password">
				</p>

				<button type="submit" name="submit">Log In</button>

			</fieldset>

		</form>

	</div> <!-- .login -->

</body>
</html>
<?php mysqli_close($connection); ?>