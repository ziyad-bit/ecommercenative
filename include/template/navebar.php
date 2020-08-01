
<nav  class="navbar navbar-dark bg-dark">
  <a class="navbar-brand" href="dashboard.php">home</a>
  
  <a class="navbar-brand" href="categories.php">categories</a>
  <a class="navbar-brand" href="items.php">items</a>
  <a class="navbar-brand" href="members.php?do=manage">members</a>
  <a class="navbar-brand" href="comments.php">comments</a>
  <a class="navbar-brand" href="#">statistics</a>
     
     
  <a class="navbar-brand" href="members.php?do=edit& userID=<?php echo $_SESSION['userID']?>">edit</a>
     
  <a class="navbar-brand" href="#">setting</a>
      
  <a class="navbar-brand" href="logout.php">logout</a>
     
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="#"> <span class="sr-only">(current)</span></a>
      </li>
      
     
    </ul>
  </div>
</nav>


