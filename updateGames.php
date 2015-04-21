<!DOCTYPE html>
<!-- 
Filename: updateGames.php, Kyle Dunne, CIS355, 2015-04-20
This file updates information for a single game
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
    }
     
    if ( null==$id ) {
        header("Location: index.php");
    }
     
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
         
        // update data
        if ($valid) {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE iGames set title = ?, genre = ? WHERE tuid = ?";
            $q = $pdo->prepare($sql);
            $q->execute(array($title,$genre,$id));
            Database::disconnect();
            header("Location: index.php");
        }
    } else {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT title, genre FROM iGames where tuid = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        $title = $data['title'];
	    $genre = $data['genre'];
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
                        <h3>Update a Game</h3>
                    </div>
             
                    <form class="form-horizontal" action="updateGames.php?id=<?php echo $id?>" method="post">
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
								if ($row[0] == $genre)
	                             echo "<option selected value='$row[0]'>$row[1]</option>";
								else
                                 echo "<option value='$row[0]'>$row[1]</option>";
                               }
                              Database::disconnect();
                             ?>
                            </select>
                        </div>
                      </div>

                      <div class="form-actions">
                          </br>
                          <button type="submit" class="btn btn-success">Update</button>
                          <a class="btn btn-default" href="index.php">Back</a>
                        </div>
                    </form>
                </div>
                 
    </div> <!-- /container -->
  </body>
</html>
