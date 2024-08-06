<?php
// Detect protocol
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

// Get the current domain
$domain = $_SERVER['HTTP_HOST'];

// Construct the URL
$url = $protocol . $domain;
?>