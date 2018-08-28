<?php
include 'connection.php';
include 'function.php';

if(!isset($_SESSION["type"]))
{
    header("location:login.php");
}

include 'header.php';
?>
<span id="alert_action"></span>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                <div class="col-md-10"><h4>Order List</h4></div>
                <div class="col-md-2" >
                    <button type="button" name="add" id="add_button" class="btn btn-primary" data-toggle="modal" data-target="#order_modal"> Add </button>
                </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12 table-responsive">
                        <table class="table table-bordered table-striped" id="order_data"> 
                            <thead>
                                <tr>
                                    <th>Order Id</th>
                                    <th>Customer Name</th>
                                    <th>Total Amount</th>
                                    <th>Payment Status</th>
                                    <th>Order Status</th>
                                    <th>Order Date</th>
                                    <?php
                                        if($_SESSION["type"]=='master')
                                        {
                                            echo '<th>Created By</th>';
                                        }
                                    ?>
                                    <th>View PDF</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $qry = "select * from inventory_order order by iny_order_id desc";
                                $statement = $connect->prepare($qry);
                                $statement->execute();
                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                $status = '';
                                $payment_status = '';
                                foreach ($result as $row)
                                {
                                    if($row['iny_order_status'] == 'active')
                                    {
                                        $status = '<span class="label label-success">Active</span>';
                                    }
                                    else
                                    {
                                        $status = '<span class="label label-danger">Inactive</span>';
                                    }
                                    if($row['payment_status'] == 'cash')
                                    {
                                        $payment_status = '<span class="label label-info">Cash</span>';
                                    }
                                    else
                                    {
                                        $payment_status = '<span class="label label-primary">Credit</span>';
                                    }
                                    echo '<tr>';
                                    echo '<td>'.$row['iny_order_id'].'</td>';
                                    echo '<td>'.get_user($connect,$row['usr_id']).'</td>';
                                    echo '<td>'.$row['iny_order_total'].'</td>';
                                    echo '<td>'.$payment_status.'</td>';
                                    echo '<td>'.$status.'</td>';
                                    echo '<td>'.$row['iny_order_create_date'].'</td>';
                                    if($_SESSION["type"]=='master')
                                    {
                                        echo '<td>'.$_SESSION['user_name'].'</td>';
                                    }
                                    echo '<td><a href="view_order.php?order_id='.$row['iny_order_id'].'&pdf=pdf" class="btn btn-primary btn-xs">View pdf</a></td>';
                                    echo '<td><button type=button name=update id='.$row['iny_order_id'].' class="btn btn-warning btn-xs update">Update</button></td>';
                                    echo '<td><button type="button" name="delete" class="btn btn-danger btn-xs delete" data-status='.$row['iny_order_status'].' id='.$row["iny_order_id"].'>Delete</button></td>';
                                    echo '</tr>';
                                    }
                                ?>
                            </tbody>
                        </table>    
                    </div>
                </div>
            </div>
        </div>
    </div>    
</div>


<div class="modal fade" id="order_modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
        <form method="post" id="order_form" >
            <div class="modal-header">
                <button class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-plus"> Create Order </i></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Enter Receiver Name</label>
                        <input type="text" name="inventory_order_name" id="inventory_order_name" required class="form-control" /> 
                    </div>
                    <div class="form-group col-md-6">
                        <label>Enter Date</label>
                        <input type="date" name="inventory_order_date" id="inventory_order_date" required  class="form-control" /> 
                    </div>
                </div>
                <div class="form-group">
                    <label>Enter Receiver Address</label>
                    <textarea name="inventory_order_address" id="inventory_order_address" required class="form-control" ></textarea> 
                </div>
                <div class="form-group">
                    <label>Enter Product Details</label>
                    <hr><span id="span_product_details"></span><hr>
                </div>
                <div class="form-group">
                    <label>Payment Status</label>
                    <select name="payment_status" id="payment_status" class="form-control">
                        <option value="cash">Cash</option>
                        <option value="credit">Credit</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="inventory_order_id" id="inventory_order_id" />
                <input type="hidden" name="btn_action" id="btn_action" value="Add" />
                <input type="submit" name="action" id="action" class="btn btn-info" value="Add" />
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </form>
        </div>
    </div>
