<?php
require_once "../inc/auth.php";
require_once "../database.php";
require_login();

$db = (new Database())->connect();

$filter = $_GET["filter"] ?? "all";
$userId = $_SESSION["user"]["id"];

$where = "1=1";
$params = [];

if ($filter === "sales") {
  $where = "c.type = ?";
  $params[] = "Sales Lead";
} elseif ($filter === "support") {
  $where = "c.type = ?";
  $params[] = "Support";
} elseif ($filter === "assigned") {
  $where = "c.assigned_to = ?";
  $params[] = $userId;
}

$sql = "
  SELECT c.id, c.title, c.firstname, c.lastname, c.email, c.company, c.type
  FROM Contacts c
  WHERE $where
  ORDER BY c.created_at DESC
";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$contacts = $stmt->fetchAll();
?>

<h2>Dashboard</h2>

<?php if (isset($_GET["contact_created"])): ?>
  <p class="success">Contact created!</p>
<?php endif; ?>


<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
  <div>
    <b>Filter by:</b>
    <button data-filter="all">All</button>
    <button data-filter="sales">Sales Leads</button>
    <button data-filter="support">Support</button>
    <button data-filter="assigned">Assigned to me</button>
  </div>

  <button id="dashboardAddContactBtn">+ Add Contact</button>
</div>


<table class="table">
  <thead>
    <tr>
      <th>Name</th>
      <th>Email</th>
      <th>Company</th>
      <th>Type</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($contacts as $c): ?>
      <tr>
        <td><?php echo htmlspecialchars(($c["title"] ? $c["title"]." " : "") . $c["firstname"] . " " . $c["lastname"]); ?></td>
        <td><?php echo htmlspecialchars($c["email"] ?? ""); ?></td>
        <td><?php echo htmlspecialchars($c["company"] ?? ""); ?></td>
        <td>
          <?php if ($c["type"] === "Sales Lead"): ?>
            <span class="badge sales">SALES LEAD</span>
          <?php else: ?>
            <span class="badge support">SUPPORT</span>
          <?php endif; ?>
        </td>
        <td><button data-view-contact="<?php echo $c["id"]; ?>">View</button></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
