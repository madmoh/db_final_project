<?php
	$license_number = $_POST['license_number'];
	
	/*$mysqli = new mysqli("localhost", "root", "", "project");
	$query = "select license_no from driver where license_no='".$license_number."'";
	$result = $mysqli->query($query);
	if (mysql_num_rows($result) == 0) {
		echo "license does not exist";
	} else {
		echo "case 2";
	}
	$mysqli->close();*/
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Car Registration</title>
		<link rel="stylesheet" href="style.css">
	</head>
	<body>
		<div class="header">
			<h1>Car Registration</h1>
			<form action="index.php">
				<button>Log Out</button>
			</form>
		</div>
		
		<!-- password notification -->
		<div class="notification "> <!-- Display if password is empty -->
			<p>Warning: your account is not protected by a password.</p>
		</div>
		
		<div class="container"> <!-- grid 2x1 or 1x2 -->
			<div class="profile-sammary"> <!-- onclick=... -->
				<h2>Profile Sammary</h2>
				<div class="profile-grid"> <!-- grid 2x2 -->
					<div class="profile-item-">
						<h3>License No</h3>
						<p></p>
					</div>
					<div class="profile-item-">
						<h3>Age</h3>
						<p></p>
					</div>
					<div class="profile-item-">
						<h3>Name</h3>
						<p></p>
					</div>
					<div class="profile-item-">
						<h3>City</h3>
						<p></p>
					</div>
				</div>
			</div>
			
			<div class="cars">
				<h2 id="cars-heading">Cars</h2>
				<p>(... items)</p> <!-- number of cars -->
				<p>Search</p>
				<input type="number" name="car-search" placeholder="Plate Number"> <!-- onupdate=... -->
				
				<!-- Table header -->
				<table class="table-header"> <!-- Only header labels, no borders -->
					<tr>
						<th>Plate Number</th><th>Car Type</th><th>Fines (AED)</th><th>City</th><th></th>
					</tr>
				</table>
				
				<!-- First row is for adding a a car -->
				<form action="add_car.php" method="post">
					
					<input type="text" name="plate_number">
					<input type="text" name="car_type">
					<input type="number" name="fines">
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
				
				<div class="cars-table">
					<!-- populate the table with php and sql query -->
				</div>
			</div>
		</div>
	</body>
</html>