<?php
require_once "../inc/auth.php";
require_once "../inc/helpers.php";
require_once "../database.php";
require_login();

$contactId = intval($_POST["contact_id"] ?? 0);
if ($contactId <= 0) json_response(["ok" => false, "error" => "Invalid contact"], 400);

$db = (new Database())->connect();

$stmt = $db->prepare("SELECT type FROM Contacts WHERE id = ?");
$stmt->execute([$contactId]);
$row = $stmt->fetch();
if (!$row) json_response(["ok" => false, "error" => "Not found"], 404);

$newType = ($row["type"] === "Sales Lead") ? "Support" : "Sales Lead";

$update = $db->prepare("UPDATE Contacts SET type = ?, updated_at = NOW() WHERE id = ?");
$update->execute([$newType, $contactId]);

json_response(["ok" => true, "type" => $newType]);
