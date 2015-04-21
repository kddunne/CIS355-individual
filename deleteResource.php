<?php

//Filename: deleteResource.php, Kyle Dunne, CIS355, 2015-04-21
//This file deletes a resource and the reviews associated with it
//Code adapted from Multifile CRUD example done in class

ini_set('display_errors',1); 
error_reporting(E_ALL);
 
    require 'database.php';
    include 'reqLogin.php';

    $id = 0;
     
    if ( !empty($_GET['id'])) {
        $id = $_REQUEST['id'];
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //Get game tuid for redirect
        $sql = $pdo->prepare("SELECT gameTUID from iResources where tuid = ".$id);
        $sql->execute();
        $row = $sql->fetch();
        $game = $row[0];
        Database::disconnect();
    }
     
    if ( !empty($_POST)) {
        // keep track post values
        $id = $_POST['id'];
         
        // delete associated reviews, then the game
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //Delete reviews of resource
        $sql = "DELETE FROM iResourceReviews WHERE resourceTUID = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        //Delete resources
        $sql = "DELETE FROM iResources WHERE tuid = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        Database::disconnect();
        //Redirect to resources
        header("Location: viewResources.php?id=".$game);
         
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
                     
                    <?php echo '<form class="form-horizontal" action="deleteResource.php?id='.$id.'" method="post">';?>
                      <input type="hidden" name="id" value="<?php echo $id;?>"/>
                      <p class="alert alert-error">Are you sure you want to delete ?</p>
                      <div class="form-actions">
                          <button type="submit" class="btn btn-danger">Yes</button>
                          <?php echo '<a class="btn btn-default" href="viewResources.php?id='.$game.'">No</a>';?>
                        </div>
                    </form>
                </div>
                 
    </div> <!-- /container -->
  </body>
</html>
