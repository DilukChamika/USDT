<?php
$redis = new Redis();
try {
    $redis->connect('127.0.0.1', 6379);
    $response = $redis->ping();
    if ($response === true) {
        echo "Redis connection successful (returned TRUE)";
    } elseif ($response === 'PONG') {
        echo "Redis connection successful (returned PONG)";
    } else {
        echo "Unexpected response: " . $response;
    }
} catch (RedisException $e) {
    echo "Redis connection failed: " . $e->getMessage();
}