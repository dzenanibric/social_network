<?php

$connection = mysqli_connect('localhost', 'root', 'root', 'social_network'); //konekcija sa bazon host, user, pass, ime baze

function escape($string){  //escape sluzi da ne prodje neki sql injection, kao neka zastita
    global $connection;    //da se string ne bi prepoznao kao sql
    return mysqli_real_escape_string($connection, $string);
}

function query($query){
    global $connection;
    return mysqli_query($connection, $query); //uzima upit za argument i ubacuje ga u mysql upit na odredjenoj konekciji
}

function confirm($result){
    global $connection;
    if(!$result){  //ako ne postoji, ako se konekcija prema bazi ne ostvari, onda die zaustavlja izvrsavanje php-a
        die("QUERY FAILED" . mysqli_error($connection));
    }
}