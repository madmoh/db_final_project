<!DOCTYPE html>
<html>
	<head>
		<title>Car Registration</title>
		<link rel="stylesheet" href="style.css">
	</head>
	<body>
		<div class="header">
			<h1>Administrator Page</h1>
		</div>
		
		<div class="main-container">
			<form action="logout.php">
				<button id="log-out">Log Out</button>
			</form>
			
<?php 
	if (isset($_GET['errorMssg'])) {
		echo '<div class="notification error-notification"><p>'.$_GET['errorMssg'].'</p></div>';
	} else if (isset($_GET['sccssMssg'])) {
		echo '<div class="notification success-notification"><p>'.$_GET['sccssMssg'].'</p></div>';
	}
?>
			<form action="show_drivers_cars.php" method="post" style="text-align:right">
				<input type="number" name="driver-search" placeholder="Search by License Number" id="search-input"> <!-- onupdate=... -->
				<input type="number" name="car-search" placeholder="Search by Plate Number" id="search-input"> <!-- onupdate=... -->
				<button id="search-button">Search</button>
			</form>
<?php 
	if ((!isset($_POST['driver-search']) || empty($_POST['driver-search'])) && (!isset($_POST['car-search']) || empty($_POST['car-search']))) {
?> 
			<div id="admin-driver-grid-container">
				<table class="admin-table-header"> <!-- Only header labels, no borders -->
					<tr>
						<th>License Number</th><th>First Name</th><th>Last Name</th><th>Birth Date</th><th>City</th><th>Password</th><th></th>
					</tr>
				</table>
				<form action="add_driver.php" method="post" id="admin-driver-form">
					<input type="text" name="license_number" id="license_number">
					<input type="text" name="first_name" id="first_name">
					<input type="text" name="last_name" id="last_name">
					<input type="date" name="birth_date" id="birth_date">
					<input type="text" name="city" id="city" list="cities">
					<datalist id="cities">
						<option value="Abu Dhabi">
						<option value="Dubai">
						<option value="Sharjah">
						<option value="Ajman">
						<option value="Umm al-Quwain">
						<option value="Ras al-Khaimah">
						<option value="Fujairah">
					</datalist>
					<input type="password" name="password" id="password">
					<button type="submit" id="add">Add</button>
				</form>
			</div>
			
<?php
	}
	
	$mysqli = new mysqli("localhost", "root", "", "project");
	$result;
	
	if (isset($_POST['car-search']) && !empty($_POST['car-search'])) {
		$query = "select * from driver dr where dr.license_no in (select cr.license_no from car cr where cr.plate_no like '%".$_POST['car-search']."%')";
		$result = $result = $mysqli->query($query);
	} else if (isset($_POST['driver-search']) && !empty($_POST['driver-search'])) {
		$query = "select * from driver where license_no like '%".$_POST['driver-search']."%'";
		$result = $result = $mysqli->query($query);
	} else {
		$query = "select * from driver";
		$result = $result = $mysqli->query($query);
	}	
	$result = $mysqli->query($query);
	if ($result) {
		while ($temp = $result->fetch_assoc()) {
?>
			<div id="admin-driver-grid-container">
				<table class="admin-table-header"> <!-- Only header labels, no borders -->
					<tr>
						<th>License Number</th><th>First Name</th><th>Last Name</th><th>Birth Date</th><th>City</th><th>Password</th><th></th>
					</tr>
				</table>
				<form action="update_driver.php" method="post" id="admin-driver-form">
					<input type="hidden" name="license_number_old" value="<?php echo $temp['license_no'] ?>">
					<input type="text" name="license_number" id="license_number" value="<?php echo $temp['license_no'] ?>">
					<input type="text" name="first_name" id="first_name" value="<?php echo $temp['fname'] ?>">
					<input type="text" name="last_name" id="last_name" value="<?php echo $temp['lname'] ?>">
					<input type="date" name="birth_date" id="birth_date" value="<?php echo $temp['bdate'] ?>">
					<input type="text" name="city" id="city" list="cities" value="<?php echo $temp['city'] ?>">
					<datalist id="cities">
						<option value="Abu Dhabi">
						<option value="Dubai">
						<option value="Sharjah">
						<option value="Ajman">
						<option value="Umm al-Quwain">
						<option value="Ras al-Khaimah">
						<option value="Fujairah">
					</datalist>
					<input type="password" name="password" id="password" placeholder="Keep empty to prevent change" value="">
					<button type="submit" formaction="delete_driver.php" id="delete">Delete</button>
					<button type="submit" id="update">Update</button>
				</form>
			</div>
			
			<div id="admin-car-grid-container">
				<table class="table-header"> <!-- Only header labels, no borders -->
					<tr>
						<th>Plate Number</th><th>Car Type</th><th>Fines (AED)</th><th>City</th><th></th>
					</tr>
				</table>
				
				<!-- First row is for adding a a car -->
				<form action="add_car.php" method="post" class="admin-car-item" id="add-car">
					<input type="hidden" name="license_number" value="<?php echo $temp['license_no'] ?>">
					<input type="text" name="plate_number">
					<input type="text" name="car_type">
					<input type="text" name="fines">
					<input type="text" name="city" list="cities">
					<datalist id="cities">
						<option value="Abu Dhabi">
						<option value="Dubai">
						<option value="Sharjah">
						<option value="Ajman">
						<option value="Umm al-Quwain">
						<option value="Ras al-Khaimah">
						<option value="Fujairah">
					</datalist>
					<button>Add</button>
				</form>
<?php		
			$query2 = '';
			if (isset($_POST['car-search']) && !empty($_POST['car-search'])) {
				$query2 = "select * from car where car.license_no='".$temp["license_no"]."' and plate_no like '%".$_POST['car-search']."%'";
			} else {
				$query2 = "select * from car where car.license_no='".$temp["license_no"]."'";
			}
			$result2 = $mysqli->query($query2);
			if ($result2) {
				while ($temp2 = $result2->fetch_assoc()) {
?>
				<form action="update_car.php" method="post" class="admin-car-item" id="update-car">
					<input type="hidden" name="license_number" value="<?php echo $temp['license_no'] ?>">
					<input type="text" name="plate_number" value="<?php echo $temp2['plate_no'] ?>">
					<input type="hidden" name="plate_number_old" value="<?php echo $temp2['plate_no'] ?>">
					<input type="text" name="car_type" value="<?php echo $temp2['car_type'] ?>">
					<input type="text" name="fines" value="<?php echo $temp2['fines'] ?>">
					<input type="text" name="city" list="cities" value="<?php echo $temp2['city'] ?>">
					<datalist id="cities">
						<option value="Abu Dhabi">
						<option value="Dubai">
						<option value="Sharjah">
						<option value="Ajman">
						<option value="Umm al-Quwain">
						<option value="Ras al-Khaimah">
						<option value="Fujairah">
					</datalist>
					<button type="submit" formaction="delete_car.php" name="car-delete-button" id="delete-car-button">Delete</button>
					<button type="submit" name="car-update-button" id="update-car-button">Update</button>
				</form>			
<?php
				} 
			}
			echo "</div>";
		}
	}
	$mysqli->close();
?>
		</div>
		<script>

		</script>
	</body>
</html>