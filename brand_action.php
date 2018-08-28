<?php

include 'connection.php';

if(isset($_POST['btn_action']))
{
    if($_POST['btn_action'] == 'Add')
    {
        $qry = "insert into brand (cat_id,brand_name) values (:cat_id,:brand_name)";
        $statement = $connect->prepare($qry);
        $statement->execute([
            ':cat_id'   =>  $_POST["cat_id"],
            ':brand_name' =>  $_POST["brand_name"]
        ]);
        $result = $statement->fetchAll();
        if(isset($result))
        {
            echo 'Brand Inserted Successfully ';
        }
        else 
        {
            echo 'Brand Not Inserted';
        }
    }
    
    if($_POST["btn_action"] == 'fetch_single')
    {
        $qry = "select * from  brand where brand_id = :brand_id";
        $statement =$connect->prepare($qry);
        $statement->execute([   ':brand_id' =>   $_POST["brand_id"]   ]);
        $result = $statement->fetch();
        echo json_encode($result);
    }
    
    if($_POST["btn_action"] == 'Edit')
    {
        $qry = "update brand set cat_id = :cat_id , brand_name = :brand_name where brand_id = :brand_id";
        $statement = $connect->prepare($qry);
        $statement->execute([
            ':cat_id'       =>  $_POST["cat_id"],
            ':brand_name'   =>  $_POST["brand_name"],
            ':brand_id'     =>  $_POST["brand_id"]
        ]);
        $result = $statement->fetch();
        if(isset($result))
        {
            echo 'Brand Updated Successfully';
        }
        else 
        {
            echo 'Brand Not Updated'; 
        }
    }
    
    if($_POST["btn_action"] == 'Delete')
    {
        $status = "active";
        if($_POST["status"] == 'active')
        {
            $status = 'inactive';
        }
        $qry = "update brand set brand_status = '".$status."' where brand_id = ".$_POST['brand_id']."";
        $statement = $connect->prepare($qry);
        $statement->execute();
        $result =$statement->fetch();
        if(isset($result))
        {
            echo "Status Change to $status";
        }         
    }
}


?>

