<!DOCTYPE html>
<!-- 
Filename: createGameReviews.php, Kyle Dunne, CIS355, 2015-04-21
This file allows a user to add a review of a game
Code adapted from Multifile CRUD example done in class
-->
<?php

ini_set('display_errors',1); 
error_reporting(E_ALL);
 
    require 'database.php';
	require 'reqLogin.php';
    //Get selected game tuid
    if ( !empty($_GET['id'])) {
       $game = $_REQUEST['id'];
       //Get title of game
       $pdo = Database::connect();
       $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       $sql = $pdo->prepare("SELECT title from iGames where tuid = ".$game);
       $sql->execute();
       $row = $sql->fetch();
       $gameTitle = $row[0];
   }
   else
   {
       header("Location: index.php");
   }
    if ( !empty($_POST)) {
        // keep track validation errors
        $titleError = null;
        $textError = null;
        
        // keep track post values
        $title = $_POST['title'];
		$text = $_POST['text'];
        $score = $_POST['score'];
        
        $text = trim($text);        
        // validate input
        $valid = true;
        if (empty($title)) {
            $titleError = 'Please enter a title';
            $valid = false;
        }
        
        if (empty($text)) {
            $textError = 'Please enter review text';
            $valid = false;
        }
    
        // insert data
        if ($valid) {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO iGameReviews (gameTUID, score, title, text) values(?, ?, ?, ?)";
            $q = $pdo->prepare($sql);
            $q->execute(array($game, $score, $title, $text));
            Database::disconnect();
            header("Location: viewGameReviews.php?id=".$game);
        }
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
                        <?php echo '<h3>Reviewing '.$gameTitle.'</h3>';?>
                    </div>
                    <?php
                    echo '<form class="form-horizontal" action="createGameReview.php?id='.$game.'" method="post">';
                    ?>
                      <!--Title input-->
                      <div class="control-group <?php echo !empty($titleError)?'error':'';?>">
                        <label class="control-label">Title</label>
                        <div class="controls">
                            <input name="title" type="text"  placeholder="title" value="<?php echo !empty($title)?$title:'';?>">
                            <?php if (!empty($titleError)): ?>
                                <span class="help-inline"><?php echo $titleError;?></span>
                            <?php endif; ?>
                        </div>
                      </div>
                      
                      <!--Score input-->
                      <div class="control-group">
                        <label class="control-label">Score</label>
                        <div class="controls">
                            <select name="score">
                                <option value=1>1</option>
                                <option value=2>2</option>
                                <option value=3>3</option>
                                <option value=4>4</option>
                                <option value=5>5</option>
                                <option value=6>6</option>
                                <option value=7>7</option>
                                <option value=8>8</option>
                                <option value=9>9</option>
                                <option value=10>10</option>
                            </select>
                        </div>
                      </div>
                      
                      <!--Text input-->
                      <div class="control-group <?php echo !empty($textError)?'error':'';?>">
                        <label class="control-label">Review Text</label>
                        <div class="controls">
                            <textarea name="text" rows="4" cols="100" maxlength="1000"><?php
                            echo !empty($text)?$text:'';
                            ?></textarea>
                            <?php if (!empty($textError)): ?>
                                <span class="help-inline"><?php echo $textError;?></span>
                            <?php endif; ?>
                        </div>
                      </div>
                      
                      <div class="form-actions">
                          </br>
                          <button type="submit" class="btn btn-success">Create</button>
                          <?php echo '<a class="btn btn-default" href="viewGameReviews.php?id='.$game.'">Back</a>' ?>
                        </div>
                    </form>
                </div>

    </div> <!-- /container -->
  </body>
</html>
