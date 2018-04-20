<?php 

function display_errors($errors){
	$display = '<ul class="bg-danger">';
	foreach ($errors as $error) {
		$display .= '<li class="text-default">'.$error.'</li>';
	}
	$display .= '</ul>';
	return $display;
}

function sanitize($dirty){
	return htmlentities($dirty, ENT_QUOTES, "UTF-8");
}

function money($number){
	return '$'.number_format($number, 2);
}

function login($user_id){
	$_SESSION['user_id'] = $user_id;
	global $db;
	$date = date("Y-m-d H:i");
	$db->query("UPDATE users SET last_login = '$date' WHERE id = '$user_id'");
	$_SESSION['success_flash'] = 'You are now logged in';
	header('Location: index.php');
}


function is_logged_in(){
	if(isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0){
		return true;
	}else{
		return false;
	}
}

function login_error_redirect($url = 'login.php'){
	$_SESSION['error_flash'] = 'You must be logged in to access that page';
	header('Location: '.$url);
}


function has_permission($permission = 'admin'){
	global $user_data;
	$permissions = explode(',', $user_data['permissions']);
	if(in_array($permission, $permissions, true)){
		return true;
	}else{
		return false;
	}
}

function permission_error_redirect($url = 'login.php'){
	$_SESSION['error_flash'] = 'You do not have permission to access that page';
	header('Location: '.$url);
}

function logout(){
	session_destroy();
	header('Location: login.php');
}

function pretty_date($date){
	return date("D, M Y", strtotime($date));
}

function pretty_time($time){
	return date("h:i A", strtotime($time));
}

function get_category($child_id){
	global $db;
	$id = sanitize($child_id);

	$sql = "SELECT p.id AS 'pid', p.category AS 'parent', c.id AS 'cid', c.category AS 'child' FROM category c INNER JOIN category p ON c.parent = p.id WHERE c.id = '$id'";

	$query = $db->query($sql);
	$category = mysqli_fetch_assoc($query);

	return $category;
}


?>