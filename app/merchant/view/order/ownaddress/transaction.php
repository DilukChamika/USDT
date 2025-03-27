<?php

require '../../../../../vendor/autoload.php';

use Web3\Web3;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\HttpRequestManager;
use phpseclib\Math\BigInteger;

header('Content-Type: application/json');

// Initialize Web3
$web3 = new Web3(new HttpProvider('http://127.0.0.1:8555'));

if (!isset($web3)) {
    echo json_encode(['error' => 'Web3 is not initialized']);
    exit;
}

$eth = $web3->eth;

// Function to fetch account and balance
function getAccountAndBalance($eth) {
    $eth->accounts(function ($err, $accounts) use ($eth, &$result) {
        if ($err !== null) {
            $result = ['error' => $err->getMessage()];
            return;
        }

        if (empty($accounts)) {
            $result = ['error' => 'No accounts found'];
            return;
        }

        $fromAddress = $accounts[0];
        $eth->getBalance($fromAddress, function ($err, $balance) use ($fromAddress, &$result) {
            if ($err !== null) {
                $result = ['error' => $err->getMessage()];
                return;
            }

            $balanceInEther = bcdiv($balance->toString(), '1000000000000000000', 18);
            $result = ['fromAddress' => $fromAddress, 'balance' => $balanceInEther];
        });
    });

    // Wait for async operations to complete (simplified for this example)
    sleep(1); // Note: In production, use proper async handling
    return $result;
}

// Handle GET request (load account and balance)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = getAccountAndBalance($eth);
    echo json_encode($result);
    exit;
}

// Handle POST request (send transaction)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toAddress']) && isset($_POST['amount'])) {
    $result = getAccountAndBalance($eth);
    if (isset($result['error'])) {
        echo json_encode(['error' => $result['error']]);
        exit;
    }

    $fromAddress = $result['fromAddress'];
    $toAddress = $_POST['toAddress'];
    $amount = '0x' . dechex($_POST['amount'] * 1e18); // Convert ETH to Wei in hex

    $tx = [
        'from' => $fromAddress,
        'to' => $toAddress,
        'value' => $amount,
        'gas' => '0x5208', // Gas limit (21,000 in hex)
        'gasPrice' => '0x3B9ACA00' // Gas price (1 Gwei in hex)
    ];

    $eth->sendTransaction($tx, function ($err, $txHash) use (&$response) {
        if ($err !== null) {
            $response = ['error' => $err->getMessage()];
            return;
        }
        $response = ['txHash' => $txHash];
    });

    // Wait for async operation (simplified)
    sleep(1); // Note: In production, use proper async handling
    echo json_encode($response);
    exit;
}

echo json_encode(['error' => 'Invalid request']);
?>