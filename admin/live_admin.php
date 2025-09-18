<?php
include "../config.php"; // existing DB connection

// Add Match
if(isset($_POST['add_match'])){
    $match_name = $_POST['match_name'];
    $match_type = $_POST['match_type'];
    $match_time = $_POST['match_time'];
    $links = json_encode(array_filter($_POST['links']));

    $stmt = $conn->prepare("INSERT INTO matches (match_name, match_type, match_time, links) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss", $match_name, $match_type, $match_time, $links);
    $stmt->execute();
}

// Delete Match
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $conn->query("DELETE FROM matches WHERE id=$id");
}

// Fetch Matches
$matches = $conn->query("SELECT * FROM matches ORDER BY match_time ASC");
?>

<h2>Admin Panel - Add Matches</h2>
<form method="POST">
    <input type="text" name="match_name" placeholder="Match Name" required>
    <input type="text" name="match_type" placeholder="Match Type" required>
    <input type="datetime-local" name="match_time" required>
    <input type="url" name="links[]" placeholder="Link 1">
    <input type="url" name="links[]" placeholder="Link 2">
    <input type="url" name="links[]" placeholder="Link 3">
    <input type="url" name="links[]" placeholder="Link 4">
    <input type="url" name="links[]" placeholder="Link 5">
    <button type="submit" name="add_match">Add Match</button>
</form>

<h3>Existing Matches</h3>
<table border="1" cellpadding="5">
<tr>
<th>ID</th>
<th>Name</th>
<th>Type</th>
<th>Time</th>
<th>Links</th>
<th>Action</th>
</tr>
<?php while($row = $matches->fetch_assoc()): ?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= $row['match_name'] ?></td>
<td><?= $row['match_type'] ?></td>
<td><?= $row['match_time'] ?></td>
<td>
<?php
$links = json_decode($row['links'], true);
foreach($links as $link){
    echo "<a href='$link' target='_blank'>Watch</a> ";
}
?>
</td>
<td><a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete?')">Delete</a></td>
</tr>
<?php endwhile; ?>
</table>
<a href="dashboard.php">Home</a>
