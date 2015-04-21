<!DOCTYPE html>
<!-- 
Filename: index.php, Kyle Dunne, CIS355, 2015-04-20
This file shows the list of games and has buttons to log in, add games, and look at genres
Code adapted from Multifile CRUD example done in class
-->

<?php
ini_set('display_errors',1); 
error_reporting(E_ALL);

include 'database.php';

//Get genre selected in viewGenre.php
if ( !empty($_GET['genre'])) {
       $genre = $_REQUEST['genre'];
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
                <h3>Kyle Dunne's Gaming Database</h3>
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
				echo '<p><a href="viewGenres.php" class="btn btn-success">View Genres</a></p>';
                
                //Show button to show games of all genres
                if (isset($genre))
                echo '<p><a href="index.php" class="btn btn-success">View all Games</a></p>';
                
				?>
                <table class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Game</th>
                          <th>Genre</th>
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
                       
                       if (!isset($genre)){
                       // SQL query to get the list of all games with genres and average review scores
                       $sql = "SELECT iGames.tuid as id, iGames.title as title, iGenres.genre as genre ".
                              "FROM iGames ".
                              "LEFT JOIN iGenres on iGames.genre = iGenres.tuid ".
                              "ORDER BY title ASC";
                       }
                       else //Get only games of a selected genre
                       {
                       $sql = "SELECT iGames.tuid as id, iGames.title as title, iGenres.genre as genre ".
                              "FROM iGames ".
                              "LEFT JOIN iGenres on iGames.genre = iGenres.tuid ".
                              "WHERE iGames.genre = ". $genre . " ".
                              "ORDER BY title ASC";
                       }
                       //pdo2 is used for getting average review scores
                       $pdo2 = Database::connect();
                       foreach ($pdo->query($sql) as $row) {
                                echo '<tr>';
                                echo '<td>'. $row['title'] . '</td>';
                                echo '<td>'. $row['genre'] . '</td>';
                                $sql2 = "SELECT Coalesce(ROUND(AVG(score),2), 'Unreviewed') as score FROM iGameReviews WHERE gameTUID = ".$row['id'];
                                //Execute query for average review scores
                                foreach($pdo2->query($sql2) as $row2){
                                echo '<td><a href="viewGameReviews.php?id='.$row['id'].'">'.$row2['score'].'</a></td>';
                                }
								echo '<td width=300>';
                                    echo '<a class="btn btn-default" href="viewResources.php?id='.$row['id'].'">View Resources</a>';
                                	
								if(isset($_SESSION['id']))
								{
                                	echo ' ';
                                	echo '<a class="btn btn-success" href="updateGames.php?id='.$row['id'].'">Update</a>';
                                	echo ' ';
                                	echo '<a class="btn btn-danger" href="deleteGame.php?id='.$row['id'].'">Delete</a>';
								}
								echo '</td>';
                                echo '</tr>';
                       }
                       Database::disconnect();
                      ?>
                      </tbody>
                </table>
                <?php
                    if(isset($_SESSION['id']))
                       {
                           echo '<p><a href="createGame.php" class="btn btn-success">Add a Game</a></p>';
                       }
                    
                ?>
        </div>
    </div> <!-- /container -->
  </body>
</html>
