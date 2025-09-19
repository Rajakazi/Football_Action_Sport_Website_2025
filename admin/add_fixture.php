<?php
include '../config.php';
session_start();

// Fetch all fixtures
$fixtures_result = $conn->query("SELECT * FROM fixtures ORDER BY id DESC");

// Handle Add Fixture
if (isset($_POST['add_fixture'])) {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $headline = $_POST['headline'] ?? '';
    $match_title = $_POST['match_title'] ?? '';
    $fixture_date = $_POST['fixture_date'] ?? ''; // Added fixture_date field
    $image = '';

    // If title is empty, stop and show error
    if (trim($title) === '') {
        echo "<p style='color:red;'>Error: Title is required.</p>";
        exit;
    }

    if (!empty($_FILES['image']['name'])) {
        // Handle file upload errors
        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            echo "<p style='color:red;'>File upload failed with error code: " . $_FILES['image']['error'] . "</p>";
            exit;
        }

        $image = time() . '_' . basename($_FILES['image']['name']);
        $target_dir = "../uploads/";
        $target_file = $target_dir . $image;

        // Create uploads directory if it doesn't exist
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Move the uploaded file
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            echo "<p style='color:red;'>Error uploading file. Check folder permissions.</p>";
            exit;
        }
    }

    // Updated to include all required fields
    $stmt = $conn->prepare("INSERT INTO fixtures (title, description, headline, match_title, fixture_date, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $title, $description, $headline, $match_title, $fixture_date, $image);

    if ($stmt->execute()) {
        header("Location: add_fixture.php");
        exit;
    } else {
        echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
    }
}

// Handle Update Fixture
if (isset($_POST['update_fixture'])) {
    $id = $_POST['fixture_id'] ?? '';
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $headline = $_POST['headline'] ?? '';
    $match_title = $_POST['match_title'] ?? '';
    $fixture_date = $_POST['fixture_date'] ?? ''; // Added fixture_date field
    $current_image = $_POST['current_image'] ?? '';
    $image = $current_image;

    // If title is empty, stop and show error
    if (trim($title) === '') {
        echo "<p style='color:red;'>Error: Title is required.</p>";
        exit;
    }

    if (!empty($_FILES['new_image']['name'])) {
        // Handle file upload errors
        if ($_FILES['new_image']['error'] !== UPLOAD_ERR_OK) {
            echo "<p style='color:red;'>File upload failed with error code: " . $_FILES['new_image']['error'] . "</p>";
            exit;
        }

        $image = time() . '_' . basename($_FILES['new_image']['name']);
        $target_dir = "../uploads/";
        $target_file = $target_dir . $image;

        // Create uploads directory if it doesn't exist
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Move the uploaded file
        if (!move_uploaded_file($_FILES['new_image']['tmp_name'], $target_file)) {
            echo "<p style='color:red;'>Error uploading file. Check folder permissions.</p>";
            exit;
        }
        
        // Delete old image if it exists
        if (!empty($current_image) && file_exists($target_dir . $current_image)) {
            unlink($target_dir . $current_image);
        }
    }

    // Updated to include all required fields
    $stmt = $conn->prepare("UPDATE fixtures SET title=?, description=?, headline=?, match_title=?, fixture_date=?, image=? WHERE id=?");
    $stmt->bind_param("ssssssi", $title, $description, $headline, $match_title, $fixture_date, $image, $id);

    if ($stmt->execute()) {
        header("Location: admin_fixtures.php");
        exit;
    } else {
        echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
    }
}

