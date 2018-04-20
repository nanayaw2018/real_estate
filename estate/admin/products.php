<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/estate/core/init.php';
include 'includes/header.php';
include 'includes/navigation.php';

if(!is_logged_in()){
	login_error_redirect();
}

$dbpath = '';
// Delete product
if(isset($_GET['delete'])){
	$id = sanitize($_GET['delete']);

	$db->query("UPDATE products SET deleted = 1 WHERE id = '$id'");
	header('Location: products.php');
}

$title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):'');
$brand = ((isset($_POST['brand']) && $_POST['brand'] != '')?sanitize($_POST['brand']):'');
$parent = ((isset($_POST['parent']) && $_POST['parent'] != '')?sanitize($_POST['parent']):'');
$category = ((isset($_POST['child']) && !empty($_POST['child']))?sanitize($_POST['child']):'');
$price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):'');
$list_price = ((isset($_POST['list_price']) && $_POST['list_price'] != '')?sanitize($_POST['list_price']):'');
$description = ((isset($_POST['description']) && $_POST['description'] != '')?sanitize($_POST['description']):'');

$sizes = ((isset($_POST['sizes']) && $_POST['sizes'] != '')?sanitize($_POST['sizes']):'');

$saved_image = "";
$error = "";
if(isset($_GET['add'])  || isset($_GET['edit'])){
	$brandQuery = $db->query("SELECT * FROM brand ORDER BY brand");
	$parentQuery = $db->query("SELECT * FROM category WHERE parent = 0 ORDER BY category");

	if(isset($_GET['edit'])){
		$edit_id = (int)sanitize($_GET['edit']);

		$productResult = $db->query("SELECT * FROM products WHERE id = '$edit_id'");
		$product = mysqli_fetch_assoc($productResult);

		if(isset($_GET['delete_image'])){
			$image_url = $_SERVER['DOCUMENT_ROOT'].$product['image'];
			unlink($image_url);

			// updating the database after deleting the picture
			$db->query("UPDATE products SET image = '' WHERE id = '$edit_id'");
			header('Location: products.php?edit='.$edit_id);
		}

		$category = ((isset($_POST['child']) && $_POST['child'] != '')?sanitize($_POST['child']):$product['categories']);

		$title = ((isset($_POST['title']) && !empty($_POST['title']))?sanitize($_POST['title']):$product['title']);
		$brand = ((isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']):$product['brand']);

		$parentQ = $db->query("SELECT * FROM category WHERE id = '$category'");
		$parentResult = mysqli_fetch_assoc($parentQ);

		$parent = ((isset($_POST['parent']) && !empty($_POST['parent']))?sanitize($_POST['parent']):$parentResult['parent']);

		$price = ((isset($_POST['price']) && !empty($_POST['price']))?sanitize($_POST['price']):$product['price']);
		$list_price = ((isset($_POST['list_price']) && !empty($_POST['list_price']))?sanitize($_POST['list_price']):$product['list_price']);
		$description = ((isset($_POST['description']) && !empty($_POST['description']))?sanitize($_POST['description']):$product['description']);

		$sizes = ((isset($_POST['sizes']) && !empty($_POST['sizes']))?sanitize($_POST['sizes']):$product['sizes']);

		$saved_image = (($product['image'] != '')?$product['image']:'');
		$dbpath = $saved_image;
		
	}


	if (!empty($sizes)) {
		//build the size array if not empty
		$sizeString = sanitize($sizes);
		$sizeString = rtrim($sizeString, ',');
		$sizesArray = explode(',', $sizeString);

		$sArray = array();
		$qArray = array();
		foreach($sizesArray as $ss){
			$s = explode(':', $ss);
			$sArray[] = $s[0];
			$qArray[] = $s[1];
		}
	}else{
		$sizesArray = array();
	}


// Making array of sizes
	if($_POST){
	$errors = array();

	$required = array('title', 'brand', 'price', 'parent', 'child', 'sizes');
	foreach($required as $field){
		if($_POST[$field] == ''){
			$errors[] = "All fields with an astrisk are required";
			break;
		}
	}

	if(!empty($_FILES)){
		$photo = $_FILES['photo'];
		$name = $photo['name'];


		
		$filename = "";
		$fileExt = "";
		if(!empty($name)){
			$nameArray = explode('.', $name);
			$filename = $nameArray[0];
			$fileExt = $nameArray[1];
		}
		


		
		$mimeType = "";
		$mimeExt = "";
		if(!empty($photo['type'])){
			$mime = explode('/', $photo['type']);
			$mimeType = $mime[0];
			$mimeExt =  $mime[1];
		}
		

		$tmploc = $photo['tmp_name'];
		$filesize = $photo['size'];

		$allowed = array('png', 'jpg', 'jpeg', 'gif');

		$uploadName = md5(microtime()).'.'.$fileExt;
		$uploadPath = BASEURL.'images/products/'.$uploadName;
		$dbpath = '/estate/images/products/'.$uploadName;
		if($mimeType != 'image'){
			$errors[] = "The file must be an image";
		}

		if (!in_array($fileExt, $allowed)) {
			$errors[] = "The file extension must be a png, jpg, jpeg or gif";
		}

		if($filesize > 15000000){
			$errors[] = "The file size must be under 10mb";
		}

		if($fileExt != $mimeExt && ($mimeExt == 'jpeg' && $fileExt != 'jpg')){
			$errors[] = "File extension does not match the file";
		}
	}

	//display errors if exist
	if(!empty($errors)){
		$error = display_errors($errors);
	}else{
		$_SESSION['success_product'] = "<ul class='bg-success'><li class='text-default text-center'>Product is successfully added!</li></ul>";
		//upload file and insert into database
		move_uploaded_file($tmploc, $uploadPath);
		$insertSql = "INSERT INTO products (title, price, list_price, brand, categories, image, description, featured, sizes, deleted) VALUES ('$title', '$price', '$list_price', '$brand', '$category', '$dbpath', '$description', 0, '$sizes', 0)";

		if(isset($_GET['edit'])){
			$_SESSION['success_product'] = "<ul class='bg-success'><li class='text-default text-center'>Product is successfully edited!</li></ul>";
			$insertSql = "UPDATE products SET title = '$title', price = '$price', list_price = '$list_price', brand = '$brand', categories = '$category', sizes = '$sizes', image = '$dbpath', description = '$description' WHERE id = '$edit_id'";
		}
		$db->query($insertSql);

		header('Location: products.php');
	}
}

?>
<div class="container">
	<h3 class="text-center text-primary"><?=((isset($_GET['edit']))?'Edit ':'Add New ');?>Product</h3><hr>
	<div class="center-edit">
	<?=$error;?>
	<form action="products.php?<?=((isset($_GET['edit']))?'edit='.$edit_id:'add=1');?>" method="post" enctype="multipart/form-data">
		<div class="form-group col-md-6">
			<label for="title">Title*:</label>
			<input type="text" name="title"  class="form-control" id="title" value="<?=$title;?>">
		</div>
		<div class="form-group col-md-6">
			<label for="brand">Brand*:</label>
			<select class="form-control" id="brand" name="brand">
				<option value="" <?=(($brand == '')?' selected':'');?>></option>
				<?php while($b = mysqli_fetch_assoc($brandQuery)): ?>
					<option value="<?=$b['id'];?>"<?=(($brand == $b['id'])?' selected':'');?>><?=$b['brand'];?></option>
				<?php endwhile; ?>
			</select>
		</div>
		<div class="form-group col-md-6">
			<label for="parent">Parent Category</label>
			<select class="form-control" id="parent" name="parent">
				<option value="" <?=(($parent == '')?' selected':'');?>></option>
				<?php while($p = mysqli_fetch_assoc($parentQuery)): ?>
					<option value="<?=$p['id'];?>" <?=(($parent == $p['id'])?' selected':'');?>><?=$p['category'];?></option>
				<?php endwhile; ?>
			</select>
		</div>
		<div class="form-group col-md-6">
			<label for="child">Child Category*:</label>
			<select id="child" name="child" class="form-control"></select>
		</div>
		<div class="form-group col-md-6">
			<label for="price">Price*:</label>
			<input type="text" id="price" name="price" class="form-control" value="<?=$price;?>">
		</div>

		<div class="form-group col-md-6">
			<label for="list_price">List Price</label>
			<input type="text" id="list_price" name="list_price" class="form-control" value="<?=$list_price;?>">
		</div>

		<div class="form-group col-md-6">
			<label>Quantity &amp; Sizes*:</label>
			<button class="btn btn-default form-control" onclick="jQuery('#sizesModal').modal('toggle'); return false;">Quantity &amp; Sizes</button>
		</div>

		<div class="form-group col-md-6">
			<label for="sizes">Sizes &amp; Qty Preview:</label>
			<input type="text" class="form-control" name="sizes" id="sizes" value="<?=rtrim($sizes, ',')?>" readonly>
		</div>

		<div class="form-group col-md-12">
			<?php if($saved_image != ''): ?>
				<div class="saved-image"><img src="<?=$saved_image?>" alt="Product Image"><br>
				<a href="products.php?delete_image=1&edit=<?=$edit_id?>" class="text-danger">Delete Image</a>
				</div>
			<?php else: ?>
				<label for="photo">Product Image:</label>
				<input type="file" name="photo" id="photo" class="form-control">
			<?php endif; ?>
		</div>

		<div class="form-group col-md-12">
			<label for="description">Description:</label>
			<textarea id="description" name="description" class="form-control" rows="6"><?=$description;?></textarea>
		</div>
		<div class="form-group pull-right">
			<input type="submit" value="<?=((isset($_GET['edit']))?'Save Changes':'Add Product');?>" class="btn btn-success">
			<a href="products.php" class="btn btn-danger">Cancel</a>
		</div><div class="clearfix"></div>
	</form>
	</div>


	<!-- Modal -->
	<div class="modal fade" id="sizesModal" tabindex="-1" role="dialog" aria-labelledby="sizesModalLabel">
		<div class="modal-dialog  modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="sizesModalLabel">Size &amp; Quantity</h4>
				</div>
				<div class="modal-body">
				<div class="container-fluid">
				<?php for($i=1; $i<=12; $i++): ?>
					<div class="form-group col-md-4">
						<label for="size<?=$i;?>">Size:</label>
						<input class="form-control" type="text" name="size<?=$i;?>" id="size<?=$i;?>" value="<?=((!empty($sArray[$i-1]))?$sArray[$i-1]:'');?>">
					</div>

					<div class="form-group col-md-2">
						<label for="qty<?=$i;?>">Quantity:</label>
						<input class="form-control" type="number" name="qty<?=$i;?>" id="qty<?=$i;?>" value="<?=((!empty($qArray[$i-1]))?$qArray[$i-1]:'');?>" min="0">
					</div>

				<?php endfor; ?>
				</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" onclick="updateSizes();jQuery('#sizesModal').modal('toggle');return false;">Save changes</button>
				</div>
			</div>
		</div>
	</div>

<?php }else{

if (isset($_GET['archived'])) {
	$sql = "SELECT * FROM products WHERE deleted = 1";
}else{
	$sql = "SELECT * FROM products WHERE deleted = 0";
}
$presult = $db->query($sql);
$prows = mysqli_num_rows($presult);

//update the featured column of the products
if(isset($_GET['featured'])){
	$id = (int)$_GET['id'];
	$featured = (int)$_GET['featured'];
	$featuredsql = "UPDATE products SET featured = '$featured' WHERE id = '$id'";
	$db->query($featuredsql);
	header('Location: products.php');
}

// Refreshing archived files to products
if(isset($_GET['refresh'])){
	$archived_id = $_GET['refresh'];
	$db->query("UPDATE products SET deleted = 0 WHERE id = '$archived_id'");
	header('Location: products.php?archived=1');
}

if(isset($_GET['cdelete'])){
	$cdelete_id = $_GET['cdelete'];
	$db->query("DELETE FROM products WHERE id = '$cdelete_id'");
	header('Location: products.php?archived=1');
}

?>
<div class="container">
	<h2 class="text-center text-primary"><?=((isset($_GET['archived']))?'Archived ':'');?>Products</h2>
	<a href="products.php?add=1" class="btn btn-success pull-right position-btn" id="add-product-btn">Add Product</a><div class="clearfix"></div><hr>
	<?=((isset($_SESSION['success_product']))?$_SESSION['success_product']:'');?>
	<?php unset($_SESSION['success_product']);?>
	<?php if($prows > 0): ?>
	<table class="table table-condensed table-bordered table-striped table-hover">
		<thead>
			<th></th>
			<th>Product</th>
			<th>Price</th>
			<th>Category</th>
			<th>Featured</th>
			<th>Sold</th>
		</thead>

		<tbody>
			<?php while($product = mysqli_fetch_assoc($presult)): 
				$childId = $product['categories'];
				$child_category = "SELECT * FROM category WHERE id = '$childId'";
				$child_result = $db->query($child_category);
				$child = mysqli_fetch_assoc($child_result);

				$parentId = $child['parent'];
				$parent_category = "SELECT * FROM category WHERE id = '$parentId'";
				$parent_result = $db->query($parent_category);
				$parent = mysqli_fetch_assoc($parent_result);

				$category = $parent['category'].'-'.$child['category'];
			?>
				<tr class="bg-primary">
					<td>
						<a href="products.php<?=((isset($_GET['archived']))?'?refresh='.$product['id']:'?edit='.$product['id']);?>" class="btn btn-xs btn-default" data-toggle="refresh" title="<?=((isset($_GET['archived']))?'Restore item to products':'Edit Product');?>"><span class="glyphicon glyphicon-<?=((isset($_GET['archived']))?'refresh':'pencil');?>"></span></a>
						<a href="products.php<?=((isset($_GET['archived']))?'?cdelete='.$product['id']:'?delete='.$product['id']);?>" class="btn btn-xs btn-default" data-toggle="tooltip" title="<?=((isset($_GET['archived']))?'Completely delete product':'Delete to archive');?>"><span class="glyphicon glyphicon-remove"></span></a>
					</td>
					<td><?=$product['title'];?></td>
					<td><?=money($product['price']);?></td>
					<td><?=$category;?></td>
					<td><a href="products.php?featured=<?=(($product['featured'] == 0)?'1':'0');?>&id=<?=$product['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-<?=(($product['featured'] == 1)?'minus':'plus');?>"></span></a>&nbsp<?=(($product['featured'] == 1)?'Featured Product':'');?></td>
					<td>0</td>
				</tr>
			<?php endwhile; ?>
		</tbody>
	</table>
	<?php else: ?>
		<h2>No Archived Products</h2><hr>
	<?php endif; ?>
	<br>
	<a href="products.php<?=((!isset($_GET['archived']))?'?archived=1':'');?>" class="btn <?=((isset($_GET['archived']))?'btn-primary':'btn-danger');?>" id="add-product-btn"><?=((!isset($_GET['archived']))?'Archived ':'');?>Products</a>
</div> 
</div><?php }?>
<?php include 'includes/footer.php'; ?>
<script type="text/javascript">
	jQuery('document').ready(function(){
		get_child_options('<?=$category;?>');

		// tooltip jquery
		$('[data-toggle="tooltip"]').tooltip();
		$('[data-toggle="refresh"]').tooltip();
	});
</script>
