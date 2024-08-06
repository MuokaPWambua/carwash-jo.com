<?php 
require 'dbconfig.php';
include '../../url.php';
session_start();
if(!isset($_SESSION["id"])) {
    header("Location:login.php");
}
?>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Check your car wash status online!">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo $url ?>">
    <meta property="og:title" content="Car Wash | Admin Area">
    <meta property="og:description" content="Check your car wash status online!">
    <meta property="og:image" content="../../assets/banner.jpg">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo $url ?>">
    <meta property="twitter:title" content="Car Wash">
    <meta property="twitter:description" content="Check your car wash status online!">
    <meta property="twitter:image" content="../../assets/banner.jpg">

	<link rel="shortcut icon" href="../../admin/img/ico.png" />
	

	<title>Car Wash</title>

	<link href="css/app.css" rel="stylesheet">
	
	<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
	
<link rel="stylesheet" type="text/css" href="css/datatables.min.css"/>
 
</head>