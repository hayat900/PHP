
<?php
session_start();

 
if(isset($_SESSION["success1"])){
        echo('<p style="color: green;">'.$_SESSION["success1"]."</p>");
     unset($_SESSION["success1"]);
 }
if(isset($_SESSION["success"])){
        echo('<p style="color: green;">'.$_SESSION["success"]."</p>");
     unset($_SESSION["success"]);
 }
?>
<!DOCTYPE html>
<html>
<head>
    </head>
<body>
    
    
<?php
require_once "pdo.php";


$stmt = $pdo->query("SELECT * FROM track");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
if($rows==null)
{
    echo("No rows found");
}
else{
            echo " <table border='1'>";
            echo "<tr><th>";
            echo "TRACK NAME";
            echo "</th><th>";
            echo "ACTION";
            echo "</th></tr>";
    
            for($i=0;$i<count($rows);$i++){
                $n=htmlentities($rows[$i]['track_name']);
                echo "<tr><td>";
                echo('<a href="details.php?track_id='.$rows[$i]['track_id'].'">'.$n.'</a>');
                echo("</td><td>");
                echo('<a href="edit.php?track_id='.$rows[$i]['track_id'].'">Edit</a> / ');
                echo('<a href="delete.php?track_id='.$rows[$i]['track_id'].'">Delete</a>');
                echo("</td></tr>\n");
                                }
            echo "</table>";   
    }
?>

    
   <a href="add.php">Add New Entry</a>
    <a href="logout.php">Logout</a>
    </body>



</html>
 