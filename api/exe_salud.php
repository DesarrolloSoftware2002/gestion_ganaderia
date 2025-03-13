<?php

include 'api.php';

$registro_salud = new ApiGanado(); 
$registro_salud -> handleRequest("registro_salud");