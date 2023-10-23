<?php
require_once "pdo.php";
// id from url 
$id = $_GET['id'];
// delete the thumbnail from the database
$sql = "DELETE FROM thumbnails WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->execute();

header("Location: index.php");