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

	<title>Communities - Discuss Software</title>

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
						<a class="nav-link" href="index.php">Home</a>
					</li>
					<li class="nav-item">
						<a class="nav-link active" aria-current="page" href="communities.php">Communities</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="write.php">Ask Question</a>
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

	<!-- Main Content -->
	<div class="container col-9">
		<div class="row">

			<!-- Left Sidebar -->
			<div class="col-2 px-1 my-4" style="max-height: 680px; overflow-y: auto;">
				<aside class="bd-sidebar">
					<nav class="bd-links">
						<ul class="list-unstyled">
							<!-- <li class="mb-1"><h4 class="mx-2 mb-2">Communities</h4></li> -->

							<!-- Fetching Community list from database -->
							<?php
							$sql = "SELECT * FROM `communities` ORDER BY `com_name`";
							$result = mysqli_query($conn, $sql);
							while($row=mysqli_fetch_assoc($result))
							{
								echo '<li class="mb-1"><a href="communities.php?com_id='.$row['com_id'].'" class="btn btn-outline-light align-items-center rounded border border-white" style="color: grey">'.$row['com_name'].'</a></li>';
							}
							?>

						</ul>
					</nav>
				</aside>
			</div>

			<!-- Center -->
			<div class="col-8 ms-2 ps-4 my-2">
				<div class="my-4">
					<div class="row">

						<!-- Getting Community Name -->
						<?php
						$com_id = 15;
						if(!empty($_GET['com_id'])){
						$com_id = $_GET['com_id'];
						}
						$sql = "SELECT * From `communities` WHERE `com_id`=".$com_id;
						$result = mysqli_query($conn, $sql);
						$row = mysqli_fetch_assoc($result);
						echo '<div class="col-9"><h2 class="ps-2">'.$row['com_name'].'</h2></div>';
						echo '<div class="col-3"><a href="write.php?com_id='.$com_id.'" class="btn btn-outline-secondary">Ask Question</a></div>';
						?>
					
				</div>
			</div>
			<nav class="navbar navbar-expand-lg navbar-light bg-light border py-1">
				<div class="container-fluid ps-2">
					<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
					<div class="collapse navbar-collapse" id="navbarSupportedContent">
						<ul class="navbar-nav me-auto mb-1 mb-lg-0">
							<li class="nav-item">
								<a class="nav-link active" href="#"><b>Questions</b></a>
							</li>
						</ul>
					</div>
				</div>
			</nav>
			<div class="pt-2">
				<?php
				$sql = "SELECT * FROM `question` WHERE `q_com_id` = ".$com_id." ORDER BY `q_time` DESC";
				$result = mysqli_query($conn, $sql);
				while ($row = mysqli_fetch_assoc($result)) {
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

	</div>
</div>
  <!-- Footer -->
  <?php include 'snippets/_footer.php'; ?>

</body>
</html>