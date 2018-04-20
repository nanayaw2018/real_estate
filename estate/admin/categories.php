<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/estate/core/init.php';
include 'includes/header.php';
include 'includes/navigation.php';

if(!is_logged_in()){
	login_error_redirect();
}

$sql = "SELECT * FROM category WHERE parent = 0";
$result = $db->query($sql);

//empty array to handle errors
$errors = array();

// Edit Category
if(isset($_GET['edit']) && !empty($_GET['edit'])){
	$edit_id = $_GET['edit'];
	$edit_id = sanitize($edit_id);

	$edit_sql = "SELECT * FROM category WHERE id = '$edit_id'";
	$edit_result = $db->query($edit_sql);
	$edit_category = mysqli_fetch_assoc($edit_result);

}

$category_value = "";
$parent_value = 0;
if(isset($_GET['edit'])){
	$category_value = $edit_category['category'];
	$parent_value = $edit_category['parent'];
}else{
	if(isset($_POST['category'])){
		$category_value = $_POST['category'];
		$parent_value = $_POST['parent'];
	}
}

// Delete Category
if(isset($_GET['delete']) && !empty($_GET['delete'])){
	$delete_id = (int)$_GET['delete'];
	$delete_id = sanitize($delete_id);
	$sql = "SELECT * FROM category WHERE id = '$delete_id'";
	$result = $db->query($sql);
	$category = mysqli_fetch_assoc($result);
	if($category['parent'] == 0){
		$sql = "DELETE FROM category WHERE parent = '$delete_id'";
		$db->query($sql);
	}
	$dsql = "DELETE FROM category WHERE id = '$delete_id'";
	$db->query($dsql);
	header('Location: categories.php');
}

// Process Form
if(isset($_POST['add_category']) && !empty($_POST['add_category'])){
	$parent = sanitize($_POST['parent']);
	$category = sanitize($_POST['category']);

	$sqlform = "SELECT * FROM category WHERE category = '$category' AND parent='$parent'";
	if (isset($_GET['edit'])) {
		$id = $edit_category['id'];
		$sqlform = "SELECT * FROM category WHERE category = '$category' AND parent='$parent_value' AND id !='$id'";
	}
	$fresult = $db->query($sqlform);
	$count = mysqli_num_rows($fresult);

	// if category is blank
	if($category == ''){
		$errors[] .= 'The category cannot be left blank';
	}

	// If exists inthe database
	if($count > 0){
		$errors[] .= $category." already exists. Please choose a new category";
	}

	// Display errors or update database
	if(!empty($errors)){
		//display errors
		$display = display_errors($errors);?>

		<script type="text/javascript">
			jQuery('document').ready(function(){
				jQuery('#errors').html('<?=$display?>');
			});
		</script>
	<?php }else{
		//update database
		$updatesql = "INSERT INTO category(category, parent) VALUES('$category', '$parent')";

		if(isset($_GET['edit'])){
			$updatesql = "UPDATE category SET category = '$category', parent = '$parent_value' WHERE id = '$edit_id'";
		}
		$db->query($updatesql);
		header('Location: categories.php');
	}
}



?>
<div class="container">
<h3 class="text-center text-primary">Categories</h3><hr>
<div class="row">
	<!--Form-->
	<div class="col-md-5">
		<form class="form" action="categories.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:'');?>" method="post">
			<legend><?=((isset($_GET['edit']))?'Edit ':'Add ');?>Category</legend>
			<div id="errors"></div>
			<div class="form-group">
				<label for="parent">Parent</label>
				<select class="form-control" name="parent" id="parent">
					<option value="0"<?=(($parent_value == 0)?' selected="selected"':'');?>>Parent</option>
					<?php while($parent = mysqli_fetch_assoc($result)): ?>
						<option value="<?=$parent['id'];?>"<?=(($parent_value == $parent['id'])?' selected="selected"':'');?>><?=$parent['category'];?></option>
					<?php endwhile; ?>
				</select>
			</div>

			<div class="form-group">
				<label for="category">Category</label>
				<input type="text" class="form-control" name="category" id="category" value="<?=$category_value;?>">
			</div>
			
			<div class="form-group">
				<input type="submit" name="add_category" value="<?=((isset($_GET['edit']))?'Edit':'Add ')?> Category" class="btn btn-success">
				<?php if(isset($_GET['edit'])): ?>
				<a href="categories.php" class="btn btn-success">Cancel</a>
				<?php endif; ?>
			</div>
		</form>
	</div>

	<!--Empty Div-->
	<div class="col-md-1"></div>

	<!--Category Table-->
	<div class="col-md-6">
		<table class="table table-bordered table-condensed">
			<thead>
				<th>Category</th>
				<th>Parent</th>
				<th></th>
			</thead>

			<tbody>
				<?php 
				$sql = "SELECT * FROM category WHERE parent = 0";
				$result = $db->query($sql);
				while($parent = mysqli_fetch_assoc($result)):
					$parent_id = (int)$parent['id']; 
					$sql2 = "SELECT * FROM category WHERE parent = '$parent_id'";
					$cresult = $db->query($sql2);
				?>

				<tr class="bg-primary">
					<td><?=$parent['category'];?></td>
					<td>Parent</td>
					<td>
						<a href="categories.php?edit=<?=$parent['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
						<a href="categories.php?delete=<?=$parent['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></span></a>
					</td>
				</tr>
					<?php while($child = mysqli_fetch_assoc($cresult)): ?>
						<tr class="bg-default">
							<td><?=$child['category'];?></td>
							<td><?=$parent['category'];?></td>
							<td>
								<a href="categories.php?edit=<?=$child['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
								<a href="categories.php?delete=<?=$child['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></span></a>
							</td>
						</tr>
					<?php endwhile; ?>
				<?php endwhile; ?>
			</tbody>
		</table>
	</div>
</div>
</div>


<?php include 'includes/footer.php'; ?>