<?php
require_once "../inc/auth.php";
require_once "../database.php";
require_login();

$db = (new Database())->connect();
$contactId = intval($_GET["id"] ?? 0);

$sql = "
  SELECT c.*, 
         u1.firstname AS assigned_first, u1.lastname AS assigned_last,
         u2.firstname AS created_first, u2.lastname AS created_last
  FROM Contacts c
  LEFT JOIN Users u1 ON c.assigned_to = u1.id
  LEFT JOIN Users u2 ON c.created_by = u2.id
  WHERE c.id = ?
";
$stmt = $db->prepare($sql);
$stmt->execute([$contactId]);
$contact = $stmt->fetch();

if (!$contact) {
  echo "<p class='error'>Contact not found.</p>";
  exit;
}

$notesStmt = $db->prepare("
  SELECT n.comment, n.created_at, u.firstname, u.lastname
  FROM Notes n
  JOIN Users u ON n.created_by = u.id
  WHERE n.contact_id = ?
  ORDER BY n.created_at DESC
");
$notesStmt->execute([$contactId]);
$notes = $notesStmt->fetchAll();

$typeLabel = ($contact["type"] === "Sales Lead") ? "Switch to Support" : "Switch to Sales Lead";
?>

<h2><?php echo htmlspecialchars(($contact["title"] ? $contact["title"]." " : "") . $contact["firstname"]." ".$contact["lastname"]); ?></h2>

<p>
  <b>Email:</b> <?php echo htmlspecialchars($contact["email"] ?? ""); ?><br/>
  <b>Telephone:</b> <?php echo htmlspecialchars($contact["telephone"] ?? ""); ?><br/>
  <b>Company:</b> <?php echo htmlspecialchars($contact["company"] ?? ""); ?><br/>
  <b>Type:</b> <?php echo htmlspecialchars($contact["type"]); ?><br/>
  <b>Assigned To:</b> <?php echo htmlspecialchars(($contact["assigned_first"] ?? "")." ".($contact["assigned_last"] ?? "")); ?><br/>
  <b>Created By:</b> <?php echo htmlspecialchars(($contact["created_first"] ?? "")." ".($contact["created_last"] ?? "")); ?><br/>
  <b>Created At:</b> <?php echo htmlspecialchars($contact["created_at"]); ?><br/>
  <b>Updated At:</b> <?php echo htmlspecialchars($contact["updated_at"]); ?><br/>
</p>

<div style="margin-bottom:12px;">
  <button id="assignToMeBtn" data-contact-id="<?php echo $contactId; ?>">Assign to me</button>
  <button id="switchTypeBtn" data-contact-id="<?php echo $contactId; ?>"><?php echo $typeLabel; ?></button>
</div>

<h3>Notes</h3>
<div style="background:white; padding:12px; border:1px solid #e5e7eb; border-radius:8px;">
  <?php foreach ($notes as $n): ?>
    <div style="margin-bottom:12px;">
      <b><?php echo htmlspecialchars($n["firstname"]." ".$n["lastname"]); ?></b><br/>
      <div><?php echo nl2br(htmlspecialchars($n["comment"])); ?></div>
      <small><?php echo htmlspecialchars($n["created_at"]); ?></small>
    </div>
    <hr/>
  <?php endforeach; ?>

  <h4>Add a note</h4>
  <form id="noteForm">
    <input type="hidden" name="contact_id" value="<?php echo $contactId; ?>" />
    <textarea name="comment" placeholder="Enter details here" required></textarea>
    <button type="submit">Add Note</button>
  </form>
</div>
