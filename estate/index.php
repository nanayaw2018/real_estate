<?php 
require_once("core/init.php");
include("includes/head.php");
include("includes/navigation.php");
include("includes/header_full.php");
include("includes/leftbar.php");


$sql = "SELECT * FROM products WHERE featured = 1 AND deleted = 0";
$featured = $db->query($sql);
?>
    <!-- main content -->
    <div class="col-md-8">
        <div class="row">
            <?php
                if(isset($_SESSION['success_flash'])){
                    echo '<div class="bg-success"><p class="text-center">'.$_SESSION['success_flash'].'</p></div>';
                    unset($_SESSION['success_flash']);
                }
            ?>
            <h2 class="text-center">Lastest Houses</h2>
            <?php while($product = mysqli_fetch_assoc($featured)) : ?>
            <div class="col-md-3">
                <h4><?= $product['title']; ?></h4>
                <img src="<?= $product['image']; ?>" alt="<?= $product['title']; ?>" class="img-thumb">
                <p class="list-price text-danger">List Price: <s>$<?= $product['list_price']; ?></s></p>
                <p class="price">Our Price: $<?= $product['price'];?></p>
                <button type="button" class="btn btn-sm btn-success" onclick="details(<?=$product['id'];?>)">
                    Details</button>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
    <?php
    
    include("includes/rightbar.php");
    include("includes/footer.php");
?>  