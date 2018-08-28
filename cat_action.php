<?php
include 'connection.php';

if(isset($_POST["btn_action"]))
{
    if($_POST["btn_action"] == 'Add')
    {
        $qry = 'insert into category(cat_name) values(:cat_name)';
        $statement = $connect->prepare($qry);
        $statement->execute([
            ':cat_name' =>  $_POST["cat_name"]
        ]);
        $result = $statement->fetchAll();
        if(isset($result))
        {
            echo 'catogry added in the list';
        }
        else 
        {
            echo 'category not added';
        }
    }
    
    if($_POST["btn_action"] == 'fetch_single')
    {
       $qry = "select * from category where cat_id = :cat_id ";
       $statement = $connect->prepare($qry);
       $statement->execute([ ":cat_id" => $_POST['cat_id'] ]);
       $result = $statement->fetch(PDO::FETCH_ASSOC);
       echo json_encode($result);
    }
    
    if($_POST["btn_action"] == 'Edit')
    {
        $qry = "update category set cat_name = :cat_name where cat_id = :cat_id ";
        $statement = $connect->prepare($qry);
        $statement->execute(
                    [
                        ':cat_name' =>  $_POST["cat_name"],
                        ':cat_id'   =>  $_POST["cat_id"]
                    ]
                );    
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if(isset($result))
        {
            echo 'Category Edited';
        }
        else
        {
            echo 'Something worng here';
        }
    }
    
    if($_POST["btn_action"] == 'Delete')
    {
        //print_r($_POST);
        $status = 'active';
        if($_POST["status"] == 'active')
        {
            $status = 'inactive';
        }
        $qry = "update category set cat_status='".$status."' where cat_id=".$_POST["cat_id"]."";
        $statement = $connect->prepare($qry);
        $statement->execute();
        $result=$statement->fetchAll();
        if(isset($result))
        {
            echo 'User changes to '.$status;
        }
    }
    
}
?>
