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

	<title>Welcome to Discuss Software</title>
</head>
<body>
	<?php
	include 'snippets/_dbconnect.php';
	?>

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
			
			<!-- Different header's depending on if user is logged in or not -->
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

	<!-- Main Content -->
	<div class="container col-9">
		
		<!-- Popular Communities Section -->
		<div>
			<div class="card mt-4">
				<div class="card-header"> 
					<div class="row">
						<div class="col-11"><h4>Popular Communities</h4></div>
						<div class="col-1 mt-1"><a class="link-dark" href="communities.php">View all</a> </div>
					</div>
				</div>
				<?php
				$trending = array(0=>0);
				for($x=0;$x<50;$x++)
				{
					$sql = "SELECT * FROM `question` WHERE `q_com_id`=$x";
					$result = mysqli_query($conn, $sql);
					$numrow = mysqli_num_rows($result);
					$trending += array($x=>$numrow);
				}
				arsort($trending);
				?>
				<div class="card-body">
					<?php
					$n = 5;
					foreach ($trending as $key => $value) {
						if($n<=0)
							break;
						$sql = "SELECT * FROM `communities` WHERE `com_id`=$key";
						$result = mysqli_query($conn, $sql);
						$row = mysqli_fetch_assoc($result);
						echo '<a href="communities.php?com_id='.$row['com_id'].'" class="btn btn-outline-dark pt-5 pb-5 m-2 fw-bold" style="width: 12rem">'.$row['com_name'].'</a>';
						$n--;
					}
					?>
				</div>
			</div>
		</div>

		<nav class="navbar navbar-expand-lg navbar-light bg-light border py-1 mt-4">
			<div class="container-fluid ps-2">
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav me-auto mb-1 mb-lg-0">
						<li class="nav-item">
							<a class="nav-link active" href="#"><b>Recently asked questions</b></a>
						</li>
					</ul>
				</div>
			</div>
		</nav>
		<div class="pt-2">
			<?php
			$n = 20;
			$sql = "SELECT * FROM `question` ORDER BY `q_time` DESC";
			$result = mysqli_query($conn, $sql);
			while ($row = mysqli_fetch_assoc($result) and $n--) {
			$xsql = "SELECT * FROM `users` WHERE `uid`=".$row['q_user_id'];
			$xresult = mysqli_query($conn, $xsql);
			$xrow = mysqli_fetch_assoc($xresult);
			echo '<div class="media py-1">
				<div class="media-body shadow-sm px-2 pt-2">
					<a href="question.php?q_id='.$row['q_id'].'&username='.$xrow['username'].'" class="text-decoration-none"><h5 class="mt-0 link-dark">'.$row['q_title'].'</h5></a>
					<div class="row">
						<p class="col-6" style="font-size: 14px;"> '.$xrow['username'].' asked on: '.$row['q_time'].'</p>
					</div>
				</div>
			</div>';
			}
			?>
		</div>
	</div>
  <!-- Footer -->
  <?php include 'snippets/_footer.php'; ?>
</body>
</html>