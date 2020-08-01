<div class="sign container"><?php
if (isset($_SESSION['user'])){
  echo 'welcome to' . ' ' .  $sessionuser . ' ';
  
  echo '<a href="profile.php ">my profile</a>';
  echo '- <a href="newad.php ">new item</a>';
  echo '- <a href="logout.php ">logout</a>';
  
  $status= checkuser( $sessionuser);
  if($status==1){
    
  }

}
else{?>
  <a href="login.php">
<span class=''>login</span>/
</a>
<a href="signup.php">
<span class=''>signup</span>

</a>
<?php }  ?>

</div>

<nav  class="navbar navbar-dark bg-dark">
  <a class="navbar-brand" href="index.php">home</a>

  <ul  class="n navbar navbar-dark bg-dark navebar-right">
  
  
    <?php
    $categories=getcats();
    foreach($categories as $cat){
    echo "<a href='categories.php?pageID=".$cat["ID"]. '&pagename='. $cat["name"]."'>". $cat['name']."</a>";
    }
    ?>

 
  </ul>
</nav>  
     
  



