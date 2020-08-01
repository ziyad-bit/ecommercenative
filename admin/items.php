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

   
    


    $stmt=$con->prepare("SELECT items.* , categories.name AS category_name , username FROM items 
                        INNER JOIN categories ON categories.ID = items.cat_ID
                        INNER JOIN users ON users.userID = items.member_ID
                        ORDER BY items_ID DESC"
                        );
    $stmt->execute();
    $items=$stmt->fetchAll();

    
    
    ?>
    <h1 class='text-center'>manage items</h1>
    <div class='container'>
        <div class='table-responsive'>
            <table class='my-table text-center table table-bordered'>
                <tr >
                    <td>ID</td>
                    <td>name</td>
                    <td>description</td>
                    <td>price</td>
                    <td>date</td>
                    <td>category</td>
                    <td>username</td>
                    <td>control</td>
                </tr>
                
                    <?php
                    foreach($items as $item){
                    echo'<tr>';    
                        echo"<td>". $item['items_ID'] . "</td>";
                        echo'<td>'. $item['name'] . '</td>';
                        echo'<td>'. $item['description'] . '</td>';
                        echo'<td>'. $item['price'] . '</td>';
                        echo'<td>' . $item["date"] .'</td>';
                      
                        echo'<td>'. $item['category_name'] . '</td>';
                        echo'<td>'. $item['username'] . '</td>';
                        
                        
                       echo "<td>
                            <a href='items.php?do=edit&items_ID=". $item['items_ID'] ."'   class='btn btn-success'><i class='fa fa-edit'></i> edit</a>
                            <a href='items.php?do=delete&items_ID=". $item['items_ID'] ."' class='btn btn-danger'><i class='fas fa-skull-crossbones'></i>delete</a>";
                            if($item['approve']==0){
                                echo "<a href='items.php?do=approve&items_ID=". $item['items_ID'] ."' class='btn btn-info activate'><i class='fas fa-thumbs-up'></i>activate</a>" ;
                            }

                         echo   "</td>";
                 echo'</tr>';   
                    }
                   
               
              ?>
            </table>
        </div>
                    
    
        <a href="items.php?do=add" class='add btn btn-primary'>add items  </a>
    </div>
<?php 
   

}

elseif($do=='add'){

   ?>
            <div class="card text-white bg-dark mb-3" style="max-width: 30rem;">
                <div class="card-header"><h2>Add items</h2></div>
                <div class="card-body"> 
                <form action="?do=insert" method='post'>
                <div class="form-group">
                    <label for="formGroupExampleInput">name</label>
                    <input type="text" required='required' class="form-control"  name='name' id="formGroupExampleInput" placeholder="write name of item">
                 
                </div>
                <div class="form-group">
                    <label for="formGroupExampleInput2">description</label>
                    <input type="text" class="form-control"  required='required' name='description' id="formGroupExampleInput2" placeholder="write your description">
                </div>
                <div class="form-group">
                    <label for="formGroupExampleInput2">price</label>
                    <input type="text" class="form-control"  required='required' name='price' id="formGroupExampleInput2" placeholder="price of item">
                </div>
                <div class="form-group">
                    <label for="formGroupExampleInput2">country made</label>
                    <input type="text" class="form-control" required='required' name='country_made' id="formGroupExampleInput2" placeholder="item is made in">
                </div>
                <div class="form-group">
                    <label for="formGroupExampleInput2">status</label>
                    <select name="status"  class='form-control' id="">
                        <option value="1">new</option>
                        <option value="2">used</option>
                        <option value="3">very old</option>
                    </select>
                       
                       
                </div>
                <div class="form-group">
                    <label for="formGroupExampleInput2">member</label>
                    <select name="member"  required='required' class='form-control' id="">
                        <option value="0">...</option>
                      <?php 
                      $stmt=$con->prepare("SELECT * FROM users");
                      $stmt->execute();
                      $users=$stmt->fetchAll();
                      foreach($users as $user){
                          echo "<option value='".$user['userID']."'>".$user['username']."<option>";
                      }?>
                        
                    </select>
                       
                       
                </div>
                <div class="form-group">
                    <label for="formGroupExampleInput2">category</label>
                    <select name="category"  required='required' class='form-control' id="">
                        <option value="0">...</option>
                      <?php 
                      $stmt2=$con->prepare("SELECT * FROM categories");
                      $stmt2->execute();
                      $cats=$stmt2->fetchAll();
                      foreach($cats as $cat){
                          echo "<option value='".$cat['ID']."'>".$cat['name']."<option>";
                      }?>
                        
                    </select>
                       
                       
                </div>
               
                       
                       
                
                <input type="submit" class="save btn btn-primary" value="add item">
                </form>
                    
                    
                </div>
            </div>


   <?php
          
       
    

}

