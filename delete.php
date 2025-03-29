<?php
session_start();
include("config/config.php");

    $id = $_GET['id'];

    $sql = "DELETE FROM users_data WHERE ID = $id";

   $result = mysqli_query($connection, $sql);

//    if (!$result) {
//     // Print the MySQL error message
//     echo "Error: " . mysqli_error($connection);
// }
    if($result) {
        header("refresh:3;url=index.php");
    }


?>


<?php include("templates/header.php"); ?>

<div class="py-5 px-3 px-md-0">
  
    <?php if($result): ?>
        <h3 class='text-center text-success'>Client Data Deleted Succesfully</h3>
    <?php else: ?>
        <h3 class="text-center text-danger">Error Deleting Client's Data</h3>
    <?php endif; ?>

</div>

<?php include("templates/footer.php"); ?>
