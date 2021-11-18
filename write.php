<?php
session_start();
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

	<title>Write - Discuss Software</title>
	<style type="text/css">
    html,
    body {
       margin:0;
       padding:0;
       height:100%;
    }
    #footer {
      position:absolute;
      bottom:0;
      margin-left: 18%;
    }
  </style>

</head>
<body>

	<!-- Bootstrap Javascript -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

	<!-- Header -->
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<div class="container-fluid col-9" >
			<a class="navbar-brand" href="index.php">Discuss Software</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNavDropdown">
				<ul class="navbar-nav">
					<li class="nav-item">
						<a class="nav-link" href="index.php">Home</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="communities.php">Communities</a>
					</li>
					<li class="nav-item">
						<a class="nav-link active" aria-current="page" href="write.php">Ask Question</a>
					</li>
				</ul>
			</div>
			<?php
			if(isset($_SESSION['loggedin']))
			{
				echo '<span class="navbar-text px-3" style="color:white">Welcome, '.$_SESSION['username'].'</span>';
				echo '<a href="logout.php" role="button" class="btn btn-sm btn-outline-light">Logout</a>';
			}else
			{
				echo '<a href="authorization.php" role="button" class="btn btn-sm btn-outline-light">Login/Sign up</a>';
			}
			?>
		</div>
	</nav>

	<!-- Validate Form -->
	<script type="text/javascript">
		function validate() {
			let title = document.forms["qform"]["qtitle"].value;
			let desc = document.forms["qform"]["qdesc"].value;
			let comid = document.forms["qform"]["qcomid"].value;
			if(title="" || title.length<10 || title.length>100) {
				alert("Title length should be between 10 to 100 characters");
				return false;
			}else if(comid=="0") {
				alert("Please select appropriate community");
				return false;
			}
			alert("Question submitted successfully");
			return true;
		}
	</script>

	<!-- Inserting Question into Database -->
	<?php
	include 'snippets/_dbconnect.php';
	if($_SERVER["REQUEST_METHOD"]=="GET" and !empty($_GET["qtitle"])) {
		function refine($text) {
			$final = "";
			for($x = 0; $x < strlen($text); $x++) {
				if($text[$x]=="'")
					$final = $final."''";
				else
					$final = $final.$text[$x];
			}
			$final = trim($final);
			$final = htmlspecialchars($final);
			$final = stripslashes($final);
			return $final;
		}
		$qtitle = refine($_GET["qtitle"]);
		$qdesc = refine($_GET["qdesc"]);
		$qcomid = $_GET["qcomid"];
		if(isset($_SESSION['loggedin']))
		{
			$uid = $_SESSION['uid'];
			$sql = "INSERT INTO `question` (`q_title`, `q_desc`, `q_com_id`, `q_user_id`, `q_time`) VALUES ('$qtitle', '$qdesc', '$qcomid', '$uid', current_timestamp())";
			$result = mysqli_query($conn, $sql);
			if($result) {
				header('Location: communities.php?com_id='.$qcomid);
			}else {
				echo '<script type="text/javascript">alert("Failed: Unidentified error")</script>';
			}
		}
	}
	?>

	<!-- Question form -->
	<div class="container-fluid col-5 my-4">
		<form method="get" action="<?php echo $_SERVER['REQUEST_URI'] ?>" name="qform" onsubmit="return validate()">
			<?php
			if(isset($_SESSION['loggedin']))
			{ 
				echo '<div class="mb-4 form-group">
				<label class="form-label"><h5 class="mb-0">Title</h5></label>
				<input type="form-text" class="form-control" name="qtitle">
				<div id="emailHelp" class="form-text">Title length should be between 10-100 characters.</div>
				</div>
				<div class="mb-4 form-group">
					<label class="form-label"><h5 class="mb-0">Description</h5></label>
					<textarea class="form-control" rows="5" name="qdesc"></textarea>
				</div>
				<div class="mb-4 form-group">
					<label class="form-label"><h5 class="mb-0">Community</h5></label>
					<select class="form-select" name="qcomid">';
			}else
			{
				echo '<div class="container px-0 col-8 pb-4"><h4>Please <a href="authorization.php">Log in</a> to ask a question</h4></div>';
			}
			?>

				<!-- Getting community list and handling default selected value -->
				<?php
				if(isset($_SESSION['loggedin']))
				{
				$com_id = 0;
				if(!empty($_GET['com_id'])){
					$com_id = $_GET['com_id'];
				}else{
					echo '<option value="0" selected>Select</option>';
				}
				$sql = "SELECT * FROM `communities` ORDER BY `com_name`";
				$result = mysqli_query($conn, $sql);
				while($row=mysqli_fetch_assoc($result)) {
					if($com_id!=0 and $row['com_id']==$com_id) {
						echo '<option selected value="'.$row['com_id'].'">'.$row['com_name'].'</option>';
					}else {
						echo '<option value="'.$row['com_id'].'">'.$row['com_name'].'</option>';
					}
				}
				}
				?>
				</select>
			</div>
			<?php
			if(isset($_SESSION['loggedin']))
			{ echo '<button type="submit" class="btn btn-primary">Submit</button>'; }
			?>
		</form>
	</div>
	  <!-- Footer -->
  <?php include 'snippets/_footer.php'; ?>
</body>
</html>