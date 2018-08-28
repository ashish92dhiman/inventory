<?php
include_once 'connection.php';

function category_list($connect)
{
    $qry = "select * from category where cat_status = 'active' order by cat_name asc";
    $statement = $connect->prepare($qry);
    $statement->execute();
    $result = $statement->fetchAll();
    $output = "";
    foreach ($result as $row)
    {
        $output.="<option value=".$row['cat_id'].">".$row['cat_name']."</option>";
    }
    return $output;
}

function brand_list($connect,$cat_id)
{
    $qry = "select * from brand where brand_status = 'active' and cat_id ='".$cat_id."' order by brand_name asc";
    $statement = $connect->prepare($qry);
    $statement->execute();
    $result = $statement->fetchAll();
    $output = "";
    foreach ($result as $row)
    {
        $output.="<option value=".$row['brand_id'].">".$row['brand_name']."</option>";
    }
    return $output;
}

function get_user($connect,$usr_id)
{
    $qry = "select * from user_details where usr_status = 'active' and usr_id ='".$usr_id."'";
    $statement = $connect->prepare($qry);
    $statement->execute();
    $result = $statement->fetchAll();
    $output = "";
    foreach ($result as $row)
    {
        $output.=$row['usr_name'];
    }
    return $output;    
}

function product_list($connect)
{
    $qry = "select * from product where prd_status = 'active' order by prd_name asc";
    $statement = $connect->prepare($qry);
    $statement->execute();
    $result = $statement->fetchAll();
    $output = "";
    foreach ($result as $row)
    {
        $output.="<option value=".$row['prd_id'].">".$row['prd_name']."</option>";
    }
    return $output;    
}

function fetch_product_details($product_id,$connect)
{
    $qry = "select * from product where prd_id ='".$product_id."' ";
    $statement = $connect->prepare($qry);
    $statement->execute();
    $result = $statement->fetchAll();
    $output = "";
    foreach ($result as $row)
    {
       $output['product_name']  =  $row['prd_name'];
       $output['quantity']  =  $row['prd_quantity'];
       $output['price']  =  $row['prd_base_price'];
       $output['tax']  =  $row['prd_tax'];
    }
    return $output;  
}

function count_total_user($connect)
{
    $qry = 'select * from user_details where usr_status ="active"';
    $statement = $connect->prepare($qry);
    $statement->execute();
    return $statement->rowCount();
}

function count_total_category($connect)
{
    $qry = 'select * from category where cat_status ="active"';
    $statement = $connect->prepare($qry);
    $statement->execute();
    return $statement->rowCount();
}

function count_total_brand($connect)
{
    $qry = 'select * from brand where brand_status ="active"';
    $statement = $connect->prepare($qry);
    $statement->execute();
    return $statement->rowCount();
}

function count_total_product($connect)
{
    $qry = 'select * from product where prd_status ="active"';
    $statement = $connect->prepare($qry);
    $statement->execute();
    return $statement->rowCount();
}

function count_total_order_value($connect)
{
    $qry = "select sum(iny_order_total) as total_order_value from inventory_order where iny_order_status ='active'";
    if($_SESSION['type'] == 'user')
    {
        $qry .='AND usr_id ="'.$_SESSION['user_id'].'"';
    }
    $statement = $connect->prepare($qry);
    $statement->execute();
    $result = $statement->fetchAll();
    foreach ($result as $row)
    {
        return number_format($row['total_order_value'],2);
    }
}

function count_total_cash_order_value($connect)
{
    $qry = "select sum(iny_order_total) as total_order_value from inventory_order where payment_status = 'cash' AND iny_order_status = 'active' ";
    if($_SESSION['type'] == 'user')
    {
        $qry .='AND usr_id ="'.$_SESSION['user_id'].'"';
    }
    $statement =$connect->prepare($qry);
    $statement->execute();
    $result = $statement->fetchAll();
    foreach ($result as $row)
    {
        return number_format($row['total_order_value'],2);
    }
}

function count_total_credit_order_value($connect)
{
    $qry = "select sum(iny_order_total) as total_order_value from inventory_order where payment_status = 'credit' AND iny_order_status = 'active' ";
    if($_SESSION['type'] == 'user')
    {
        $qry .='AND usr_id ="'.$_SESSION['user_id'].'"';
    }
    $statement =$connect->prepare($qry);
    $statement->execute();
    $result = $statement->fetchAll();
    foreach ($result as $row)
    {
        return number_format($row['total_order_value'],2);
    }
}

function get_user_wise_total_order($connect)
{
    $qry = "SELECT inventory_order.usr_id,sum(inventory_order.iny_order_total) as total_order_value,"
           . "sum(case when inventory_order.payment_status = 'cash'then inventory_order.iny_order_total else 0 end) as cash_order_value,"
           . "sum(case when inventory_order.payment_status = 'credit'then inventory_order.iny_order_total else 0 end) as credit_order_value,"
           . "user_details.usr_name "
           . "FROM `inventory_order` inner join user_details on inventory_order.usr_id = user_details.usr_id "
           . "where inventory_order.iny_order_status ='active' group by inventory_order.usr_id";
    $statement =$connect->prepare($qry);
    $statement->execute();
    $result = $statement->fetchAll();
    $output = '<div class="table-responsive">'
            . '<table class="table table-bordered table-striped">'
            . '<tr>'
            . '<th>User Name</th>'
            . '<th>Total Order Value</th>'
            . '<th>Total Cash Order</th>'
            . '<th>Total Credit Order</th>'
            . '</tr>';
    $total_order = 0;
    $total_cash_order = 0;
    $total_credit_order = 0;
    foreach ($result as $row)
    {
        $output .= '<tr>'
                . '<td>'.$row['usr_name'].'</td>'
                . '<td>$'.$row['total_order_value'].'</td>'
                . '<td>$'.$row['cash_order_value'].'</td>'
                . '<td>$'.$row['credit_order_value'].'</td>'
                . '</tr>';
        
        $total_order = $total_order + $row['total_order_value'];
        $total_cash_order = $total_cash_order + $row['cash_order_value'];
        $total_credit_order =$total_credit_order + $row['credit_order_value'];
    }
    
    $output .='<tr>'
            . '<td align="right"><b>Total Order</b></td>'
            . '<td align="right">$'.number_format($total_order,2).'</td>'
            . '<td align="right">$'.number_format($total_cash_order,2).'</td>'
            . '<td align="right">$'.number_format($total_credit_order,2).'</td>'
            . '</tr>'
            . '</table>'
            . '</div>';
    return $output;
}

?>