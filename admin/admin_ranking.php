<?php
require_once "../config.php";

$msg = "";

// Add new ranking
if(isset($_POST['add'])){
    $ranking = isset($_POST['rank']) ? intval($_POST['rank']) : 0;
    $country_name = $_POST['country_name'] ?? '';
    $points = $_POST['points'] ?? 0;
    $matches_played = $_POST['matches_played'] ?? 0;
    $wins = $_POST['wins'] ?? 0;
    $draws = $_POST['draws'] ?? 0;
    $losses = $_POST['losses'] ?? 0;

    // Handle flag upload
    $flag = $_FILES['country_flag']['name'];
    $tmp_name = $_FILES['country_flag']['tmp_name'];
    move_uploaded_file($tmp_name, "../uploads/".$flag);

    $sql = "INSERT INTO fifa_ranking (ranking, country_name, country_flag, points, matches_played, wins, draws, losses, last_update)
            VALUES ('$ranking', '$country_name', '$flag', '$points', '$matches_played', '$wins', '$draws', '$losses', NOW())";

    if($conn->query($sql)){
        $msg = "Ranking added successfully!";
    } else {
        $msg = "Error: ".$conn->error;
    }
}

// Delete ranking
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $conn->query("DELETE FROM fifa_ranking WHERE id=$id");
}

$rankings = $conn->query("SELECT * FROM fifa_ranking ORDER BY ranking ASC");

?>

<!DOCTYPE html>
<html>
<head>
    <title>FIFA Rankings Admin</title>
    <style>
        body{font-family:sans-serif; padding:20px;}
        table{width:100%; border-collapse:collapse;}
        th, td{border:1px solid #ddd; padding:8px; text-align:center;}
        th{background:#222; color:#fff;}
        form{margin-bottom:20px;}
        input, button{padding:5px; margin:5px;}
    </style>
</head>
<body>
<h2>FIFA Rankings Admin Panel</h2>
<p style="color:green;"><?php echo $msg; ?></p>

<form method="post" enctype="multipart/form-data">
    Rank: <input type="number" name="rank" required>
    Country Name: <input type="text" name="country_name" required>
    Flag: <input type="file" name="country_flag" required>
    Points: <input type="number" name="points" required>
    Matches: <input type="number" name="matches_played" required>
    Wins: <input type="number" name="wins" required>
    Draws: <input type="number" name="draws" required>
    Losses: <input type="number" name="losses" required>
    <button type="submit" name="add">Add</button>
</form>

<table>
    <tr>
        <th>Rank</th>
        <th>Country</th>
        <th>Flag</th>
        <th>MP</th>
        <th>W</th>
        <th>D</th>
        <th>L</th>
        <th>Action</th>
    </tr>
    <?php while($row = $rankings->fetch_assoc()): ?>
    <tr>
    <td><?php echo $row['ranking']; ?></td>
        <td><?php echo $row['country_name']; ?></td>
        <td><img src="../uploads/<?php echo $row['country_flag']; ?>" width="40"></td>
        <td><?php echo $row['points']; ?></td>
        <td><?php echo $row['matches_played']; ?></td>
        <td><?php echo $row['wins']; ?></td>
        <td><?php echo $row['draws']; ?></td>
        <td><?php echo $row['losses']; ?></td>
        <td><a href="?delete=<?php echo $row['id']; ?>">Delete</a></td>
    </tr>
    <?php endwhile; ?>
</table>
</body>
</html>
