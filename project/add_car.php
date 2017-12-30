<?php
	if (isset($_POST['plate_number']) && isset($_POST['license_number']) && isset($_POST['car_type']) && isset($_POST['fines']) && isset($_POST['city'])) {
		$plate_number = $_POST['plate_number'];
		$license_number = $_POST['license_number'];
		$car_type = $_POST['car_type'];
		$fines = $_POST['fines'];
		$city = $_POST['city'];
		
		session_start();
		$_SESSION['license_number'] = $license_number;
		
		$mysqli = new mysqli("localhost", "root", "", "project");
		if ($mysqli->connect_errno) {
			$mysqli->close();
			header('Location: ' . basename(substr($_SERVER['HTTP_REFERER'], 0, strpos($_SERVER['HTTP_REFERER'], ".php") + 4)) . "?errorMssg=".urlencode("Could not connect to database"));
			exit();
		}
		$query = "insert into car values ('".$plate_number."', '".$license_number."', '".$car_type."', ".$fines.", '".$city."')";
		$mysqli->query($query);
		if (strcmp($mysqli->error,'')) {
			$mysqli->close();
			header('Location: ' . basename(substr($_SERVER['HTTP_REFERER'], 0, strpos($_SERVER['HTTP_REFERER'], ".php") + 4)) . "?errorMssg=".urlencode("Please check added car details"));
			exit();
		}
		$mysqli->close();
	}
	
	header('Location: ' . basename(substr($_SERVER['HTTP_REFERER'], 0, strpos($_SERVER['HTTP_REFERER'], ".php") + 4)) . "?sccssMssg=".urlencode("Car was added successfully"));
	exit();
?>