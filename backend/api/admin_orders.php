<?php
require __DIR__ . '/../config/db.php';
try {
  $pdo = db();
  if ($_SERVER['REQUEST_METHOD'] !== 'GET') json_response(['error' => 'Method not allowed'], 405);
  $orders = $pdo->query('SELECT * FROM orders ORDER BY id DESC')->fetchAll();
  foreach ($orders as &$order) {
    $stmt = $pdo->prepare('SELECT * FROM order_items WHERE order_id = ?');
    $stmt->execute([$order['id']]);
    $order['items'] = $stmt->fetchAll();
  }
  json_response(['orders' => $orders]);
} catch (Throwable $e) { json_response(['error' => $e->getMessage()], 500); }
