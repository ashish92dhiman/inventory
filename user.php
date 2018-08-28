<?php
include 'connection.php';

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
                <div class="col-md-10"><h4>User List</h4></div>
                <div class="col-md-2" >
                    <button type="button" name="add" id="add_button" class="btn btn-primary" data-toggle="modal" data-target="#user_modal"> Add </button>
                </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12 table-responsive">
                        <table class="table table-bordered table-striped" id="user_data"> 
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Email</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <?php
                                $qry = "select * from user_details where usr_type = 'user'";
                                $statement=$connect->prepare($qry);
                                $statement->execute();
                                $result=$statement->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($result as $row)
                                {
                                    
                                    $status=''; 
                                    if($row['usr_status']=='active')
                                    {
                                        $status = "<span class='label label-success'>Active</label>";
                                    }
                                    else
                                    {
                                        $status = "<span class='label label-danger'>Inctive</label>";
                                    }
                                    echo '<tr>';
                                    echo "<td>".$row['usr_id']."</td>";
                                    echo "<td>".$row['usr_email']."</td>";
                                    echo "<td>".$row['usr_name']."</td>";
                                    echo "<td>$status</td>";
                                    echo '<td><button type=button name=update id='.$row['usr_id'].' class="btn btn-warning btn-xs update">Update</button></td>';
                                    echo '<td><button type=button name=delete id='.$row['usr_id'].' data-status='.$row['usr_status'].'  class="btn btn-info btn-xs delete">Delete</button></td>';
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
<div class="modal fade" id="user_modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
        <form method="post" id="user_form" >
            <div class="modal-header">
                <button class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-plus"> Add User </i></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Enter User Name</label>
                    <input type="text" name="user_name" id="user_name" required class="form-control" /> 
                </div>
                <div class="form-group">
                    <label>Enter User Email</label>
                    <input type="email" name="user_email" id="user_email" required  class="form-control" /> 
                </div>
                <div class="form-group">
                    <label>Enter User Password</label>
                    <input type="password" name="user_password" id="user_password" required class="form-control" /> 
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="user_id" id="user_id" />
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
        var dataTable = $('#user_data').dataTable({
            'processing':true,
            'pageLength':10,
            'order':[]
        });
        $(document).on('submit','#user_form',function(e){
            e.preventDefault();
            $('#action').attr('disabled','disabled');
            var form_data = $('#user_form').serialize();
            $.ajax({
                url:'user_action.php',
                method:'POST',
                data:form_data,
                success: function (data) {
                      $('#user_form')[0].reset();
                      $('#user_modal').modal('hide');
                      $('#alert_action').fadeIn().html("<div class='alert alert-success'>"+data+"</div>");
                      $('#action').attr('disabled',false);
                      //location.ajax.reload();
                      dataTable.ajax.reload();
                    }
            });
        });

        $(document).on('click','.update',function(){
            var user_id = $(this).attr('id');
            var btn_action = 'fetch_single';
            $.ajax({
                url:'user_action.php',
                method:'POST',
                data:{user_id:user_id,btn_action:btn_action},
                dataType: 'json',
                success: function (data) {
                    $('#user_modal').modal('show');
                    $('#user_name').val(data.user_name);
                    $('#user_email').val(data.user_email);
                    $('.modal-title').html("<i class='fa fa-plus'>Edit User</i>");
                    $('#user_id').val(user_id);
                    $('#action').val('Edit');
                    $('#btn_action').val('Edit');
                    $('#user_password').attr('required',false);
                }
            });
        });
        
        $(document).on('click','.delete',function(){
            var user_id = $(this).attr('id');
            var status = $(this).data('status');
            var btn_action = 'Delete' ;
            if(confirm('Are you sure you want to change the user status '))
            {
                $.ajax({
                    url:'user_action.php',
                    method:'POST',
                    data:{user_id:user_id,status:status,btn_action:btn_action},
                    success: function (data) {
                        $('#alert_action').fadeIn().html("<div class='alert alert-success'>"+data+"</div>");
                        dataTable.ajax.reload();
                    }
                });
            }
            else
            {
                return  false;
            }
        });
    });
</script>


<?php include 'footer.php'; ?>