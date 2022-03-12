<?php

function setMessage($message){
    if(!empty($message)){
        $_SESSION['message'] = $message;  //ako varijabla nije prazna, u sesiju postavi tu varijablu kao [ime]
    }
    else{
        $message = "";
    }
}

function displayMessage(){
    if(isset($_SESSION['message'])){  //ako je sesija postavljena ispisi iz sesije varijablu message
        echo $_SESSION['message'];
        unset($_SESSION['message']);  //obrisi varijablu message iz sesije
    }
}

function clean($string){
    return htmlentities($string); //prociscavanje stringa od html injectiona, da ne bi browser 
}                                 //koristio string kao html

function redirect($location){
    header("location: {$location}");  //fja za auto preusmjeravanje korisnika s jedne stranice na drugu
    exit();
}

function emailExistsCheck($email){
    $email = filter_var($email, FILTER_SANITIZE_EMAIL); //provjera da li je email zapravo email
    $query = "SELECT id FROM users WHERE email = '$email'"; //sql upit
    $result = query($query); //sql upit poslan pomocu funkcije iz db.php

    if($result->num_rows > 0){ //ako postoji broj redova znaci korisnik sa tim emailom vec postoji
        return true;
    }
    else{
        return false;
    }
}

function usernameExistsCheck($username){
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $query = "SELECT id FROM users WHERE username = '$username'";
    $result = query($query);

    if($result->num_rows > 0){
        return true;
    }
    else{
        return false;
    }
}

function validateUserRegistration(){  //funkcija validira jel confirm_password isti, jel email vec postoji,
    $errors = [];                     //jel username vec postoji itd itd...
    //provjera da li je kliknuto dugme register now i da li je serveru poslan POST zahtjev
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        //dodjeljivanje varijablama vrijednosti iz html forme uz celan funkciju- zastitu od html injectiona
        //clean funkciju smo predhodno napravili
        $first_name = clean($_POST['first_name']); 
        $last_name = clean($_POST['last_name']);
        $username = clean($_POST['username']);
        $email = clean($_POST['email']);
        $password = clean($_POST['password']);
        $confirm_password = clean($_POST['confirm_password']);

        if(strlen($first_name)<2){
            $errors[] = "Your first name cannot be less then 2 characters!";
        }
        if(strlen($last_name)<2){
            $errors[] = "Your last name cannot be less then 2 characters!";
        }
        if(strlen($username)<5){
            $errors[] = "Your username cannot be less then 5 characters!";
        }
        if(strlen($username)>15){
            $errors[] = "Your username cannot be bigger then 20 characters!";
        }
    
        if(emailExistsCheck($email)){
            $errors[] = "Sorry that email is already is taken!";
        }
    
        if(usernameExistsCheck($username)){
            $errors[] = "Sorry that username is already is taken!";
        }
    
        if(strlen($password)<8){
            $errors[] = "Your password cannot be less then 8 characters";
        }
        
        if($password != $confirm_password){
            $errors[] = "The password was not confirmed correctly";
        }

        if(!empty($errors)){
            foreach ($errors as $error){
                echo "<div class='alert'>". $error ."</div>";  //ispis errora ako ih ima
            }
        }
        else{
            //ako nema errora, procisti jos jednom podatke i pozovi createUser fju i daj joj podatke
            $first_name = filter_var($first_name, FILTER_SANITIZE_STRING);
            $last_name = filter_var($last_name, FILTER_SANITIZE_STRING);
            $username = filter_var($username, FILTER_SANITIZE_STRING);
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            $password = filter_var($password, FILTER_SANITIZE_STRING);
            createUser($first_name, $last_name, $username, $email, $password);
        }
    }
}

