<?php

include "functions/init.php"; //zbog startovane sesije
session_destroy();

redirect("index.php");

?>