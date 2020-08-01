<?php
session_start();
if (isset($_SESSION['username'])){
  
  
}
else{
    header('location:index.php');
    exit();
}

$pagetitel='dashboard';

include 'init.php';

include $tpl . '/navebar.php';




?>

<div class="container text-center">
    <h1>Dachboard</h1>
    <div class="row">
        <div class="col-md-3">
            <div class="my card">
                <div class="main card-body">

                <i class='fa fa-users'></i>
                total members
                <span><a href="members.php"><?php echo countitems('userID','users');?></a></span>
                </div>
        </div>
            
        </div>
        <div class="col-md-3">
            <div class="my card">
                <div class="main card-body">
                <i class="fas fa-user-plus"></i>
                pending members
                <span> <a href="members.php?do=manage&page=pending"><?php echo checkitem('regstatus','users',0)?></a> </span>
                </div>
        </div>
        </div>

        <div class="col-md-3">
            <div class="my card">
                <div class="main card-body">
                <i class='fa fa-tag'></i>    
                total items
                <span><a href="items.php"><?php echo countitems('items_ID','items');?></a></span>
                </div>
        </div>
        </div>
        <div class="col-md-3">
            <div class="my card">
                <div class="main card-body">
                <i class="fas fa-comment"></i>
                total comments
                <span> <a href="comments.php"><?php echo countitems('com_ID','comments');?></a></span>
                </div>
        </div>
        
    </div>
</div>


<div class='container'>
    <div class='row'>
        <div class='col-sm-6'>
            <ul class="ul list-group">
                <i class='fa fa-users'></i> <div class='users'>latest registered users</div> 
                <li class="ut list-group-item"><?php
                    $latestusers=getlatest('*','users','userID',3);
                    foreach($latestusers as $user){
                        echo  $user['username'] . ' <span class="btn btn-success  "><i class="fa fa-edit"></i><a href="members.php?do=edit&userID='. $user['userID'].'">edit</a></span></br>' ;
                    }?>
                
                
                </li>
            </ul>
            
        </div>
        <div class='col-sm-6' >
            <ul class="ul list-group">
                <i class='fa fa-tag'></i>latest items
                <li class="ut list-group-item"><?php
                    $latestitems=getlatest('*','items','items_ID',3);
                    foreach($latestitems as $item){
                        echo  $item['name'] . ' <span class="btn btn-success  "><i class="fa fa-edit"></i><a href="items.php?do=edit&items_ID='. $item['items_ID'].'">edit</a></span></br>' ;
                    }?></li>
            </ul>
            
        </div>


    </div>
    
       


    



</div>











