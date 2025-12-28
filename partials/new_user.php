<?php
require_once "../inc/auth.php";
require_admin();
?>
<h2>New User</h2>
<p id="formMessage"></p>

<form id="newUserForm">
  <input name="firstname" placeholder="First name" required />
  <input name="lastname" placeholder="Last name" required />
  <input name="email" type="email" placeholder="Email" required />
  <input name="password" type="password" placeholder="Password" required />
  <select name="role" required>
    <option value="Member">Member</option>
    <option value="Admin">Admin</option>
  </select>
  <button type="submit">Save</button>
</form>