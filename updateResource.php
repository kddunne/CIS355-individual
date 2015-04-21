<!DOCTYPE html>
<!-- 
Filename: updateResource.php, Kyle Dunne, CIS355, 2015-04-21
This file updates information for a single resource
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
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //Get game tuid for redirect
        $sql = $pdo->prepare("SELECT gameTUID from iResources where tuid = ".$id);
        $sql->execute();
        $row = $sql->fetch();
        $game = $row[0];
        Database::disconnect();
    }
     
    if ( null==$id ) {
        header("Location: index.php");
    }
     
    if ( !empty($_POST)) {
        // keep track validation errors
        $titleError = null;
        $linkError = null;
        $descriptionError = null;
        
        // keep track post values
        $title = $_POST['title'];
		$link = $_POST['link'];
        $description = $_POST['description'];
        $description = trim($description);
        //Retrieve game tuid
        $sql = $pdo->prepare("SELECT gameTUID from iResources where tuid = ".$id);
        $sql->execute();
        $row = $sql->fetch();
        $game = $row[0];
         
        // validate input
        $valid = true;
        if (empty($title)) {
            $titleError = 'Please enter a title';
            $valid = false;
        }
        
        if (empty($link)) {
            $linkError = 'Please enter a link';
            $valid = false;
        }
        
        if (empty($description)) {
            $descriptionError = 'Please enter a description';
            $valid = false;
        }
         
        // update data
        if ($valid) {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE iResources set title = ?, link = ?, description = ? WHERE tuid = ?";
            $q = $pdo->prepare($sql);
            $q->execute(array($title,$link,$description,$id));
            Database::disconnect();
            header('Location: viewResources.php?id='.$game);
        }
    } else {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT title, link, description, gameTUID FROM iResources where tuid = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        $title = $data['title'];
	    $link = $data['link'];
        $description = $data['description'];
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
                        <h3>Update a Resource</h3>
                    </div>
                    <?php
                    echo '<form class="form-horizontal" action="updateResource.php?id='.$id.'" method="post">';
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
                      
                      <!--Link input-->
                      <div class="control-group <?php echo !empty($linkError)?'error':'';?>">
                        <label class="control-label">Link</label>
                        <div class="controls">
                            <input name="link" type="text"  placeholder="link" value="<?php echo !empty($link)?$link:'';?>">
                            <?php if (!empty($linkError)): ?>
                                <span class="help-inline"><?php echo $linkError;?></span>
                            <?php endif; ?>
                        </div>
                      </div>
                      
                      <!--Description input-->
                      <div class="control-group <?php echo !empty($DescriptionError)?'error':'';?>">
                        <label class="control-label">Description</label>
                        <div class="controls">
                            <textarea name="description" rows="4" cols="100" maxlength="1000"><?php 
                            echo !empty($description)?$description:'';
                            ?></textarea>
                            <?php if (!empty($descriptionError)): ?>
                                <span class="help-inline"><?php echo $descriptionError;?></span>
                            <?php endif; ?>
                        </div>
                      </div>

                      <div class="form-actions">
                          </br>
                          <button type="submit" class="btn btn-success">Update</button>
                          <?php echo '<a class="btn btn-default" href="viewResources.php?id='.$game.'">Back</a>';?>
                        </div>
                    </form>
                </div>
                 
    </div> <!-- /container -->
  </body>
</html>
