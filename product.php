<?php
include 'connection.php';
include 'function.php';

if(!isset($_SESSION["type"]))
{
    header("location:login.php");
}
if($_SESSION["type"]!='master')
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
                <div class="col-md-10"><h4>Product List</h4></div>
                <div class="col-md-2" >
                    <button type="button" name="add" id="add_button" class="btn btn-primary" data-toggle="modal" data-target="#product_modal"> Add </button>
                </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12 table-responsive">
                        <table class="table table-bordered table-striped" id="product_data"> 
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Category</th>
                                    <th>Brand</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Enter By</th>
                                    <th>Status</th>
                                    <th>View</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                                <?php
                                    $qry = "select * from product inner join brand on brand.brand_id=product.brand_id inner join category on category.cat_id=product.cat_id inner join user_details on user_details.usr_id=product.prd_enter_by order by prd_id desc";
                                    $statement = $connect->prepare($qry);
                                    $statement->execute();
                                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                    $status = '';
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
                                        echo '<tr>';
                                        echo '<td>'.$row['prd_id'].'</td>';
                                        echo '<td>'.$row['cat_name'].'</td>';
                                        echo '<td>'.$row['brand_name'].'</td>';
                                        echo '<td>'.$row['prd_name'].'</td>';
                                        echo '<td>'.$row['prd_quantity'].'</td>';
                                        echo '<td>'.ucwords($row['usr_name']).'</td>';
                                        echo '<td>'.$status.'</td>';
                                        echo '<td><button type="button" name="view" class="btn btn-info btn-xs view" data-toggle="modal" data-target="#product_details_modal" id='.$row["prd_id"].'>View</button></td>';
                                        echo '<td><button type=button name=update id='.$row['prd_id'].' class="btn btn-warning btn-xs update">Update</button></td>';
                                        echo '<td><button type="button" name="delete" class="btn btn-danger btn-xs delete" data-status='.$row['prd_status'].' id='.$row["prd_id"].'>Delete</button></td>';
                                        echo '</tr>';
                                    }
                                ?>
                        </table>    
                    </div>
                </div>
            </div>
        </div>
    </div>    
</div>

<div class="modal fade" id="product_modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
        <form method="post" id="product_form" >
            <div class="modal-header">
                <button class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-plus"> Add Product </i></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Select Category</label>
                    <select name="cat_id" id="cat_id" class="form-control">
                        <option value=""> Select Category </option>
                        <?= category_list($connect); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Select Brand</label>
                    <select name="brand_id" id="brand_id" class="form-control">
                        <option value=""> Select Brand </option>
                        
                    </select>
                </div>
                <div class="form-group">
                    <label>Enter Product Name</label>
                    <input type="text" name="product_name" id="product_name" required class="form-control" /> 
                </div>
                <div class="form-group">
                    <label>Enter Product Description</label>
                    <textarea type="text" name="product_desc" id="product_desc" required class="form-control" ></textarea> 
                </div>
                <div class="form-group">
                    <label>Enter Product Quantity</label>
                    <input type="text" name="product_quantity" id="product_quantity" class="form-control" required pattern="[+-]?([0-9]*[.])?[0-9]+" />
                    <span class="input-group-addon">
                        <select name="product_unit" required id="product_unit" class="form-control">
                            <option value="">Select Unit</option>
                            <option value="bags">Bags</option>
                            <option value="bottles">Bottles</option>
                            <option value="box">Box</option>
                            <option value="dozens">Dozens</option>
                            <option value="feet">Feet</option>
                            <option value="gallon">Gallon</option>
                            <option value="kg">Kg</option>
                            <option value="inch">Inch</option>
                            <option value="liters">Liters</option>
                            <option value="nos">Nos</option>
                            <option value="pakets">Packets</option>
                        </select>
                    </span>
                </div>
                <div class="form-group">
                    <label>Enter Product Base Price</label>
                    <input type="text" name="product_base_price" id="product_base_price" class="form-control" required pattern="[+-]?([0-9]*[.])?[0-9]+" />
                </div>
                <div class="form-group">
                    <label>Enter Product Tax(%)</label>
                    <input type="text" name="product_tax" id="product_tax" class="form-control" required pattern="[+-]?([0-9]*[.])?[0-9]+" />
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="product_id" id="product_id" />
                <input type="hidden" name="btn_action" id="btn_action" value="Add" />
                <input type="submit" name="action" id="action" class="btn btn-info" value="Add" />
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </form>
        </div>
    </div>
</div>

<div class="modal fade" id="product_details_modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-plus"> Product Details </i></h4>
            </div>
            <div class="modal-body">
                <div id="product_details">
                    
                </div>
            </div>
            <div class="modal-footer">   
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function(){
        var dataTable = $('#product_data').dataTable({
            'processing':true,
            'pageLength':10,
            'order':[]
        });
        
        $('#cat_id').change(function (){
            var cat_id = $(this).val();
            var btn_action = 'load_brand';
            $.ajax({
                url:"product_action.php",
                method:'POST',
                data:{cat_id:cat_id,btn_action:btn_action},
                success: function (data) {
                    $('#brand_id').html(data);
                }
            });
        });
        
        $(document).on('submit','#product_form',function (e){
            e.preventDefault();
            $('#action').attr('disabled','disabled');
            var form_data = $(this).serialize();
            $.ajax({
                url:'product_action.php',
                method:'POST',
                data:form_data,
                success:function (data)
                {
                    $('#product_form')[0].reset();
                    $('#product_modal').modal('hide');
                    $('#alert_action').fadeIn().html('<div class="alert alert-success">'+data+'</div>');
                    $("#action").attr('disabled',false);
                    dataTable.ajax.reload();
                }
            });
        });
        
        $(document).on('click','.view',function (){
            var product_id = $(this).attr('id');
            var btn_action = 'product_details';
            $.ajax({
                url:'product_action.php',
                method:"POST",
                data:{product_id:product_id,btn_action:btn_action},
                success: function (data)
                {
                    $('#product_details_modal').modal('show');
                    $('#product_details').html(data);
                }
            });
        });
        
        $(document).on('click','.update',function(){
            var product_id = $(this).attr('id');
            var btn_action = 'fetch_single';
            $.ajax({
                url:'product_action.php',
                method:'POST',
                data:{product_id:product_id,btn_action:btn_action},
                dataType: 'json',
                success: function (data)               
                {                     
                    $('#product_modal').modal('show');
                    $('.modal-title').html('<i class="fa fa-plus"> Update Product </i>');
                    $('#cat_id').val(data.cat_id);
                    $('#brand_id').val(data.brand_id);
                    $('#brand_id').html(data.brand_select_box);
                    $('#product_name').val(data.product_name);
                    $('#product_desc').val(data.product_description);
                    $('#product_quantity').val(data.product_quantity);
                    $('#product_unit').val(data.product_unit);
                    $('#product_base_price').val(data.product_base_price);
                    $('#product_tax').val(data.product_tax);
                    $('#product_id').val(product_id);
                    $('#btn_action').val('Edit');
                    $('#action').val('Edit');
                }
            });
        });
        
        $(document).on('click','.delete',function (){
            var product_id = $(this).attr('id');
            var status = $(this).data('status');
            var btn_action = 'Delete';
            if(confirm('Are you sure you want to change the status '))
            {
                $.ajax({
                   url:'product_action.php',
                   method:'POST',
                   data:{product_id:product_id,status:status,btn_action:btn_action},
                   success: function (data) {
                        $('#alert_action').fadeIn().html('<div class="alert alert-info">'+data+'<div>');
                        dataTable.ajax.reload();
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


<?php include 'footer.php'; ?>