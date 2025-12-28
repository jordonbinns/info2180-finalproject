<?php
require_once "../inc/auth.php";
require_once "../inc/helpers.php";
require_once "../database.php";
require_login();

$contactId = intval($_POST["contact_id"] ?? 0);
if ($contactId <= 0) json_response(["ok" => false, "error" => "Invalid contact"], 400);

$db = (new Database())->connect();
$userId = $_SESSION["user"]["id"];

$stmt = $db->prepare("UPDATE Contacts SET assigned_to = ?, updated_at = NOW() WHERE id = ?");
$stmt->execute([$userId, $contactId]);

json_response(["ok" => true]);