function createUser($first_name, $last_name, $username, $email, $password){
    //prima za argumente varijable(podatke) od validateUserRegistration funkcije i sa escape funkcijom
    //pravimo zastitu od sql injactiona, escape funkcija je napravljena u db.php
    $first_name = escape($first_name);
    $last_name = escape($last_name);
    $username = escape($username);
    $email = escape($email);
    $password = escape($password);
    //kriptovanje passworda
    $password = password_hash($password, PASSWORD_DEFAULT); 
    //upit za upis korisnickih podataka u bazu
    //ubaci u tabelu users u polja ta i ta...
    $sql = "INSERT INTO users(first_name,last_name,username,profile_image,email,password)";
    //davanje vrijednosti za unos u polja, redom [.= znaci da se string nastavlja]
    $sql .= "VALUES('$first_name','$last_name','$username','uploads/default.jpg','$email','$password')";

    confirm(query($sql)); //obje funkcije su iz db.php, izvrsavanje upita i potvrdjivanje da li je upit izvrsen
    setMessage("You have been successfully registreted, please Log in!");
    redirect("login.php");
}

function validateUserLogin(){
    $errors = [];
    //provjera da li je server dobio post zahtjev odnosno da li je kliknuto dugme log in na formi u login.php
    //prepoznaje na koje dugme se odnosi po tome stoje ova fja pozvana tacno tamo u login.php dijelu ove web app
    if($_SERVER['REQUEST_METHOD']=="POST"){
        $email = clean($_POST['email']);
        $password = clean($_POST['password']);

        if(empty($email)){
            $errors[] = "Email field cannot be empty!";
        }
        if(empty($password)){
            $errors[] = "Password field cannot be empty!";
        }
        if(empty($errors)){
            if(userLogin($email, $password)){
                redirect("index.php");
            }
            else{
                $errors[] = "Your email or password is incorrect, please try again!";
            }
        }
        if(!empty($errors)){
            foreach ($errors as $error){
                echo "<div class='alert'>". $error ."</div>";
            }
        }
    }
}

function userLogin($email, $password){
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $password = filter_var($password, FILTER_SANITIZE_STRING);

    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = query($query);
    //ako ima rezultata ovog upita prema bazi, znaci da je uneseni email tacan
    if($result->num_rows > 0){
        $data = $result->fetch_assoc(); //uzima iz baze red i smijesta u associjativni niz
        //provjera tacnosti plain text passworda sa hash passwordom iz baze
        if(password_verify($password, $data['password'])){
            $_SESSION['email'] = $email; //startovanje sesije sa email-om ako je password tacan
            return true; //vracanje true jer je funkcija userLogin predhodno postavljena u if, znaci t or f
        }
        else{
            return false;
        }
    }
    else{
        return false;
    }
}

function getUser($id=NULL){
    if($id != NULL){
        $query = "SELECT * FROM users WHERE id =" . $id;
        $result = query($query);

        if($result->num_rows > 0){
            return $result->fetch_assoc();
        }
        else{
            echo "User not found!";
        }
    }
    else{
        $query = "SELECT * FROM users WHERE email ='" . $_SESSION['email'] . "'";
        $result = query($query);

        if($result->num_rows > 0){
            return $result->fetch_assoc(); //ako ima rezultata vrati result kao asocijativni niz
        }
        else{
            echo "User not found!";
        }
    }
}

