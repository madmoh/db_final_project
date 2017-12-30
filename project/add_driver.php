<?php
	$location = '';
	if (strpos(basename($_SERVER['HTTP_REFERER']), 'index.php') !== false) $location = "show_driver_cars.php";
	else $location = "show_drivers_cars.php";
	
	$license_number = $first_name = $last_name = $birth_date_string = $city = $password = $hashed_password = '';
	$birth_date;
	if (isset($_POST['license_number']) && isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['birth_date']) && isset($_POST['city'])) {
		$license_number = $_POST['license_number'];
		$first_name = $_POST['first_name'];
		$last_name = $_POST['last_name'];
		$birth_date_string = $_POST['birth_date'];
		$city = $_POST['city'];
		$password = $_POST['password'];
	}
	$birth_date_date = new DateTime($birth_date_string);
	$today = new DateTime();
	$age = $today->diff($birth_date_date);
	$birth_date_formatted = date_format($birth_date_date, 'Y-m-d');
	if (strcmp($password, '') != 0) {
		$hashed_password = password_hash($password, PASSWORD_BCRYPT);
		$password = '';
	}
		
	$mysqli = new mysqli("localhost", "root", "", "project");
	if ($mysqli->connect_errno) {
		$mysqli->close();
		
		header('Location: ' . basename(substr($_SERVER['HTTP_REFERER'], 0, strpos($_SERVER['HTTP_REFERER'], ".php") + 4)) . "?errorMssg=".urlencode("Could not connect to database"));
		exit();
	}
	$query = "insert into driver values ('".$license_number."', '".$first_name."', '".$last_name."', ".$age->format("%Y").", '".$birth_date_formatted."', '".$city."', '".$hashed_password."')";
	$mysqli->query($query);
	if (strcmp($mysqli->error,'')) {
		$mysqli->close();
		header('Location: ' . basename(substr($_SERVER['HTTP_REFERER'], 0, strpos($_SERVER['HTTP_REFERER'], ".php") + 4)) . "?errorMssg=".urlencode("Error in creating account"));
		exit();
	}
	$mysqli->close();
	
	session_start();
	$_SESSION['license_number'] = $license_number;
	header('Location: ' . $location);
	exit();
?>