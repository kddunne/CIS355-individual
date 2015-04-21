<!DOCTYPE html>
<!-- 
Filename: viewResourceReviews.php, Kyle Dunne, CIS355, 2015-04-21
This file shows the list reviews for a resource
Code adapted from Multifile CRUD example done in class
-->

<?php
ini_set('display_errors',1); 
error_reporting(E_ALL);

include 'database.php';

//Get selected resource tuid
if ( !empty($_GET['id'])) {
       $resource = $_REQUEST['id'];
       //Get title of resource
       $pdo = Database::connect();
       $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       $sql = $pdo->prepare("SELECT title from iResources where tuid = ".$resource);
       $sql->execute();
       $row = $sql->fetch();
       $resourceTitle = $row[0];
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
            <div class="row">
                <?php
                echo "<h3>Reviews for ".$resourceTitle."</h3>";
            ?>
            </div>
            <div class="row">
				<?php
				session_start();

				if(!isset($_SESSION['id']) && empty($_SESSION['id']))
				{
					echo '<p><a href="login.php" class="btn btn-success">Log In</a></p>';
				}
				else 
				{
					echo  '<p><a href="logout.php" class="btn btn-success">Log Out</a></p>';
				}
				
				?>
                <table class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Title</th>
                          <th>Score</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php
                       $pdo = Database::connect();
                       // SQL query to get the list of reviews for the resource
                       $sql = "SELECT tuid as id, title, score ".
                              "FROM iResourceReviews ".
                              "WHERE resourceTUID = ".$resource." ".
                              "ORDER BY title ASC";
                       foreach ($pdo->query($sql) as $row) {
                                echo '<tr>';
                                echo '<td>'. $row['title'] . '</td>';
                                echo '<td>'. $row['score'] . '</td>';
								echo '<td width=300>';
                                    echo '<a class="btn btn-default" href="viewResourceReview.php?id='.$row['id'].'">View Full Review</a>';
                                	
								if(isset($_SESSION['id']))
								{
                                	echo ' ';
                                	echo '<a class="btn btn-success" href="updateResourceReviews.php?id='.$row['id'].'">Update</a>';
                                	echo ' ';
                                	echo '<a class="btn btn-danger" href="deleteResourceReview.php?id='.$row['id'].'&resource='.$resource.'">Delete</a>';
								}
								echo '</td>';
                                echo '</tr>';
                       }
                       Database::disconnect();
                      ?>
                      </tbody>
                </table>
                <?php
                    echo '<p>';
                    if(isset($_SESSION['id']))
                       {
                           echo '<a href="createResourceReview.php?id='.$resource.'" class="btn btn-success">Add a Review</a>  ';
                       }
                       echo '<a class="btn btn-default" href="index.php">Back</a></p>';
                    
                ?>
        </div>
    </div> <!-- /container -->
  </body>
</html>
