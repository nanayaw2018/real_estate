<?php 
require_once("../core/init.php");
include("includes/header.php");
include("includes/navigation.php");

if(!is_logged_in()){
	header('Location: login.php');
}

$themeArray = array("cyborg", "darkly", "superhero", "slate", "sandstone", "readable", "paper", "lumen", "solar");
sort($themeArray);

if (isset($_POST['change_theme']) && $_POST['theme']) {
	$t = $_POST['theme'];

	$db->query("UPDATE themes SET status = 1 WHERE theme_name = '$t'");
	$db->query("UPDATE themes SET status = 0 WHERE theme_name != '$t'");


	header('Location: index.php');
}
?>
<div class="container">
	<?php
		if(isset($_SESSION['success_msg'])){
			echo '<div class="bg-success center-div"><p class="text-center">'.$_SESSION['success_msg'].'</p></div>';
			unset($_SESSION['success_msg']);
		}
	?>
	<h2>Administrator</h2><hr>
	<?php 
		$db_theme = $db->query("SELECT * FROM themes WHERE status = 1");
		$theme_name = mysqli_fetch_assoc($db_theme);
	?>
	<form action="index.php" method="post">
		<div class="col-md-6">
			<div class="form-group col-md-6">
				<label for="theme">Select Theme:</label>
				<select class="form-control" name="theme" id="theme">
					<option value="default">Default</option>
					<?php foreach($themeArray as $theme): ?>
						<option value="<?=$theme;?>" <?=((!empty($theme_name['theme_name']) && $theme_name['theme_name'] == $theme)?' selected':'');?>><?=ucfirst($theme);?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="form-group col-md-6">
				<label for="change_theme">Change Theme:</label>
				<input type="submit" class="btn btn-primary form-control" name="change_theme" value="Change Theme">
			</div>
		</div>
	</form>
</div>
<?php include("includes/footer.php"); ?>