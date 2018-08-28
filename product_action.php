<?php
include 'connection.php';
include 'function.php';

if(isset($_POST["btn_action"]))
{
    if($_POST["btn_action"] == 'load_brand')
    {
        echo brand_list($connect,$_POST["cat_id"]);
    }
    
    if($_POST["btn_action"] == 'Add')
    {
        $qry = "insert into product(cat_id,brand_id,prd_name,prd_description,prd_quantity,prd_unit,prd_base_price,prd_tax,prd_minimum_ord,prd_enter_by,prd_status,prd_date)"
                . " values(:cat_id,:brand_id,:prd_name,:prd_description,:prd_quantity,:prd_unit,:prd_base_price,:prd_tax,:prd_minimum_ord,:prd_enter_by,:prd_status,:prd_date)";
        $statement = $connect->prepare($qry);
        $statement->execute([
            ':cat_id'           =>  $_POST['cat_id'],
            ':brand_id'         =>  $_POST['brand_id'],
            ':prd_name'         =>  $_POST["product_name"],
            ':prd_description'  =>  $_POST["product_desc"],
            ':prd_quantity'     =>  $_POST["product_quantity"],
            ':prd_unit'         =>  $_POST["product_unit"],
            ':prd_base_price'   =>  $_POST["product_base_price"],
            ':prd_tax'          =>  $_POST["product_tax"],
            'prd_minimum_ord'   =>  $_POST["product_base_price"],
            ':prd_enter_by'     =>  $_SESSION["user_id"],
            ':prd_status'       =>  'active',      
            ':prd_date'         => date('Y-m-d')
        ]);
        $result = $statement->fetchAll();
        if(isset($result))
        {
            echo 'Product Added';
        }
        else
        {
            echo 'Something Wrong';
        }
    }
    
    if($_POST["btn_action"] == 'product_details')
    {
        $qry = "select * from product inner join brand on brand.brand_id=product.brand_id inner join category on category.cat_id=product.cat_id inner join user_details on user_details.usr_id=product.prd_enter_by where prd_id =".$_POST["product_id"];
        $statement = $connect->prepare($qry);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $status = '';
        $output = '<table class="table table-striped" id="product_details">';
        foreach ($result as $row)
        {
            if($row['prd_status'] == 'active')
            {
                $status = '<span class="label label-success">Active</span>';
            }
            else
            {
                $status = '<span class="label label-danger">Inactive</span>';
            }
            $output.="<tr><td> Product Name</td><td>".$row['prd_name']."</td></tr>";
            $output.="<tr><td> Product Description</td><td>".$row['prd_description']."</td></tr>";
            $output.="<tr><td> Category</td><td>".$row['cat_name']."</td></tr>";
            $output.="<tr><td> Quantity</td><td>".$row['prd_quantity']."</td></tr>";
            $output.="<tr><td> unit</td><td>".ucfirst($row['prd_unit'])."</td></tr>";
            $output.="<tr><td> Base Price</td><td>".$row['prd_base_price']."</td></tr>";
            $output.="<tr><td> Tax(%)</td><td>".$row['prd_tax']."</td></tr>";
            $output.="<tr><td> Enter By</td><td>".$row['usr_name']."</td></tr>";
            $output.="<tr><td> Status</td><td>".$status."</td></tr>";
        }
        $output.='</table>';
        echo $output;
    }
    
    if($_POST["btn_action"] == 'fetch_single')
    {
        $qry = "select * from product where prd_id = :prd_id ";
        $statement = $connect->prepare($qry);
        $statement->execute([ ":prd_id" => $_POST['product_id'] ]);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row)
        {
            $output['cat_id']               =   $row['cat_id'];
            $output["brand_id"]             =   $row["brand_id"];
            $output["brand_select_box"]     =   brand_list($connect, $row["cat_id"]);
            $output['product_name']         =   $row['prd_name'];
            $output['product_description']  =   $row['prd_description'];
            $output["product_quantity"]     =   $row["prd_quantity"];
            $output['product_unit']         =   $row["prd_unit"];
            $output['product_base_price']   =   $row["prd_base_price"];
            $output['product_tax']          =   $row['prd_tax'];
        }
        echo json_encode($output);
    }
    
    if($_POST['btn_action'] == 'Delete')
    {
        $status = "active";
        if($_POST["status"] == 'active')
        {
            $status = 'inactive';
        }
        $qry = "update product set prd_status = '".$status."' where prd_id = ".$_POST['product_id']."";
        $statement = $connect->prepare($qry);
        $statement->execute();
        $result =$statement->fetch();
        if(isset($result))
        {
            echo "Status Change to $status";
        }         
    }
    
    if($_POST["btn_action"] == 'Edit')
    {
        $qry = "update product set cat_id=:cat_id,brand_id=:brand_id,prd_name=:prd_name,prd_description=:prd_description,prd_quantity=:prd_quantity,prd_unit=:prd_unit,prd_base_price=:prd_base_price,prd_tax=:prd_tax where prd_id=:prd_id";
        $statement = $connect->prepare($qry);
        $statement->execute([
            ':cat_id'           =>  $_POST['cat_id'],
            ':brand_id'         =>  $_POST['brand_id'],
            ':prd_name'         =>  $_POST["product_name"],
            ':prd_description'  =>  $_POST["product_desc"],
            ':prd_quantity'     =>  $_POST["product_quantity"],
            ':prd_base_price'   =>  $_POST["product_base_price"],
            ':prd_unit'         =>  $_POST["product_unit"],
            ':prd_tax'          =>  $_POST["product_tax"],
            ':prd_id'           =>  $_POST["product_id"],
        ]);
        $result = $statement->fetchAll();
        if(isset($result))
        {
            echo 'Product Edited';
        }
        else
        {
            echo 'Something Wrong';
        }
    }
}
?>

