<?php
include 'connection.php';
include_once 'function.php';

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
                <div class="col-md-10"><h4>Brand List</h4></div>
                <div class="col-md-2" >
                    <button type="button" name="add" id="add_button" class="btn btn-primary" data-toggle="modal" data-target="#brand_modal"> Add </button>
                </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12 table-responsive">
                        <table class="table table-bordered table-striped" id="brand_data"> 
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Category</th>
                                    <th>Brand Name</th>
                                    <th>Status</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <?php
                                $qry = "select * from brand inner join category on brand.cat_id = category.cat_id order by brand_id desc";
                                $statement=$connect->prepare($qry);
                                $statement->execute();
                                $result=$statement->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($result as $row)
                                {
                                    //print_r($row);
                                    $status=''; 
                                    if($row['brand_status']=='active')
                                    {
                                        $status = "<span class='label label-success'>Active</label>";
                                    }
                                    else
                                    {
                                        $status = "<span class='label label-danger'>Inctive</label>";
                                    }
                                    echo '<tr>';
                                    echo "<td>".$row['brand_id']."</td>";
                                    echo "<td>".$row['cat_name']."</td>";
                                    echo "<td>".$row['brand_name']."</td>";
                                    echo "<td>$status</td>";
                                    echo '<td><button type=button name=update id='.$row['brand_id'].' class="btn btn-warning btn-xs update">Update</button></td>';
                                    echo '<td><button type=button name=delete id='.$row['brand_id'].' data-status='.$row['brand_status'].'  class="btn btn-info btn-xs delete">Delete</button></td>';
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
<div class="modal fade" id="brand_modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
        <form method="post" id="brand_form" >
            <div class="modal-header">
                <button class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-plus"> Add Brand </i></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Select Category</label>
                    <select name="cat_id" id="cat_id" class="form-control">
                        <option value=""> Select Category </option>
                        <?= category_list($connect);  ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Enter Brand Name</label>
                    <input type="text" name="brand_name" id="brand_name" required class="form-control" /> 
                </div>                              
            </div>
            <div class="modal-footer">
                <input type="hidden" name="brand_id" id="brand_id" />
                <input type="hidden" name="btn_action" id="btn_action" value="Add" />
                <input type="submit" name="action" id="action" class="btn btn-info" value="Add" />
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        var dataTable = $('#brand_data').dataTable({
            'processing':true,
            'pageLength':10,
            'order':[]
        });
        
        $(document).on('submit','#brand_form',function(e){
            e.preventDefault();
            $('#action').attr('disabled','disabled');
            var form_data = $(this).serialize();
            //alert(form_data);
            $.ajax({
                url:'brand_action.php',
                method:'POST',
                data: form_data,
                success: function (data)
                {
                    //alert(data);
                    $('#brand_form')[0].reset();
                    $('#brand_modal').modal('hide');
                    $('#alert_action').fadeIn().html('<div class="alert alert-success">'+data+'</div>');
                    dataTable.ajax.reload();
                }
            });
        });
        
        $(document).on('click','.update',function(){
            var brand_id = $(this).attr('id');
            var btn_action = 'fetch_single';
            $.ajax({
                url:'brand_action.php',
                method:'POST',
                data:{brand_id:brand_id,btn_action:btn_action},
                dataType: 'json',
                success: function (data)
                {
                    $('#brand_modal').modal('show');
                    $('.modal-title').html('<i class="fa fa-plus"> Edit Brand </i>');
                    $('#cat_id').val(data.cat_id);
                    $('#brand_name').val(data.brand_name);
                    $('#brand_id').val(brand_id);
                    $('#action').val('Edit');
                    $('#btn_action').val('Edit');
                }
            });
        });
        
        $(document).on('click','.delete',function (){
            var brand_id =  $(this).attr('id');
            var status = $(this).data('status');
            var btn_action = 'Delete';
            if(confirm('Are you sure you want to change brand status'))
            {
                $.ajax({
                   url:'brand_action.php',
                   method:'POST',
                   data:{brand_id:brand_id,status:status,btn_action:btn_action},
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