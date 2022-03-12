<?php include "includes/header.php"; 
loginCheck();
?>
    
    <div>
    <?php
    validateUserRegistration();
    displayMessage();
    ?>
    </div>

    <form method = "POST">
        <input type="text" name="first_name" placeholder="First Name" required>
        <input type="text" name="last_name" placeholder="Last Name" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <input type="submit" name="submit_register" placeholder="Register Now">
    </form>

<?php include "includes/footer.php"; ?>