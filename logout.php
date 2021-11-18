<?php
session_start();
session_destroy();

// Going back to previous url
if(isset($_SERVER['HTTP_REFERER'])) {
    $previous = $_SERVER['HTTP_REFERER'];
}
header('Location: '.$previous);
?>