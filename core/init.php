<?php

$db = mysqli_connect('localhost', 'root', '', 'real_estate');
session_start();

if(mysqli_connect_error()){
    echo 'Database connection failed with following errors: '.mysqli_connect_error();
    die();
}

// creating a helper file so we can use functions
require_once $_SERVER['DOCUMENT_ROOT'].'/estate/config.php';
require_once BASEURL.'helpers/helpers.php';


// cart cookie
$cart_id = '';
if (isset($_COOKIE[CART_COOKIE])) {
	$cart_id = sanitize($_COOKIE[CART_COOKIE]);
}

if(isset($_SESSION['user_id'])){
	$user_id = $_SESSION['user_id'];
	$query = $db->query("SELECT * FROM users WHERE id = '$user_id'");
	$user_data = mysqli_fetch_assoc($query);
	$fn = explode(' ', $user_data['full_name']);
	$user_data['first'] = $fn[0];
	$user_data['last'] = $fn[1];
}

?> 