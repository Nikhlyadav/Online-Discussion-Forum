<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

	<title>Signup - Discuss Software</title>

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

	<!-- Validating form -->
	<script type="text/javascript">
		function validateSignup()
		{
			let name = document.forms["signupform"]["Name"].value;
			let email = document.forms["signupform"]["Email"].value;
			let pass = document.forms["signupform"]["pass"].value;
			let cpass = document.forms["signupform"]["cpass"].value;
			if(name=="")
			{
				alert("Name cannot be empty");
				return false;
			}
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
			if(cpass=="")
			{
				alert("Confirm Password cannot be empty");
				return false;
			}
			if(pass != cpass)
			{
				alert("Confirm Password should be same as Password");
				return false;
			}
			return true;
		}
	</script>

	<!-- Inserting data into database -->
	<?php

	if($_SERVER["REQUEST_METHOD"]=="POST")
	{
		include 'snippets/_dbconnect.php';
		$username = $_POST['Name'];
		$email = $_POST['Email'];
		$password = $_POST['pass'];
		$cpassword = $_POST['cpass'];

		$existSQL = "SELECT * FROM `users` WHERE `email` = '$email'";
		$result = mysqli_query($conn, $existSQL);
		$row = mysqli_num_rows($result);
		if($row>0){
			echo '<script type="text/javascript">alert("User already exist.")</script>';
		}else{
			$password = password_hash($password, PASSWORD_DEFAULT);
			$insert = "INSERT INTO `users` (`uid`, `username`, `email`, `password`) VALUES (NULL, '$username', '$email', '$password')";
			$result = mysqli_query($conn, $insert);
			if($result) {
				echo '<script type="text/javascript">alert("Account created successfully. You can Log in now.")</script>';
			}else {
				echo '<script type="text/javascript">alert("Something went wrong")</script>';
			}
		}
	}
	?>

	<!-- Signup Form -->
	<div class="container-fluid my-3 py-5">
		<div class="row">
			<div class="col-4"></div>
			<div class="col-4 shadow py-4 px-4 mx-5 bg-light">
				<form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>" name="signupform" onsubmit="return validateSignup()">
					<h4 class="fw-bold mb-3">Sign Up</h4>
					<div class="mb-3">
						<label class="form-label">Name</label>
						<input class="form-control" id="Name" name="Name">
					</div>
					<div class="mb-3">
						<label for="exampleInputEmail1" class="form-label">Email address</label>
						<input type="email" class="form-control" id="Email" name="Email">
						<div id="emailHelp" class="form-text mb-3">We'll never share your email with anyone else.</div>
					</div>
					<div class="mb-3">
						<label for="exampleInputPassword1" class="form-label">Password</label>
						<input type="password" class="form-control" id="pass" name="pass">
					</div>
					<div class="mb-3">
						<label for="exampleInputPassword1" class="form-label">Confirm Password</label>
						<input type="password" class="form-control" id="cpass" name="cpass">
					</div>
					<div class="form-text mb-3">Already have an account <a href="authorization.php">Log in</a>!</div>
					<button type="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>
		</div>
	</div>
</body>
</html>