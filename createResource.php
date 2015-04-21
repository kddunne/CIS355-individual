<!DOCTYPE html>
<!-- 
Filename: createResource.php, Kyle Dunne, CIS355, 2015-04-21
This file allows a user to add a resource to the database
Code adapted from Multifile CRUD example done in class
-->
<?php

ini_set('display_errors',1); 
error_reporting(E_ALL);
 
    require 'database.php';
	require 'reqLogin.php';
    if ( !empty($_GET['id'])) {
        $id = $_REQUEST['id'];
    }
    else
    {
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
    
        // insert data
        if ($valid) {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO iResources (title, link, description, gameTUID) values(?, ?, ?, ?)";
            $q = $pdo->prepare($sql);
            $q->execute(array($title, $link, $description, $id));
            Database::disconnect();
            header("Location: viewResources.php?id=".$id);
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
                        <h3>Create a Resource</h3>
                    </div>
                    <?php
                    echo '<form class="form-horizontal" action="createResource.php?id='.$id.'" method="post">';
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
                      <!--Hidden input for game tuid
                      <?php
                      //echo '<input type="hidden" name="id" value="'..'">';
                      ?>
                      -->
                      <div class="form-actions">
                          </br>
                          <button type="submit" class="btn btn-success">Create</button>
                          <a class="btn btn-default" href="viewResources.php">Back</a>
                        </div>
                    </form>
                </div>

    </div> <!-- /container -->
  </body>
</html>
