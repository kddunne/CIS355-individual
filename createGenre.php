<!DOCTYPE html>
<!-- 
Filename: createGenre.php, Kyle Dunne, CIS355, 2015-04-20
This file allows a user to add a genre to the database
Code adapted from Multifile CRUD example done in class
-->
<?php

ini_set('display_errors',1); 
error_reporting(E_ALL);
 
    require 'database.php';
	require 'reqLogin.php';

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
            $sql = "SELECT COUNT(genre) as count from iGenres where genre = ?";
            $q = $pdo->prepare($sql);
            $q->execute(array($genre));
            $data = $q->fetch(PDO::FETCH_ASSOC);
            Database::disconnect();
            if ($data['count'] != 0)
                $valid = false;
                $genreError = 'Genre already in database';
        }
    
        // insert data
        if ($valid) {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO iGenres (genre) values(?)";
            $q = $pdo->prepare($sql);
            $q->execute(array($genre));
            Database::disconnect();
            header("Location: viewGenres.php");
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
                        <h3>Create a Genre</h3>
                    </div>

                    <form class="form-horizontal" action="createGenre.php" method="post">
                      <div class="control-group <?php echo !empty($genreError)?'error':'';?>">
                        <label class="control-label">Title</label>
                        <div class="controls">
                            <input name="genre" type="text"  placeholder="genre" value="<?php echo !empty($title)?$title:'';?>">
                            <?php if (!empty($genreError)): ?>
                                <span class="help-inline"><?php echo $genreError;?></span>
                            <?php endif; ?>
                        </div>
                      </div>
                       
                      <div class="form-actions">
                          </br>
                          <button type="submit" class="btn btn-success">Create</button>
                          <a class="btn btn-default" href="viewGenres.php">Back</a>
                        </div>
                    </form>
                </div>

    </div> <!-- /container -->
  </body>
</html>
