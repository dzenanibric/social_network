<?php include "includes/header.php"; 
userRestrictions();
?>

<div>
    <?php displayMessage(); ?>
</div>

<?php 
$user = getUser();
echo "<img class = 'profile-image' src='" . $user['profile_image'] . "'>";
profileImageUpload();
?>

    <form method ="POST" enctype="multipart/form-data">
        <p>Upload image:</p>
        <input type="file" name="profile_image_file">
        <input type="submit" name ="submit-profile-image" value="Upload Image">
    </form>

<?php include "includes/footer.php"; ?>