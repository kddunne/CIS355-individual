<?php

//Filename: deleteGameReview.php, Kyle Dunne, CIS355, 2015-04-21
//This file deletes a game review
//Code adapted from Multifile CRUD example done in class

ini_set('display_errors',1); 
error_reporting(E_ALL);
 
    require 'database.php';
    include 'reqLogin.php';

    $id = 0;
     
    if ( !empty($_GET['id'])) {
        $id = $_REQUEST['id'];
    }
    if (!empty($_GET['game'])){
        $game = $_REQUEST['game'];
    }
     
    if ( !empty($_POST)) {
        // keep track post values
        $id = $_POST['id'];
        $game = $_POST['game']; 
        // delete associated reviews, resources, resource reviews, then the game
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //Delete Review
        $sql = "DELETE FROM iGameReviews WHERE tuid = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        Database::disconnect();
        header("Location: viewGameReviews.php?id=".$game);
         
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
                     
                    <form class="form-horizontal" action="deleteGameReview.php" method="post">
                      <input type="hidden" name="id" value="<?php echo $id;?>"/>
                      <input type="hidden" name="game" value="<?php echo $game;?>"/>
                      <p class="alert alert-error">Are you sure you want to delete ?</p>
                      <div class="form-actions">
                          <button type="submit" class="btn btn-danger">Yes</button>
                          <?php echo '<a class="btn btn-success" href="viewGameReviews.php?='.$game.'">No</a>';?>
                        </div>
                    </form>
                </div>
                 
    </div> <!-- /container -->
  </body>
</html>
