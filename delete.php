
<?php
require_once "pdo.php";
session_start();

if ( isset($_POST['delete']) && isset($_POST['track_id']) ) {
    $stmt1 = $pdo->prepare("SELECT album_id FROM track where track_id = :xyz");
$stmt1->execute(array(":xyz" => $_POST['track_id']));
$row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

     $sql = "DELETE FROM track WHERE track_id = :zip";//delete track
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_POST['track_id']));
    $_SESSION['success'] = 'Record deleted';
    header( 'Location: index.php' ) ;
    return;
}

//  Make sure that track_id is present
if ( ! isset($_GET['track_id']) ) {
  $_SESSION['error'] = "Missing track_id";
  header('Location: index.php');
  return;
}
//if track id is a value which doesn't exist in database
$stmt = $pdo->prepare("SELECT * FROM track where track_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['track_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for track_id';
    header( 'Location: index.php' ) ;
    return;
}

?>
<p>Confirm: Deleting <?= htmlentities($row['track_name']) ?></p>

<form method="post">
<input type="hidden" name="track_id" value="<?= $row['track_id'] ?>">
<input type="submit" value="Delete" name="delete">
<a href="details.php">Cancel</a>
</form>
