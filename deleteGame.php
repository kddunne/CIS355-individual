<?php

//Filename: deleteGame.php, Kyle Dunne, CIS355, 2015-04-20
//This file deletes a game and the reviews associated with it
//Code adapted from Multifile CRUD example done in class

ini_set('display_errors',1); 
error_reporting(E_ALL);
 
    require 'database.php';
    include 'reqLogin.php';

    $id = 0;
     
    if ( !empty($_GET['id'])) {
        $id = $_REQUEST['id'];
    }
     
    if ( !empty($_POST)) {
        // keep track post values
        $id = $_POST['id'];
         
        // delete associated reviews, resource reviews, resources, then the game
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //Delete game reviews
        $sql = "DELETE FROM iGameReviews WHERE gameTUID = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        //Delete Resource reviews
        $pdo2 = Database::connect();
        $pdo2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql2 = "SELECT tuid from iResources where gameTUID = ".$id;
        foreach ($pdo2->query($sql2) as $row) 
        {
            if ($row[0]<>null)
            {
                $sql = "DELETE FROM iResourceReviews WHERE resourceTUID = ?";
                $q = $pdo->prepare($sql);
                $q->execute(array($row[0]));
            }
        }
        //Delete Resources
        $sql = "DELETE FROM iResources WHERE gameTUID = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        //Delete game
        $sql = "DELETE FROM iGames WHERE tuid = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        Database::disconnect();
        header("Location: index.php");
         
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
                        <h3>Delete a Game</h3>
                    </div>
                     
                    <form class="form-horizontal" action="deleteGame.php" method="post">
                      <input type="hidden" name="id" value="<?php echo $id;?>"/>
                      <p class="alert alert-error">Are you sure you want to delete ?</p>
                      <div class="form-actions">
                          <button type="submit" class="btn btn-danger">Yes</button>
                          <a class="btn btn-default" href="index.php">No</a>
                        </div>
                    </form>
                </div>
                 
    </div> <!-- /container -->
  </body>
</html>
