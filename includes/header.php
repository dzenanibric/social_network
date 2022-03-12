<?php

/* da se ne bi na svakoj stranici ponavljao isti kod za header, footer i slicne stvari zato cemo napraviti
odvojene fajlove za header i footer i kasnije ih samo ukljucivati gdje god zatrebaju. */

include "functions/init.php";

// u header ukljucujemo init zato sto cemo time na svakoj stranici imati i fajlove koji su u initu ukljuceni
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    
    <title>Dzenan Social Network</title>
</head>
<body>
    
<div class="container">
    <nav class="navbar">
        <ul id="main-nav-ul">
            <li><a href="index.php">Home</a></li>
            <?php if(!isset($_SESSION['email'])) : ?> <!-- ako sesija nije startovana log in i register ce biti sakriveni -->
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            <?php else : ?>
                <li><a href="profile.php">Profile </a><li>
                <li><a href="logout.php">Logout</a></li>
                <!-- u varijablu user smijestamo sve info o korisniku pomocu getUser funkcije i ispisujemo samo ime-->
                <li class="welcome_message"><a><?php $user = getUser(); echo $user['first_name']; ?>, welcome!</a></li>
            <?php endif; ?>
        </ul>
    </nav>
<! –– nije zatvoren ni container div a ni html jer se nastavlja u footer.php a oboje se ukljucuje u index.php––>