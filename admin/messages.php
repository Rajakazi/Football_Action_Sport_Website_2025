<?php
include '../config.php';

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $res = $conn->query("SELECT image FROM contact_messages WHERE id=$id");
    $row = $res->fetch_assoc();
    if ($row['image'] && file_exists("../uploads/".$row['image'])) {
        unlink("../uploads/".$row['image']);
    }
    $conn->query("DELETE FROM contact_messages WHERE id=$id");
    header("Location: messages.php");
}
$messages = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin - Contact Messages</title>
  <style>
    body {font-family: Arial, sans-serif; background:#f9f9f9;}
    h2 {text-align:center; margin:20px;}
    table {width:90%; margin:20px auto; border-collapse:collapse;}
    table, th, td {border:1px solid #ccc;}
    th, td {padding:12px; text-align:left;}
    th {background:#007bff; color:#fff;}
    a.delete {color:red; text-decoration:none; font-weight:bold;}
    a.download {color:green; text-decoration:none;}
  </style>
</head>
<body>
  <h2>📩 Contact Messages</h2>
  <table>
    <tr>
      <th>ID</th><th>Name</th><th>Email</th><th>Message</th><th>Image</th><th>Date</th><th>Action</th>
    </tr>
    <?php while($row=$messages->fetch_assoc()): ?>
    <tr>
      <td><?= $row['id'] ?></td>
      <td><?= htmlspecialchars($row['name']) ?></td>
      <td><?= htmlspecialchars($row['email']) ?></td>
      <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
      <td>
        <?php if($row['image']): ?>
          <a class="download" href="../uploads/<?= $row['image'] ?>" download>Download</a>
        <?php else: ?>
          No Image
        <?php endif; ?>
      </td>
      <td><?= $row['created_at'] ?></td>
      <td><a class="delete" href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this message?')">Delete</a></td>
    </tr>
    <?php endwhile; ?>
  </table>
<a href="dashboard.php">Home</a>
</body>
</html>
