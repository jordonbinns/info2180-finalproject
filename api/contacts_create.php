<?php
require_once "../inc/auth.php";
require_once "../inc/helpers.php";
require_once "../database.php";
require_login();

require_fields($_POST, ["firstname","lastname","email","type","assigned_to"]);

$title = clean_text($_POST["title"] ?? "");
$firstname = clean_text($_POST["firstname"]);
$lastname = clean_text($_POST["lastname"]);
$email = clean_email($_POST["email"]);
$telephone = clean_text($_POST["telephone"] ?? "");
$company = clean_text($_POST["company"] ?? "");
$type = clean_text($_POST["type"]);
$assignedTo = intval($_POST["assigned_to"]);

$createdBy = $_SESSION["user"]["id"];

$db = (new Database())->connect();
$stmt = $db->prepare("
  INSERT INTO Contacts (title, firstname, lastname, email, telephone, company, type, assigned_to, created_by)
  VALUES (?,?,?,?,?,?,?,?,?)
");
$stmt->execute([$title, $firstname, $lastname, $email, $telephone, $company, $type, $assignedTo, $createdBy]);

json_response(["ok" => true]);
