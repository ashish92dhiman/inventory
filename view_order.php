<?php

if(isset($_GET['pdf']) && isset($_GET['order_id']))
{
    require 'pdf.php';
    require 'connection.php';
    require 'function.php';
    
    if(!isset($_SESSION['type']))
    {
        header("loaction:login.php");
    }
    
    $output = "";
    $statement = $connect->prepare('select * from inventory_order where iny_order_id = :iny_order_id');
    $statement->execute([
        ':iny_order_id' =>  $_GET['order_id']
    ]);
    $result = $statement->fetchAll();
    
    foreach ($result as $row)
    {
        $output .= '<table width="100%" border="1" cellpadding="5" cellspacing="0">'
                . '<tr>'
                . '<td colspan="2" align="center" style="font-size:18px"><b>Invoice</b></td>'
                . '</tr>'
                . '<tr>'
                . '<td colspan="2">'
                . '<table width="100%">'
                . '<tr>'
                . '<td width="65%">To <br><b>RECEIVER(Bill To)</b><br>Name : '.$row['iny_order_name'].'<br>Billing Address : '.$row['iny_order_address'].'</td>'
                . '<td width="35%">Reverse Charge<br>Invoice No. : '.$row['iny_order_id'].'<br>Invoice Date : '.$row['iny_order_date'].'<br></td>'
                . '</tr>'
                . '</table><br>'
                . '<table width="100%" border="1" cellpadding="5" cellspacing="0">'
                . '<tr>'
                . '<th rowspan="2">Sr No</th>'
                . '<th rowspan="2">Product</th>'
                . '<th rowspan="2">Quantity</th>'
                . '<th rowspan="2">Price</th>'
                . '<th rowspan="2">Actual Amt.</th>'
                . '<th colspan="2">Tax(%)</th>'
                . '<th rowspan="2">Total</th>'
                . '</tr>'
                . '<tr>'
                . '<th>Rate</th>'
                . '<th>Amt.</th>'
                . '</tr>'
                . '</td>';
        
        $statement = $connect->prepare('select * from inventory_order_product where iny_order_id = :iny_order_id');
        $statement->execute([
            ':iny_order_id' =>  $_GET['order_id']
        ]);
        $product_result = $statement->fetchAll();
        $count = 0;
        $total = 0;
        $total_actual_amount = 0;
        $total_tax_amount = 0;
        $actual_amount = 0;
        $tax_amount = 0;
        $total_product_amount  = 0;
        
        foreach ($product_result as $sub_row)
        {
            $count = $count + 1;
            $product_data = fetch_product_details($sub_row['product_id'], $connect);
            
            $actual_amount = $sub_row['price'] * $sub_row['quantity'];
            $tax_amount = ($actual_amount*$sub_row['tax'])/100;
            $total_product_amount = $actual_amount + $tax_amount;
            $total_tax_amount = $total_tax_amount + $tax_amount;
            $total_actual_amount = $total_actual_amount + $actual_amount;
            $total =$total + $total_product_amount;
            
            $output .='<tr>'
                    . '<td>'.$count.'</td>'
                    . '<td>'.$product_data['product_name'].'</td>'
                    . '<td>'.$sub_row['quantity'].'</td>'
                    . '<td>'.$sub_row['price'].'</td>'
                    . '<td>'.  number_format($actual_amount,2).'</td>'
                    . '<td>'.$product_data['tax'].'%</td>'
                    . '<td>'.  number_format($tax_amount,2).'</td>'
                    . '<td>'.  number_format($total).'</td>'
                    . '</tr>';
            
        }
        
        $output .='<tr>'
                . '<td align="right" colspan="4"><b>Total</b></td>'
                . '<td align="right"><b>'.number_format($total_actual_amount,2).'</b></td>'
                . '<td>&nbsp;</td>'
                . '<td align="right"><b>'.number_format($total_tax_amount,2).'</b></td>'
                . '<td align="right"><b>'.number_format($total,2).'</b></td>'
                . '</tr>';
        
        $output .='</table>'
                . '<br><br><br><br><br>'
                . '<p align="right">-----------------------------------------------------'
                . '<br>Receiver Signature</p>'
                . '</br></br></br>'
                . '</td>'
                . '</tr>'
                . '</table>';
    }
    
    $pdf =new Pdf();
    $file_name = 'Order-'.$row['iny_order_id'].'pdf';
    $pdf->loadHtml($output);
    $pdf->setPaper('A4');
    $pdf->render();
    $pdf->stream($file_name,array('Attachment'=>false));
}

?>