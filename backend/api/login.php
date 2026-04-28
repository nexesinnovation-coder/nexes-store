<?php
require __DIR__ . '/../config/db.php';
$input = json_decode(file_get_contents('php://input'), true) ?? [];
if (($input['email'] ?? '') === 'admin@nexes.pt' && ($input['password'] ?? '') === 'admin123') {
  json_response(['ok'=>true,'user'=>['name'=>'NEXES Admin','role'=>'admin'],'token'=>'demo-admin-token']);
}
json_response(['ok'=>false,'error'=>'Invalid login'],401);
