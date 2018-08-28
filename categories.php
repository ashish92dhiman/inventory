<?php
include './connection.php';
if(!isset($_SESSION['type']))
{
    header("location:login.php");
}
if($_SESSION['type'] != 'master')
{
    header("location:index.php");
}
include './header.php';  ?>
        
        <span id="alert_action"></span>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                        <div class="col-md-10"><h4>List of Categories</h4></div>
                        <div class="col-md-2" >
                            <button type="button" name="add" id="add_button" class="btn btn-primary" data-toggle="modal" data-target="#cat_modal"> Add </button>
                        </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12 table-responsive" id="categories">
                                <!--   data fetched from categories_fetch.php using jquery and ajax; -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="cat_modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content" >
                    <form method="post" id="cat_form" class="form-group"> 
                        <div class="modal-header">
                            <button class="close" data-dismiss="modal">&times;</button>
                            <h3 class="modal-title"><i class="fa fa-plus"> Add Category </i></h3>
                        </div>
                        <div class="modal-body">
                            <label>Category Name</label>
                            <input type="text" id="cat_name" name="cat_name" class="form-control" value="" placeholder="Enter Category Name" required >
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="cat_id" id="cat_id">
                            <input type="hidden" name="btn_action" id="btn_action" >
                            <input type="submit" id="action" name="action" value="Add" class="btn btn-primary" />
                            <button class="btn btn-default" data-dismiss="modal" type="button">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        
        <script>
            $(document).ready(function(){
                
                var cat =  $.ajax({
                    url:'categories_fetch.php',
                    type:'POST',
                    success: function (data) {
                        $('#categories').html(data);
                        $('#user_data').dataTable({
                            'processing':true,
                            'pageLength':10,
                            'order':[]
                        });
                    }
                });
                
                
                $('#add_button').click(function(){
                    $('#cat_form')[0].reset();
                    $('.modal-title').html('<i class="fa fa-plus"> Add Category </i>');
                    $('#action').val('Add');
                    $('#btn_action').val('Add');
                });
        
                $(document).on('submit','#cat_form',function (event){
                    event.preventDefault();
                    $('#action').attr('disabled','desabled');
                    var form_data = $(this).serialize();
                    //alert(form_data);
                    $.ajax({
                       url:'cat_action.php',
                       method:'POST',
                       data:form_data,
                       success: function (data) {
                           //alert(data);
                           $('#cat_form')[0].reset();
                           $('#cat_modal').modal('hide');
                           $('#alert_action').fadeIn().html('<div class="alert alert-success">'+data+'</div>');
                           $('#action').attr('disabled',false);
                           cat.ajax.reload();
                        }
                    });
                })
                
                $(document).on('click','.update',function(){
                    var cat_id = $(this).attr('id');
                    var btn_action = 'fetch_single';
                    $.ajax({
                        url:'cat_action.php',
                        method:'POST',
                        data:{cat_id:cat_id,btn_action:btn_action},
                        dataType: 'json',
                        success: function (data){
                            $('#cat_modal').modal('show');
                            $('.modal-title').html('<i class="fa fa-plus"> Update Category </i>');
                            $('#cat_name').val(data.cat_name);
                            $('#cat_id').val(cat_id);
                            $('#btn_action').val('Edit');
                            $('#action').val('Edit');
                        }
                    });
                });
                 
                
                 $(document).on('click','.delete',function (){
                    var cat_id = $(this).attr('id');
                    var btn_action = 'Delete';
                    var status = $(this).data('status');
                    if(confirm('Are you sure you want to change the status'))
                    {
                        $.ajax({
                            url:'cat_action.php',
                            method:'post',
                            data:{cat_id:cat_id,btn_action:btn_action,status:status},
                            success:function(data)
                            {
                               $('#alert_action').fadeIn().html('<div class="alert alert-info">'+data+'</div>');
                               cat.ajax.reload();
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
    
<?php include './footer.php'; ?>