<?php
require_once "../inc/auth.php";
require_once "../inc/helpers.php";
require_once "../database.php";
require_login();

$db = (new Database())->connect();
$filter = $_GET["filter"] ?? "all";
$userId = $_SESSION["user"]["id"];

$where = "1=1";
$params = [];

if ($filter === "sales") { $where = "type = ?"; $params[] = "Sales Lead"; }
if ($filter === "support") { $where = "type = ?"; $params[] = "Support"; }
if ($filter === "assigned") { $where = "assigned_to = ?"; $params[] = $userId; }

$stmt = $db->prepare("SELECT * FROM Contacts WHERE $where ORDER BY created_at DESC");
$stmt->execute($params);

json_response(["ok" => true, "contacts" => $stmt->fetchAll()]);
