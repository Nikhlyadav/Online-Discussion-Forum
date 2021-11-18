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

  <title>Question - Discuss Software</title>

  <style type="text/css">
    .answer {
      min-height: 300px;
    }
  </style>

</head>

<body>

  <!-- Option 1: Bootstrap Bundle with Popper -->
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
            <a class="nav-link" aria-current="page" href="index.php">Home</a>
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

  <?php 
  include 'snippets/_dbconnect.php';
  $question_id = $question_title = $question_desc = $question_time = $username = "";
  if(!empty($_GET["q_id"])) {
    $question_id = $_GET["q_id"];
    $username = $_GET["username"];
    $sql = "SELECT * FROM `question` WHERE q_id=".$question_id;
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $question_title = $row["q_title"];
    $question_desc = $row["q_desc"];
    $question_time = $row["q_time"];
  }
  ?>

  <div class="container-fluid col-8 my-4 mb-5 shadow-sm pt-2">
    <h3><?php echo $question_title; ?></h3>
    <?php if(!empty($question_desc)){ echo '<hr>';} ?>
    <p><?php echo $question_desc; ?></p>
    <?php if(!empty($question_desc)){ echo '<br>';} ?>
    <div class="row border-top pt-1">
      <?php
      echo '<p class="col-10 mb-2" style="font-size: 14px">'.$username.' asked at '.$question_time.' </p>';
      ?>
    </div>
  </div>

  <?php
  $answer_content = "";
  if($_SERVER["REQUEST_METHOD"]=="POST" and !empty($_POST["answer_content"])){
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
    $uid = $_SESSION['uid'];
    $answer_content = refine($_POST["answer_content"]);
    $sql = "INSERT INTO `answer` (`a_content`, `a_user`, `a_question`, `a_time`) VALUES ('$answer_content', '$uid', '$question_id', current_timestamp());";
    $result = mysqli_query($conn, $sql);
    if($result){
      echo '<script type="text/javascript">alert("Comment added successfully")</script>';
    } else{
      echo '<script type="text/javascript">alert("Something went wrong")</script>';
    }
  }
  ?>
  <?php
  if(isset($_SESSION["loggedin"]))
  {
    echo
    '<div class="container px-0 col-8">
          <form method="post" class="my-4" name="answer_content">
            <div class="mb-1 form-group">
              <textarea class="form-control" rows="3" name="answer_content" placeholder="Type comment here..."></textarea>
            </div>
            <button type="submit" class="btn-sm ms-2 mt-1 btn-dark">Submit</button>
          </form> 
        </div>';
  }else
  {
    echo '<div class="container px-0 col-8 pb-4"><h4>Please <a href="authorization.php">Log in</a> to comment</h4></div>';
  }

  ?>

  <div class="container px-0 col-8">
    <nav class="navbar navbar-expand-lg navbar-light bg-light border py-1">
      <div class="container-fluid ps-2">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-1 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link active" href="#"><b>Comments</b></a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </div>

  <div class="container answer col-8 px-0">
  <?php
    $sql = "SELECT * FROM `answer` WHERE a_question=".$question_id." ORDER BY `a_time`";
    $result = mysqli_query($conn, $sql);
    echo mysqli_error($conn);
    while ($row = mysqli_fetch_assoc($result)) {
      $uid = $row['a_user'];
      $xsql = "SELECT * FROM `users` WHERE uid=".$uid;
      $xresult = mysqli_query($conn, $xsql);
      $xrow = mysqli_fetch_assoc($xresult);
      echo '<div class="media mt-0 ">
      <div class="media-body border border-2 px-2 pt-2">
      <div class="row">
      <p class="col-6 fst-italic fw-bold" style="font-size: 14px;">'.$xrow['username'].' posted at '.$row['a_time'].'</p>
      </div>
      <p class="mt-0">'.$row['a_content'].'</p>
      </div>
      </div>';
    }
    ?>
  </div>
  <!-- Footer -->
  <?php include 'snippets/_footer.php'; ?>
</body>
</html>