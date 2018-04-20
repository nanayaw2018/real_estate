<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/estate/core/init.php';
include 'includes/header.php';

$email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
$email = trim($email);
$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
$password = trim($password);
$errors = array();
?>
<div class="center-div">

	<?php 
	//form validation
	if(isset($_POST['login_user'])){
		if(empty($_POST['email']) || empty($_POST['password'])){
			$errors[] = "You must provide email and password";
		}else{
			//validate email
			if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
				$errors[] = "You must enter a valid email";
			}

			// password is more than 6 characters
			if(strlen($password) < 6){
				$errors[] = "Password must be at least 6 characters";
			}
			// check if email exist in the database 
			$query = $db->query("SELECT * FROM users WHERE email = '$email'");
			$user = mysqli_fetch_assoc($query);
			$user_count = mysqli_num_rows($query);
			if($user['deleted'] == 1){
				$errors[] = "You don't have access anymore";
			}
			if($user_count < 1){
				$errors[] = "That email doesn't exist in our database";
			}else{
				if(!password_verify($password, $user['password'])){
					$errors[] = 'Invalid password';
				}
			}
		}

		// Check for errors
		if(!empty($errors)){
			echo display_errors($errors);
		}else{
			$user_id = $user['id'];
			login($user_id);
		}
	}

	?>


	<div></div>
	<h2 class="text-center">Login</h2><hr>
	<?php 

	if(isset($_SESSION['error_flash'])){
		echo '<div class="bg-danger text-primary"><p class="text-center">'.$_SESSION['error_flash'].'</p></div>';
		unset($_SESSION['error_flash']);
	}

	?>
	<form action="login.php" method="post">
		<div class="form-group">
			<label for="email">Email:</label>
			<input type="email" name="email" id="email" class="form-control" value="<?=$email;?>">
		</div>

		<div class="form-group">
			<label for="password">Password:</label>
			<div class="input-group">
				<input type="password" class="form-control" name="password" id="password" value="<?=$password;?>">
				<div class="input-group-btn">
					<button class="btn btn-default" type="button" id="show_password" name="show_password">
						<i class="glyphicon glyphicon-eye-close"></i>
					</button>
				</div>
			</div>
		</div>
		<div class="form-group">
			<input type="submit" value="Login" class="btn btn-primary" name="login_user">
		</div>
		<p>Don't have an account? <a href="register.php">Register</a> </p>
	</form>
</div>
<?php include'includes/footer.php'; ?>
