<?php
include_once 'connection.php';
if(isset($_POST["user_name"]))
{
    if($_POST["user_new_password"] !=='')
    {
        $qry = "update user_details set usr_name ='".$_POST["user_name"]."' , usr_email = '".$_POST["user_email"]."', usr_password ='".$_POST["user_new_password"]."' where usr_id = '".$_SESSION["user_id"]."' ";
    }
    else 
    {
        $qry = "update user_details set usr_name ='".$_POST["user_name"]."' , usr_email = '".$_POST["user_email"]."' where usr_id = '".$_SESSION["user_id"]."' ";
    }
    
    $statement = $connect->prepare($qry);
    $statement->execute();
    $result = $statement->fetchAll();
    if(isset($result))
    {
        echo "<div class='alert alert-success'>Profile Edited !</div>";
    }
    else 
    {
        echo "<div class='alert alert-danger'>Something Wrong</div>";
    }
}
?>