elseif($do=='insert'){
    echo "<h1 class='text-center'>insert item</h1>";
    echo '<div class="container" >';
    if ($_SERVER['REQUEST_METHOD']=='POST'){
           $name=$_POST['name'];
           $description=$_POST['description'];
           $price=$_POST['price'];
           $country_made=$_POST['country_made'];
           $status=$_POST['status'];
           $category=$_POST['category'];
           $member=$_POST['member'];
           

          
       
           $formerrors=array();

           if(strlen($name)<4){
                $formerrors[]='<div class="alert alert-danger" role="alert">name cant be less than 4 characters</div>';
           }

           if(strlen($name)>15){
            $formerrors[]='<div class="alert alert-danger" role="alert">name cant be more than 15 characters;</div>';
       }

          
           if(empty($description)){
                $formerrors[]='<div class="alert alert-danger" role="alert">description cant be empty</div>';
                
           }
           if(empty($price)){
            $formerrors[]='<div class="alert alert-danger" role="alert">price cant be empty</div>';
            
       }
           
           
            foreach($formerrors as $error){
                echo $error ;
        }
            if (empty($formerrors)){

               
               
                
               
                $stmt=$con->prepare('INSERT INTO items(name ,description , price,status,country_made,date,cat_ID,member_ID) VALUES (:zname,:zdesc,:zprice,:zstatus,:zcountry_made,now(),:zcat,:zmember)');
                $stmt->execute(array('zname'=>$name,'zdesc'=>$description,'zprice'=>$price,'zstatus'=>$status,'zcountry_made'=>$country_made,'zcat'=>$category,'zmember'=>$member));
                $msg='<div class="alert alert-success" role="alert">' . $stmt->rowcount() . 'item added</div>';         
                redirect($msg,'back');    
                


                   
            }
           
           

 
    }
 
    else {
       echo "<div class='container'>" ;
       $msg= "<div class='alert alert-danger'>you cant browse this page directly</div>";
       redirect($msg,'back');
       echo '</div>';
    }
    echo '</div>';

}



