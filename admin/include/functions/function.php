<?php



function getallv2($select,$table,$where=NULL){
    global $con;
    $stmt=$con->prepare("SELECT $select FROM $table $where");
    $stmt->execute(array());
    $allv2=$stmt->fetchAll();
    return $allv2;
}






function gettitel(){
    global $pagetitel;
    if(isset($pagetitel)){
        echo $pagetitel;
    }
    else{
        echo'default';
    }
}


function redirect($msg,$url=null,$seconds=3){
    if($url===null){
        $url='index.php';
        $link='homepage';
    }
    else{
        if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !==''){
            $url=$_SERVER['HTTP_REFERER'];
            $link='previous page';
        }
        else{
            $url='index.php'; 
            $link='homepage';       
        }
        
       
    }

   echo $msg;
   echo "<div class='alert alert-info'>you will be redirected to $link in $seconds seconds</div>";
   header ("refresh:$seconds;url=$url");
   exit();
}


function checkitem($select,$from,$value){
    global $con;
    $stmt=$con->prepare("SELECT $select FROM $from WHERE $select=? ");
    $stmt->execute(array($value));
    $count=$stmt->rowCount();
    return $count;
}


function countitems($items,$table){
    global $con;

    $stmt=$con->prepare("SELECT COUNT($items) FROM $table");
    $stmt->execute();
    return $stmt->fetchcolumn();
}



function getlatest($select, $table,$order,$latest){
    global $con;
    $stmt=$con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $latest ");
    $stmt->execute(array());
    $row=$stmt->fetchAll();
    return $row;

}