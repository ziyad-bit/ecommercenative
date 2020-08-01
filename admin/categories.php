<?php

session_start();

$pagetitel='categories';

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
    $sort='DESC';
    $sort_array=array('ASC','DESC');

    if(isset($_GET['sort'])&& in_array($_GET['sort'],$sort_array)){
        $sort=$_GET['sort'];
    }

    $stmt=$con->prepare("SELECT * FROM categories WHERE parent=0 ORDER BY ordering $sort");
    $stmt->execute();
    $cats=$stmt->fetchAll();
    ?>
    <h1 class='text-center'>categories</h1>
    <div class="container" >
        <ul class="list-group">
        <li class="list-group-item active">
            <h2>manage categories</h2>
            <div class='ordering'>
                <i class='fa fa-sort'></i>  ordering:[
                <a href="?sort=DESC" class="<?php if($sort=='DESC'){echo 'act';}?>">DESC</a>|
                <a href="?sort=ASC" class="<?php if($sort=='ASC'){echo 'act';}?>">ASC</a>]

            </div>

        </li>
           
        <li class="list-group-item"><?php
            foreach($cats as $cat){
                
                echo '<div class="cat">';
                    echo '<div class="hidden-buttons">';
                        echo "<a href='categories.php?do=edit&ID=". $cat['ID'] ."' class='btn btn-xs btn-primary'><i class='fa fa-edit'>edit</i></a>";
                        echo "<a href='categories.php?do=delete&ID=". $cat['ID'] ."' class='btn btn-xs btn-danger'><i class='fa fa-trash'>delete</i></a>";

                    echo '</div>';
                    echo  '<h2>' . $cat['name'].'</h2>';
                    echo   '<p>';if($cat['description']==''){echo 'no discription';} else{echo $cat['description'];} '</p>';
                    echo '<div>';
                        if($cat['visibility']==1){echo '<span class="vis"> hidden </span>';} 
                        if($cat['allow_comment']==1){echo '<span class="com"> comment disabled </span>';}
                        if($cat['allow_ads']==1){echo '<span class="ads"> ads disabled </span>';}
                    echo '</div>';
                echo '</div>';
                
                
                $childcats=getallv2("*","categories","WHERE parent ={$cat['ID']}");
                if(! empty($childcats)){
                    echo '<h4 class="sub"> subcategory<h4>';
                    foreach($childcats as $childcat){
                        echo"<a href='categories.php?do=edit&ID=". $childcat['ID'] ."'> <h5 class='subname' >". $childcat['name']."</h5><a> ";
                    }
                    
                }
                echo '<hr>';
            
            }

       ?> </li>
        </ul>
        <a class='add btn btn-primary' href="categories.php?do=add"><i class='fa fa-plus'></i>add category</a>
    </div>

    <?php

}

