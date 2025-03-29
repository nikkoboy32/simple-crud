    <?php 
    include("config/config.php");


    $sql = "SELECT ID, full_name, email_address, phone_number, address, 
       DATE_FORMAT(created_at, '%M %d, %Y %h:%i:%s %p') AS formatted_created_at 
FROM users_data";
    $result = mysqli_query($connection, $sql);
    $result_arr = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // print_r($result_arr)

    

    ?>

   <?php include("templates/header.php")?>

    <div class="container-lg py-5">
        <div class="header_con d-flex justify-content-between align-items-center">
            <h2 class="ps-3">List of Clients</h2>
            <a class="btn btn-primary" href="add.php">New Client</a>
        </div>
        <div class="table_con overflow-auto">
            <table class="table table-hover">
                <thead class="text-center">
                    <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Address</th>
                    <th scope="col">Created At</th>
                    <th scope="col">Action</th>
                    </tr>
                </thead>
                    <tbody>
                        <?php foreach ($result_arr as $user): ?>
                        <tr class="align-middle">
                        <th scope="row" class="text-center"><?php echo $user['ID']?></th>
                        <td class="text-center"><?php echo $user['full_name']?></td>
                        <td class="text-center"><?php echo $user['email_address']?></td>
                        <td class="text-center"><?php echo $user['phone_number']?></td>
                        <td class="text-center"><?php echo $user['address']?></td>
                        <td class="text-center"><?php $creation_time = date('Y-m-d', strtotime($user['formatted_created_at'])); echo $user['formatted_created_at']?></td>
                        <td class="text-center">
                            <a href="edit.php?id=<?php echo $user['ID']?>" class="btn btn-primary my-2 my-md-0">Edit</a>
                            <a class="btn btn-danger" href="delete.php?id=<?php echo $user['ID']?>">Delete</a>
                        </td>
                        </tr>
                        <?php endforeach?>
                    </tbody>
                </table>
         </div>
    </div>

<?php include("templates/footer.php")?>