elseif($do=='edit'){

    if(isset($_GET['items_ID']) && is_numeric($_GET['items_ID'])){
        $items_ID=$_GET['items_ID'];
        $stmt=$con->prepare('SELECT * FROM items where items_ID=? LIMIT 1 ');
        $stmt->execute(array($items_ID));
        $item=$stmt->fetch();
        $count=$stmt->rowcount();

        if($stmt->rowcount()>0){?>
            <div class="card text-white bg-dark mb-3" style="max-width: 30rem;">
                <div class="card-header"><h2>edit items</h2></div>
                <div class="card-body"> 
                <form action="?do=update" method='post'>
                <input type='hidden' name='items_ID' value='<?php echo $items_ID?>'>
                <div class="form-group">
                    <label for="formGroupExampleInput">name</label>
                    <input type="text" required='required' value="<?php echo $item['name']?>" class="form-control"  name='name' id="formGroupExampleInput" placeholder="write name of item">
                 
                </div>
                <div class="form-group">
                    <label for="formGroupExampleInput2">description</label>
                    <input type="text" class="form-control" value="<?php echo $item['description']?>" required='required' name='description' id="formGroupExampleInput2" placeholder="write your description">
                </div>
                <div class="form-group">
                    <label for="formGroupExampleInput2">price</label>
                    <input type="text" class="form-control" value="<?php echo $item['price']?>"  required='required' name='price' id="formGroupExampleInput2" placeholder="price of item">
                </div>
                <div class="form-group">
                    <label for="formGroupExampleInput2">country made</label>
                    <input type="text" class="form-control" required='required' value="<?php echo $item['country_made']?>" name='country_made' id="formGroupExampleInput2" placeholder="item is made in">
                </div>
                <div class="form-group">
                    <label for="formGroupExampleInput2">status</label>
                    <select name="status"  class='form-control' id="">
                        <option value="1" <?php if($item['status']==1){echo 'selected';}?>>new</option>
                        <option value="2"  <?php if($item['status']==2){echo 'selected';}?>>used</option>
                        <option value="3" <?php if($item['status']==3){echo 'selected';}?> >very old</option>
                    </select>
                       
                       
                </div>
                <div class="form-group">
                    <label for="formGroupExampleInput2">member</label>
                    <select name="member"  required='required' class='form-control' id="">
                        <option value="0">...</option>
                      <?php 
                      $stmt=$con->prepare("SELECT * FROM users");
                      $stmt->execute();
                      $users=$stmt->fetchAll();
                      foreach($users as $user){
                          echo "<option value='".$user['userID']."'";if($item['member_ID']==$user['userID']){echo 'selected';}echo">".$user['username']."<option>";
                      }?>
                        
                    </select>
                       
                       
                </div>
                <div class="form-group">
                    <label for="formGroupExampleInput2">category</label>
                    <select name="category"  required='required' class='form-control' id="">
                        <option value="0">...</option>
                      <?php 
                      $stmt2=$con->prepare("SELECT * FROM categories");
                      $stmt2->execute();
                      $cats=$stmt2->fetchAll();
                      foreach($cats as $cat){
                          echo "<option value='".$cat['ID']."'";if($item['cat_ID']==$cat['ID']){echo 'selected';}echo">".$cat['name']."<option>";
                      }?>
                        
                    </select>
                       
                       
                </div>
               
                       
                       
                
                <input type="submit" class="save btn btn-primary" value="add item">
                </form>
                    
                    
                </div>
                
            </div> <?php
            $stmt=$con->prepare("SELECT comments.*,users.username FROM comments
                      
                        INNER JOIN users ON users.userID=comments.user_ID
                        WHERE item_ID=? ");
    $stmt->execute(array($items_ID));
    $rows=$stmt->fetchAll();

    
    
    ?>
    <h1 class='text-center'>manage [<?php echo $item['name']?>] comments</h1>
    <div class='container'>
        <div class='table-responsive'>
            <table class='my-table text-center table table-bordered'>
                <tr >
                   
                    <td>comment</td>
                   
                    <td>user name</td>
                    <td> date</td>
                    <td>control</td>
                </tr>
                
                    <?php
                    foreach($rows as $row){
                    echo'<tr>';    
                        
                        echo'<td>'. $row['comment'] . '</td>';
                    
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

elseif($do=='update'){

    echo "<h1 class='text-center'>update item</h1>";
    echo '<div class="container" >';
    if ($_SERVER['REQUEST_METHOD']=='POST'){
           $name=$_POST['name'];
           $description=$_POST['description'];
           $price=$_POST['price'];
           $items_ID=$_POST['items_ID'];
           $country_made=$_POST['country_made'];
           $member=$_POST['member'];
           $category=$_POST['category'];
           $status=$_POST['status'];

           $formerrors=array();

           if(strlen($name)<4){
                $formerrors[]='<div class="alert alert-danger" role="alert">name cant be less than 4 characters</div>';
           }

           if(strlen($name)>15){
            $formerrors[]='<div class="alert alert-danger" role="alert">name cant be more than 15 characters;</div>';
       }

          
           if(empty($description)){
                $formerrors[]='<div class="alert alert-danger" role="alert">description cant be empty</div>';
                
           }
           if(empty($price)){
            $formerrors[]='<div class="alert alert-danger" role="alert">price cant be empty</div>';
            
       }
           
           
            foreach($formerrors as $error){
                echo $error ;
        } 
            if (empty($formerrors)){
                $stmt=$con->prepare('UPDATE items SET name=? , description=?,price=? ,country_made=? , status=?,member_ID=?,cat_ID=? WHERE items_ID=?');
                $stmt->execute(array($name,$description,$price,$country_made ,$status,$member,$category,$items_ID));
                $msg='<div class="alert alert-success" role="alert">' . $stmt->rowcount() . 'updated</div>'; 
                redirect($msg,'back');           
            }
           
           

 
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

        if(isset($_GET['items_ID']) && is_numeric($_GET['items_ID'])){
            $items_ID=$_GET['items_ID'];
            $stmt=$con->prepare('SELECT * FROM items where items_ID=? LIMIT 1 ');
            
            $check=checkitem('items_ID','items',$items_ID);
            

            if ($check>0){
            $stmt=$con->prepare('DELETE FROM items WHERE items_ID=:zuser');
            $stmt->bindParam(':zuser',$items_ID);
            $stmt->execute();
            $msg='<div class="alert alert-success" role="alert">' . $stmt->rowcount() . 'item deleted</div>'; 
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

        if(isset($_GET['items_ID']) && is_numeric($_GET['items_ID'])){
            $items_ID=$_GET['items_ID'];
           
            
            $check=checkitem('items_ID','items',$items_ID);
            

            if ($check>0){
            $stmt=$con->prepare('UPDATE items SET approve=1 WHERE items_ID=?');
           
            $stmt->execute(array($items_ID));
            $msg='<div class="alert alert-success" role="alert">' . $stmt->rowcount() . 'item approved</div>'; 
            redirect($msg,'back')  ;  
        }
        else{
            $msg= '<div class="alert alert-danger">id not exist</div>';
            redirect($msg);
        } 
    echo '</div>';
    }

}

else{
    header('location:index.php');
    exit();
}
