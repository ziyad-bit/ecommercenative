<?php
session_start();

$pagetitel='comments';

if (isset($_SESSION['username'])){
    include 'init.php';
    include $tpl . '/navebar.php';

$do='';

if(isset($_GET['do'])){
    $do=$_GET['do'];
}
else{
    $do='manage';
}
if($do=='manage'){
  


    $stmt=$con->prepare("SELECT comments.*,items.name AS item_name,users.username FROM comments
                        INNER JOIN items ON items.items_ID=comments.item_ID 
                        INNER JOIN users ON users.userID=comments.user_ID ");
    $stmt->execute();
    $rows=$stmt->fetchAll();

    
    
    ?>
    <h1 class='text-center'>manage comments</h1>
    <div class='container'>
        <div class='table-responsive'>
            <table class='my-table text-center table table-bordered'>
                <tr >
                    <td>ID</td>
                    <td>comment</td>
                    <td>item name</td>
                    <td>user name</td>
                    <td> date</td>
                    <td>control</td>
                </tr>
                
                    <?php
                    foreach($rows as $row){
                    echo'<tr>';    
                        echo"<td>". $row['com_ID'] . "</td>";
                        echo'<td>'. $row['comment'] . '</td>';
                        echo'<td>'. $row['item_name'] . '</td>';
                        echo'<td>'. $row['username'] . '</td>';
                        echo'<td>' . $row["com_date"] .'</td>';
                        
                       echo "<td>
                            <a href='comments.php?do=edit&com_ID=". $row['com_ID'] ."'   class='btn btn-success'><i class='fa fa-edit'></i> edit</a>
                            <a href='comments.php?do=delete&com_ID=". $row['com_ID'] ."' class='btn btn-danger'><i class='fas fa-skull-crossbones'></i>delete</a>";
                            if($row['status']==0){
                                echo "<a href='comments.php?do=approve&com_ID=". $row['com_ID'] ."' class='btn btn-info activate'><i class='fas fa-thumbs-up'></i>approve</a>" ;
                            }

                         echo   "</td>";
                 echo'</tr>';   
                    }
                   
               
              ?>
            </table>
        </div>
                    
    
        
    </div>
<?php }


elseif($do=='edit'){
   
    if(isset($_GET['com_ID']) && is_numeric($_GET['com_ID'])){
        $com_ID=$_GET['com_ID'];
        $stmt=$con->prepare('SELECT * FROM comments where com_ID=? ');
        $stmt->execute(array($com_ID));
        $row=$stmt->fetch();
        $count=$stmt->rowcount();
       

        if($count>0){?>
            <h1 class='text-center'>edit comment</h1>
            <div class='container'>
                <form action="?do=update" method='POST'>
                    <input type='hidden' name='com_ID' value='<?php echo $com_ID?>'>
                    <div class="input-group flex-nowrap">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="addon-wrapping">comment</span>
                    </div >
                    <div class='col-sd-4'>
                        <textarea class='form-control' name="comment" id="" cols="30" rows="10"><?php echo $row['comment']?></textarea>
                    </div>
                    </div>

                    

                    <input type="submit" value='save' class="btn btn-primary" />
                </form>
            </div>
   <?php
        }  
        else{
            
            echo "<div class='container'>" ;
            $msg= "<div class='alert alert-danger'>wrong ID</div>";
            redirect($msg,);
            echo '</div>';
        }
    }

    
}

elseif ($do=='update'){
    echo "<h1 class='text-center'>update</h1>";
    echo '<div class="container" >';
    if ($_SERVER['REQUEST_METHOD']=='POST'){
           $com_ID=$_POST['com_ID'];
           $comment=$_POST['comment'];
           
          
           
                $stmt=$con->prepare('UPDATE comments SET comment=?  WHERE com_ID=?');
                $stmt->execute(array($comment,$com_ID));
                $msg='<div class="alert alert-success" role="alert">' . $stmt->rowcount() . 'updated</div>'; 
                redirect($msg,'back');           
            
           
           

 
    }
 
    else {
        echo '<div class="container">';
        $msg= '<div class="alert alert-danger">you cant browse this page directly</div>';
        redirect($msg);
        echo '</div>';
    }
    echo '</div>'; 
}

elseif($do=='delete'){

    echo "<h1 class='text-center'>update</h1>";
    echo '<div class="container" >';

        if(isset($_GET['com_ID']) && is_numeric($_GET['com_ID'])){
            $com_ID=$_GET['com_ID'];
            $stmt=$con->prepare('SELECT * FROM comments where com_ID=? LIMIT 1 ');
            
            $check=checkitem('com_ID','comments',$com_ID);
            

            if ($check>0){
            $stmt=$con->prepare('DELETE FROM comments WHERE com_ID=:zuser');
            $stmt->bindParam(':zuser',$com_ID);
            $stmt->execute();
            $msg='<div class="alert alert-success" role="alert">' . $stmt->rowcount() . 'comment deleted</div>'; 
            redirect($msg,'back')  ;  
        }
        else{
            $msg= '<div class="alert alert-danger">id not exist</div>';
            redirect($msg);
        } 
    echo '</div>';
    }
    
}   


elseif($do=='approve'){

    echo "<h1 class='text-center'>approve item</h1>";
    echo '<div class="container" >';

        if(isset($_GET['com_ID']) && is_numeric($_GET['com_ID'])){
            $com_ID=$_GET['com_ID'];
           
            
            $check=checkitem('com_ID','comments',$com_ID);
            

            if ($check>0){
            $stmt=$con->prepare('UPDATE comments SET status=1 WHERE com_ID=?');
           
            $stmt->execute(array($com_ID));
            $msg='<div class="alert alert-success" role="alert">' . $stmt->rowcount() . 'comment approved</div>'; 
            redirect($msg,'back')  ;  
        }
        else{
            $msg= '<div class="alert alert-danger">id not exist</div>';
            redirect($msg);
        } 
    echo '</div>';
    }

}


}


