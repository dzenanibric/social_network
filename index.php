<?php include "includes/header.php"; ?>

<?php if(isset($_SESSION['email'])) : //ako je sesija postavljena prikazivat ce se ono sto je unutar ovog ifa na taj nacin se određuje sta vidi prijavljen korisnik a sta gost?> 
    <?php createPost(); ?>    

    <form method="POST">
        <h3>Create new post: </h3>
        <textarea name="post_content" cols="50" rows="15" placeholder="What is on your mind?"></textarea>
        <input type="submit" name="submit-post" value="Post">
    </form>

    <div>
        <?php displayMessage(); ?>
    </div>

    <hr>

    <div class="posts">
        <?php fetchAllPosts(); ?>

    </div>
<?php else : ?>

    <div class="homepage">
        <h1> Welcome to the new and best social network! </h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Distinctio accusantium beatae blanditiis architecto maxime itaque dolores omnis possimus necessitatibus sequi! Temporibus distinctio reprehenderit cum, deleniti atque quisquam dolorem nostrum veniam.</p>
        <h3>Log in and become part of our family, <a href="login.php">click here!</a></h3>
        <img src="css/img/homepage.png" alt="">
    </div>
<?php endif; ?>

    <hr>

<?php include "includes/footer.php"; ?> 