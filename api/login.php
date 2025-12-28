<?php
require_once "../database.php";
require_once "../inc/helpers.php";

session_start();

$email = clean_email($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";

if ($email === "" || $password === "") {
  json_response(["ok" => false, "error" => "Email and password required"], 400);
}

$db = (new Database())->connect();
$stmt = $db->prepare("SELECT id, firstname, lastname, email, password, role FROM Users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user["password"])) {
  json_response(["ok" => false, "error" => "Invalid login"], 401);
}

$_SESSION["user"] = [
  "id" => $user["id"],
  "firstname" => $user["firstname"],
  "lastname" => $user["lastname"],
  "email" => $user["email"],
  "role" => $user["role"]
];

json_response(["ok" => true]);
