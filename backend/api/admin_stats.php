<?php
require __DIR__ . '/../config/db.php';
try {
  $pdo = db();
  if ($_SERVER['REQUEST_METHOD'] !== 'GET') json_response(['error' => 'Method not allowed'], 405);
  $products = (int) $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
  $orders = (int) $pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn();
  $revenue = (float) $pdo->query('SELECT COALESCE(SUM(total),0) FROM orders')->fetchColumn();
  $stock = (int) $pdo->query('SELECT COALESCE(SUM(stock),0) FROM products')->fetchColumn();
  json_response(['stats' => ['products' => $products, 'orders' => $orders, 'revenue' => $revenue, 'stock' => $stock]]);
} catch (Throwable $e) { json_response(['error' => $e->getMessage()], 500); }
