<?php

session_start();

$pagetitel='';

if (isset($_SESSION['username'])){
    include 'init.php';
    include $tpl . '/navebar.php';
}
$do='';
if(isset($_GET['do'])){
    $do=$_GET['do'];
}

else{
    $do='manage';
}
if($do=='manage'){

}

elseif($do=='add'){

}

elseif($do=='insert'){

}

elseif($do=='update'){

}
elseif($do=='delete'){
    
}

else{
    header('location:index.php');
    exit();
}

