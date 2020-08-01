<?php
session_start();

$pagetitel='members';

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
    $pend='';
    if(isset($_GET['page'])&&$_GET['page']=='pending'){
        $pend='AND regstatus = 0';
    }
    


    $stmt=$con->prepare("SELECT * FROM users WHERE groupID !=1 $pend ORDER BY userID DESC ");
    $stmt->execute();
    $rows=$stmt->fetchAll();

    
    
    ?>
    <h1 class='text-center'>manage members</h1>
    <div class='container'>
        <div class='table-responsive'>
            <table class='my-table text-center table table-bordered'>
                <tr >
                    <td>userID</td>
                    <td>username</td>
                    <td>img</td>
                    <td>email</td>
                    <td>fullname</td>
                    <td>regesterd date</td>
                    <td>control</td>
                </tr>
                
                    <?php
                    foreach($rows as $row){
                    echo'<tr>';    
                        echo"<td>". $row['userID'] . "</td>";
                        echo"<td>". $row['username'] . "</td>";
                        echo"<td><img src='imgs/uploads/".$row['img']."'/></td>";
                       
                        echo'<td>'. $row['email'] . '</td>';
                        echo'<td>'. $row['fullname'] . '</td>';
                        echo'<td>' . $row["date"] .'</td>';
                        
                       echo "<td>
                            <a href='members.php?do=edit&userID=". $row['userID'] ."'   class='btn btn-success'><i class='fa fa-edit'></i> edit</a>
                            <a href='members.php?do=delete&userID=". $row['userID'] ."' class='btn btn-danger'><i class='fas fa-skull-crossbones'></i>delete</a>";
                            if($row['regstatus']==0){
                                echo "<a href='members.php?do=activate&userID=". $row['userID'] ."' class='btn btn-info activate'><i class='fas fa-skull-crossbones'></i>activate</a>" ;
                            }

                         echo   "</td>";
                 echo'</tr>';   
                    }
                   
               
              ?>
            </table>
        </div>
                    
    
        <a href="members.php?do=add" class='add btn btn-primary'>add member  </a>
    </div>
<?php }


elseif($do=='activate'){
    echo "<h1 class='text-center'>update</h1>";
    echo '<div class="container" >';

        if(isset($_GET['userID']) && is_numeric($_GET['userID'])){
            $userID=$_GET['userID'];
           
            
            $check=checkitem('userID','users',$userID);
            

            if ($check>0){
            $stmt=$con->prepare('UPDATE users SET regstatus=1 WHERE userID=:userID');
            $stmt->bindParam(':userID',$userID,PDO::PARAM_INT);
            $stmt->execute();
            $msg='<div class="alert alert-success" role="alert">' . $stmt->rowcount() . 'member activated</div>'; 
            redirect($msg,'back')  ;  
        }
        else{
            $msg= '<div class="alert alert-danger">id not exist</div>';
            redirect($msg,'back');
        } 
    echo '</div>';
    }
}



elseif($do=='add'){?>
   
    <h1 class='text-center'>add member</h1>
    <div class='container'>
        <form action="?do=insert" method='POST' enctype='multipart/form-data'>
            <input type='hidden' name='userID' value='<?php echo $userID?>'>
            <div class="input-group flex-nowrap">
            <div class="input-group-prepend">
                <span class="input-group-text" id="addon-wrapping">username</span>
            </div >
            <div class='col-sd-4'>
                <input type="text" required='required'  name='username' class="form-control"  aria-label="Username" aria-describedby="addon-wrapping">
            </div>
            </div>

            <div class="input-group flex-nowrap">
            <div class="input-group-prepend">
                <span class="input-group-text" id="addon-wrapping">password</span>
            </div>
            <div class='col-sd-4'>
                
                <input type="password" name=' password' required='required' class="form-control"  aria-label="Username" aria-describedby="addon-wrapping">
               
            </div>
            </div>

            <div class="input-group flex-nowrap">
            <div class="input-group-prepend">
                <span class="input-group-text" id="addon-wrapping">photo</span>
            </div>
            <div class='col-sd-4'>
                
                <input type="file" name='img' required='required' class="form-control"  aria-label="Username" aria-describedby="addon-wrapping">
               
            </div>
            </div>

            <div class="input-group flex-nowrap">
            <div class="input-group-prepend">
                <span class="input-group-text" id="addon-wrapping">your mail </span>
            </div>
            <div class='col-sd-5'>
                <input type="email" required='required' name='email'  class="form-control"  aria-label="Username" aria-describedby="addon-wrapping">
            </div>
            </div>

            <div class="input-group flex-nowrap">
            <div class="input-group-prepend">
                <span class="input-group-text" id="addon-wrapping">full name</span>
            </div>
            <div class='col-sd-4'>
                <input type="text" name='fullname'  class="form-control"  aria-label="Username" aria-describedby="addon-wrapping">
            </div>
            </div>

            <input type="submit" value='add member' class=" btn btn-primary" />
        </form>
    </div>
<?php }

