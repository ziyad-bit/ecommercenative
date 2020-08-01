<?php
session_start();

$pagetitel='homepage';

include "init.php";




?>

<div class='container' >
<h1 class='text-center'> all items </h1>

<div class="row">
    <?php
    $items=getall('items','items_ID');
    foreach($items as $item){

   echo '<div class="col-sm-6 col-md-4">';
        echo'<div class="it card" style="width: 18rem;">';
        echo    '<img src="download.jpg" class="card-img-top" alt="...">';
        echo    '<div class="card-body">';
        echo       '<h3 class="card-title"><a href="items.php?items_ID='.$item["items_ID"].'">'. $item["name"].'</a></h3>';
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
    
</div> 

