<?php session_start(); ?>
<?php require_once('inc/connection.php'); ?>
<?php require_once('inc/functions.php'); ?>
<?php

	//checking if a user logged in
	if(!isset($_SESSION["user_id"])){
		header("location: index.php");
	}

	$errors = array();

	$first_name = '';
	$last_name = '';
	$email = '';
	$password = '';

	if(isset($_POST["submit"])){

		$first_name = $_POST['first_name'];
		$last_name = $_POST['last_name'];
		$email = $_POST['email'];
		$password = $_POST['password'];

		//checking required fileds

		$req_fields = array('first_name','last_name','email','password');

		$errors = array_merge($errors,check_req_fields($req_fields));


		//checking max length
		$max_len_fields = array('first_name' => 50,'last_name' => 100,'email' => 100,'password' => 40);

		$errors = array_merge($errors,check_max_len($max_len_fields));
		

		//checking email address
		if(!is_email($_POST['email'])){
			$errors[] = 'Email address in invalied.';
		}

		//checking if email address already exists
		$email = mysqli_real_escape_string($connection,$_POST['email']);

		$query = "SELECT * FROM user WHERE email = '{$email}' LIMIT 1";

		$result_set = mysqli_query($connection, $query);

		if($result_set){
			if(mysqli_num_rows($result_set) == 1){
				$errors[] = 'email address laready exists';
			}
		}

		if(empty($errors)){
			//no errors found...addin new record

			$first_name = mysqli_real_escape_string($connection,$_POST['first_name']);
			$last_name = mysqli_real_escape_string($connection,$_POST['last_name']);
			$password = mysqli_real_escape_string($connection,$_POST['password']);
			//email address is already sanitized
			$hashed_password = sha1($password);

			$query = "INSERT INTO user ( ";
			$query .= "first_name, last_name, email, password, is_deleted";
			$query .= ") VALUES (";
			$query .= "'{$first_name}','{$last_name}','{$email}','{$hashed_password}',0";
			$query .= ")";

			$result = mysqli_query($connection,$query);

			if($result){
				//query successfull....redirecting to users page
				header("Location: users.php?user_added=true");
			}else{
				$errors[] = 'Failed to add the new record';
			}

		}

	} 

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Add New User</title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>

	<header>
		<div class="appname">User Management System</div>
		<div class="logedin">Welcome <?php echo $_SESSION["first_name"]; ?> <a href="logout.php">Log Out</a></div>
	</header>

	<main>
		<h1>Add New User <span><a href="users.php"> < Back to user list</a></span></h1>

		<?php 

			if(!empty($errors)){
				display_errors($errors);
			}

		 ?>
		
		<form action="add-user.php" method="post" class="userform">
			
			<p>
				<label>First Name:</label>
				<input type="text" name="first_name" <?php echo 'value="'. $first_name .'"' ?>>
			</p>

			<p>
				<label>Last Name:</label>
				<input type="text" name="last_name" <?php echo 'value="'. $last_name .'"' ?>>
			</p>

			<p>
				<label>Email Address:</label>
				<input type="text" name="email" <?php echo 'value="'. $email .'"' ?>>
			</p>

			<p>
				<label>New Password:</label>
				<input type="password" name="password" >
			</p>

			<p>
				<label>&nbsp;</label>
				<button type="submit" name="submit">Save</button>
			</p>

		</form>

	</main>


</body>
</html>