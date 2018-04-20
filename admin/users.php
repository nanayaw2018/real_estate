<?php 
require_once("../core/init.php");
include("includes/header.php");
include("includes/navigation.php");

if(!is_logged_in()){
	login_error_redirect();
}

if(!has_permission()){
	permission_error_redirect('brands.php');
}

if(isset($_GET['delete'])){
	$delete_id = $_GET['delete'];
	$db->query("UPDATE users SET deleted = 1 WHERE id = '$delete_id'");
	header('Location: users.php');
}

if(isset($_GET['cdelete'])){
	$cdelete_id = $_GET['cdelete'];
	$db->query("DELETE FROM users WHERE id = '$cdelete_id' AND deleted = 1");
	header('Location: users.php?archived=1');
}

if (isset($_GET['restore'])) {
	$restore_id = $_GET['restore'];
	$db->query("UPDATE users SET deleted = 0 WHERE id = '$restore_id'");
	header('Location: users.php?archived=1');
}

$firstname = ((isset($_POST['firstname']) && !empty($_POST['firstname']))?$_POST['firstname']:'');
$lastname = ((isset($_POST['lastname']) && !empty($_POST['lastname']))?$_POST['lastname']:'');
$email = ((isset($_POST['email']) && !empty($_POST['email']))?$_POST['email']:'');
$password = ((isset($_POST['password']) && !empty($_POST['password']))?$_POST['password']:'');
$confirm = ((isset($_POST['confirm']) && !empty($_POST['confirm']))?$_POST['confirm']:'');
$perm = "";
if(isset($_POST['permission']) && !empty($_POST['permission'])){
	foreach ($_POST['permission'] as $permission) {
		$perm .= $permission.",";
	}
}

$errors = array();

