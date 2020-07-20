<?php
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=music','your_db_username','your_db_password');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>