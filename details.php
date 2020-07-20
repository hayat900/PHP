
<?php
require_once "pdo.php";
session_start();
if ( ! isset($_GET['track_id']) ) {
  $_SESSION['error'] = "Missing user_id";
  header('Location: index.php');
  return;
}
//get track name
$stmt = $pdo->prepare("SELECT * FROM track where track_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['track_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for user_id';
    header( 'Location: index.php' ) ;
    return;
}
//get album name
 $stmt2 = $pdo->prepare("SELECT * FROM album where album_id = :abc");
$stmt2->execute(array(":abc" => $row['album_id']));
$rowalbum = $stmt2->fetch(PDO::FETCH_ASSOC);
       //get artist name
$stmt3 = $pdo->prepare("SELECT * FROM artist where artist_id = :id1");
$stmt3->execute(array(":id1" => $rowalbum['artist_id']));
$rowartist = $stmt3->fetch(PDO::FETCH_ASSOC);
//view for a single product
echo " <table border='1'>";
echo "<tr><th>";
echo "TRACK NAME";           
echo "</th><th>";
echo "ALBUM NAME";
echo "</th><th>";
echo "ARTIST NAME";
echo "</th><th>";
 echo "PRICE";
echo "</th><th>";
echo "DESCRIPTION";
echo "</th><th>";
echo "IMAGE";
echo "</th></tr>";


echo "<tr><td>";
echo htmlentities($row['track_name']);
echo "</td><td>";
echo htmlentities($rowalbum['album_name']);
echo "</td><td>";
echo  htmlentities($rowartist['artist_name']);
echo "</td><td>";
echo htmlentities($row['price']);
echo "</td><td>";
echo htmlentities($row['description']);
echo "</td><td>";
$image=$row['img'];
echo "<img height='100px' width='100px'  src='images/".$image."'>";
  echo "</td></tr>";
echo "</table>";
echo '<a href="index.php">Back</a>';
?>