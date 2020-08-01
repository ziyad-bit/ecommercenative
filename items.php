<?php
session_start();

$pagetitel='show items';

include "init.php";


if(isset($_GET['items_ID']) && is_numeric($_GET['items_ID'])){
    $items_ID=$_GET['items_ID'];
    $stmt=$con->prepare('SELECT items.* , categories.name AS category_name, users.username  FROM items 
                        INNER JOIN categories ON categories.ID=items.cat_ID
                        INNER JOIN users ON users.userID=items.member_ID
                        where items_ID=? AND approve=1  ');
    $stmt->execute(array($items_ID));
    $count=$stmt->rowcount();
    
    
    if($count>0){
        
    

    $item=$stmt->fetch();
    
    
    
   
    ?>
    <h1 class='text-center'>items</h1>
    <div class='container'>
        <div class='row'>
            <div class='col-md-4'>
            <div class="it card" style="width: 18rem;">
                <img src="download.jpg" class="card-img-top" alt="...">
            </div>
            </div>

            <div class='col-md-8'>
                <ul class='i list-group'>
                    <li class='ig list-group-item'><span>name</span>:<?php  echo $item['name']; ?></li>   
                    <li class='ig list-group-item'><span>description</span>:<?php  echo $item['description']; ?></li>    
                    <li class='ig list-group-item'><span>date</span>:<?php  echo $item['date']; ?> </li>
                        
                    <li class='ig list-group-item'><span>user</span>:<?php  echo $item['username']; ?></li>
                    <li class='ig list-group-item'><span>category</span>: <a href="categories.php?pageID=<?php echo $item['cat_ID'];?>"> <?php  echo $item['category_name']; ?></a></li> 
                    <li class='ig list-group-item'><span>price </span>:  <?php  echo $item['price']; ?></li>
                </ul>  

            
            </div>
        
        
        </div>
        <hr>
    <?php    if (isset($_SESSION['user'])){?>
        <div class='row'>
            <div class='comment col-md-8 offset-md-4'>
                <h4>add comment</h4>
                <form action="/ecommerce/items.php?items_ID=<?php echo $item['items_ID'];?>" method='POST'>
                <textarea name="comment"  required='required' class='form-control'></textarea>
                <input type="submit" value='add comment' class='btn btn-primary'>
                </form>
                <?php
                if($_SERVER['REQUEST_METHOD']=='POST'){
                    $comment=filter_var($_POST['comment'],FILTER_SANITIZE_STRING);
                    $itemID=$item['items_ID'];
                    $userID=$_SESSION['uID'];
                    

                    if(!empty($comment)){
                        $stmt=$con->prepare('INSERT INTO 
                                            comments(comment , status , com_date , item_ID , user_ID)
                                            VALUES(:zcomment , 1 , NOW() , :zitem_ID , :zuser_ID)');
                        
                        $stmt->execute(array(
                            'zcomment'=>$comment,
                            
                            'zitem_ID'=>$itemID,
                            'zuser_ID'=>$userID

                        ));
                    
                        
                        echo '<div class="alert alert-success"> comment added </div>';
                        
                    
                    }    



                }
                    
                      
                    
                    ?>


            </div>
            

        </div>
    <?php } 
            else{
                echo '<a href="login.php"> login<a> to add comment';
            }?>

        <hr>
        <?php

        $stmt=$con->prepare("SELECT comments.* , users.username FROM comments
                       
                            INNER JOIN users ON users.userID=comments.user_ID 
                            where item_ID=? AND status=1");

        $stmt->execute(array($item['items_ID']));
        $comments=$stmt->fetchAll();

           ?>

        <script>
            if ( window.history.replaceState ) {
                window.history.replaceState( null, null, window.location.href );
            }
        </script>

        
           
                <?php
                foreach($comments as $comment){?>
                    <div class='comi row'>
                        <div class="col-md-2 text-center">
                            <img src="download.jpg " class="card-img-top" alt="...">
                        
                            <?php echo $comment['username'];?> 
                        
                        </div>
                        <div class="comi2 col-md-10"><h4><?php echo $comment['comment'];?></h4> </div>

                       
                        
                    </div>

                    <hr>
               <?php }
            ?>
            
                

      
       
    
    
    
    </div>



<?php }
else{
    ?>
    <h2 class='text-center alert alert-danger'> not approved item</h2>
<?php }


}
    

        
       



   
    
 


   

 
 