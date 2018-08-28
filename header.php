<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>inventory</title>
        <link href="bootstrap/css/bootstrap.css"  rel="stylesheet" />
        <script src="bootstrap/jquery/jquery.js"></script>
        <script src="bootstrap/js/bootstrap.js"></script>
        <script src="bootstrap/datatable/js/jquery.dataTables.js"></script>
        <script src="bootstrap/datatable/js/dataTables.bootstrap.js"></script>
        <link href="bootstrap/datatable/css/dataTables.bootstrap.css" rel="stylesheet" />
    </head>
    <body>
       <br />
       <div class="container">
           <h2 align="center">Inventory Management System</h2>
           <nav class="navbar navbar-inverse">
               <div class="container-fluid">
                   <div class="navbar-header">
                       <a href="index.php" class="navbar-brand">Home</a>
                   </div>
                   <ul class="nav navbar-nav">
                       <?php
                        if($_SESSION["type"]=='master')
                        {
                        ?>
                            <li><a href="user.php">User</a></li>
                            <li><a href="categories.php">Category</a></li>
                            <li><a href="brand.php">Brand</a></li>
                            <li><a href="product.php">Product</a></li>         
                        <?php
                        }
                       ?>
                        <li><a href="order.php">Order</a></li>
                   </ul>
                   <ul class="nav navbar-nav navbar-right">
                       <li class="dropdown"> 
                           <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                               <span class="label label-danger count"></span>
                               <?php echo $_SESSION["user_name"]; ?>
                           </a>
                           <ul class="dropdown-menu">
                               <li><a href="profile.php">Profile</a></li>
                               <li><a href="logout.php">Logout</a></li>
                           </ul>
                       </li>
                   </ul>
               </div>
           </nav>
