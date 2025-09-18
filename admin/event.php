<?php
require_once "../config.php";


// Add Live Update
if(isset($_POST['add_live'])){
    $headline = $_POST['headline'];
    $stmt = $conn->prepare("INSERT INTO live_updates (headline) VALUES (?)");
    $stmt->bind_param("s",$headline);
    $stmt->execute();
}

// Delete Live Update
if(isset($_GET['delete_live'])){
    $id = (int)$_GET['delete_live'];
    $conn->query("DELETE FROM live_updates WHERE id=$id");
    header("Location: live_admin.php");
    exit;
}

// Fetch live updates
$live = $conn->query("SELECT * FROM live_updates ORDER BY created_at DESC");
// Handle Add Fixture
if(isset($_POST['add_fixture'])){
    $team1 = $_POST['team1'];
    $team2 = $_POST['team2'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $venue = $_POST['venue'];
    $image = '';

    if(!empty($_FILES['image']['name'])){
        $image = time().'_'.basename($_FILES['image']['name']);
        $uploadDir = "../uploads/";
        if(!is_dir($uploadDir)) mkdir($uploadDir,0777,true);
        move_uploaded_file($_FILES['image']['tmp_name'],$uploadDir.$image);
    }

    $stmt = $conn->prepare("INSERT INTO club_fixture (team1,team2,date,time,venue,image) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param("ssssss",$team1,$team2,$date,$time,$venue,$image);
    $stmt->execute();
}

// Handle Delete Fixture
if(isset($_GET['delete_fixture'])){
    $id = (int)$_GET['delete_fixture'];
    $res = $conn->query("SELECT image FROM club_fixture WHERE id=$id");
    $row = $res->fetch_assoc();
    if($row['image'] && file_exists("../uploads/".$row['image'])) unlink("../uploads/".$row['image']);
    $conn->query("DELETE FROM club_fixture WHERE id=$id");
    header("Location: event.php");
    exit;
}

// Fetch Fixtures
$fixtures = $conn->query("SELECT * FROM club_fixture ORDER BY date ASC");
// Handle Add Event
if(isset($_POST['add_event'])){
    $title = $_POST['title'];
    $description = $_POST['description'];
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $live_link = $_POST['live_link'];
    $image = '';

    if(!empty($_FILES['image']['name'])){
        $image = time().'_'.basename($_FILES['image']['name']);
        $uploadDir = "../uploads/";
        if(!is_dir($uploadDir)) mkdir($uploadDir,0777,true);
        move_uploaded_file($_FILES['image']['tmp_name'],$uploadDir.$image);
    }

    $stmt = $conn->prepare("INSERT INTO events (title,description,event_date,event_time,image,live_link) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param("ssssss",$title,$description,$event_date,$event_time,$image,$live_link);
    $stmt->execute();
}

// Handle Delete Event
if(isset($_GET['delete_event'])){
    $id = (int)$_GET['delete_event'];
    $res = $conn->query("SELECT image FROM events WHERE id=$id");
    $row = $res->fetch_assoc();
    if($row['image'] && file_exists("../uploads/".$row['image'])) unlink("../uploads/".$row['image']);
    $conn->query("DELETE FROM events WHERE id=$id");
    header("Location: event.php");
    exit;
}

// Fetch Events
$events = $conn->query("SELECT * FROM events ORDER BY event_date ASC");

if(isset($_POST['add_news'])){
    $title = $_POST['title'];
    $description = $_POST['description'];
    $news_date = $_POST['news_date'];
    $news_time = $_POST['news_time'];

    $stmt = $conn->prepare("INSERT INTO news (title,description,news_date,news_time) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss",$title,$description,$news_date,$news_time);
    $stmt->execute();
}

// Delete News
if(isset($_GET['delete_news'])){
    $id = (int)$_GET['delete_news'];
    $conn->query("DELETE FROM news WHERE id=$id");
    header("Location: event.php");
    exit;
}

// Fetch News
$news = $conn->query("SELECT * FROM news ORDER BY news_date ASC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin - Events</title>
<style>
body{font-family:Poppins,sans-serif; background:#f4f6f9; padding:20px;}
form, .entry-box{background:#fff; padding:20px; border-radius:10px; margin-bottom:20px; box-shadow:0 6px 20px rgba(0,0,0,0.1);}
input,textarea,button{width:100%;padding:10px;margin:5px 0;border-radius:6px;border:1px solid #ccc;}
button{background:#0b74de;color:#fff;border:none;cursor:pointer;}
button:hover{background:#094a99;}
.entry-box img{max-width:150px;margin-bottom:10px;}
.actions a{margin-right:10px;text-decoration:none;padding:5px 10px;color:#fff;background:#ff5722;border-radius:5px;}
.actions a.delete{background:#dc3545;}
</style>
</head>
<body>

<h2>Add Event</h2>
<form method="post" enctype="multipart/form-data">
<input type="text" name="title" placeholder="Title" required>
<textarea name="description" placeholder="Description" required></textarea>
<input type="date" name="event_date" required>
<input type="time" name="event_time" required>
<input type="text" name="live_link" placeholder="Live Link (URL)">
<input type="file" name="image" accept="image/*">
<button type="submit" name="add_event">Add Event</button>
</form>

<h2>Existing Events</h2>
<?php while($row = $events->fetch_assoc()): ?>
<div class="entry-box">
<?php if($row['image']): ?>
<img src="../uploads/<?=htmlspecialchars($row['image'])?>" alt="">
<?php endif; ?>
<h3><?=htmlspecialchars($row['title'])?></h3>
<p><?=nl2br(htmlspecialchars($row['description']))?></p>
<p>Date: <?=htmlspecialchars($row['event_date'])?> Time: <?=htmlspecialchars($row['event_time'])?></p>
<?php if($row['live_link']): ?>
<a href="<?=htmlspecialchars($row['live_link'])?>" target="_blank">Watch Live</a>
<?php endif; ?>
<div class="actions">
<a href="?delete_event=<?= $row['id'] ?>" class="delete" onclick="return confirm('Are you sure?')">Delete</a>
</div>
</div>
<?php endwhile; ?>


<h2>Add Fixture</h2>
<form method="post" enctype="multipart/form-data">
<input type="text" name="team1" placeholder="Team 1" required>
<input type="text" name="team2" placeholder="Team 2" required>
<input type="date" name="date" required>
<input type="time" name="time" required>
<input type="text" name="venue" placeholder="Venue" required>
<input type="file" name="image" accept="image/*">
<button type="submit" name="add_fixture">Add Fixture</button>
</form>

<h2>Existing Fixtures</h2>
<?php while($row = $fixtures->fetch_assoc()): ?>
<div class="entry-box">
<img src="../uploads/<?=htmlspecialchars($row['image'])?>" alt="">
<h3><?=htmlspecialchars($row['team1'])?> vs <?=htmlspecialchars($row['team2'])?></h3>
<p>Date: <?=htmlspecialchars($row['date'])?> Time: <?=htmlspecialchars($row['time'])?></p>
<p>Venue: <?=htmlspecialchars($row['venue'])?></p>
<div class="actions">
<a href="?delete_fixture=<?= $row['id'] ?>" class="delete" onclick="return confirm('Are you sure?')">Delete</a>
</div>
</div>
<?php endwhile; ?>

<h2>Add Live Update</h2>
<form method="post">
<input type="text" name="headline" placeholder="Headline" required>
<button type="submit" name="add_live">Add</button>
</form>

<h2>Existing Live Updates</h2>
<?php while($row=$live->fetch_assoc()): ?>
<div>
<?=htmlspecialchars($row['headline'])?>
<a href="?delete_live=<?= $row['id'] ?>" onclick="return confirm('Delete?')">Delete</a>
</div>
<?php endwhile; ?>
</body>
</html>
