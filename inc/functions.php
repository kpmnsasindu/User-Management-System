<?php 

	function verify_query($result_set){
		global $connection;

		if(!$result_set){
			die("Database query failed.". mysqli_error(
				$connection));
		}
	}

	function check_req_fields($req_fields){

		$errors = array();

		//check required fields
		foreach ($req_fields as $fields) {

			if(empty(trim($_POST[$fields]))){
				$errors[] = $fields . ' is required';
			}
		}

		return $errors;
	}

	function check_max_len($max_len_fields){

		$errors = array();

		foreach ($max_len_fields as $fields => $max_len) {

			if(strlen(trim($_POST[$fields])) > $max_len){
				$errors[] = $fields . ' must be less than '. $max_len . 'characters';
			}
		}

		return $errors;
	}

	function display_errors($errors){

		echo '<div class="errmsg">';
				echo 'There were errors on your form <br>';
				foreach ($errors as $error) {
					$error = ucfirst(str_replace("_", " ", $error));
					echo $error . "<br>";
				}
				echo '</div>';
	
	}

	function is_email($email) {
		return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email)) ? FALSE : TRUE;
	}

 ?>