elseif($do=='add'){
    ?>
   
    <h1 class='text-center'>add category</h1>
    <div class='container'>
        <form action="?do=insert" method='POST'>
            <input type='hidden' name='userID' value='<?php echo $userID?>'>
            <div class="input-group flex-nowrap">
            <div class="input-group-prepend">
                <span class="input-group-text" id="addon-wrapping">name</span>
            </div >
            <div class='col-sd-10'>
                <input type="text" placeholder='name of category'  required='required'  name='name' class="form-control"  aria-label="Username" aria-describedby="addon-wrapping">
            </div>
            </div>

            <div class="input-group flex-nowrap " >
            <div class="input-group-prepend  "  >
                <span class="input-group-text " id="addon-wrapping">description</span>
            </div>
            <div class='col-sd-3'>
                
                <input type="text" name=' description' placeholder='write description'  class="form-control"  aria-label="Username" aria-describedby="addon-wrapping">
               
            </div>
            </div>

            <div class="input-group flex-nowrap">
            <div class="input-group-prepend">
                <span class="input-group-text" id="addon-wrapping">ordering </span>
            </div>
            <div class='col-sd-5'>
                <input type="text"  name='ordering' placeholder='number to arrange'  class="form-control"  aria-label="Username" aria-describedby="addon-wrapping">
            </div>
            </div>

            <div class="form-group">
                    <label for="formGroupExampleInput2">parent</label>
                    <select name="parent"  class='form-control' id="">
                        <option value="0">none</option>
                        <?php
                        $allparents=getallv2("*","categories","WHERE parent =0");
                        foreach($allparents as $parent){ 

                        echo     "<option value='" . $parent['ID']."' >" . $parent['name']."</option>";
                         }
                       ?>
                        
                    </select>
            </div>

            <div class="input-group flex-nowrap">
            <div class="input-group-prepend" >
                <span class="input-group-text" id="addon-wrapping">visibility</span>
            </div>
            <div class='radio'>
                <input id='vis-yes' type="radio" name='visibility' value='0' checked/>
                <label for="vis-yes">yes</label>
            </div>
            <div class='radio'>
                <input  id='vis-no' type="radio" name='visibility' value='1' />
                <label for="vis-no">no</label>
            </div>
            </div>
            <div class="input-group flex-nowrap">
            <div class="input-group-prepend">
                <span class="input-group-text" id="addon-wrapping">comment</span>
            </div>
            <div class='radio'>
                <input id='com-yes' type="radio" name='allow_comment' value='0' checked/>
                <label for="com-yes">yes</label>
            </div>
            <div class='radio'>
                <input  id='com-no' type="radio" name='allow_comment' value='1' />
                <label for="com-no">no</label>
            </div>
            </div>
            <div class="input-group flex-nowrap">
            <div class="input-group-prepend">
                <span class="input-group-text" id="addon-wrapping">allow ads</span>
            </div>
            <div class='radio'>
                <input id='ads-yes' type="radio" name='allow_ads' value='0' checked/>
                <label for="ads-yes">yes</label>
            </div>
            <div class='radio'>
                <input  id='ads-no' type="radio" name='allow_ads' value='1' />
                <label for="ads-no">no</label>
            </div>
            </div>
            

            <input type="submit" value='add category' class=" btn btn-primary" />
        </form>
    </div>
<?php }

