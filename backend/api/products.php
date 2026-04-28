<?php
require __DIR__ . '/../config/db.php';

function input_json() {
  return json_decode(file_get_contents('php://input'), true) ?? [];
}

try {
  $pdo = db();
  $method = $_SERVER['REQUEST_METHOD'];

  if ($method === 'GET') {
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    if ($id > 0) {
      $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
      $stmt->execute([$id]);
      $product = $stmt->fetch();
      if (!$product) json_response(['error' => 'Product not found'], 404);
      json_response(['product' => $product]);
    }
    $stmt = $pdo->query('SELECT * FROM products ORDER BY id DESC');
    json_response(['products' => $stmt->fetchAll()]);
  }

  if ($method === 'POST') {
    $input = input_json();
    $stmt = $pdo->prepare('INSERT INTO products (brand,name,category,price,stock,image,badge) VALUES (?,?,?,?,?,?,?)');
    $stmt->execute([
      trim($input['brand'] ?? ''),
      trim($input['name'] ?? ''),
      trim($input['category'] ?? ''),
      (float) ($input['price'] ?? 0),
      (int) ($input['stock'] ?? 0),
      trim($input['image'] ?? ''),
      trim($input['badge'] ?? 'NEW')
    ]);
    json_response(['ok' => true, 'id' => (int) $pdo->lastInsertId()], 201);
  }

  if ($method === 'PUT') {
    $input = input_json();
    $id = (int) ($input['id'] ?? 0);
    if ($id <= 0) json_response(['error' => 'Missing product id'], 422);
    $stmt = $pdo->prepare('UPDATE products SET brand=?, name=?, category=?, price=?, stock=?, image=?, badge=? WHERE id=?');
    $stmt->execute([
      trim($input['brand'] ?? ''),
      trim($input['name'] ?? ''),
      trim($input['category'] ?? ''),
      (float) ($input['price'] ?? 0),
      (int) ($input['stock'] ?? 0),
      trim($input['image'] ?? ''),
      trim($input['badge'] ?? ''),
      $id
    ]);
    json_response(['ok' => true]);
  }

  if ($method === 'DELETE') {
    $input = input_json();
    $id = (int) ($_GET['id'] ?? $input['id'] ?? 0);
    if ($id <= 0) json_response(['error' => 'Missing product id'], 422);
    $stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
    $stmt->execute([$id]);
    json_response(['ok' => true]);
  }

  json_response(['error' => 'Method not allowed'], 405);
} catch (Throwable $e) {
  json_response(['error' => $e->getMessage()], 500);
}
