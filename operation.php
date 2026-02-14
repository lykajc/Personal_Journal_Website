<?php

require_once 'connect_db.php';

header('Content-Type: application/json');

$action = $_REQUEST['action'] ?? '';
$pdo = getConnection();

function jsonOk(mixed $data = null): void {
    echo json_encode(['success' => true, 'data' => $data]);
    exit;
}

function jsonErr(string $msg, int $code = 400): void {
    http_response_code($code);
    echo json_encode(['success' => false, 'message' => $msg]);
    exit;
}
function sanitize(string $value): string {
    return trim(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
}

switch ($action) {

case 'list':
    $stmt = $pdo->query(
        'SELECT id, title, content, entry_date, updated_at
             FROM journal
             ORDER BY entry_date DESC, id DESC'        
    );
    jsonOk($stmt->fetchAll());

case 'create':
    $title = trim($_POST['title']?? '');
    $content = trim($_POST['content']?? '');
    $entry_date = trim($_POST['entry_date']?? '');
    
    if ($title === '') jsonErr('Title is required, please add title for this content.');
    if ($content === '') jsonErr('Content is required, please add content.');
    if ($entry_date === '') jsonErr('Entry date is required, please input date');
    if(!preg_match('/^\d{4}-\d{2}-\d{2}$/', $entry_date)) {
        jsonErr('Invalid date format (YYYY-MM-DD expected).');
    }

    $stmt = $pdo->prepare(
        'INSERT INTO journal (title, content, entry_date)
             VALUES (:title, :content, :entry_date)'
    );

    $stmt->execute([
         ':title'      => $title,
            ':content'    => $content,
            ':entry_date' => $entry_date,
    ]);
    jsonOk(['id'=> (int) $pdo->lastInsertId()]);

case 'get' :
    $id = (int) ($_GET['id']?? 0);
    if ($id <= 0) jsonErr('Invalid ID.');

    $stmt = $pdo->prepare(
        'SELECT id, title, content, entry_date
             FROM journal WHERE id = :id LIMIT 1'
    );
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch();

    if(!$row) jsonErr('Entry not found.', 404);
    jsonOk($row);

case 'update':
    $id = (int) ($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $entry_date = trim($_POST['entry_date'] ?? '');

    if($id <=0) jsonErr('Invalid ID.');
    if ($title === '')  jsonErr('Title is required.');
    if ($content === '') jsonErr('Content is required.');
    if ($entry_date === '') jsonErr('Entry date is required.');
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $entry_date)) {
        jsonErr('Invalid date format.');
    }

    $stmt = $pdo->prepare(
        'UPDATE Journal
        SET title = :title, content = :content, entry_date = :entry_date
        WHERE id = :id'
    );

    $stmt->execute([
        ':title' => $title,
        ':content' => $content,
        ':entry_date' => $entry_date,
        ':id' => $id,
    ]);

    if ($stmt->rowCount() === 0) jsonErr('Entry not found or nothing changed.', 404);
    jsonOk();
    
case 'delete':
    $id = (int) ($_POST['id'] ?? 0);
    if ($id <= 0) jsonErr('Invalid ID.');

    $stmt = $pdo->prepare('DELETE FROM journal WHERE id = :id');
    $stmt->execute([':id' => $id]);

    if ($stmt->rowCount() === 0) jsonErr('Entry not found.' , 404);
    jsonOk();

default:
    jsonErr('Unknown action.', 400);
}

?>