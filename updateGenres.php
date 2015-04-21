<!DOCTYPE html>
<!-- 
Filename: updateGenres.php, Kyle Dunne, CIS355, 2015-04-20
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
        header("Location: viewGenres.php");
    }
     
    if ( !empty($_POST)) {
        // keep track validation errors
        $genreError = null;
        
        // keep track post values
		$genre = $_POST['genre'];
         
        // validate input
        $valid = true;
        if (empty($genre)) {
            $genreError = 'Please enter a new genre';
            $valid = false;
        }
        else
        //Prevent duplicate data
        {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT COUNT(genre) as count from iGenres where genre = ? and tuid <> ". $id;
            $q = $pdo->prepare($sql);
            $q->execute(array($genre));
            $data = $q->fetch(PDO::FETCH_ASSOC);
            Database::disconnect();
            if ($data['count'] != 0)
                $valid = false;
                $genreError = 'Genre already in database';
        }
         
        // update data
        if ($valid) {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE iGenres set genre = ? WHERE tuid = ?";
            $q = $pdo->prepare($sql);
            $q->execute(array($genre,$id));
            Database::disconnect();
            header("Location: viewGenres.php");
        }
    } else {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT genre FROM iGenres where tuid = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        $data = $q->fetch(PDO::FETCH_ASSOC);
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
                        <h3>Update a Genre</h3>
                    </div>
             
                    <form class="form-horizontal" action="updateGenres.php?id=<?php echo $id?>" method="post">
                      <div class="control-group <?php echo !empty($genreError)?'error':'';?>">
                        <label class="control-label">Genre</label>
                        <div class="controls">
                            <input name="genre" type="text"  placeholder="genre" value="<?php echo !empty($genre)?$genre:'';?>">
                            <?php if (!empty($genreError)): ?>
                                <span class="help-inline"><?php echo $genreError;?></span>
                            <?php endif; ?>
                        </div>
                      </div>

                      <div class="form-actions">
                          </br>
                          <button type="submit" class="btn btn-success">Update</button>
                          <a class="btn btn-default" href="viewGenres.php">Back</a>
                        </div>
                    </form>
                </div>
                 
    </div> <!-- /container -->
  </body>
</html>
