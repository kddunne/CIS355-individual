<!DOCTYPE html>
<!-- 
Filename: viewGameReview.php, Kyle Dunne, CIS355, 2015-04-21
This file allows a user to view a review's details
Code adapted from Multifile CRUD example done in class
-->
<?php

ini_set('display_errors',1); 
error_reporting(E_ALL);
 
    require 'database.php';
	require 'reqLogin.php';
    //Get review tuid
    if ( !empty($_GET['id'])) {
       $id = $_REQUEST['id'];
       //Get record
       $pdo = Database::connect();
       $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       $sql = $pdo->prepare("SELECT gameTUID, score, title, text from iGameReviews where tuid = ".$id);
       $sql->execute();
       $row = $sql->fetch();
       $game = $row[0];
       $score = $row[1];
       $title = $row[2];
       $text = $row[3];
       //Get title of game
       $sql = $pdo->prepare("SELECT title from iGames where tuid = ".$game);
       $sql->execute();
       $row = $sql->fetch();
       $gameTitle = $row[0];
   }
   else
   {
       header("Location: index.php");
   }
?>
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
                        <?php echo '<h3>Review of '.$gameTitle.'</h3>';?>
                    </div>
                      <!--Title output-->
                      <div class="control-group <?php echo !empty($titleError)?'error':'';?>">
                        <label class="control-label">Title</label>
                        <div class="controls">
                            <input name="title" type="text"  placeholder="title" value="<?php echo $title;?>" readonly>
                        </div>
                      </div>
                      
                      <!--Score output-->
                      <div class="control-group">
                        <label class="control-label">Score</label>
                        <div class="controls">
                            <input name="score" type="text"  placeholder="title" size="2" value="<?php echo $score;?>" readonly>
                        </div>
                      </div>
                      
                      <!--Text input-->
                      <div class="control-group <?php echo !empty($textError)?'error':'';?>">
                        <label class="control-label">Review Text</label>
                        <div class="controls">
                            <textarea name="text" rows="4" cols="100" maxlength="1000" readonly><?php
                            echo $text;
                            ?></textarea>
                        </div>
                      </div>
                      
                      <div class="form-actions">
                          </br>
                          <?php echo '<a class="btn btn-default" href="viewGameReviews.php?id='.$game.'">Back</a>' ?>
                        </div>
                </div>

    </div> <!-- /container -->
  </body>
</html>
