<!DOCTYPE html>
<!-- 
Filename: viewGameReviews.php, Kyle Dunne, CIS355, 2015-04-21
This file shows the list reviews for a game
Code adapted from Multifile CRUD example done in class
-->

<?php
ini_set('display_errors',1); 
error_reporting(E_ALL);

include 'database.php';

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
                echo "<h3>Reviews for ".$gameTitle."</h3>";
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
                       // SQL query to get the list of reviews for the game
                       $sql = "SELECT tuid as id, title, score ".
                              "FROM iGameReviews ".
                              "WHERE gameTUID = ".$game." ".
                              "ORDER BY title ASC";
                       foreach ($pdo->query($sql) as $row) {
                                echo '<tr>';
                                echo '<td>'. $row['title'] . '</td>';
                                echo '<td>'. $row['score'] . '</td>';
								echo '<td width=300>';
                                    echo '<a class="btn btn-default" href="viewGameReview.php?id='.$row['id'].'">View Full Review</a>';
                                	
								if(isset($_SESSION['id']))
								{
                                	echo ' ';
                                	echo '<a class="btn btn-success" href="updateGameReviews.php?id='.$row['id'].'">Update</a>';
                                	echo ' ';
                                	echo '<a class="btn btn-danger" href="deleteGameReview.php?id='.$row['id'].'&game='.$game.'">Delete</a>';
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
                           echo '<a href="createGameReview.php?id='.$game.'" class="btn btn-success">Add a Review</a>  ';
                       }
                       echo '<a class="btn btn-default" href="index.php">Back</a></p>';
                    
                ?>
        </div>
    </div> <!-- /container -->
  </body>
</html>