if($do=='insert'){

    echo "<h1 class='text-center'>insert</h1>";
    echo '<div class="container" >';
    if ($_SERVER['REQUEST_METHOD']=='POST'){
           $username=$_POST['username'];
           $password=sha1($_POST['password']);
           $fullname=$_POST['fullname'];
           $email=$_POST['email'];
           $img=$_FILES['img'];

           $hashpass=$_POST['password'];
          
           $imgname=$_FILES['img']['name'];
           $imgtmp=$_FILES['img']['tmp_name'];
           $imgsize=$_FILES['img']['size'];
           $imgtype=$_FILES['img']['type'];
            $imgAextension=array('jpg','jpeg','gif','png','webp');
            $tmp=explode('.',$imgname);
            $img2=strtolower(end($tmp));
            
          
       
           $formerrors=array();

           if(strlen($username)<4){
                $formerrors[]='<div class="alert alert-danger" role="alert">username cant be less than 4 characters</div>';
           }

           if(strlen($username)>15){
            $formerrors[]='<div class="alert alert-danger" role="alert">username cant be more than 15 characters;</div>';
       }

          
           if(empty($username)){
                $formerrors[]='<div class="alert alert-danger" role="alert">username cant be empty</div>';
                
           }
           if(empty($password)){
            $formerrors[]='<div class="alert alert-danger" role="alert">password cant be empty</div>';
            
       }
           if(empty($fullname)){
            $formerrors[]='<div class="alert alert-danger" role="alert">fullname cant be empty</div>';
            
        }
            if(empty($email)){
            $formerrors[]='<div class="alert alert-danger" role="alert">email cant be empty</div>';
            
       }
            if(empty($imgname)){
                $formerrors[]='<div class="alert alert-danger" role="alert">img cant be empty</div>';
            }
            if($imgsize>4000000){
                $formerrors[]='<div class="alert alert-danger" role="alert">size of img cant be more than 4mg</div>';
            }


            if(! in_array($img2,$imgAextension)){
                $formerrors[]='<div class="alert alert-danger" role="alert">this extension is not allowed </div>';
    }
            foreach($formerrors as $error){
            echo $error ;
        }
             if (empty($formerrors)){
                $finalimg=rand(0,1000000). '_'. $imgname;
                move_uploaded_file($imgtmp,'imgs\uploads\\'.$finalimg);
             
              
                $check= checkitem('username','users',$username);
                if($check==1){
                    $msg= '<div class="alert alert-danger">sorry this user is exist</div>';
                    redirect($msg,'back');
                }
                else{
                    $stmt=$con->prepare('INSERT INTO users(username , password , email,fullname,regstatus,date,img) VALUES (:zuser,:zpassword,:zemail,:zfullname,1,now(),:zimg)');
                    $stmt->execute(array('zuser'=>$username,'zpassword'=>$password,'zemail'=>$email,'zfullname'=>$fullname,'zimg'=>$finalimg));
                    $msg='<div class="alert alert-success" role="alert">' . $stmt->rowcount() . 'inserted</div>';         
                    redirect($msg,'back');    
                }


                   
            }
           
           

 
    }
 
    else {
       echo "<div class='container'>" ;
       $msg= "<div class='alert alert-danger'>you cant browse this page directly</div>";
       redirect($msg);
       echo '</div>';
    }
    echo '</div>';
 
    
}
elseif($do=='delete'){

    echo "<h1 class='text-center'>update</h1>";
    echo '<div class="container" >';

        if(isset($_GET['userID']) && is_numeric($_GET['userID'])){
            $userID=$_GET['userID'];
            $stmt=$con->prepare('SELECT * FROM users where userID=? LIMIT 1 ');
            
            $check=checkitem('userID','users',$userID);
            

            if ($check>0){
            $stmt=$con->prepare('DELETE FROM users WHERE userID=:zuser');
            $stmt->bindParam(':zuser',$userID);
            $stmt->execute();
            $msg='<div class="alert alert-success" role="alert">' . $stmt->rowcount() . 'member deleted</div>'; 
            redirect($msg)  ;  
        }
        else{
            $msg= '<div class="alert alert-danger">id not exist</div>';
            redirect($msg,'back');
        } 
    echo '</div>';
    }
    
}   

