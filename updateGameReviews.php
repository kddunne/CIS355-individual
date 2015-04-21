<!DOCTYPE html>
<!-- 
Filename: updateGameReviews.php, Kyle Dunne, CIS355, 2015-04-21
This file updates a review for a game
Code adapted from Multifile CRUD example done in class
-->
<?php

ini_set('display_errors',1); 
error_reporting(E_ALL);
 
    require 'database.php';
    require 'reqLogin.php';
 
    $id = null;
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
       Database::disconnect();
    }
     
    if ( null==$id ) {
        header("Location: index.php");
    }
     
    if ( !empty($_POST)) {
        // keep track validation errors
        $titleError = null;
        $textError = null;
        
        // keep track post values
        $title = $_POST['title'];
		$score = $_POST['score'];
        $text = $_POST['text'];
        $text = trim($text);
        //Retrieve game tuid
        $sql = $pdo->prepare("SELECT gameTUID from iGameReviews where tuid = ".$id);
        $sql->execute();
        $row = $sql->fetch();
        $game = $row[0];
         
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
         
        // update data
        if ($valid) {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE iGameReviews set title = ?, score = ?, text = ? WHERE tuid = ?";
            $q = $pdo->prepare($sql);
            $q->execute(array($title,$score,$text,$id));
            Database::disconnect();
            header('Location: viewGameReviews.php?id='.$game);
        }
    } else {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT title, score, text, gameTUID FROM iGameReviews where tuid = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        $title = $data['title'];
	    $score = $data['score'];
        $text = $data['text'];
        $game = $data['gameTUID'];
        Database::disconnect();
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
                        <h3>Update a Review</h3>
                    </div>
                    <?php
                    echo '<form class="form-horizontal" action="updateGameReviews.php?id='.$id.'" method="post">';
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
                                <?php
                                //Fill the dropdown list and select a number
                                for ($i = 1; $i <= 10; $i++)
                                {
                                    if ($i == $score)
                                    {
                                        echo '<option value='.$i.' selected="selected">'.$i.'</option>';
                                    }
                                    else
                                    {
                                        echo '<option value='.$i.'>'.$i.'</option>';
                                    }
                                }
                                ?>
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
                          <button type="submit" class="btn btn-success">Update</button>
                          <?php echo '<a class="btn btn-default" href="viewGameReviews.php?id='.$game.'">Back</a>';?>
                        </div>
                    </form>
                </div>
                 
    </div> <!-- /container -->
  </body>
</html>
