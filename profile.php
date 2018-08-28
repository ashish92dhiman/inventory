<?php
include 'connection.php';

if(!isset($_SESSION["type"]))
{
    
    header("location:login.php");
}

$qry="select * from user_details where usr_id='".$_SESSION["user_id"]."'";
$statement = $connect->prepare($qry);
$statement->execute();
$result = $statement->fetchAll();
$name="";
$email="";
$user_id="";
foreach ($result as $row)
{
    $name=$row["usr_name"];
    $email=$row["usr_email"];
}

include 'header.php';
?>

<div class="panel panel-primary">
    <div class="panel-heading">Edit Profile</div>
    <div class="panel-body">
        <form method="post" id="edit_profile_form"> 
            <span id="message"></span>
            <div class="form-group">
                <label>Name</label>
                <input type="text" class="form-control" id="user_name" name="user_name" required id="user_name" value="<?php echo $name; ?>" />
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" id="user_email" name="user_email" required id="user_email" value="<?php echo $email; ?>" />
            </div>
            <hr />
            <label class="text-info">* Leave password blank if you do not want to change the password!</label>
            <div class="form-group">
                <label>New Password</label>
                <input type="password" class="form-control"  id="user_new_password" name="user_new_password" />
            </div>
            <div class="form-group">
                <label>Re-enter Password</label>
                <input type="password" class="form-control" id="user_re_enter_password" name="user_re_enter_password" />
                <span id="error_message"></span>
            </div>
            <div class="form-group">
                <input type="submit" name="edit_profile" id="edit_profile" value="Edit" class="btn btn-danger" />
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#edit_profile_form').on('submit',function (e){
            e.preventDefault();
            if($('#user_new_password').val() !== '' )
            {
                if($('#user_new_password').val() !== $('#user_re_enter_password').val())
                {
                    $('#error_message').html('<label class=text-danger>Password Not Match</label>');
                    return ;
                }
                else 
                {
                    $('#error_message').html(' ');
                }
            }
            $('#edit_profile').attr('disabled','disabled');
            var form_data = $(this).serialize();
            $.ajax({
                url:"edit_profile.php",
                method:"POST",
                data:form_data,
                success: function (data) {
                    $('#edit_profile').attr('disabled',false);
                    $('#user_new_password').val('');
                    $('#user_re_enter_password').val('');
                    $('#message').html(data);
                    }
                });
        });
    });
</script>



<?php include 'footer.php';  ?>