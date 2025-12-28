<?php
require_once "../inc/auth.php";
require_once "../database.php";
require_admin();

$db = (new Database())->connect();

$stmt = $db->query("SELECT firstname, lastname, email, role, created_at FROM Users ORDER BY created_at DESC");
$users = $stmt->fetchAll();
?>

<h2>Users</h2>

<?php if (isset($_GET["user_created"])): ?>
  <p class="success">User created!</p>
<?php endif; ?>

<button id="showNewUserFormBtn">Add User</button>

<table class="table" style="margin-top:12px;">
  <thead>
    <tr>
      <th>Name</th><th>Email</th><th>Role</th><th>Created</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($users as $u): ?>
      <tr>
        <td><?php echo htmlspecialchars($u["firstname"]." ".$u["lastname"]); ?></td>
        <td><?php echo htmlspecialchars($u["email"]); ?></td>
        <td><?php echo htmlspecialchars($u["role"]); ?></td>
        <td><?php echo htmlspecialchars($u["created_at"]); ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
