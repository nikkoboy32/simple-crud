<?php
session_start();
include("config/config.php");
$full_name = $email_address = $phone_number = $address = "";
$full_name_err = $email_address_err = $phone_number_err = $address_err = $form_error = "";

// Generate CSRF token if not already set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_button'])) {

    // Check CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token");
    }


    if (empty(trim($_POST['full_name']))) {
        $full_name_err = "Please Enter Full Name";
    } else {
        $full_name = htmlspecialchars(trim($_POST["full_name"]), ENT_QUOTES, 'UTF-8');
        if (!preg_match("/^[a-zA-Z-' ]+$/", $full_name)) {
            $full_name_err = "Only letters and white space allowed";
        }
    }


    if (empty(trim($_POST['email_address']))) {
        $email_address_err = "Please enter a valid Email Address";
    } else {
        $email_address = filter_var(trim($_POST['email_address']), FILTER_SANITIZE_EMAIL);
        if (!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
            $email_address_err = "Invalid email format";
        }
    }


    if (empty(trim($_POST['phone_number']))) {
        $phone_number_err = "Please Input a Phone Number";
    } else {
        $phone_number = htmlspecialchars(trim($_POST['phone_number']), ENT_QUOTES, 'UTF-8');
        if (!preg_match("/^09[0-9]{9}$/", $phone_number)) {
            $phone_number_err = "Invalid phone number format. It should start with 09 and have 11 digits in total.";
        }
    }


    if (empty(trim($_POST['address']))) {
        $address_err = "Please enter Address";
    } else {
        $address = htmlspecialchars(trim($_POST['address']), ENT_QUOTES, 'UTF-8');
        if (!preg_match("/^[a-zA-Z0-9\s\-,.#()'`]+$/", $address)) {
            $address_err = "Invalid address format.";
        }
    }

   
    if (empty($full_name_err) && empty($email_address_err) && empty($phone_number_err) && empty($address_err)) {
        
        $sql = "INSERT INTO users_data (full_name, email_address, phone_number, address) VALUES (?, ?, ?, ?)";
    
        if ($stmt = mysqli_prepare($connection, $sql)) {
          
            mysqli_stmt_bind_param($stmt, "ssss", $full_name, $email_address, $phone_number, $address);
    
          
            if (mysqli_stmt_execute($stmt)) {
                echo "Data inserted successfully!";
                header("Location: index.php");
                exit;
            } else {
                echo "Error inserting data: " . mysqli_error($connection);
            }
    
           
            mysqli_stmt_close($stmt);
        } else {
            echo "Prepare statement failed: " . mysqli_error($connection);
        }
    }
    
}
?>

<?php include("templates/header.php"); ?>

<div class="py-5 px-3 px-md-0">
    <h2 class="text-center">Add a New Client</h2>
    <div class="col-12 col-md-8 col-lg-6 mx-auto">
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <div class="form-group">
                <label for="inputFullName4">Full Name</label>
                <input type="text" class="form-control" id="inputFullName4" placeholder="Full Name" name="full_name" 
                       value="<?php echo htmlspecialchars($full_name, ENT_QUOTES, 'UTF-8'); ?>">
                <span class="text-danger"><?php echo $full_name_err; ?></span>
            </div>
            
            <div class="form-group">
                <label for="inputEmail4">Email</label>
                <input type="email" class="form-control" id="inputEmail4" placeholder="Email" name="email_address" 
                       value="<?php echo htmlspecialchars($email_address, ENT_QUOTES, 'UTF-8'); ?>">
                <span class="text-danger"><?php echo $email_address_err; ?></span>
            </div>
            
            <div class="form-group">
                <label for="inputPhone4">Phone</label>
                <input type="text" class="form-control" id="inputPhone4" placeholder="Phone Number" name="phone_number" 
                       value="<?php echo htmlspecialchars($phone_number, ENT_QUOTES, 'UTF-8'); ?>">
                <span class="text-danger"><?php echo $phone_number_err; ?></span>
            </div>
            
            <div class="form-group">
                <label for="inputAddress">Address</label>
                <input type="text" class="form-control" id="inputAddress" placeholder="1234 Main St" name="address" 
                       value="<?php echo htmlspecialchars($address, ENT_QUOTES, 'UTF-8'); ?>">
                <span class="text-danger"><?php echo $address_err; ?></span>
            </div>
            
            <div class="btn_con my-3 text-center">
                <button type="submit" class="btn btn-primary" name="add_button">Add</button>
                <a href="index.php" class="btn btn-secondary">Go Back</a>
            </div>
            
            <span class="text-danger text-center"><?php echo $form_error; ?></span>
        </form>
    </div>
</div>

<?php include("templates/footer.php"); ?>
