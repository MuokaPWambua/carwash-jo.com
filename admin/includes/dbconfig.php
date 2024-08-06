<?php
$con = mysqli_connect("localhost","carwash_user","carwash_user","carwash_user");

// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}
?>