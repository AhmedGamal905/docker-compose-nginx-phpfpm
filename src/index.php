<?php
require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__, '.env');
$dotenv->load();

$db = new PDO(
    'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS']
);



$email = rand(0, 90) . 'Mical@gmail.com';
$name = 'Mich bl';
$amount = 25;
$invoiceId = date('Ymmddhm');
try {
    $db->beginTransaction();

    $newUserStmt = $db->prepare('INSERT INTO users (email, name, is_active, created_at)
VALUES (?, ?, true, now())');

    $newInvoiceStmt = $db->prepare('INSERT INTO invoices (amount, user_id, invoice_id)
VALUES(?, ?, ?)');

    $newUserStmt->execute([$email, $name]);

    $userId = (int) $db->lastInsertId();

    $newInvoiceStmt->execute([$amount, $userId, $invoiceId]);

    $db->commit();
} catch (\Throwable $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
        echo ($e);
    }
};
$fetchStmt = $db->prepare(
    'SELECT invoices.id AS invoice_id, amount, user_id, name
FROM invoices
INNER JOIN users ON user_id = users.id
WHERE email = ?'
);

$fetchStmt->execute([$email]);
echo '<pre>';
var_dump($fetchStmt->fetch(PDO::FETCH_ASSOC));
echo '<pre>';