elseif($do=='edit'){
    if(isset($_GET['userID']) && is_numeric($_GET['userID'])){
        $userID=$_GET['userID'];
        $stmt=$con->prepare('SELECT * FROM users where userID=? LIMIT 1 ');
        $stmt->execute(array($userID));
        $row=$stmt->fetch();
        $count=$stmt->rowcount();

        if($stmt->rowcount()>0){?>
            <h1 class='text-center'>edit member</h1>
            <div class='container'>
                <form action="?do=update" method='POST'>
                    <input type='hidden' name='userID' value='<?php echo $userID?>'>
                    <div class="input-group flex-nowrap">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="addon-wrapping">username</span>
                    </div >
                    <div class='col-sd-4'>
                        <input type="text" required='required' value="<?php echo $row['username']?>" name='username' class="form-control"  aria-label="Username" aria-describedby="addon-wrapping">
                    </div>
                    </div>

                    <div class="input-group flex-nowrap">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="addon-wrapping">password</span>
                    </div>
                    <div class='col-sd-4'>
                        <input type="hidden" name=' password' value="<?php echo $row['password']?>" >
                        <input type="password" name=' newpassword' class="form-control"  aria-label="Username" aria-describedby="addon-wrapping">
                    </div>
                    </div>

                    <div class="input-group flex-nowrap">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="addon-wrapping">your mail </span>
                    </div>
                    <div class='col-sd-5'>
                        <input type="email" required='required' name='email' value="<?php echo $row['email']?>" class="form-control"  aria-label="Username" aria-describedby="addon-wrapping">
                    </div>
                    </div>

                    <div class="input-group flex-nowrap">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="addon-wrapping">full name</span>
                    </div>
                    <div class='col-sd-4'>
                        <input type="text" name='fullname' value="<?php echo $row['fullname']?>" class="form-control"  aria-label="Username" aria-describedby="addon-wrapping">
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
           $username=$_POST['username'];
           $fullname=$_POST['fullname'];
           $email=$_POST['email'];
           $userID=$_POST['userID'];

           $password='';
           if(empty($_POST['password'])){
               $password=$_POST['password'];
           }
           else{
               $password=sha1($_POST['newpassword']);
           }
           $formerrors=array();

           if(strlen($username)<4){
                $formerrors[]='<div class="alert alert-danger" role="alert">username cant be less than 4 characters</div>';
           }

           if(strlen($username)>15){
            $formerrors[]='<div class="alert alert-danger" role="alert">username cant be more than 15 characters;</div>';
       }

          
           if(empty($username)){
                $formerrors[]='<div class="alert alert-danger" role="alert">username cant be empty</div>';
                
           }
           if(empty($fullname)){
            $formerrors[]='<div class="alert alert-danger" role="alert">fullname cant be empty</div>';
            
        }
            if(empty($email)){
            $formerrors[]='<div class="alert alert-danger" role="alert">email cant be empty</div>';
            
       }
            foreach($formerrors as $error){
            echo $error ;
        }
            if (empty($formerrors)){
                $stmt2=$con->prepare("SELECT * FROM users WHERE username=?AND userID !=?");
                $stmt2->execute(array($username,$userID));
                $count=$stmt2->rowCount();
                if($count==1){
                    $msg='<div class="alert alert-danger" role="alert">  this user is exist</div>';
                    redirect($msg,'back'); 
                }
                else{
                    $stmt=$con->prepare('UPDATE users SET username=? , email=? ,fullname=? , password=? WHERE userID=?');
                    $stmt->execute(array($username,$email,$fullname,$password ,$userID));
                    $msg='<div class="alert alert-success" role="alert">' . $stmt->rowcount() . 'updated</div>'; 
                    redirect($msg,'back');  
                }

                         
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
 
  
}



else{
    header('location:index.php');
    exit();
}

?>


