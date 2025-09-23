<?php
require_once "../config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accept = $_POST['accept_message'];
    $reject = $_POST['reject_message'];

    $stmt = $conn->prepare("INSERT INTO consent_settings (accept_message, reject_message) VALUES (?, ?)");
    $stmt->bind_param("ss", $accept, $reject);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Messages updated successfully!');window.location='accept.php';</script>";
}

// Load latest
$res = $conn->query("SELECT * FROM consent_settings ORDER BY id DESC LIMIT 1");
$row = $res->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Admin - Consent Settings</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body {font-family:Arial,sans-serif; background:#f4f4f4; padding:20px;}
    .card {background:white; padding:20px; border-radius:12px; max-width:500px; margin:auto;
      box-shadow:0 4px 10px rgba(0,0,0,0.2);}
    input[type=text], textarea {
      width:100%; padding:10px; margin:10px 0; border:1px solid #ccc; border-radius:8px;
    }
    button {
      padding:10px 20px; background:#4CAF50; border:none; color:white; border-radius:8px; cursor:pointer;
    }
  </style>
</head>
<body>
  <div class="card">
    <h2>Update Consent Messages</h2>
    <form method="post">
      <label>Accept Message:</label>
      <textarea name="accept_message" required><?php echo htmlspecialchars($row['accept_message']); ?></textarea>

      <label>Reject Message:</label>
      <textarea name="reject_message" required><?php echo htmlspecialchars($row['reject_message']); ?></textarea>

      <button type="submit">Save</button>
    </form>
  </div>
</body>
</html>