function profileImageUpload(){
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $target_dir = "uploads/";
        $user = getUser();
        $user_id = $user['id'];
        //cijela putanja gdje ce bit smjestena uploadovana slika, a pathinfo uzima ekstenziju slike
        //i dodaje je na uploads/id, neka id bude 12, uploads/12 pa ce biti uploads/12.jbg
        //profile_image_file je input polje za upload slike u html formi u profile.php fajlu
        $target_file = $target_dir . $user_id . "." . pathinfo(basename($_FILES["profile_image_file"]["name"]), PATHINFO_EXTENSION);;
        $upload_status = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION)); //uzimanje same ekstenzije
        $error = "";

        $check = getimagesize($_FILES["profile_image_file"]["tmp_name"]); //provjera da li je fajl slika
        if($check !== false){
            $upload_status = 1;
        }
        else{
            $error = "File is not an image!";
            $upload_status = 0;
        }

        if($_FILES["profile_image_file"]["size"]>5000000){
            $error = "Sorry, your file is too large!";
            $upload_status = 0;
        }

        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif"){
            $error = "Sorry, only JPG, PNG, JPEG AND GIF files are allowed!";
            $upload_status = 0;
        }

        if($upload_status == 0){ //ako se upload nije izvrsio odnosno ako postoji error
            setMessage('Error uploading file: ' . $error);
        }
        else{ //a ako je se uspjesno izvrsio zamijeni profile_image ui bazi za $target_file gdje je id = $user_id
            $sql = "UPDATE users SET profile_image = '$target_file' WHERE id=$user_id";
            confirm(query($sql));
            setMessage('Profile Image Uploaded!');
            //ako se nije uspjesno izvrsilo premijestanje uploadovanog fajla u nas folder
            if(!move_uploaded_file($_FILES["profile_image_file"]["tmp_name"], $target_file)){
                setMessage('Error uploading file: ' . $error);
            }
        }
        redirect('profile.php');
    }
}

function userRestrictions(){
    if(!isset($_SESSION['email'])){ //ako sesija nije postavljena (korisnik nije loginovan) onemogucujemo
        redirect("login.php");      //pristup profile.php kroz url i slicnim unutrasnjim dijelovima
    }
}

function loginCheck(){  //ako je korisnik loginovan onemogucujemo pristup login.php i register.php kroz url
    if(isset($_SESSION['email'])){
        redirect("index.php");
    }
}

function createPost(){
    $errors;

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        //cistimo string od html injectiona, pomocu POST zahtjeva prepoznaje 'post_content' iz html forme
        $post_content = clean($_POST['post_content']);
        if(strlen($post_content)>300){
            $errors = "Your post content is too long!";
            echo "<div class='alert'>" . $errors . "</div>";
        }
        else{
            //ukljanjanje svih tagova ili desifrovanje specijalnih karaktera datog stringa
            $post_content = filter_var($post_content, FILTER_SANITIZE_STRING);
            $user = getUser(); //uzimamo podatke o korisniku
            $user_id = $user['id']; //iz svih podataka vadimo samo user id da bi znali koji je korisnik objavio post

            $sql = "INSERT INTO posts(user_id, content, likes)"; //ubacujemo u tabelu posts, u polja (...)
            $sql .= "VALUES($user_id, '$post_content', 0)"; //dajemo vrijednosti za upisivanje u polja redom

            confirm(query($sql)); //potvrdjujemo jel sql upit prosao
            setMessage("You added a post!");
            redirect("index.php");
        }
    }
}

function fetchAllPosts(){
    $query = "SELECT * FROM posts ORDER BY created_time DESC"; //uzimamo sve iz tabele posts sortirano po vremenu kreiranja obrnuto
    $result = query($query);

    if($result->num_rows > 0){ //ako ima rezultata
        while($row = $result->fetch_assoc()){ //smijestamo sve rezultat u $row asocijativni niz
            $user = getUser($row['user_id']); //uzimamo podatke o korisniku, pretrazujemo ga po id-u

            echo "<div class = 'post'>
                        <p><img src = '" . $user['profile_image'] . "'><i><b>" . $user['first_name'] . " " . $user['last_name'] . "</b></i></p>
                        <p>" . $row['content'] . " </p>
                        <p><i>Date: <b>" . $row['created_time'] . "</b></i></p>
                        <div class='likes'>Likes: <b id='likes_" . $row['id'] . "'>" . $row['likes'] . "</b>
                        <button data-post-id='" . $row['id'] . "' onclick='likePost(this)'>LIKE</button>
                        </div>
                  </div>";
        }
    }
}