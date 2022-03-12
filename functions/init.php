<?php

session_start(); //za upravljanje sesijama, kad se neko uloguje, izloguje, registruje... mogu se otvarat, zatvarat i unistavat

include "db.php";
include "functions.php";

// ovi fajlovi ce se ukljucivati korz init php u svakom narednom fajlu
