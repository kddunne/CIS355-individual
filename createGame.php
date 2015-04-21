<!DOCTYPE html>
<!-- 
Filename: createGame.php, Kyle Dunne, CIS355, 2015-04-20
This file allows a user to add a game to the database
Code adapted from Multifile CRUD example done in class
-->
<?php

ini_set('display_errors',1); 
error_reporting(E_ALL);
 
    require 'database.php';
	require 'reqLogin.php';

    if ( !empty($_POST)) {
        // keep track validation errors
        $titleError = null;
        
        // keep track post values
        $title = $_POST['title'];
		$genre = $_POST['genre'];
         
        // validate input
        $valid = true;
        if (empty($title)) {
            $titleError = 'Please enter a title';
            $valid = false;
        }
    
        // insert data
        if ($valid) {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO iGames (title,genre) values(?, ?)";
            $q = $pdo->prepare($sql);
            $q->execute(array($title,$genre));
            Database::disconnect();
            header("Location: index.php");
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
                        <h3>Create a Game</h3>
                    </div>

                    <form class="form-horizontal" action="createGame.php" method="post">
                      <div class="control-group <?php echo !empty($titleError)?'error':'';?>">
                        <label class="control-label">Title</label>
                        <div class="controls">
                            <input name="title" type="text"  placeholder="title" value="<?php echo !empty($title)?$title:'';?>">
                            <?php if (!empty($titleError)): ?>
                                <span class="help-inline"><?php echo $titleError;?></span>
                            <?php endif; ?>
                        </div>
                      </div>
                       <!-- Dropdown list for genres -->
					  <div class="control-group">
                       <label class="control-label">Genre</label>
                         <div class="controls">
                           <select name="genre">
                             <?php
                              $pdo = Database::connect();
                              $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                              $sql = "SELECT * from iGenres ORDER BY genre ASC";
                              foreach($pdo->query($sql) as $row)
                               {
                                 echo "<option value='$row[0]'>$row[1]</option>";
                               }
                              Database::disconnect();
                             ?>
                            </select>
                        </div>
                      </div>
                      
                      <div class="form-actions">
                          </br>
                          <button type="submit" class="btn btn-success">Create</button>
                          <a class="btn btn-default" href="index.php">Back</a>
                        </div>
                    </form>
                </div>

    </div> <!-- /container -->
  </body>
</html>
