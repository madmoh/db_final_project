<?php
	if (isset($_POST['plate_number']) && isset($_POST['license_number']) && isset($_POST['car_type']) && isset($_POST['fines']) && isset($_POST['city']) && isset($_POST['plate_number_old'] )) {
		$plate_number = $_POST['plate_number'];
		$plate_number_old = $_POST['plate_number_old'];
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
		$query = "update car set plate_no='".$plate_number."', license_no='".$license_number."', car_type='".$car_type."', fines=".$fines.", city='".$city."' where plate_no='".$plate_number_old."'";
		$mysqli->query($query);
		if (strcmp($mysqli->error,'')) {
			$mysqli->close();
			header('Location: ' . basename(substr($_SERVER['HTTP_REFERER'], 0, strpos($_SERVER['HTTP_REFERER'], ".php") + 4)) . "?errorMssg=".urlencode("Please check updated car details"));
			exit();
		}
		$mysqli->close();
		
		header('Location: ' . basename(substr($_SERVER['HTTP_REFERER'], 0, strpos($_SERVER['HTTP_REFERER'], ".php") + 4)) . "?sccssMssg=".urlencode("Car was updated successfully"));
		exit();
	}
?>