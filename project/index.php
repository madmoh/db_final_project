<!DOCTYPE html>
<html>
	<head>
		<title>Car Registration</title>
		<link rel="stylesheet" href="style.css">
	</head>
	<body>
		<div class="header">
			<h1>Welcome to Car Registration</h1>
		</div>
		<div class="main-container">
<?php 
	if (isset($_GET['errorMssg'])) {
		echo '<div class="notification error-notification"><p>'.$_GET['errorMssg'].'</p></div>';
	}
?>
			<form action="show_driver_cars.php" method="post" id="login-form">
				<div><span id="license-number">License Number</span><input type="text" name="license_number" id="license_number" onfocusout="checkInput()" onkeyup="checkInput()"></div>
				<div><span>Password</span><input type="password" name="password" id="password" onfocusout="checkInput()" onkeyup="checkInput()"></div>
				<div class="hidden" id="registration">
					<progress value="0" max="100" id="progressbar"></progress>
					<div><span>First Name*</span><input type="text" name="first_name" id="first_name" onfocusout="checkInput()" onkeyup="checkInput()"></div>
					<div><span>Last Name*</span><input type="text" name="last_name" id="last_name" onfocusout="checkInput()" onkeyup="checkInput()"></div>
					<div><span>Birth Date*</span><input type="date" name="birth_date" id="birth_date" onfocusout="checkInput()" onkeyup="checkInput()"></div>
					<div><span>City*</span><input type="text" name="city" id="city" list="cities" onfocusout="checkInput()" onkeyup="checkInput()"></div>
					<datalist id="cities">
						<option value="Abu Dhabi">
						<option value="Dubai">
						<option value="Sharjah">
						<option value="Ajman">
						<option value="Umm al-Quwain">
						<option value="Ras al-Khaimah">
						<option value="Fujairah">
					</datalist>
				</div>
				<button id="login" class="disabled prohibited" onhover="checkInputs()">Log In</button>
				<a id="new-account" onclick="createNewAccount();checkInputs()">Create New Account</a>
				<a id="admin" href="show_drivers_cars.php">Log In as Admin</a>
			</form>
		</div>
		<script>
			function createNewAccount() { // If user clicks on "Create New Account"
				document.getElementById("login-form").setAttribute("action", "add_driver.php");
				document.getElementById("license-number").innerHTML = "License Number*";
				document.getElementById("password").setAttribute("onkeyup", "updateMeter()");
				document.getElementById("registration").classList.remove("hidden");
				document.getElementById("login").innerHTML = "Create Account";
				document.getElementById("new-account").innerHTML = "I Have an Account";
				document.getElementById("new-account").setAttribute("onclick", "login()");
			}
			function login() { // If user clicks on "I Have an Account"
				document.getElementById("login-form").setAttribute("action", "show_driver_cars.php");
				document.getElementById("license-number").innerHTML = "License Number";
				document.getElementById("password").removeAttribute("onkeyup");
				document.getElementById("registration").classList.add("hidden");
				document.getElementById("login").innerHTML = "Log In";
				document.getElementById("new-account").innerHTML = "Create New Account";
				document.getElementById("new-account").setAttribute("onclick", "createNewAccount()");
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
				switch (document.getElementById("login").innerHTML) {
					case "Log In": {
						if ($invalidLN) {
							document.getElementById("login").classList.add("disabled");
							document.getElementById("login").classList.add("prohibited");
						} else {
							document.getElementById("login").classList.remove("disabled");
							document.getElementById("login").classList.remove("prohibited");
						}
						break;
					}
					case "Create Account": {
						if ($invalidLN || $invalidfN || $invalidlN || $invalidBD || $invalidCT) {
							document.getElementById("login").classList.add("disabled");
							document.getElementById("login").classList.add("prohibited");
						} else {
							document.getElementById("login").classList.remove("disabled");
							document.getElementById("login").classList.remove("prohibited");
						}
						break;
					}
				}
				
			}
		</script>
	</body>
</html>