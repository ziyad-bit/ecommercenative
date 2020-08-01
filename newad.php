<?php
session_start();

$pagetitel='newad';

include "init.php";

if (isset($_SESSION['user'])){
    



    if ($_SERVER['REQUEST_METHOD']=='POST') {
        $formerrors=array();

        $name=filter_var($_POST['name'],FILTER_SANITIZE_STRING);
        $description=filter_var($_POST['description'],FILTER_SANITIZE_STRING);
        $price=filter_var($_POST['price'],FILTER_SANITIZE_NUMBER_INT);
    
        $country_made=filter_var($_POST['country_made'],FILTER_SANITIZE_STRING);
        $status=filter_var($_POST['status'],FILTER_SANITIZE_NUMBER_INT);
        $category=filter_var($_POST['category'],FILTER_SANITIZE_NUMBER_INT);

        
        if(strlen($name)<4){
            $formerrors[]='name cant be less than 4 characters';
    }

        if(strlen($name)>15){
            $formerrors[]= 'name cant be more than 15 characters';
        }


        if(empty($name)){
            $formerrors[]= 'name cant be empty';
            
    }

        
        if(empty($description)){
                $formerrors[]= 'description cant be empty';
                
        }
        if(empty($price)){
            $formerrors[]='price cant be empty';
            
        }
        if (empty($formerrors)){?>
            <script>
            if ( window.history.replaceState ) {
                window.history.replaceState( null, null, window.location.href );
            }
            </script>
            <?php


               
               
                
               
            $stmt=$con->prepare('INSERT INTO items(name ,description , price,status,country_made,date,cat_ID,member_ID) 
            
                                           VALUES (:zname,:zdesc,:zprice,:zstatus,:zcountry_made,now(),:zcat,:zmember)');

            $stmt->execute(array('zname'=>$name,'zdesc'=>$description,'zprice'=>$price,'zstatus'=>$status,'zcountry_made'=>$country_made,'zcat'=>$category,'zmember'=>$_SESSION['uID']));

            ?>
           <h3 class='text-center alert alert-success'>item added</h3>  
           <?php   
                
            


               
        }
        
        }   
            
        





    ?>
    <div class='ad container'>
        <ul class="list-group">
            <li class="list-group-item active">create new item</li>
          
            <li class="list-group-item">
                <div class='row'>
                    <div class='col-md-8'>
                        <form action="/ecommerce/newad.php" method='post'>
                            <div class="form-group">
                                <label for="formGroupExampleInput">name</label>
                                <div class='col-md-8'>
                                    <input type="text" required='required' pattern='.{4,15}' title='username should be 4 characters and less than 15' class="form-control"  name='name' id="formGroupExampleInput" placeholder="write name of item">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="formGroupExampleInput2">description</label>
                                <div class='col-md-8'>
                                    <input type="text" class="form-control" pattern='.{4,}' title='description should be 4 character or more' required='required' name='description' id="formGroupExampleInput2" placeholder="write your description">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="formGroupExampleInput2">price</label>
                                <div class='col-md-8'>
                                    <input type="text" class="form-control"  required='required' name='price' id="formGroupExampleInput2" placeholder="price of item">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="formGroupExampleInput2">country made</label>
                                <div class='col-md-8'>
                                    <input type="text" class="form-control" required='required' name='country_made' id="formGroupExampleInput2" placeholder="item is made in">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="formGroupExampleInput2">status</label>
                                <div class='col-md-8'>
                                    <select name="status" value='...' required='required' class='form-control' id="">
                                        <option value="1">...</option>
                                        <option value="2">new</option>
                                        <option value="2">used</option>
                                        <option value="3">very old</option>
                                    </select>
                                </div>
                                
                                
                            </div>
                           
                            <div class="form-group">
                                <label for="formGroupExampleInput2">category</label>
                                <div class='col-md-8'>
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
                                
                                
                            </div>
                        
                                
                                
                            
                            <input type="submit" class="save btn btn-primary" value="add item">
                        </form>   

                    </div>
                    <div class='col-md-4'>
                        <div class="it card" style="width: 18rem;">
                            <img src="download.jpg" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h3 class="card-title">title</h3>
                                <p class="card-text">description</p>
                              
                                
                            </div>
                        </div>
                        
                    </div>


                </div>
                <?php
                if(!empty($formerrors)){
                    foreach($formerrors as $error){
                        echo "<div class='alert alert-danger'>". $error . "</div>";
                    }
                }
                 ?>
                 
            
            </li>
            


   

<?php }
else{
    header('location:login.php');
    exit();
}    