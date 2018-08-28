<?php include 'connection.php'; ?>

<table class="table table-bordered table-striped" id="user_data"> 
    <thead>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Status</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
    <?php
       $qry = "SELECT * FROM `category` order by cat_id desc";
       $statement = $connect->prepare($qry);
       $statement->execute();
       $result = $statement->fetchAll(PDO::FETCH_BOTH);
       {
         if(count($result) > 0)
         {
             foreach ($result as $row)
             {
                 $status ='';
                 if($row['cat_status'] == 'active')
                 {
                     $status = '<span class="label label-success">Active</span>';
                 }
                 else
                 {
                     $status = '<span class="label label-danger">Inactive</span>';
                 }
                 ?>
                    <tr>
                        <td><?= $row['cat_id'] ?></td>
                        <td><?= $row['cat_name'] ?></td>
                        <td><?= $status ?></td>
                        <td><button type="button" name='update' class="btn btn-info btn-xs update" id="<?= $row['cat_id'] ?>">Update</button></td>
                        <td><button type="button" name="delete" id="<?= $row['cat_id'] ?>" data-status="<?= $row['cat_status'] ?>" class="btn btn-danger btn-xs delete " >Delete</button></td>
                    </tr>
                 <?php
             }
         }
         else
         {
             ?>
                <tr>
                    <td colspan="5"><span class="text-primary">No Record Found !</span></td>
                </tr>
             <?php
         }
       }
    ?>
    </tbody>
</table>