<!DOCTYPE html>
<!-- 
Filename: viewGenres.php, Kyle Dunne, CIS355, 2015-04-20
This file shows the list of genres and buttons to update, delete or add genres
Code adapted from Multifile CRUD example done in class
-->
<?php
ini_set('display_errors',1); 
error_reporting(E_ALL);
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
                <h3>Genres</h3>
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
                          <th>Genre</th>
                          <th width = 150>Game Count</th>
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
                       include 'database.php';
                       $pdo = Database::connect();
                       // SQL query to get the list of games with genres and average review scores
                       $sql = "SELECT tuid as id, genre, ".
                                "(SELECT COUNT(iGames.tuid) ".
                                "From iGames WHERE iGames.genre = iGenres.tuid) as count ".
                              "from iGenres ".
                              "ORDER BY iGenres.genre ASC";
                       foreach ($pdo->query($sql) as $row) {
                                echo '<tr>';
                                if ($row['count'] > 0)
                                {
                                echo '<td><a href="index.php?genre='.$row['id'].'">'. $row['genre'] . '</a></td>';
                                }
                                else
                                {
                                echo '<td>'. $row['genre'] . '</td>';
                                }
                                echo '<td>'.$row['count'].'</a></td>';
					 	
								if(isset($_SESSION['id']))
								{
                                	echo ' <td>';
                                	echo '<a class="btn btn-success" href="updateGenres.php?id='.$row['id'].'">Update</a>';
                                	echo ' ';
                                    //Only allows deletion if there are no games in the genre
                                    if ($row['count'] == 0)
                                    {
                                        echo '<a class="btn btn-danger" href="deleteGenre.php?id='.$row['id'].'">Delete</a>';
                                    }
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
                           echo '<p><a href="createGenre.php" class="btn btn-success">Add a Genre</a></p>';
                       }
                    echo '<p><a href="index.php" class="btn btn-default">Back</a></p>';
                ?>
        </div>
    </div> <!-- /container -->
  </body>
</html>
