<?php
session_start();

if (isset($_SESSION['username'])){
  header('location:dashboard.php');  
}

$pagetitel='login';

include "init.php";





if($_SERVER['REQUEST_METHOD']=='POST'){
    $username=$_POST['username'];
    $password=$_POST['password'];
    $hashedpassword=sha1($password);


    $stmt=$con->prepare('SELECT username , password ,userID  FROM users where username=? and password=? and groupID=1 ');
    $stmt->execute(array($username,$hashedpassword));
    $row=$stmt->fetch();
    $count=$stmt->rowcount();
   
    
  
    if($count>0){
      $_SESSION['username']=$username;
      $_SESSION['userID']=$row['userID'];
      header('location:dashboard.php');
      
      exit();
     
    }


}




?>





<form action='/ecommerce/admin/' method='POST'>
  <div class="card text-white bg-dark mb-3" style="max-width: 18rem;" >
  <div class="card-header">admin login</div>
  <div class="card-body">
    <h5 class="card-title"></h5>
    <p class="card-text"></p>
  </div>
  
  <div class="form-group">
    <label for="exampleInputEmail1">username</label>
    <input type="text" class="form-control"   placeholder="enter username" name='username'>
    <small  class="form-text text-muted"></small>
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type="password" class="form-control" name='password' id="exampleInputPassword1" placeholder="Password">
  </div>
  
  <input type="submit" value='login'  class="btn btn-primary"></input>

  </div>
</form>







<?php

?>