elseif($do=='edit'){
    if(isset($_GET['ID']) && is_numeric($_GET['ID'])){
        $ID=$_GET['ID'];
        $stmt=$con->prepare('SELECT * FROM categories where ID=?  ');
        $stmt->execute(array($ID));
        $cat=$stmt->fetch();
        $count=$stmt->rowcount();

        if($stmt->rowcount()>0){?>
            <div class="card text-white bg-dark mb-3" style="max-width: 18rem;">
                <div class="card-header"><h2>Edit categories</h2></div>
                <div class="card-body"> 
                <form action="?do=update" method='post'>
                <div class="form-group">
                    <label for="formGroupExampleInput">name</label>
                    <input type="text" class="form-control" value="<?php echo $cat['name']?>" name='name' id="formGroupExampleInput" placeholder="write name of category">
                    <input type='hidden' name='ID' value='<?php echo $ID?>'>
                </div>
                <div class="form-group">
                    <label for="formGroupExampleInput2">description</label>
                    <input type="text" class="form-control" value="<?php echo $cat['description']?>" name='description' id="formGroupExampleInput2" placeholder="write your description">
                </div>
                <div class="form-group">
                    <label for="formGroupExampleInput2">ordering</label>
                    <input type="text" class="form-control" value="<?php echo $cat['ordering']?>" name='ordering' id="formGroupExampleInput2" placeholder="write number to arrange">
                </div>
                <div class="form-group">
                    <label for="formGroupExampleInput2">visibility</label>
                    <div class='nvis'>
                    <input type="radio"  name="visibility" id="vis-yes" value='0' <?php if ($cat['visibility']=='0'){echo 'checked';} ?> >
                    <label for="vis-yes" >yes</label>
                    <input type="radio"  name="visibility" id="vis-no" value='1'  <?php if ($cat['visibility']=='1'){echo 'checked';} ?>   >
                    <label for="vis-no" >no</label>
                    </div>
                </div>
                <div class="form-group">
                    
                    <label for="formGroupExampleInput2">allow_comment</label>
                    <div class='nvis'>
                    <input type="radio" name="allow_comment" id="vis-yes" value='0' <?php if ($cat['allow_comment']=='0'){echo 'checked';} ?> >
                    <label for="vis-yes" >yes</label>
                    <input type="radio"  name="allow_comment" id="vis-no" value='1'  <?php if ($cat['allow_comment']=='1'){echo 'checked';} ?>>
                    <label for="vis-no" >no</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="formGroupExampleInput2">allow_ads</label>
                    <div class='nvis'>
                    <input type="radio"  name="allow_ads"  id="vis-yes" value='0' <?php if ($cat['allow_ads']=='0'){echo 'checked';} ?> >
                    <label for="vis-yes" >yes</label>
                    <input type="radio"  name="allow_ads"  id="vis-no" value='1' <?php if ($cat['allow_ads']=='1'){echo 'checked';} ?> >
                    <label for="vis-no" >no</label>
                    </div>
                </div>
                <input type="submit" class="save btn btn-primary" value="save">
                </form>
                    
                    
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

elseif($do=='insert'){
    echo "<h1 class='text-center'>insert category</h1>";
    echo '<div class="container" >';
    if ($_SERVER['REQUEST_METHOD']=='POST'){
        $name=$_POST['name'];
        $description=$_POST['description'];
        $parent=$_POST['parent'];
        $ordering=$_POST['ordering'];
        $visibility=$_POST['visibility'];
        $allow_comment=$_POST['allow_comment'];
        
        $allow_ads=$_POST['allow_ads'];
           

          
           
            

        
        $check= checkitem('name','categories',$name);
        if($check==1){
            $msg= '<div class="alert alert-danger">sorry this category is exist</div>';
            redirect($msg,'back');
        }
        else{
            $stmt=$con->prepare('INSERT INTO categories(name , description,parent , ordering,visibility,allow_comment,allow_ads) VALUES (:zname,:zdesc ,:zparent,:zorder,:zvis,:zcom,:zads)');
            $stmt->execute(array('zname'=>$name,'zdesc'=>$description, 'zparent'=>$parent ,'zorder'=>$ordering,'zvis'=>$visibility,'zcom'=>$allow_comment,'zads'=>$allow_ads));
            $msg='<div class="alert alert-success" role="alert">' . $stmt->rowcount() . 'inserted</div>';         
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

elseif($do=='update'){
    echo "<h1 class='text-center'>update</h1>";
    echo '<div class="container" >';
    if ($_SERVER['REQUEST_METHOD']=='POST'){
           $ID=$_POST['ID'];
           $name=$_POST['name'];
           $description=$_POST['description'];
           $ordering=$_POST['ordering'];
           $visibility=$_POST['visibility'];
           $allow_comment=$_POST['allow_comment'];
           $allow_ads=$_POST['allow_ads'];

          
            if (empty($formerrors)){
                $stmt=$con->prepare('UPDATE categories SET name=? , description=? ,ordering=? , visibility=? , allow_comment=?, allow_ads=? WHERE ID=?');
                $stmt->execute(array($name,$description,$ordering,$visibility,$allow_comment,$allow_ads,$ID));
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
    echo "<h1 class='text-center'>delete category</h1>";
    echo '<div class="container" >';

        if(isset($_GET['ID']) && is_numeric($_GET['ID'])){
            $ID=$_GET['ID'];
            $stmt=$con->prepare('SELECT * FROM categories where ID=? LIMIT 1 ');
            
            $check=checkitem('ID','categories',$ID);
            

            if ($check>0){
            $stmt=$con->prepare('DELETE FROM categories WHERE ID=:zuse');
            $stmt->bindParam(':zuse',$ID);
            $stmt->execute();
            $msg='<div class="alert alert-success" role="alert">' . $stmt->rowcount() . 'category deleted</div>'; 
            redirect($msg,'back')  ;  
        }
        else{
            $msg= '<div class="alert alert-danger">id not exist</div>';
            redirect($msg,'back');
        } 
    echo '</div>';
    }
    
}

else{
    header('location:index.php');
    exit();
}
