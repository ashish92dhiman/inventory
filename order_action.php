<?php
include 'connection.php';
include 'function.php';

if(isset($_POST["btn_action"]))
{
  if($_POST['btn_action'] == "Add")
  {
      $qry = "insert into inventory_order(usr_id,iny_order_total,iny_order_date,iny_order_name,iny_order_address,payment_status,iny_order_status,iny_order_create_date)"
              ."values(:usr_id,:iny_order_total,:iny_order_date,:iny_order_name,:iny_order_address,:payment_status,:iny_order_status,:iny_order_create_date)";
      $statement = $connect->prepare($qry);
      $statement->execute([
          ':usr_id'             =>  $_SESSION["user_id"],
          ':iny_order_total'    =>  0,
          ':iny_order_date'     =>  $_POST["inventory_order_date"],
          ':iny_order_name'     =>  $_POST["inventory_order_name"],
          ':iny_order_address'  =>  $_POST["inventory_order_address"],
          ':payment_status'     =>  $_POST["payment_status"],
          ':iny_order_status'   =>  'active',
          ':iny_order_create_date'  => date('Y-m-d')
      ]);
      $result = $statement->fetchAll();
      $statement = $connect->prepare('select LAST_INSERT_ID()');
      $statement->execute();
      $inventory_order_id = $statement->fetchColumn();
      //echo $inventory_order_id;
      if(isset($inventory_order_id))
      {
          $total_amount = 0;
          for($i=0;$i<count($_POST['product_id']);$i++)
          {
              $product_details = fetch_product_details($_POST['product_id'][$i], $connect);
              $sub_qry = "insert into inventory_order_product values(:iny_order_product_id,:iny_order_id,:product_id,:quantity,:price,:tax)";
              //$qry = $connect->query($sub_qry);            
              $statement = $connect->prepare($sub_qry);
              $statement->execute([
                  ':iny_order_product_id'   =>  null,
                  ':iny_order_id'   =>  $inventory_order_id,
                  ':product_id'     =>  $_POST['product_id'][$i],
                  ':quantity'       =>  $_POST['quantity'][$i],
                  ':price'          =>  $product_details['price'],
                  ':tax'            =>  $product_details['tax'],
              ]);
              $base_price = $product_details['price']*$_POST['quantity'][$i];
              $tax = ($base_price/100)*$product_details['tax'];
              $total_amount = $total_amount + ($base_price + $tax);
          }
          $update_qry = "update inventory_order set iny_order_total = '".$total_amount."' where iny_order_id = '".$inventory_order_id."'";
          $statement = $connect->prepare($update_qry);
          $statement->execute();
          $result = $statement->fetchAll();
          if(isset($result))
          {
              echo 'order created....';
          }
          else 
          {
              echo 'order not cretaed';
          }
      }
  }
  
  if($_POST['btn_action'] == 'fetch_single')
  {
      $qry = "select * from inventory_order where iny_order_id = :iny_order_id";
      $statement = $connect->prepare($qry);
      $statement->execute([
          ':iny_order_id'   =>  $_POST["inventory_order_id"]
      ]);
      $result = $statement->fetchAll();
      $output = [];
      foreach ($result as $row)
      {
          $output['inventory_order_name'] = $row['iny_order_name'];
          $output['inventory_order_date'] = $row['iny_order_date'];
          $output['inventory_order_address'] = $row['iny_order_address'];
          $output['payment_status'] = $row['payment_status'];
      }
      $sub_qry = "select * from inventory_order_product where iny_order_id = '".$_POST['inventory_order_id']."'";
      $statement = $connect->prepare($sub_qry);
      $statement->execute();
      $sub_result = $statement->fetchAll();
      $product_details = '';
      $count='';
      foreach ($sub_result as $sub_row)
      {
          $product_details .='<sapn id="row'.$count.'"><div class="row">'
                  . '<div class="col-md-8">'
                  . '<select name="product_id[]" id="product_id'.$count.'" class="form-control" require >'
                  . product_list($connect)
                  . '</select>'
                  . '<input type="hidden" name="hidden_product_id[]" id="hidden_product_id'.$count.'" value="'.$sub_row['product_id'].'" >'
                  . '</div><div class="col-md-3">'
                  . '<input type="text" name="quantity[]" class="form-control" require value="'.$sub_row['quantity'].'" >'
                  . '</div><div class="col-md-1">';
          
          if($count == "")
          {
              $product_details .= '<button type="button" id="add_more" name="add_more" class="btn btn-success btn-xs">+</button>';
          }
          else 
          {
              $product_details .= '<button type="button" id='.$count.' name="remove" class="btn btn-danger btn-xs remove">-</button>';
          }
          $product_details .= '</div></div><br></sapan>';
          $count =$count + 1 ;
      }
      $output['product_details'] = $product_details;
      echo json_encode($output);
  }
  
  
  if($_POST['btn_action'] == 'Edit')
  {
      $delete_qry = "delete from inventory_order_product where iny_order_id = '".$_POST['inventory_order_id']."'";
      $statement = $connect->prepare($delete_qry);
      $statement->execute();
      
      $delete_result = $statement->fetchAll();
      if(isset($delete_result))
      {
          $total_amount = 0;
          for($i=0;$i<count($_POST['product_id']);$i++)
          {
              $product_details = fetch_product_details($_POST["product_id"][$i], $connect);
              $sub_qry = "insert into inventory_order_product(iny_order_id,product_id,quantity,price,tax) values(:iny_order_id,:product_id,:quantity,:price,:tax)";
              $statement = $connect->prepare($sub_qry);
              $statement->execute([
                  ':iny_order_id'   =>  $_POST['inventory_order_id'],
                  ':product_id'     =>  $_POST['product_id'][$i],
                  ':quantity'       =>  $_POST['quantity'][$i],
                  ':price'          =>  $product_details['price'],
                  ':tax'            =>  $product_details['tax'],
              ]);
              $base_price = $product_details['price']*$_POST['quantity'][$i];
              $tax = ($base_price/100)*$product_details['tax'];
              $total_amount = $total_amount + ($base_price + $tax);
              
              $update_qry = "update inventory_order set iny_order_name = :iny_order_name,iny_order_date = :iny_order_date,iny_order_address = :iny_order_address,iny_order_total = :iny_order_total,payment_status = :payment_status where iny_order_id = :iny_order_id";
              $statement = $connect->prepare($update_qry);
              $statement->execute([
                  ':iny_order_name' =>  $_POST['inventory_order_name'],
                  ':iny_order_date' =>  $_POST['inventory_order_date'],
                  ':iny_order_address' =>  $_POST['inventory_order_address'],
                  ':iny_order_total' =>  $total_amount,
                  ':payment_status' =>  $_POST['payment_status'],
                  ':iny_order_id'   =>  $_POST['inventory_order_id']
              ]);
              $result = $statement->fetchAll();
          }
          if(isset($result))
          {
              echo 'order Edited';
          }
      }
      else 
      {
          echo 'record not deleted';
      }
  }
  
  if($_POST['btn_action'] == 'Delete')
  {
      $status = 'active';
      if($_POST['status'] == 'active')
      {
          $status = 'inactive';
      }
      $qry = "update inventory_order set iny_order_status = :status where iny_order_id = :iny_order_id";
      $statement = $connect->prepare($qry);
      $statement->execute([
          ':status' =>  $status,
          ':iny_order_id'   =>  $_POST["inventory_order_id"],
      ]);
      $result = $statement->fetchAll();
      if(isset($result))
      {
          echo "status change to $status";
      }
  }
  
}

?>