// Handle Delete Fixture
if (isset($_GET['delete_fixture'])) {
    $id = $_GET['delete_fixture'];
    
    // Get image name to delete it from server
    $result = $conn->query("SELECT image FROM fixtures WHERE id = $id");
    $row = $result->fetch_assoc();
    $image = $row['image'];
    
    // Delete from database
    $stmt = $conn->prepare("DELETE FROM fixtures WHERE id=?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        // Delete image file if exists
        if (!empty($image) && file_exists("../uploads/" . $image)) {
            unlink("../uploads/" . $image);
        }
        header("Location: admin_fixtures.php");
        exit;
    } else {
        echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Fixtures</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; background: #f4f6f9; padding: 20px; color: #333; }
        h2 { color: #0b74de; margin-bottom: 10px; border-bottom: 2px solid #0b74de; padding-bottom: 5px; }
        form { background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1); margin-bottom: 20px; }
        input[type="text"], input[type="date"], textarea, input[type="file"], button { padding: 10px 12px; margin: 8px 0; border-radius: 6px; border: 1px solid #ccc; width: 100%; box-sizing: border-box; }
        button { background: #0b74de; color: #fff; cursor: pointer; border: none; font-weight: 600; transition: background 0.3s ease; }
        button:hover { background: #094a99; }
        .fixture-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; margin-top: 20px; }
        .fixture-card { background: #fff; padding: 15px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); text-align: center; }
        .fixture-card img { width: 100%; height: 150px; object-fit: cover; border-radius: 6px; margin-bottom: 10px; }
        .fixture-card p { margin: 5px 0; color: #555; }
        .fixture-card .fixture-title { font-weight: bold; color: #0b74de; font-size: 1.2em; }
        .fixture-card .fixture-headline { font-weight: 600; color: #333; font-size: 1em; }
        .fixture-card .match-title { font-weight: 500; color: #e74c3c; font-size: 1.1em; }
        .fixture-card .fixture-date { font-weight: 500; color: #27ae60; font-size: 0.9em; }
        .fixture-actions { display: flex; justify-content: space-around; margin-top: 10px; }
        .fixture-actions button, .fixture-actions a { padding: 8px 12px; border-radius: 6px; text-decoration: none; font-weight: 500; }
        .delete-btn { background: #dc3545; color: white; border: none; }
        .delete-btn:hover { background: #c82333; }
        .edit-btn { background: #ffc107; color: #333; border: none; }
        .edit-btn:hover { background: #e0a800; }
        #edit-form-container { display: none; margin-top: 20px; border-top: 2px solid #ccc; padding-top: 20px; }
        .cancel-edit { background: #6c757d; margin-left: 10px; }
        .cancel-edit:hover { background: #5a6268; }
    </style>
</head>
<body>

<h2>Add New Fixture</h2>
<form method="post" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="Title" required>
    <input type="text" name="headline" placeholder="Headline" required>
    <input type="text" name="match_title" placeholder="Match Title" required>
    <input type="date" name="fixture_date" placeholder="Fixture Date" required> <!-- Added fixture_date field -->
    <textarea name="description" placeholder="Short Description" rows="3"></textarea>
    <label>Fixture Image:</label>
    <input type="file" name="image" accept="image/*" required>
    <button type="submit" name="add_fixture">Add Fixture</button>
</form>

<div id="edit-form-container">
    <h2>Edit Fixture</h2>
    <form id="edit-form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="update_fixture">
        <input type="hidden" name="fixture_id" id="edit-id">
        <input type="text" name="title" id="edit-title" placeholder="Title" required>
        <input type="text" name="headline" id="edit-headline" placeholder="Headline" required>
        <input type="text" name="match_title" id="edit-match-title" placeholder="Match Title" required>
        <input type="date" name="fixture_date" id="edit-fixture-date" placeholder="Fixture Date" required> <!-- Added fixture_date field -->
        <textarea name="description" id="edit-description" placeholder="Short Description" rows="3"></textarea>
        <input type="hidden" name="current_image" id="edit-current-image">
        <label>Current Image: <span id="current-image-name"></span></label><br>
        <label>New Fixture Image (optional):</label>
        <input type="file" name="new_image" accept="image/*">
        <button type="submit">Update Fixture</button>
        <button type="button" class="cancel-edit" onclick="cancelEdit()">Cancel</button>
    </form>
</div>

<h2>Existing Fixtures</h2>
<div class="fixture-grid">
    <?php while ($row = $fixtures_result->fetch_assoc()): ?>
        <div class="fixture-card">
            <?php if ($row['image'] != ''): ?>
                <img src="../uploads/<?= htmlspecialchars($row['image']) ?>" alt="Fixture Image">
            <?php endif; ?>
            <p class="fixture-title"><?= htmlspecialchars($row['title']) ?></p>
            <p class="fixture-headline"><?= htmlspecialchars($row['headline']) ?></p>
            <p class="match-title"><?= htmlspecialchars($row['match_title']) ?></p>
            <p class="fixture-date"><?= date('M j, Y', strtotime($row['fixture_date'])) ?></p> <!-- Added fixture_date display -->
            <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>
            <div class="fixture-actions">
                <button class="edit-btn" onclick="editFixture(<?= $row['id'] ?>, '<?= htmlspecialchars($row['title'], ENT_QUOTES) ?>', '<?= htmlspecialchars($row['headline'], ENT_QUOTES) ?>', '<?= htmlspecialchars($row['match_title'], ENT_QUOTES) ?>', '<?= $row['fixture_date'] ?>', '<?= htmlspecialchars($row['description'], ENT_QUOTES) ?>', '<?= htmlspecialchars($row['image'], ENT_QUOTES) ?>')">Edit</button>
                <a href="?delete_fixture=<?= $row['id'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this fixture?');">Delete</a>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<script>
    function editFixture(id, title, headline, match_title, fixture_date, description, image) {
        document.getElementById('edit-form-container').style.display = 'block';
        window.scrollTo({
            top: document.getElementById('edit-form-container').offsetTop - 20,
            behavior: 'smooth'
        });
        
        document.getElementById('edit-id').value = id;
        document.getElementById('edit-title').value = title;
        document.getElementById('edit-headline').value = headline;
        document.getElementById('edit-match-title').value = match_title;
        document.getElementById('edit-fixture-date').value = fixture_date;
        document.getElementById('edit-description').value = description;
        document.getElementById('edit-current-image').value = image;
        document.getElementById('current-image-name').textContent = image || 'No image';
    }
    
    function cancelEdit() {
        document.getElementById('edit-form-container').style.display = 'none';
        document.getElementById('edit-form').reset();
    }
</script>

</body>
</html>