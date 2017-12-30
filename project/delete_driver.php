<?php
	if (isset($_POST['license_number'])) {
		$location = '';
		if (strpos(basename($_SERVER['HTTP_REFERER']), 'show_driver_cars.php') !== false) $location = "logout.php";
		else $location = "show_drivers_cars.php";
	
		$license_number = $_POST['license_number'];
		
		$mysqli = new mysqli("localhost", "root", "", "project");
		if ($mysqli->connect_errno) {
			$mysqli->close();
			header('Location: ' . basename(substr($_SERVER['HTTP_REFERER'], 0, strpos($_SERVER['HTTP_REFERER'], ".php") + 4)) . "?errorMssg=".urlencode("Could not connect to database"));
			exit();
		}
		$query = "delete from driver where license_no='".$license_number."'";
		$mysqli->query($query);
		if (strcmp($mysqli->error,'')) {
			$mysqli->close();
			header('Location: ' . basename(substr($_SERVER['HTTP_REFERER'], 0, strpos($_SERVER['HTTP_REFERER'], ".php") + 4)) . "?errorMssg=".urlencode("Could not delete the driver"));
			exit();
		}
		$mysqli->close();
		header("Location: " . $location . "?sccssMssg=".urlencode("Driver was deleted successfully"));
		exit();
	}
?>