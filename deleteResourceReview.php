<?php

//Filename: deleteResourceReview.php, Kyle Dunne, CIS355, 2015-04-21
//This file deletes a resource review
//Code adapted from Multifile CRUD example done in class

ini_set('display_errors',1); 
error_reporting(E_ALL);
 
    require 'database.php';
    include 'reqLogin.php';

    $id = 0;
     
    if ( !empty($_GET['id'])) {
        $id = $_REQUEST['id'];
    }
    if (!empty($_GET['resource'])){
        $resource = $_REQUEST['resource'];
    }
     
    if ( !empty($_POST)) {
        // keep track post values
        $id = $_POST['id'];
        $resource = $_POST['resource']; 
        // delete associated reviews, resources, resource reviews, then the resource
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //Delete Review
        $sql = "DELETE FROM iResourceReviews WHERE tuid = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        Database::disconnect();
        header("Location: viewResourceReviews.php?id=".$resource);
         
    }
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
</head>
 
<body>
    <div class="container">
     
                <div class="span10 offset1">
                    <div class="row">
                        <h3>Delete a Resource</h3>
                    </div>
                     
                    <form class="form-horizontal" action="deleteResourceReview.php" method="post">
                      <input type="hidden" name="id" value="<?php echo $id;?>"/>
                      <input type="hidden" name="resource" value="<?php echo $resource;?>"/>
                      <p class="alert alert-error">Are you sure you want to delete ?</p>
                      <div class="form-actions">
                          <button type="submit" class="btn btn-danger">Yes</button>
                          <?php echo '<a class="btn btn-default" href="viewResourceReviews.php?='.$resource.'">No</a>';?>
                        </div>
                    </form>
                </div>
                 
    </div> <!-- /container -->
  </body>
</html>
