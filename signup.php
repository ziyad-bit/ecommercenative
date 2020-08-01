<?php
session_start();

if (isset($_SESSION['user'])){
  header('location:index.php');  
}

$pagetitel='signup';


include 'init.php';

if($_SERVER['REQUEST_METHOD']=='POST'){
  $username=$_POST['username'];
  $password=$_POST['password'];
  $hashedpassword=sha1($password);


  $stmt=$con->prepare('SELECT username , password   FROM users where username=? and password=?  ');
  $stmt->execute(array($username,$hashedpassword));

  $count=$stmt->rowcount();
 
  

  if($count>0){
    $_SESSION['user']=$username;
    
  
    header('location:index.php');
    
    exit();
   
  }
  else{
      $formerrors=array();


      $username=$_POST['username'];
      $email=$_POST['email'];
      $password=sha1($_POST['password']);
      $password2=sha1($_POST['password2']);

    if(isset($username)){
      $filtername=filter_var($username,FILTER_SANITIZE_STRING);
      
      
      if(strlen($filtername)<4){
        $formerrors[]='username should be larger than 3 characters';
      }
    }

    if(isset( $password)&&isset($password2)){
      if(empty( $password)){
        $formerrors[]='passwords cant be empty';
      }
      $pass1=sha1($password) ;
      $pass2=sha1($password2) ;
      if($pass1!==$pass2){
        $formerrors[]='passwords are not match';
      }
    }
    if(isset($email)){
      $filteremail=filter_var($email,FILTER_SANITIZE_EMAIL);
    if (filter_var($email,FILTER_VALIDATE_EMAIL) != true){
      $formerrors[]='your emai is not valid';
    }
     
     
  
  }

    if (empty($formerrors)){

                
      $check= checkitem('username','users',$username);
      if($check==1){
        $formerrors[]='this username is exist';
      }
      else{
          $stmt=$con->prepare('INSERT INTO users(username , password , email,regstatus,date) VALUES (:zuser,:zpassword,:zemail,0,now())');
          $stmt->execute(array('zuser'=>$username,'zpassword'=>$password,'zemail'=>$email));
          $smsg='congratulation you are registered';
      }


       
}



 }
    
}


?>



<div class="card text-white bg-dark mb-3" style="max-width: 25rem;">
  <div class="card-header"> <h2>signup</h2></div>
  <div class="card-body">
  <form action='/ecommerce/signup.php' method='POST'>
    <div class="form-group">
        <label for="exampleInputEmail1">username</label>
        <input type="text" pattern='.{4,15}' title='username should be 4 characters and less than 15' name='username' required='required' class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
       
    </div>
    <div class="form-group">
        <label for="exampleInputEmail1">Email address</label>
        <input type="email" name='email' pattern='.{4,}' title='email should be 4 characters or more ' required='required' class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
       
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">Password</label>
        <input type="password" name='password'  pattern='.{6,}' title='password should be 6 characters or more ' required='required' class="form-control" id="exampleInputPassword1">
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">Password confirm</label>
        <input type="password" name='password2'  pattern='.{6,}' title='password should be 6 characters or more ' required='required' class="form-control" id="exampleInputPassword1">
    </div>
    <div class="form-group">
    <input type="submit" value='signup'  class=" btn btn-success"></input>
    </div>
    </form>
    <p>you have account <a href="login.php"><h4>login</h4> </a></p>
  </div>
  <div class='errors'>
    <?php
    if(!empty($formerrors)){
      foreach($formerrors as $error){
        echo '<div class="errors" >'. $error . '</div> </br>';
      }
    }

    if(isset($smsg)){
      echo '<div class="success" >'. $smsg . '</div> ';
    }
  ?>
  </div>
</div>

