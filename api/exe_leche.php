<?php

include 'api.php';

$produccion_lechera = new ApiGanado();
$produccion_lechera -> handleRequest("produccion_lechera"); 