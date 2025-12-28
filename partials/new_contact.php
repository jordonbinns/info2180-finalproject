<?php
require_once "../inc/auth.php";
require_once "../database.php";
require_login();

$db = (new Database())->connect();
$stmt = $db->query("SELECT id, firstname, lastname FROM Users ORDER BY firstname ASC");
$users = $stmt->fetchAll();
?>

<h2>New Contact</h2>
<p id="formMessage"></p>

<form id="newContactForm">
  <select name="title">
    <option value="">Title (optional)</option>
    <option>Mr</option><option>Mrs</option><option>Ms</option><option>Dr</option><option>Prof</option>
  </select>

  <input name="firstname" placeholder="First name" required />
  <input name="lastname" placeholder="Last name" required />
  <input name="email" type="email" placeholder="Email" required />
  <input name="telephone" placeholder="Telephone" />
  <input name="company" placeholder="Company" />

  <select name="type" required>
    <option value="Sales Lead">Sales Lead</option>
    <option value="Support">Support</option>
  </select>

  <select name="assigned_to" required>
    <option value="">Assigned To</option>
    <?php foreach ($users as $u): ?>
      <option value="<?php echo $u["id"]; ?>">
        <?php echo htmlspecialchars($u["firstname"]." ".$u["lastname"]); ?>
      </option>
    <?php endforeach; ?>
  </select>

  <button type="submit">Save</button>
</form>
