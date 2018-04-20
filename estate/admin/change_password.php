<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/estate/core/init.php';
if(!is_logged_in()){
	login_error_redirect();
}
include 'includes/header.php';

$hashed = $user_data['password'];
$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
$password = trim($password);

$old_password = ((isset($_POST['old_password']))?sanitize($_POST['old_password']):'');
$old_password = trim($old_password);

$confirm = ((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
$confirm = trim($confirm);
$errors = array();
?>
<div class="center-div">

	<?php 
	//form validation
	if(isset($_POST['change_password'])){
		if(empty($_POST['old_password']) || empty($_POST['password']) || empty($_POST['confirm'])){
			$errors[] = "Fields cannot be empty";
		}else{

			// Get user password from the database
			$user_id = $_SESSION['user_id'];
			$password_query = $db->query("SELECT * FROM users WHERE id = '$user_id'");
			$user = mysqli_fetch_assoc($password_query);

			if(!password_verify($old_password, $user['password'])){
				$errors[] = 'The old password you entered is incorrect';
			}else{

					// password is more than 6 characters
				if(strlen($password) < 6){
					$errors[] = "Password must be at least 6 characters";
				}

				// if new password matches confirm
				if($password != $confirm){
					$errors[] = "New password and confirm password do not match";
				}

			}
			
		}

		// Check for errors
		if(!empty($errors)){
			echo display_errors($errors);
		}else{
			// Update the password field
			$user_id = $user['id'];
			$password = password_hash($password, PASSWORD_DEFAULT);
			$_SESSION['success_msg'] = "Password successfully changed";
			$db->query("UPDATE users SET password = '$password' WHERE id = '$user_id'");
			header('Location: index.php');
		}
	}

	?>


	<div></div>
	<h2 class="text-center">Change Password</h2><hr>
	<?php 

	if(isset($_SESSION['error_flash'])){
		echo '<div class="bg-danger"><p class="text-center">'.$_SESSION['error_flash'].'</p></div>';
		unset($_SESSION['error_flash']);
	}else if(!empty($success_msg)){
		echo '<div class="bg-success"><p class="text-center">'.$success_msg.'</p></div>';
	}

	?>
	<form action="change_password.php" method="post">
		<div class="form-group">
			<label for="old_password">Old Password:</label>
			<input type="password" name="old_password" id="old_password" class="form-control" value="<?=((!empty($errors))?$old_password:'');?>">
		</div>
		<div class="form-group">
			<label for="password">New Password:</label>
			<input type="password" name="password" id="password" class="form-control" value="<?=((!empty($errors))?$password:'');?>">
		</div>
		<div class="form-group">
			<label for="confirm">Confirm Password:</label>
			<input type="password" name="confirm" id="confirm" class="form-control" value="<?=((!empty($errors))?$confirm:'');?>">
		</div>
		<div class="form-group">
			<a href="index.php" class="btn btn-default">Cancel</a>
			<input type="submit" value="Change Password" class="btn btn-primary" name="change_password">
		</div>
	</form>
</div>
<?php include'includes/footer.php'; ?>
