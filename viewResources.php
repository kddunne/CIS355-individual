<!DOCTYPE html>
<!-- 
Filename: viewResources.php, Kyle Dunne, CIS355, 2015-04-21
This file shows the resources for a given game
Code adapted from Multifile CRUD example done in class
-->

<?php
ini_set('display_errors',1); 
error_reporting(E_ALL);

include 'database.php';

//Get the tuid of the selected game or redirect to index if none selected
if ( !empty($_GET['id'])) {
       $game = $_REQUEST['id'];
   }
else
   {
       header("Location: index.php");
   }
//Get title of game
$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = $pdo->prepare("SELECT title from iGames where tuid = ".$game);
$sql->execute();
$row = $sql->fetch();
$gameTitle = $row[0];
Database::disconnect();
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
                echo "<h3>Resources for ".$gameTitle."</h3>";
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
                          <th>Resource</th>
                          <th>Description</th>
                          <th>Average Rating</th>
						  <?php
							if (isset($_SESSION['id']))
							{
							echo '<th>Action</th>';
							}
							?>
                        </tr>
                      </thead>
                      <tbody>
                      <?php
                       
                       $pdo = Database::connect();
                       $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                       // SQL query to get the list of all resources for a game
                       $sql = "SELECT tuid as id, title, link , description ".
                              "FROM iResources ".
                              "WHERE gameTUID = ".$game." ".
                              "ORDER BY title ASC";
                       //pdo2 is used for getting average review scores
                       $pdo2 = Database::connect();
                       $pdo2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                       foreach ($pdo->query($sql) as $row) {
                                echo '<tr>';
                                echo '<td><a href="'.$row['link'].'">'. $row['title'] . '</a></td>';
                                echo '<td>'. $row['description'] . '</td>';
                                $sql2 = "SELECT Coalesce(ROUND(AVG(score),2), 'Unreviewed') as score FROM iResourceReviews WHERE resourceTUID = ".$row['id'];
                                //Execute query for average review scores
                                foreach($pdo2->query($sql2) as $row2){
                                echo '<td><a href="viewResourceReviews.php?id='.$row['id'].'">'.$row2['score'].'</a></td>';
                                }
								
								if(isset($_SESSION['id']))
								{
                                    echo '<td width=300>';
                                	echo ' ';
                                	echo '<a class="btn btn-success" href="updateResource.php?id='.$row['id'].'">Update</a>';
                                	echo ' ';
                                	echo '<a class="btn btn-danger" href="deleteResource.php?id='.$row['id'].'">Delete</a>';
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
                           echo '<a href="createResource.php?id='.$game.'" class="btn btn-success">Add a Resource</a>  ';
                       }
                       echo '<a class="btn btn-default" href="index.php">Back</a></p>';
                ?>
        </div>
    </div> <!-- /container -->
  </body>
</html>
