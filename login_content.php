<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   $user_name = $_POST['user_name'];
   $user_pass = $_POST['user_pass'];

   // Process login logic here (e.g., check credentials in the database)
   if (/* valid login */) {
      echo "Login successful!";
   } else {
      echo "Invalid credentials.";
   }
}
?>
<div class="modal-content">
<form action="" method="post">
    User
    <input name="user_name" id="user_name" type="text"> Password
    <input name="user_pass" id="user_pass" type="password">
    <button type="submit" id="login">Login</button>
</form>