<?php

$do='';

if(isset($_GET['do'])){
    $do=$_GET['do'];
}
else{
    $do='manage';
}

if($do=='manage'){
    
  
}
elseif($do=="add"){
    
}
elseif($do=="update"){
    
}
elseif($do=="edit"){
   
    
}

else {
    echo 'error';
}