<?php
require_once "../inc/auth.php";
require_once "../inc/helpers.php";
require_once "../database.php";

require_admin();

require_fields($_POST, ["firstname", "lastname", "email", "password", "role"]);

$firstname = clean_text($_POST["firstname"]);
$lastname  = clean_text($_POST["lastname"]);
$email     = clean_email($_POST["email"]);
$password  = $_POST["password"];
$role      = clean_text($_POST["role"]);

// Simple password rule 
if (!preg_match("/^(?=.*[A-Z])(?=.*\d).{8,}$/", $password)) {
  json_response(["ok" => false, "error" => "Password must be 8+ chars with at least 1 capital letter and 1 number"], 400);
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$db = (new Database())->connect();

try {
  $stmt = $db->prepare("INSERT INTO Users (firstname, lastname, email, password, role) VALUES (?,?,?,?,?)");
  $stmt->execute([$firstname, $lastname, $email, $hash, $role]);

  json_response(["ok" => true]);
} catch (PDOException $e) {
  json_response(["ok" => false, "error" => "Could not create user (email may already exist)"], 400);
}
