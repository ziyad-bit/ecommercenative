<?php

session_start();

if (isset($_SESSION['user'])){
  header('location:index.php');  
}

$pagetitel='login';




include 'init.php';


if($_SERVER['REQUEST_METHOD']=='POST'){
    $username=$_POST['username'];
    $password=$_POST['password'];
    $hashedpassword=sha1($password);


    $stmt=$con->prepare('SELECT username , password , userID   FROM users where username=? and password=?  ');
    $stmt->execute(array($username,$hashedpassword));
    $ID=$stmt->fetch();
 
    $count=$stmt->rowcount();
   
    
  
    if($count>0){
      $_SESSION['user']=$username;
      $_SESSION['uID']=$ID['userID'];
      
    
      header('location:index.php');
      
      exit();
     
    }
    else{
      if(isset($_POST['username'])){
        $filtername=filter_var($_POST['username'],FILTER_SANITIZE_STRING);
        
        
      }
    }

}


?>



<div class="card text-white bg-dark mb-3" style="max-width: 18rem;">
  <div class="card-header"> <h2> login</h2></div>
  <div class="card-body">
    <form action='/ecommerce/login.php' method='POST'>
    <div class="form-group">
        <label for="exampleInputEmail1">username</label>
        <input type="text" name='username' required='required' class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
       
    </div>
   
    <div class="form-group">
        <label for="exampleInputPassword1">Password</label>
        <input type="password" name='password' required='required' class="form-control" id="exampleInputPassword1">
    </div>
    <div class="form-group">
    <input type="submit" value='login'  class=" btn btn-primary"></input>
    </div>
    </form>
    <p>you havnt account <a href="signup.php"><h4>signup</h4> </a></p>
  </div>
</div>


