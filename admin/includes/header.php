<!DOCTYPE html>
<?php 
	$theme_sql = "SELECT * FROM themes WHERE status = 1";
	$theme_result = $db->query($theme_sql);
	$theme = mysqli_fetch_assoc($theme_result);
?>
<html>
	<head>
		<title>Administrator</title>
		<link rel="stylesheet" href="../css/bootstrap<?=((!empty($theme))?'-'.$theme['theme_name']:'');?>.min.css" type="text/css">
        <link rel="stylesheet" href="../css/main.css">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
        <script src="../js/jquery.js"></script>
        <script src="../js/bootstrap.min.js"></script>
	</head>
	<body>