<?php
require_once "config.php";

$id = intval($_GET['id']);
$conn->query("UPDATE news SET views = views + 1 WHERE id=$id");

$result = $conn->query("SELECT views FROM news WHERE id=$id");
$row = $result->fetch_assoc();

echo json_encode(['views'=> (int)$row['views']]);
?>
