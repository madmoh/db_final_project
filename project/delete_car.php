<?php
	if (isset($_POST['plate_number']) && isset($_POST['license_number'])) {
		$plate_number = $_POST['plate_number'];
		$license_number = $_POST['license_number'];
		
		session_start();
		$_SESSION['license_number'] = $license_number;
		
		$mysqli = new mysqli("localhost", "root", "", "project");
		if ($mysqli->connect_errno) {
			$mysqli->close();
			header('Location: ' . basename(substr($_SERVER['HTTP_REFERER'], 0, strpos($_SERVER['HTTP_REFERER'], ".php") + 4)) . "?errorMssg=".urlencode("Could not connect to database"));
			exit();
		}
		$query = "delete from car where plate_no='".$plate_number."'";
		$mysqli->query($query);
		if (strcmp($mysqli->error,'')) {
			$mysqli->close();
			header('Location: ' . basename(substr($_SERVER['HTTP_REFERER'], 0, strpos($_SERVER['HTTP_REFERER'], ".php") + 4)) . "?errorMssg=".urlencode("An error occured while deleting the car"));
			exit();
		}
		$mysqli->close();
		
		header('Location: ' . basename(substr($_SERVER['HTTP_REFERER'], 0, strpos($_SERVER['HTTP_REFERER'], ".php") + 4)) . "?sccssMssg=".urlencode("Car was deleted successfully"));
		exit();
	}
?>