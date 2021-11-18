<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

	<title>Login - Discuss Software</title>

	<!-- Styling for background image -->
	<style type="text/css">
		body {
			background-image: url("images/bg_image.jpg");
			background-repeat: no-repeat;
			background-size: 100% 100%;
		}
		html {
			height: 100%
		}
	</style>
</head>
<body>
	<!-- Bootstrap Javascript -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

	<!-- Header -->
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
		<div class="container-fluid col-9" >
			<a class="navbar-brand" href="index.php">Discuss Software</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNavDropdown">
				<ul class="navbar-nav">
					<li class="nav-item">
						<a class="nav-link active" aria-current="page" href="index.php">Home</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="communities.php">Communities</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="write.php">Ask Question</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>

	<!-- Script to validate form -->
	<script type="text/javascript">
		function validateLogin()
		{
			let email = document.forms["loginForm"]["loginEmail"].value;
			let pass = document.forms["loginForm"]["loginPass"].value;
			if(email=="")
			{
				alert("Email cannot be empty");
				return false;
			}
			if(pass=="")
			{
				alert("Password cannot be empty");
				return false;
			}
			return true;
		}
	</script>

	<!-- Matching information filled by user from database -->
	<?php
		include 'snippets/_dbconnect.php';
		if($_SERVER["REQUEST_METHOD"]=="POST") {
			$email = $_POST["loginEmail"];
			$pass = $_POST["loginPass"];
			$existSQL = "SELECT * FROM `users` WHERE `email` = '$email'";
			$result = mysqli_query($conn, $existSQL);
			$row = mysqli_num_rows($result);
			if($row==1) {
				$row = mysqli_fetch_assoc($result);
				if(password_verify($pass, $row['password'])) {
					session_start();
					$_SESSION['loggedin'] = true;
					$_SESSION['username'] = $row['username'];
					$_SESSION['uid'] = $row['uid'];
					header("Location: index.php");
				} else {
					echo '<script type="text/javascript">alert("Email or Password are not correct")</script>';
				}
			} else {
				echo '<script type="text/javascript">alert("User does not exist")</script>';
			}
		}
	?>

	<!-- Login Form -->
	<div class="container-fluid my-5 py-5">
		<div class="row">
			<div class="col-4"></div>
			<div class="col-4 shadow py-4 px-4 my-5 mx-5 bg-light">
				<form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>" name="loginForm" onsubmit="return validateLogin()">
					<h4 class="fw-bold mb-3">Login</h4>
					<div class="mb-3">
						<label for="loginEmail" class="form-label">Email address</label>
						<input type="email" class="form-control" name="loginEmail" id="loginEmail" aria-describedby="emailHelp">
					</div>
					<div class="mb-3">
						<label for="loginPass" class="form-label">Password</label>
						<input type="password" class="form-control" id="loginPass" name="loginPass">
					</div>
					<div id="emailHelp" class="form-text mb-3 dark">Don't have an account? <a href="signup.php">Create one</a>!</div>
					<button type="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>
		</div>
	</div>
</body>
</html>