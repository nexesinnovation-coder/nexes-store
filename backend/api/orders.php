<?php
require __DIR__ . '/../config/db.php';
try {
  $pdo = db();
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true) ?? [];
    $items = $input['items'] ?? [];
    $total = array_reduce($items, fn($s,$i)=>$s + (($i['price'] ?? 0) * ($i['qty'] ?? 1)), 0);
    $stmt = $pdo->prepare('INSERT INTO orders (customer_name, customer_email, total, status) VALUES (?,?,?,?)');
    $stmt->execute([$input['name'] ?? 'Guest', $input['email'] ?? '', $total, 'pending']);
    $orderId = $pdo->lastInsertId();
    $line = $pdo->prepare('INSERT INTO order_items (order_id, product_id, product_name, price, qty) VALUES (?,?,?,?,?)');
    foreach ($items as $item) $line->execute([$orderId, $item['id'], $item['name'], $item['price'], $item['qty'] ?? 1]);
    json_response(['ok'=>true,'order_id'=>$orderId,'total'=>$total], 201);
  }
  if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    json_response(['orders'=>$pdo->query('SELECT * FROM orders ORDER BY id DESC')->fetchAll()]);
  }
  json_response(['error'=>'Method not allowed'],405);
} catch (Throwable $e) { json_response(['error'=>$e->getMessage()],500); }
