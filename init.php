<?php

ini_set('display_errors' , 'on');
error_reporting(E_ALL);

$sessionuser='';
if (isset($_SESSION['user'])){
    $sessionuser= $_SESSION['user'];
  }



$tpl="include/template";
$func='include/functions';

include "admin/connect.php";

include $func . '/function.php';




include $tpl . "/header.php";
include  "navebar.php";
include $tpl . '/footer.php';





