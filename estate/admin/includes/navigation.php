<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
      <a href="index.php" class="navbar-brand">Real Estate</a>
        <div class="collapse navbar-collapse" id="myNavbar">
        <ul class="nav navbar-nav">
            <!-- Menu Items -->
            <li class="<?=((basename($_SERVER['PHP_SELF']) == "brands.php")?'active':'');?>"><a href="brands.php">Brands</a></li>
            <li class="<?=((basename($_SERVER['PHP_SELF']) == "categories.php")?'active':'');?>"><a href="categories.php?">Categories</a></li>
            <li class="<?=((basename($_SERVER['PHP_SELF']) == "products.php")?'active':'');?>"><a href="products.php">Products</a></li>
            <li><a href="../index.php">Home Page</a></li>
            <?php if(has_permission('admin')): ?>
            <li class="<?=((basename($_SERVER['PHP_SELF']) == "users.php")?'active':'');?>"><a href="users.php">Users</a></li>
            <?php endif; ?>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown pull-right">
                 <a href="brands.php" class="dropdown-toggle" data-toggle="dropdown"><?=$user_data['first'];?> <span class="caret"></span></a>
                 <ul class="dropdown-menu" role="menu">
                     <li><a href="change_password.php">Change Password</a></li>
                     <li><a href="logout.php">Log Out</a></li>
                 </ul>
            </li>
        </ul>
    </div>
</nav>