<?php
function db() {
  $c = require __DIR__ . '/database.php';
  $dsn = "mysql:host={$c['host']};dbname={$c['database']};charset={$c['charset']}";
  return new PDO($dsn, $c['user'], $c['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
}
function json_response($data, $code = 200) {
  http_response_code($code);
  header('Content-Type: application/json');
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Headers: Content-Type, Authorization');
  header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
  echo json_encode($data);
  exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') json_response(['ok' => true]);