if(isset($_GET['edit']) || isset($_GET['add'])){
	$edit_id = ((isset($_GET['edit']))?$_GET['edit']:'');

	if(isset($_GET['edit'])){
		$edit_query = $db->query("SELECT * FROM users WHERE id = '$edit_id'");
		$user_info = mysqli_fetch_assoc($edit_query);
		$full_name = explode(' ', $user_info['full_name']);

		if(count($full_name) > 1){
			$firstname = ((isset($_POST['firstname']) && !empty($_POST['firstname']))?sanitize($_POST['firstname']):$full_name[0]);
			$lastname = ((isset($_POST['lastname']) && !empty($_POST['lastname']))?sanitize($_POST['lastname']):$full_name[1]);
		}else{
			$firstname = ((isset($_POST['firstname']) && !empty($_POST['firstname']))?sanitize($_POST['firstname']):$full_name[0]);
			$lastname = ((isset($_POST['lastname']) && !empty($_POST['lastname']))?sanitize($_POST['lastname']):'');
		}

		$email = ((isset($_POST['email']) && !empty($_POST['email']))?sanitize($_POST['email']):$user_info['email']);

		if(isset($_POST['permission']) && !empty($_POST['permission'])){
			$perm = "";
			foreach ($_POST['permission'] as $permission) {
				$perm .= $permission.",";
			}
		}else{
			$perm = $user_info['permissions'];
		}
		
	}

	if(isset($_POST['add_user'])){
		if(empty($_POST['firstname']) || empty($_POST['lastname']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['confirm']) || empty($_POST['permission'])){
			$errors[] = "All fields are required";
		}else{
			if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
				$errors[] = "Please enter a valid email";
			}

			if($confirm != $password){
				$errors[] = "Passwords do not match";
			}
		}

		//checking for errors
		if(!empty($errors)){
			$error = display_errors($errors);
		}else{

			//insert details into database if there exist no errors.
			$full_name = $firstname." ".$lastname;
			$permissions = rtrim($perm, ',');
			$hashed_password = password_hash($password, PASSWORD_DEFAULT);
			$success = "<ul class='bg-success'><li class='text-default text-center'>User is successfully added!</li></ul>";
			$db->query("INSERT INTO users(full_name, email, password, permissions) VALUES('$full_name', '$email', '$hashed_password', '$permissions')");
		}

	}


	if(isset($_POST['edit_user'])){
		if(empty($_POST['firstname']) || empty($_POST['lastname']) || empty($_POST['email']) || empty($_POST['permission'])){
			$errors[] = "All fields are required";
		}else{
			if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
				$errors[] = "Please enter a valid email";
			}
		}

		//checking for errors
		if(!empty($errors)){
			$error = display_errors($errors);
		}else{

			//update details if there exist no errors.
			$full_name = $firstname." ".$lastname;
			$permissions = rtrim($perm, ',');
			$success = "<ul class='bg-success'><li class='text-default text-center'>User is successfully edited!</li></ul>";
			$db->query("UPDATE users SET full_name = '$full_name', email = '$email', permissions = '$permissions' WHERE id = '$edit_id'");
		}

	}

	
	?>
	<div class="container">
		<h2 class="text-center text-primary"><?=((isset($_GET['edit']))?'Edit ':'Add New ');?>User</h2><hr>
			<div class="center-edit">
			<?=((!empty($error))?$error:'');?>
			<?=((!empty($success))?$success:'');?>
			<form action="users.php?<?=((isset($_GET['edit']))?'edit='.$edit_id:'add=1');?>" method="post">
				<div class="form-group col-md-6">
					<label for="firstname">First Name *:</label>
					<input type="text" class="form-control" name="firstname" id="firstname" value="<?=((!empty($errors) || isset($_GET['edit']))?$firstname:'');?>" placeholder="Enter first name">
				</div>
				<div class="form-group col-md-6">
					<label for="lastname">Last Name *:</label>
					<input type="text" class="form-control" name="lastname" id="lastname" value="<?=((!empty($errors) || isset($_GET['edit']))?$lastname:'');?>" placeholder="Enter last name">
				</div>
				<div class="form-group col-md-12">
					<label for="email">Email *:</label>
					<input type="text" class="form-control" name="email" id="email" value="<?=((!empty($errors) || isset($_GET['edit']))?$email:'');?>" placeholder="Enter email">
				</div>
				<?php if(isset($_GET['add'])): ?>
				<div class="form-group col-md-6">
					<label for="password">Password *:</label>
					<input type="password" class="form-control" name="password" id="password" value="<?=((!empty($errors))?$password:'');?>">
				</div>
				<div class="form-group col-md-6">
					<label for="confirm">Confirm Password *:</label>
					<input type="password" class="form-control" name="confirm" id="confirm" value="<?=((!empty($errors))?$confirm:'');?>">
				</div>
				<?php endif; ?>
				<div class="col-md-12">
					<label for="checkbox">Permissions *:</label>
				 	<div class="checkbox-inline">
				 		<?php $permitted = explode(',', rtrim($perm, ','));?>
						<label><input type="checkbox" name="permission[]" value="admin" <?=(((in_array('admin', $permitted) || !empty($errors)) && isset($_GET['edit']))?'checked':'');?>>Admin</label>
					</div>
					<div class="checkbox-inline">
						<label><input type="checkbox" name="permission[]" value="editor" <?=(((in_array('editor', $permitted) || !empty($errors)) && (isset($_GET['edit']) || isset($_POST['permission'])))?'checked':'');?>>Editor</label>
					</div> 
				</div>
				<div class="form-group col-md-12">
					<input class="btn btn-success" type="submit" name="<?=((isset($_GET['edit']))?'edit_user':'add_user');?>" value="<?=((isset($_GET['edit']))?'Save Changes':'Add User');?>">
					<?php if(isset($_GET['edit'])): ?>
						<a href="users.php?add=1" class="btn btn-primary">Add New User</a>
					<?php else: ?>
						<a href="users.php" class="btn btn-primary">All Users</a>
					<?php endif; ?>
				</div>
			</form>
			</div>
	</div>

