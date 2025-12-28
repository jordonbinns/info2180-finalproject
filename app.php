<?php
require_once "inc/auth.php";
require_login();

$user = $_SESSION["user"];
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <title>Dolphin CRM</title>
  <link rel="stylesheet" href="assets/styles.css" />
</head>
<body>
  <div class="topbar">
    <div class="brand">🐬 Dolphin CRM</div>
    <div class="userbox">
      Signed in as: <b><?php echo htmlspecialchars($user["firstname"] . " " . $user["lastname"]); ?></b>
      (<?php echo htmlspecialchars($user["role"]); ?>)
      <button id="logoutBtn" class="link-btn">Logout</button>
    </div>
  </div>

  <div class="layout">
    <div class="sidebar">
      <button class="nav-btn" data-page="dashboard">Home</button>
      <button class="nav-btn" data-page="new_contact">New Contact</button>
      <?php if ($user["role"] === "Admin"): ?>
        <button class="nav-btn" data-page="users">Users</button>
      <?php endif; ?>
    </div>

    <div class="main">
      <div id="content"></div>
    </div>
  </div>

  <script src="assets/app.js"></script>
</body>
</html>
