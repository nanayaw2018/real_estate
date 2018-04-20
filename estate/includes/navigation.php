<?php

$sql = "SELECT * FROM category WHERE parent = 0";
$result = $db->query($sql);

// if(isset($_POST['search'])){
//     if(isset($_POST['search_query']) && !empty($_POST['search_query'])){
//         $search = $_POST['search_query']);
//         $ssql = "SELECT * FROM "
//     }
// }

?>

<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <a href="index.php" class="navbar-brand">Real Estate</a>
        <ul class="nav navbar-nav">
            <?php while($parent = mysqli_fetch_assoc($result)) : ?>
            <?php 
            
            $parent_id = $parent['id']; 
            
            $sql2 = "SELECT * FROM category WHERE parent = '$parent_id'";
            $cquery = $db->query($sql2);
            
            ?>
            <!-- Menu Items -->
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?=$parent["category"];?><span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <?php while($child = mysqli_fetch_assoc($cquery)) : ?>
                    <li><a href="category.php?cat=<?=$child['id'];?>"><?=$child["category"];?></a></li>
                    <?php endwhile; ?>
                </ul>
            </li>
            <?php endwhile; ?>
        </ul>
        <div class="col-sm-3 col-md-3">
            <form class="navbar-form" role="search">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search" name="search_query">
                <div class="input-group-btn">
                    <button class="btn btn-default" type="submit" name="search"><i class="glyphicon glyphicon-search"></i></button>
                </div>
            </div>
            </form>
        </div>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="cart.php"><span class="glyphicon glyphicon-shopping-cart"></span>  My Cart</a></li>
            <li><a href="admin/login.php"><span class="glyphicon glyphicon-log-in"></span>  Login</a></li>
            <li><a href="admin/register.php"><span class="glyphicon glyphicon-log-in"></span>  Register</a></li>
        </ul>
    </div>
</nav>