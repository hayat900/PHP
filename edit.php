
<?php
require_once "pdo.php";
session_start();

if ( isset($_POST['track_name']) && isset($_POST['price'])
     && isset($_POST['description']) && isset($_POST['track_id']) && isset($_POST['album_name']) &&isset($_POST['artist_name']) ) {

    // Data validation
    if ( strlen($_POST['track_name']) < 1 || strlen($_POST['price']) < 1 || strlen($_POST['description']) < 1 || strlen($_POST['track_id']) < 1|| strlen($_POST['album_name']) < 1|| strlen($_POST['artist_name']) < 1) {
        $_SESSION['error'] = 'All fields are required';
        header("Location: edit.php?track_id=".$_POST['track_id']);
        return;
    }

  
    
else{
         
        //get album id
         $stmt1 = $pdo->prepare("SELECT * FROM track where track_id = :xyz");
$stmt1->execute(array(":xyz" => $_POST['track_id']));
$rowtrack = $stmt1->fetch(PDO::FETCH_ASSOC);
        //get artist id
$stmt2 = $pdo->prepare("SELECT * FROM album where album_id = :abc");
$stmt2->execute(array(":abc" => $rowtrack['album_id']));
$rowalbum = $stmt2->fetch(PDO::FETCH_ASSOC);
       //get artist name
$stmt3 = $pdo->prepare("SELECT * FROM artist where artist_id = :id1");
$stmt3->execute(array(":id1" => $rowalbum['artist_id']));
$rowartist = $stmt3->fetch(PDO::FETCH_ASSOC);  
        
      //update artist
$sql1 = "UPDATE artist SET artist_name = :name 
            WHERE artist_id = :id1";
$stmt4 = $pdo->prepare($sql1);
$stmt4->execute(array(
        ':name' => $_POST['artist_name'],
        ':id1' => $rowalbum['artist_id']
        ));
        
        //update album
 $sql2 = "UPDATE album SET album_name = :name,
            artist_id=:id3 WHERE album_id = :id2";
$stmt5 = $pdo->prepare($sql2);
$stmt5->execute(array(
        ':name' => $_POST['album_name'],
         ':id3' => $rowartist['artist_id'],
        ':id2' => $rowtrack['album_id']));
        
       //update track
$imgfile = $_FILES['img']['name'];
$tmp_dir = $_FILES['img']['tmp_name'];
$imgsize = $_FILES['img']['size'];
if($imgFile)
{
   $upload_dir = 'images/';
   $imgext = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); // get image extension
   $valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
   $pic = rand(1000,1000000).".".$imgext;
   if(in_array($imgext, $valid_extensions))
   {   
      if($imgsize < 5000000)//if size of image is greater than 5mb
      {
       unlink($upload_dir.$rowtrack['img']);
       move_uploaded_file($tmp_dir,$upload_dir.$pic);
      }
      else
       {
       $_SESSION['error1'] =" file  should be less then 5MB";
       }
   }
   else
   {
    $_SESSION['error1'] = "only JPG, JPEG, PNG & GIF files are allowed.";  
   } 
}
else
{
   $pic = $rowtrack['img']; //if user doesn't select new pic,old pic should be there as it is
} 
 $sql = "UPDATE track SET track_name = :make,
 price = :model, description = :mileage, img=:img1
 WHERE track_id = :user_id";
 $stmt = $pdo->prepare($sql);
$stmt->execute(array(
        ':make' => $_POST['track_name'],
        ':model' => $_POST['price'],
        ':mileage' => $_POST['description'],
        ':img1'=>$pic,
        ':user_id' => $_POST['track_id']));
$_SESSION['success'] = 'Record updated';
header( 'Location: index.php' ) ;
return;

}
}

//  Make sure that track_id is present
if ( ! isset($_GET['track_id']) ) {
  $_SESSION['error'] = "Missing track_id";
  header('Location: index.php');
  return;
}
//if track id  doesn't exist in database
$stmt = $pdo->prepare("SELECT * FROM track where track_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['track_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for track_id';
    header( 'Location: index.php' ) ;
    return;
}

 $stmt2 = $pdo->prepare("SELECT * FROM album where album_id = :abc");
$stmt2->execute(array(":abc" => $row['album_id']));
$rowalbum = $stmt2->fetch(PDO::FETCH_ASSOC);
       
$stmt3 = $pdo->prepare("SELECT * FROM artist where artist_id = :id1");
$stmt3->execute(array(":id1" => $rowalbum['artist_id']));
$rowartist = $stmt3->fetch(PDO::FETCH_ASSOC);  

// Flash pattern
if ( isset($_SESSION['error1']) ) {
    echo '<p style="color:red">'.$_SESSION['error1']."</p>\n";
    unset($_SESSION['error1']);
}

$n = htmlentities($row['track_name']);
$e = htmlentities($row['price']);
$p = htmlentities($row['description']);
$s=htmlentities($rowalbum['album_name']);
$t=htmlentities($rowartist['artist_name']);
$user_id = $row['track_id'];

?>
<img src="images/<?php echo $row['img'];?>"width="250px" height="250px">;
<p>Edit User</p>
<form method="post">
<p>Track Name:
<input type="text" name="track_name" value="<?= $n ?>"></p>
<p>Price:
<input type="text" name="price" value="<?= $e ?>"></p>
<p>Description:</p>
<textarea rows="10" cols="30" name="description"><?= $p ?></textarea>`` 
 <p>Image</p>
<input type="file" name="img" id="img" >
<p>Album name:
<input type="text" name="album_name" value="<?= $s ?>"></p>
<p>Artist name:
<input type="text" name="artist_name" value="<?= $t ?>"></p>
    
<input type="hidden" name="track_id" value="<?= $user_id ?>">
<p><input type="submit" value="Save"/>
<a href="index.php">Cancel</a></p>
</form>