</div>


<script>
    $(document).ready(function (){
       var OrderTable = $('#order_data').dataTable({
            'processing':true,
            'pageLength':10,
            'order':[]
        });
        
        $('#add_button').click(function (){
            //alert('hii');
            add_product_row(count);
        });
        
        function add_product_row( count = '')
        {
            var html = '';
            html +='<span id="row'+count+'"><div class="row">';
            html +='<div class="col-md-8">';
            html +='<select name="product_id[]" id="product_id'+count+'" class="form-control" require >';
            html +='<?php echo product_list($connect);  ?>';
            html +='</select>';
            html +='<input type="hidden" name="hidden_product_id[]" id="hidden_product_id'+count+'" >';
            html +='</div><div class="col-md-3">';
            html +='<input type="text" name="quantity[]" class="form-control" require >';
            html +='</div>';
            html +='<div class="col-md-1">';
            if(count == '')
            {
                html +='<button type="button" id="add_more" name="add_more" class="btn btn-success btn-xs">+</button>';
            }
            else 
            {
                html +='<button type="button" id='+count+' name="remove" class="btn btn-danger btn-xs remove">-</button>';
            }
            html +="</div>";
            html +="</div><br></span>";
            $('#span_product_details').append(html);
        }
        
        var count = 0;
        
        $(document).on('click','#add_more',function (){
            count = count + 1;
            add_product_row(count);
        });
        $(document).on('click','.remove',function (){
            var row_no = $(this).attr('id');
            $('#row'+row_no).remove();
        });
        
        $(document).on('submit','#order_form',function (e){
            e.preventDefault();
            $('#action').attr('disabled','disabled');
            var form_data = $('#order_form').serialize();
            //alert(form_data);
            $.ajax({
                url:'order_action.php',
                method:'post',
                data:form_data,
                success: function (data)
                {
                    $('#order_modal').modal('hide');
                    $('#alert_action').fadeIn().html('<div class="alert alert-success">'+data+'</div>');
                    $('#action').attr('disabled',false);
                    //OrderTable.ajax.reload();
                }
            });
        });
        
        $(document).on('click','.update',function (){
            var inventory_order_id = $(this).attr('id');
            var btn_action = 'fetch_single';
            $.ajax({
                url:'order_action.php',
                method:'POST',
                data:{inventory_order_id:inventory_order_id,btn_action:btn_action},
                dataType: 'json',
                success: function (data) {
                        $('#order_modal').modal('show');
                        $('#inventory_order_name').val(data.inventory_order_name);
                        $('#inventory_order_date').val(data.inventory_order_date);
                        $('#inventory_order_address').val(data.inventory_order_address);
                        $('#payment_status').val(data.payment_status);
                        $('#span_product_details').html(data.product_details);
                        $('.modal-title').html("<i class='fa fa-plus'>Edit Order</i>");
                        $('#inventory_order_id').val(inventory_order_id);
                        $('#action').val('Edit');
                        $('#btn_action').val('Edit');
                    }
            });
        });
        
         $(document).on('click','.delete',function(){
            var inventory_order_id = $(this).attr('id');
            var status = $(this).data('status');
            var btn_action = 'Delete';
            if(confirm('Are you sure you want to change status'))
            {
                $.ajax({
                    url:'order_action.php',
                    method:'post',
                    data:{inventory_order_id:inventory_order_id,status:status,btn_action:btn_action},
                    success: function (data) {
                        $('#alert_action').fadeIn().html('<div class="alert alert-info">'+data+'</div>');
                        OrderTable.ajax.reload();
                    }
                });
            }
            else
            {
                return false;
            }
        });
        
    });
</script>

<?php  include 'footer.php';   ?>