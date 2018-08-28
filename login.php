<?php
include 'connection.php';
if(isset($_SESSION["type"]))
{
    header("location:index.php");
}

$message='';
if(isset($_POST["login"]))
{
    $qry="select * from user_details where usr_email = :user_email";
    $statement = $connect->prepare($qry);
    $statement->execute([
             ":user_email"=>$_POST["email"]
            ]);
    $count = $statement->rowCount();
    if($count>0)
    {
        $result = $statement->fetchAll();
        foreach ($result as $row)
        {
            if($_POST["password"] == $row["usr_password"])
            {
                if($row["usr_status"] == 'active')
                {
                    $_SESSION["type"] = $row["usr_type"];
                    $_SESSION["user_id"] = $row["usr_id"];
                    $_SESSION["user_name"] = $row["usr_name"];
                    header("location:index.php");
                }
                else 
                {
                    $message = "<label>Your Account Is Disable, Contact To The Master  </label>";
                }
            }
            else 
            {
                $message = "<label>Password Do Not Match</label>";
            }
        }
    }
    else 
    {
        $message = "<label>Wrong Email Address </label>";
    }
}
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link href="bootstrap/css/bootstrap.css" rel="stylesheet" />
    </head>
    <body>
        <br />
        <div class="container">
            <h2 align="center">Inventory Management System</h2> <br />
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-primary">
                    <div class="panel-heading">Login</div>
                    <div class="panel-body">
                        <form action="login.php" method="post">
                            <?php if(isset($message)) echo $message;  ?>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" placeholder="Enter Email Id" class="form-control" required />
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" placeholder="Enter Password " class="form-control" required />
                            </div>
                            <div class="form-group">
                                <input type="submit" name="login" value="Login" class="btn btn-primary" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
