<?php



function getall($table,$order){
    global $con;
    $stmt=$con->prepare("SELECT * FROM $table WHERE approve = 1 ORDER BY $order DESC ");
    $stmt->execute(array());
    $all=$stmt->fetchAll();
    return $all;

}

function getitems($where,$value,$approve=NULL){
    global $con;

    if($approve==NULL){
        $app='AND approve = 1';
    }
    else{
        $app=NULL;
    }

    $stmt=$con->prepare("SELECT * FROM items WHERE $where=? $app ORDER BY items_ID DESC ");
    $stmt->execute(array($value));
    $cats=$stmt->fetchAll();
    return $cats;

}


function checkuser($user){
    global $con;
    $stmt2=$con->prepare("SELECT username , regstatus   FROM users where username=? and regstatus=0  ");
    $stmt2->execute(array($user));
 
    $count2=$stmt2->rowcount();
    return $count2;

}

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
   echo "<div class='alert alert-info'>you will be   redirected to $link in $seconds seconds</div>";
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


function getcats(){
    global $con;
    $stmt=$con->prepare("SELECT * FROM categories WHERE parent=0 ORDER BY ID ASC ");
    $stmt->execute(array());
    $cats=$stmt->fetchAll();
    return $cats;

}