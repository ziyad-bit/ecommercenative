<?php
session_start();

$pagetitel='profile';

include "init.php";

if (isset($_SESSION['user'])){
    $stmt3=$con->prepare("SELECT * FROM users where username=?");
    $stmt3->execute(array($sessionuser));
    $info=$stmt3->fetch();
    





    ?>
    <div class='pro container'>
        <ul class="list-group">
            <li class="list-group-item active">information</li>
          
            <li class="list-group-item">
                <i class="fas fa-user-tie"></i>
                <span>name</span>  : <?php echo  $info['username'] ;?>  
            
            </li>
            <li class="list-group-item">
                <i class="fas fa-envelope"></i>
                <span>email</span> : <?php echo  $info['email'] ;?>
        
            </li>
            <li class="list-group-item">
                <i class="fas fa-calendar-alt"></i>
                <span>register date</span> : <?php echo  $info['date'] ;?>   
        
            </li>
            <li class="list-group-item">
                <i class="fas fa-tag"></i>
                <span>Fav category</span> : <?php  ?></br> 
        
            </li>
        
        </ul>
        <a href="" class='p btn btn-success'>Edit </a>
    </div>


    <div class='pro container'>
        <ul class="list-group">
        <li class="list-group-item active">ads</li>
        <li class="list-group-item">
        <div class="row">
        
        <?php
    foreach(getitems('member_ID',$info['userID'],1) as $item){

        echo '<div class="col-sm-6 col-md-4">';
            
                echo'<div class="it card" style="width: 18rem;">';
                if($item['approve']==0){
                    ?>
                    <p class='appr'>waiting for approve</p>
                    <?php
                } else{
                    ?>
                    <p class='appr2'>approved</p>
                    <?php
                }
               
                echo    '<img src="download.jpg" class="card-img-top" alt="...">';
                echo    '<div class="card-body">';
                echo        '<h3 class="card-title"><a href="items.php?items_ID='.$item["items_ID"].'">'. $item["name"].'</a></h3>';
                        
                echo        '<p class="card-text">'. $item["description"].'</p>';
                echo        '<h5 class="card-text">'. $item["date"].'</h5>';
                echo        '<h4 class="card-text">'. $item["price"].'</h4>';
                echo        '<a href="#" class="btn btn-primary"> buy</a>';
                echo    '</div>';
                echo'</div>';
        echo '</div>';
    }
   ?>
        
        
       </div>
        </li>
        
        </ul>

    </div>


   

<?php }
else{
    header('location:login.php');
    exit();
}    