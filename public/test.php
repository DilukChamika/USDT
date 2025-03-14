<?php
$url = 'http://127.0.0.1:7545';
$data = json_encode(['jsonrpc' => '2.0', 'method' => 'eth_blockNumber', 'params' => [], 'id' => 1]);
$options = ['http' => ['method' => 'POST', 'header' => 'Content-Type: application/json', 'content' => $data]];
$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);
echo $result;