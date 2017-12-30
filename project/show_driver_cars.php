<?php
	// Declaring variables
	$license_number = $fname = $lname = $age = $city = $password = $hashpassword = $signinup = '';
	$validPassword = false;
	$birthDate;
	
	session_start();
	if (isset($_SESSION['license_number'])) { // User already logged in, no need to check password
		$license_number = $_SESSION['license_number'];
		$mysqli = new mysqli("localhost", "root", "", "project");
		$query = "select * from driver where license_no='".$license_number."'";
		$result = $mysqli->query($query);
		$row = $result->fetch_assoc();
		$fname = $row['fname'];
		$lname = $row['lname'];
		$name = $row["fname"] . ' ' . $row["lname"];
		$age = $row["age"];
		$city = $row["city"];
		$birthDate = $row['bdate'];
		$password = "exists";
	} else { // User is logging in, need to check password
		$license_number = $_POST['license_number']; 
		$password = $_POST['password'];
		$mysqli = new mysqli("localhost", "root", "", "project");
		$query = "select license_no from driver where license_no='".$license_number."'";
		$result = $mysqli->query($query);
		if ($result->num_rows != 0) { // If license does exist, check the password
			$query = "select password from driver where license_no='".$license_number."'";
			$result = $mysqli->query($query);
			$row = $result->fetch_assoc();
			$hashpassword = $row["password"];
			$validPassword = password_verify($password, $hashpassword) || $hashpassword == '';
			if ($validPassword) { // If password is correct
				$query = "select * from driver where license_no='".$license_number."'";
				$result = $mysqli->query($query);
				$row = $result->fetch_assoc();
				$fname = $row['fname'];
				$lname = $row['lname'];
				$name = $row["fname"] . ' ' . $row["lname"];
				$age = $row["age"];
				$city = $row["city"];
				$birthDate = $row['bdate'];
				$_SESSION['license_number'] = $license_number;
			} else { // If password is not correct
				$mysqli->close();
				header("Location: index.php?errorMssg=".urlencode("Password is incorrect"));
				exit();
			}
		} else { // If license does not exist
			$mysqli->close();
			header("Location: index.php?errorMssg=".urlencode("License number does not exist"));
			exit();
		}
		$mysqli->close();
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Car Registration</title>
		<link rel="stylesheet" href="style.css">
	</head>
	<body>
		<div class="header">
			<h1>Driver Profile</h1>
		</div>
		
		<div class="main-container">
			<form action="logout.php">
				<button id="log-out">Log Out</button>
			</form>
			
			<?php 
				if (empty($password)) {
					$message = "Warning: your account is not protected by a password.";
					echo '<div class="notification warning-notification"><p>'.$message.'</p></div>';
				}
			?>
			<?php 
				if (isset($_GET['errorMssg'])) {
					echo '<div class="notification error-notification"><p>'.$_GET['errorMssg'].'</p></div>';
				} else if (isset($_GET['sccssMssg'])) {
					echo '<div class="notification success-notification"><p>'.$_GET['sccssMssg'].'</p></div>';
				}
			?>
			
			<div class="info-container"> <!-- grid 2x1 or 1x2 -->
				<div class="profile-summary"> <!-- onclick=... -->
					<h2 class="profile-cars-heading">Profile summary</h2>
					<a class="inline-link-text" id="edit-profile" onclick="editProfile();checkInputs()">Edit Profile</a>
					<div id="profile-grid-container">
						<div class="profile-grid" id="profile-grid"> <!-- grid 2x2 -->
							<div class="profile-item-">
								<h3>License Number</h3>
								<p><?php echo $license_number ?> </p>
							</div>
							<div class="profile-item-">
								<h3>Age</h3>
								<p><?php echo $age ?> </p>
							</div>
							<div class="profile-item-">
								<h3>Name</h3>
								<p><?php echo $name ?> </p>
							</div>
							<div class="profile-item-">
								<h3>City</h3>
								<p><?php echo $city ?> </p>
							</div>
						</div>
					</div>
				</div>
				
				<div class="cars">
					<div class="cars-search">
						<div>
							<h2 class="profile-cars-heading">Cars</h2>
							<p class="inline-link-text">
							<?php 
								$mysqli = new mysqli("localhost", "root", "", "project");
								$query = '';
								if (isset($_POST['car-search']) && !empty($_POST['car-search'])) {
									$query = "select count(*) as numofcars from car where license_no='".$license_number."' and plate_no like '%".$_POST['car-search']."%'";
								} else {
									$query = "select count(*) as numofcars from car where license_no='".$license_number."'";
								}
								$result = $mysqli->query($query);
								if ($result) {
									$row = $result->fetch_assoc();
									echo $row['numofcars']." items";
								} else {
									echo "0 items";
								}
							?> 
							</p> <!-- number of cars -->
						</div>
						
						<form action="show_driver_cars.php" method="post">
							<input type="number" name="car-search" placeholder="Search by Plate Number" id="search-input"> <!-- onupdate=... -->
							<button id="search-button">Search</button>
						</form>
						
					</div>
					
					<div class="cars-info">
						<!-- Table header -->
						<table class="table-header"> <!-- Only header labels, no borders -->
							<tr>
								<th>Plate Number</th><th>Car Type</th><th>Fines (AED)</th><th>City</th><th></th>
							</tr>
						</table>
						
						<!-- First row is for adding a a car -->
						<form action="add_car.php" method="post" id="add-car">
							<input type="hidden" name="license_number" value="<?php echo $license_number?>">
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
						
						<div class="cars-table">
<?php 
								$mysqli = new mysqli("localhost", "root", "", "project");
								$query = '';
								if (isset($_POST['car-search']) && !empty($_POST['car-search'])) {
									$query = "select * from car where license_no='".$license_number."' and plate_no like '%".$_POST['car-search']."%'";
								} else {
									$query = "select * from car where license_no='".$license_number."'";
								}
								$result = $mysqli->query($query);
								if ($result = $mysqli->query($query)) {
									while ($temp = $result->fetch_assoc()) {
?>
											<form action="update_car.php" method="post" id="update-car">
												<input type="hidden" name="license_number" value="<?php echo $license_number ?>">
												<input type="text" name="plate_number" value="<?php echo $temp['plate_no'] ?>">
												<input type="hidden" name="plate_number_old" value="<?php echo $temp['plate_no'] ?>">
												<input type="text" name="car_type" value="<?php echo $temp['car_type'] ?>">
												<input type="text" name="fines" value="<?php echo $temp['fines'] ?>">
												<input type="text" name="city" list="cities" value="<?php echo $temp['city'] ?>">
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
								$mysqli->close();
?> 
						</div>
					</div>
				</div>
			</div>
		</div>
		<script>
			var backupInnerHTML;
			function editProfile() {
				backupInnerHTML = document.getElementById("profile-grid-container").innerHTML;
				var html = '<form action="update_driver.php" method="post" class="profile-grid" id="login-form" style="width:auto !important">\
					<input type="hidden" name="license_number_old" value="<?php echo $license_number ?>">\
					<div>\
						<h3>License Number</h3>\
						<input type="text" name="license_number" id="license_number" onfocusout="checkInput()" onkeyup="checkInput()" value="<?php echo $license_number ?>">\
					</div>\
					<div>\
						<h3>First Name</h3>\
						<input type="text" name="first_name" id="first_name" onfocusout="checkInput()" onkeyup="checkInput()" value="<?php echo $fname ?>">\
					</div>\
					<div>\
						<h3>Last Name</h3>\
						<input type="text" name="last_name" id="last_name" onfocusout="checkInput()" onkeyup="checkInput()" value="<?php echo $lname ?>">\
					</div>\
					<div>\
						<h3>City</h3>\
						<input type="text" name="city" id="city" list="cities" onfocusout="checkInput()" onkeyup="checkInput()" value="<?php echo $city ?>">\
						<datalist id="cities">\
							<option value="Abu Dhabi">\
							<option value="Dubai">\
							<option value="Sharjah">\
							<option value="Ajman">\
							<option value="Umm al-Quwain">\
							<option value="Ras al-Khaimah">\
							<option value="Fujairah">\
						</datalist>\
					</div>\
					<div>\
						<h3>Birth Date</h3>\
						<input type="date" name="birth_date" id="birth_date" onfocusout="checkInput()" onkeyup="checkInput()" value="<?php echo $birthDate ?>">\
					</div>\
					<div>\
						<h3>Password</h3>\
						<input type="password" name="password" id="password" onfocusout="checkInput()" onkeyup="checkInput();updateMeter()" placeholder="Keep empty to prevent change" value="">\
						<progress value="0" max="100" id="progressbar"></progress>\
					</div>\
					<button type="submit" formaction="delete_driver.php" id="delete-driver">Delete</button>\
					<button type="submit" id="update-driver" class="disabled prohibited" onhover="checkInputs()">Update</button>\
				</form>';
				document.getElementById("profile-grid-container").innerHTML = html;
				document.getElementById("edit-profile").setAttribute("onclick", "cancelEditProfile()");
				document.getElementById("edit-profile").innerHTML = 'Cancel';
			}
			function cancelEditProfile() {
				document.getElementById("profile-grid-container").innerHTML = backupInnerHTML;
				document.getElementById("edit-profile").setAttribute("onclick", "editProfile()");
				document.getElementById("edit-profile").innerHTML = 'Edit Profile';
			}
			function scorePassword(pass) { // https://stackoverflow.com/a/11268104/1573222
				var score = 0;
				if (!pass) return score;
				var letters = new Object();
				for (var i=0; i<pass.length; i++) {
					letters[pass[i]] = (letters[pass[i]] || 0) + 1;
					score += 5.0 / letters[pass[i]];
				}
				var variations = {
					digits: /\d/.test(pass),
					lower: /[a-z]/.test(pass),
					upper: /[A-Z]/.test(pass),
					nonWords: /\W/.test(pass),
				}
				variationCount = 0;
				for (var check in variations)
					variationCount += (variations[check] == true) ? 1 : 0;
				score += (variationCount - 1) * 10;
				return parseInt(score);
			}
			function updateMeter() { // Called whenever password input is updated
				var pass = document.getElementById("password").value;
				var score = scorePassword(pass);
				document.getElementById("progressbar").setAttribute("value", score);
				if (score < 40) {
					document.getElementById("progressbar").classList.remove("progress-40");
					document.getElementById("progressbar").classList.remove("progress-80");
				} else if (score >= 40 && score < 80) {
					document.getElementById("progressbar").classList.add("progress-40");
					document.getElementById("progressbar").classList.remove("progress-80");
				} else {
					document.getElementById("progressbar").classList.remove("progress-40");
					document.getElementById("progressbar").classList.add("progress-80");
				}
			}
			function checkInput() {
				if (event.srcElement.getAttribute("id") != "password") {
					var $value = event.srcElement.value;
					$value = event.srcElement.value;
					if (!$value) {
						event.srcElement.classList.add("invalid");
					} else {
						event.srcElement.classList.remove("invalid");
					}
				}
				checkInputs();
			}
			function checkInputs() {
				var $invalidLN = document.getElementById("license_number").value == "";
				var $invalidfN = document.getElementById("first_name").value == "";
				var $invalidlN = document.getElementById("last_name").value == "";
				var $invalidBD = document.getElementById("birth_date").value == "";
				var $invalidCT = document.getElementById("city").value == "";
				if ($invalidLN || $invalidfN || $invalidlN || $invalidBD || $invalidCT) {
					document.getElementById("update-driver").classList.add("disabled");
					document.getElementById("update-driver").classList.add("prohibited");
				} else {
					document.getElementById("update-driver").classList.remove("disabled");
					document.getElementById("update-driver").classList.remove("prohibited");
				}
			}
		</script>
	</body>
</html>