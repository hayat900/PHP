
<?php
 session_start();
require_once "pdo.php";

// Demand a GET parameter

if ( isset($_POST['cancel']) ) {
    header('Location: index.php');
    return;
}

if ( isset($_POST['track_name'])  && isset($_POST['price'])&& isset($_POST['album_name']) && isset($_POST['artist_name'])  && isset($_POST['description'])) {
    
    if(strlen($_POST["track_name"])<1 ||strlen($_POST["album_name"])<1||strlen($_POST["artist_name"])<1 ||strlen($_POST["price"])<1 ||strlen($_POST["description"])<1){
            $_SESSION["error1"]="All fields are required";
             header( 'Location: add.php' ) ;
            return;
        }
    
    else{
       
        $imgfile=$_FILES['img']['name'];
        $tmp_dir=$_FILES['img']['tmp_name'];
        $imgsize=$_FILES['img']['size'];
           if(empty($imgfile)){
               $_SESSION['error1']="please select a file";
            }
            else{
            $upload_dir='images/';//uploaded images should be present in images folder
                $imgext=strtolower(pathinfo($imgfile,PATHINFO_EXTENSION));
            $valid_extensions=array('jpeg','jpg','png','gif');
            $pic=rand(1000,1000000).".".$imgext;
                if(in_array($imgext,$valid_extensions)){
                    if($imgsize<5000000){//file size should be less than 5 mb
                        move_uploaded_file($tmp_dir,$upload_dir.$pic);
                    }
                    else{
                    $_SESSION['error1']="file too large";
                     }
                }
               else{
                $_SESSION['error1']="invalid file type";
                }
            }
    //insert into artist
        $stmt = $pdo->prepare("SELECT * FROM artist where artist_name = :xyz");
        $stmt->execute(array(":xyz" => $_POST['artist_name']));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ( $row === false ) {
        $sql = "INSERT INTO artist(artist_name) values (:name)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
        ':name'=>$_POST['artist_name']));
        }
        //insert into album
         $stmt1 = $pdo->prepare("SELECT * FROM album where album_name = :abc");
        $stmt1->execute(array(":abc" => $_POST['album_name']));
        $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
        $stmt = $pdo->prepare("SELECT * FROM artist where artist_name = :xyz");
        $stmt->execute(array(":xyz" => $_POST['artist_name']));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);       
        if ( $row1 === false ) {
        $sql1 = "INSERT INTO album(album_name,artist_id) values (:name,:id)";
        $stmt1 = $pdo->prepare($sql1);
        $stmt1->execute(array(
        ':name'=>$_POST['album_name'],
        ':id'=>$row['artist_id']
        ));
        }
        //insert into track
        $stmt1 = $pdo->prepare("SELECT * FROM album where album_name = :abc");
        $stmt1->execute(array(":abc" => $_POST['album_name']));
        $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
        $sql3 = "INSERT INTO track (track_name,album_id,price,description,img) 
              VALUES (:track_name, :album_id,:price, :description,:img)";
   
        $stmt = $pdo->prepare($sql3);
        $stmt->execute(array(
        ':track_name' => $_POST['track_name'],
         ':album_id' => $row1['album_id'],
        ':price' => $_POST['price'],
        ':img'=>$pic,
        ':description' => $_POST['description']));
        
        $_SESSION["success1"]=" Record added";
        
    }
}


if(isset($_SESSION["success1"])){
    
        header("Location: index.php");//go to index if record is successfully added
        return;
    }
if(isset($_SESSION["error1"])){
        echo('<p style="color:red">'.$_SESSION["error1"]."</p>\n");//  error
        unset($_SESSION["error1"]);
    }
    

?>

<html>
    
    <body>
<form method="post" enctype='multipart/form-data'>
<p> Track Name:
<input type="text" name="track_name" size="40"></p>
    <p> Album Name:
<input type="text" name="album_name" size="40"></p>
    <p> artist Name:
<input type="text" name="artist_name" size="40"></p>
    <p>Product price:
<input type="text" name="price"></p>


    <p>Description:</p>
        <textarea rows="10" cols="30" name="description"></textarea>
    <p>Image</p>
    <input type="file" name="img" id="img">
<p><input type="submit" value="Add" name="submit"/>
    <input type="submit" value="cancel" name="cancel"/></p>
</form>
</body>
</html>