<?php }else{?>
			<?php
				if(isset($_GET['archived'])){
					$user_query = $db->query("SELECT * FROM users WHERE deleted = 1 ORDER BY full_name"); 
				}else{
					$user_query = $db->query("SELECT * FROM users WHERE deleted = 0 ORDER BY full_name"); 
				}

				$urows = mysqli_num_rows($user_query);
			 ?>
<div class="container">
	<h3 class="text-center text-primary"><?=((isset($_GET['archived']))?'Archived ':'');?>Users</h3>
	<a href="users.php?add=1" class="btn btn-success pull-right position-btn">Add New User</a><div class="clearfix"></div><hr>
	<?php if($urows > 0): ?>
	<table class="table table-condensed table-striped table-bordered">
		<thead>
			<th></th>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Email</th>
			<th>Permissions</th>
			<th>Join Date</th>
			<th>Last Login</th>
			<th>Time Of Login</th>
		</thead>
		<tbody>

			<?php while($users = mysqli_fetch_assoc($user_query)): ?>
				<?php
					$name = explode(' ', $users['full_name']); 
					if(count($name) > 1){
						$firstname = $name[0];
						$lastname = $name[1];
					}else{
						$firstname = $name[0];
					}

					$join_date = explode(' ', $users['join_date']);
					if(count($join_date) > 1){
						$date_join = pretty_date($join_date[0]);
						$time_join = pretty_time($join_date[1]);
					}else{
						$date_join = $date[0];
					}

					$last_login = explode(' ', $users['last_login']);
					if(count($last_login) > 1){
						$last_date = (($last_login[0] == "0000-00-00")?'Never':pretty_date($last_login[0]));
						$last_time = (($last_login[1]) == "00:00:00"?'Never':pretty_time($last_login[1]));
					}else{
						$last_date = $last_login[0];
					}

					$permissions = explode(',', $users['permissions']);
					$permits = "";
					foreach ($permissions as $permission) {
						$permits .= ucfirst($permission)." , ";
					}
				?>
				<tr class="bg-primary">
					<td>
						<?php if($users['id'] != $user_data['id']): ?>
						<a href="users.php?<?=((!isset($_GET['archived']))?'edit='.$users['id']:'restore='.$users['id']);?>" class="btn btn-xs btn-default" data-toggle="edit" title="<?=((isset($_GET['archived']))?'Restore User':'Edit User');?>"><span class="glyphicon glyphicon-<?=((isset($_GET['archived']))?'refresh':'pencil');?>"></span></a>

						<a href="users.php?<?=((!isset($_GET['archived']))?'delete='.$users['id']:'cdelete='.$users['id']);?>" class="btn btn-xs btn-default" data-toggle="delete" title="<?=((isset($_GET['archived']))?'Completely Delete User Account':'Delete user to archive');?>"><span class="glyphicon glyphicon-remove"></span></a>
						<?php endif; ?>
					</td>
					<td><?=$firstname;?></td>
					<td><?=$lastname;?></td>
					<td><?=$users['email'];?></td>
					<td><?=rtrim($permits, " , ");?></td>
					<td><?=$date_join;?></td>
					<td <?=(($last_date == "-")?'class="text-center"':'');?>><?=$last_date;?></td>
					<td <?=(($last_time == "-")?'class="text-center"':'');?>><?=$last_time;?></td>
				</tr>
			<?php endwhile; ?>
		</tbody>
	</table>
	<?php else: ?>
		<h3>No Archived User</h3><hr>
	<?php endif; ?>
	<a href="users.php<?=((!isset($_GET['archived']))?'?archived=1':'');?>" class="btn <?=((isset($_GET['archived']))?'btn-primary':'btn-danger');?>"><?=((isset($_GET['archived']))?'All ':'Archived ');?>Users</a>
</div> <?php } ?>
<?php include("includes/footer.php"); ?>
<script type="text/javascript">
	jQuery('document').ready(function(){
		$('[data-toggle="edit"]').tooltip();
		$('[data-toggle="delete"]').tooltip();
	});
</script>