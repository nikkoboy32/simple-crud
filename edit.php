<?php
session_start();
include("config/config.php");

$full_name = $email_address = $phone_number = $address = "";
$full_name_err = $email_address_err = $phone_number_err = $address_err = "";

// Check if ID is provided
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($connection, $_GET['id']);

    // Fetch the user data
    $sql = "SELECT * FROM users_data WHERE ID = $id";
    $result = mysqli_query($connection, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        $full_name = $user['full_name'];
        $email_address = $user['email_address'];
        $phone_number = $user['phone_number'];
        $address = $user['address'];
    } else {
        echo "User not found!";
        exit;
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_button'])) {
    $id = $_POST['id']; // Get hidden input ID
    
    // Validate and sanitize input
    $full_name = htmlspecialchars(trim($_POST["full_name"]), ENT_QUOTES, 'UTF-8');
    $email_address = filter_var(trim($_POST['email_address']), FILTER_SANITIZE_EMAIL);
    $phone_number = htmlspecialchars(trim($_POST['phone_number']), ENT_QUOTES, 'UTF-8');
    $address = htmlspecialchars(trim($_POST['address']), ENT_QUOTES, 'UTF-8');

    if (!preg_match("/^[a-zA-Z-' ]+$/", $full_name)) {
        $full_name_err = "Only letters and white space allowed";
    }
    if (!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
        $email_address_err = "Invalid email format";
    }
    if (!preg_match("/^09[0-9]{9}$/", $phone_number)) {
        $phone_number_err = "Invalid phone number format. It should start with 09 and have 11 digits in total.";
    }
    if (!preg_match("/^[a-zA-Z0-9\s\-,.#()'`]+$/", $address)) {
        $address_err = "Invalid address format.";
    }

    // If no errors, update database
    if (empty($full_name_err) && empty($email_address_err) && empty($phone_number_err) && empty($address_err)) {
        $sql = "UPDATE users_data SET full_name=?, email_address=?, phone_number=?, address=? WHERE ID=?";
        if ($stmt = mysqli_prepare($connection, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssssi", $full_name, $email_address, $phone_number, $address, $id);
            if (mysqli_stmt_execute($stmt)) {
                header("Location: index.php"); // Redirect back to list
                exit;
            } else {
                echo "Error updating record: " . mysqli_error($connection);
            }
            mysqli_stmt_close($stmt);
        }
    }
}
?>

<?php include("templates/header.php"); ?>

<div class="py-5 px-3 px-md-0">
    <h2 class="text-center">Edit Client's Info</h2>
    <div class="col-12 col-md-8 col-lg-6 mx-auto">
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            
            <div class="form-group">
                <label for="inputFullName">Full Name</label>
                <input type="text" class="form-control" name="full_name" value="<?php echo htmlspecialchars($full_name, ENT_QUOTES, 'UTF-8'); ?>">
                <span class="text-danger"><?php echo $full_name_err; ?></span>
            </div>

            <div class="form-group">
                <label for="inputEmail">Email</label>
                <input type="email" class="form-control" name="email_address" value="<?php echo htmlspecialchars($email_address, ENT_QUOTES, 'UTF-8'); ?>">
                <span class="text-danger"><?php echo $email_address_err; ?></span>
            </div>

            <div class="form-group">
                <label for="inputPhone">Phone</label>
                <input type="text" class="form-control" name="phone_number" value="<?php echo htmlspecialchars($phone_number, ENT_QUOTES, 'UTF-8'); ?>">
                <span class="text-danger"><?php echo $phone_number_err; ?></span>
            </div>

            <div class="form-group">
                <label for="inputAddress">Address</label>
                <input type="text" class="form-control" name="address" value="<?php echo htmlspecialchars($address, ENT_QUOTES, 'UTF-8'); ?>">
                <span class="text-danger"><?php echo $address_err; ?></span>
            </div>

            <div class="btn_con my-3 text-center">
                <button type="submit" class="btn btn-primary" name="update_button">Update</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include("templates/footer.php"); ?>
