<?php
session_start();
if (isset($_SESSION["user"])) {
    header("Location: app.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <title>Dolphin CRM - Login</title>
  <link rel="stylesheet" href="assets/styles.css" />
</head>
<body>
  <div class="center-card">
    <h1>🐬 Dolphin CRM</h1>

    <form id="loginForm">
      <p id="loginError" class="error"></p>
      <input type="email" name="email" placeholder="Email address" required />
      <input type="password" name="password" placeholder="Password" required />
      <button type="submit">Login</button>
    </form>

    <p class="hint">Use: admin@project2.com / password123</p>
  </div>

  <script>
    const form = document.getElementById("loginForm");
    const loginError = document.getElementById("loginError");

    form.addEventListener("submit", async (e) => {
      e.preventDefault();
      loginError.textContent = "";

      const formData = new FormData(form);

      const res = await fetch("api/login.php", {
        method: "POST",
        body: formData
      });

      const data = await res.json();
      if (!data.ok) {
        loginError.textContent = data.error || "Login failed";
        return;
      }

      // Login successful
      window.location.href = "app.php";
    });
  </script>
</body>
</html>
