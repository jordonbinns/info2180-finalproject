<?php
require_once "../inc/auth.php";
require_once "../inc/helpers.php";
require_once "../database.php";
require_login();

require_fields($_POST, ["contact_id","comment"]);

$contactId = intval($_POST["contact_id"]);
$comment = trim($_POST["comment"]);

if ($contactId <= 0) json_response(["ok" => false, "error" => "Invalid contact"], 400);
if ($comment === "") json_response(["ok" => false, "error" => "Comment required"], 400);

$db = (new Database())->connect();
$userId = $_SESSION["user"]["id"];

$stmt = $db->prepare("INSERT INTO Notes (contact_id, comment, created_by) VALUES (?,?,?)");
$stmt->execute([$contactId, $comment, $userId]);

// also update contact updated_at
$update = $db->prepare("UPDATE Contacts SET updated_at = NOW() WHERE id = ?");
$update->execute([$contactId]);

json_response(["ok" => true]);
