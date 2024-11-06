<?php
include 'top_menu.php';
include "cfg/dbconnect.php";

// Initialize variables
$email = $err_msg = "";
$register_success_msg = "";

// Handle Login
if (isset($_POST['submit'])) { // if Form is submitted
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    // generate md5 hash, because password is stored in database with md5 hash
    $password = md5($password);
    
    // check if same email id and password are stored in the database
    $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $password);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $_SESSION['name'] = $row['name'];
            $_SESSION['userid'] = $email;
            header("Location: index.php");
        } else {
            $err_msg = "Incorrect Email id/Password";
        }
    } else {
        $err_msg = "Some error occurred";
    }
}

// Handle Registration
if (isset($_POST['register'])) { // if Register form is submitted
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    
    // Check if password and confirm password match
    if ($password !== $confirm_password) {
        $err_msg = "Passwords do not match!";
    } else {
        // Hash the password before storing
        $hashed_password = md5($password);
        
        // Check if email already exists in database
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $err_msg = "Email already registered!";
        } else {
            // Insert new user data into database
            $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $name, $email, $hashed_password);
            if ($stmt->execute()) {
                $register_success_msg = "Registration successful! You can now login.";
            } else {
                $err_msg = "Some error occurred. Please try again.";
            }
        }
    }
}
?>

<!-- Toggle Between Login and Register -->
<div id="login-form">
    <form class="form-1" action="login.php" method="post">
        <h2>Login Form</h2>
        <?php if ($err_msg != ""): ?>
            <p class="err-msg"><?php echo $err_msg; $err_msg = ""; ?></p>
        <?php endif; ?>

        <div class="col-md-12 form-group">
            <label>Email Id</label>
            <input type="text" class="form-control" name="email" id="email" value="<?php echo $email; ?>" placeholder="Enter your Email Id" required>
        </div>

        <div class="col-md-12 form-group">
            <label>Password</label>
            <input type="password" class="form-control" name="password" id="password" placeholder="Enter Password" required>
        </div>

        <div class="col-md-12 form-group">
            <input type="checkbox" class="check" onclick="togglePwd()">Show Password
        </div>

        <div class="col-md-12 form-group text-right">
            <button type="submit" class="btn btn-primary" name="submit">Login</button>
        </div>

        <div class="col-md-12 form-group">
            <p>Don't have an account? <a href="#" onclick="showRegisterForm()">Register here</a></p>
        </div>
    </form>
</div>

<!-- Registration Form -->
<div id="register-form" style="display:none;">
    <form class="form-1" action="login.php" method="post">
        <h2>Registration Form</h2>
        <?php if ($err_msg != ""): ?>
            <p class="err-msg"><?php echo $err_msg; $err_msg = ""; ?></p>
        <?php endif; ?>

        <?php if ($register_success_msg != ""): ?>
            <p class="success-msg"><?php echo $register_success_msg; ?></p>
        <?php endif; ?>

        <div class="col-md-12 form-group">
            <label>Name</label>
            <input type="text" class="form-control" name="name" id="name" placeholder="Enter your Name" required>
        </div>

        <div class="col-md-12 form-group">
            <label>Email Id</label>
            <input type="text" class="form-control" name="email" id="email" placeholder="Enter your Email Id" required>
        </div>

        <div class="col-md-12 form-group">
            <label>Password</label>
            <input type="password" class="form-control" name="password" id="password" placeholder="Enter Password" required>
        </div>

        <div class="col-md-12 form-group">
            <label>Confirm Password</label>
            <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Confirm your Password" required>
        </div>

        <div class="col-md-12 form-group">
            <button type="submit" class="btn btn-primary" name="register">Register</button>
        </div>

        <div class="col-md-12 form-group">
            <p>Already have an account? <a href="#" onclick="showLoginForm()">Login here</a></p>
        </div>
    </form>
</div>

<script>
    // Show the registration form and hide the login form
    function showRegisterForm() {
        document.getElementById('login-form').style.display = 'none';
        document.getElementById('register-form').style.display = 'block';
    }

    // Show the login form and hide the registration form
    function showLoginForm() {
        document.getElementById('register-form').style.display = 'none';
        document.getElementById('login-form').style.display = 'block';
    }

    $(document).ready(function() {
        $("#login").addClass("active");
    });
</script>
</body>
</html>
