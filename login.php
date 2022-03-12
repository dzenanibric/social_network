<?php include "includes/header.php";
loginCheck();
?>

<div>
<?php
displayMessage();
validateUserLogin();
?>
</div>

<form method = "POST">
    <input type = "email" placeholder = "Email" name = "email" required>
    <input type = "password" placeholder = "Password" name = "password" required>
    <input type = "submit" name = "submit-login" value = "Log In">
</form>

<?php include "includes/footer.php";?>