<?php

// require 'vendor/autoload.php';

// use Web3\Web3;
// use Web3\Providers\HttpProvider;
// use Web3\RequestManagers\HttpRequestManager;
// use phpseclib\Math\BigInteger;

// $web3 = new Web3(new HttpProvider('http://127.0.0.1:8555'));

// $web3->clientVersion(function ($err, $version) {
//     if ($err !== null) {
//         echo 'Error: ' . $err->getMessage();
//         return;
//     }
//     echo 'Ethereum Client Version: ' . $version . PHP_EOL;
// });

// if (!isset($web3)) {
//     die("Error: Web3 is not initialized.");
// }

// $eth = $web3->eth;
// $eth->accounts(function ($err, $accounts) use ($eth) { // Pass $eth into the callback
//     if ($err !== null) {
//         echo "Error fetching accounts: " . $err->getMessage();
//         return;
//     }

//     if (empty($accounts)) {
//         echo "No accounts found.";
//         return;
//     }

//     echo 'Accounts: ' . implode(', ', $accounts) . PHP_EOL;

//     $fromAddress = $accounts[0];

//     $tx = [
//         'from' => $fromAddress,
//         'to' => '0xEDe54Cc03dAb2c6e9112914c08D7a51a4dc346C1',
//         'value' => '0x' . dechex(1000000000000000000), // 1 ETH in wei (hex)
//         'gas' => '0x5208', // Gas limit (21,000 in hex)
//         'gasPrice' => '0x3B9ACA00' // Gas price (1 Gwei in hex)
//     ];

//     $eth->sendTransaction($tx, function ($err, $txHash) {
//         if ($err !== null) {
//             echo "Transaction Error: " . $err->getMessage();
//             return;
//         }
//         echo "Transaction Hash: " . $txHash . PHP_EOL;
//     });
// });





// require 'vendor/autoload.php';

// use Web3\Web3;
// use Web3\Providers\HttpProvider;
// use Web3\RequestManagers\HttpRequestManager;
// use phpseclib\Math\BigInteger;

// // Load blockchain configuration
// $config = require 'config/blockchain.php';

// // Initialize Web3 with Ganache provider from config
// $web3 = new Web3(new HttpProvider($config['provider']));

// // Test connection to Ganache
// $web3->clientVersion(function ($err, $version) {
//     if ($err !== null) {
//         echo 'Error: ' . $err->getMessage();
//         return;
//     }
//     echo 'Ethereum Client Version: ' . $version . PHP_EOL;
// });

// // Check if Web3 is initialized
// if (!isset($web3)) {
//     die("Error: Web3 is not initialized.");
// }

// // Fetch Ethereum accounts from Ganache
// $eth = $web3->eth;
// $eth->accounts(function ($err, $accounts) use ($eth, $config) {
//     if ($err !== null) {
//         echo "Error fetching accounts: " . $err->getMessage();
//         return;
//     }

//     if (empty($accounts)) {
//         echo "No accounts found.";
//         return;
//     }

//     echo 'Accounts: ' . implode(', ', $accounts) . PHP_EOL;

//     // Use the account from config as the sender
//     $fromAddress = $config['accounts']['from'];

//     // Define transaction parameters
//     $tx = [
//         'from' => $fromAddress,
//         'to' => '0xEDe54Cc03dAb2c6e9112914c08D7a51a4dc346C1',
//         'value' => '0x' . dechex(1000000000000000000), // 1 ETH in wei (hex)
//         'gas' => '0x' . dechex($config['gas_limit']),
//         'gasPrice' => '0x' . dechex($config['gas_price'])
//     ];

//     // Send the transaction using eth_sendTransaction
//     $eth->sendTransaction($tx, function ($err, $txHash) {
//         if ($err !== null) {
//             echo "Transaction Error: " . $err->getMessage();
//             return;
//         }
//         echo "Transaction Hash: " . $txHash . PHP_EOL;
//     });
// });













require 'vendor/autoload.php';

use Web3\Web3;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\HttpRequestManager;
use phpseclib\Math\BigInteger;

$web3 = new Web3(new HttpProvider('http://127.0.0.1:8555'));

$web3->clientVersion(function ($err, $version) {
    if ($err !== null) {
        echo 'Error: ' . $err->getMessage();
        return;
    }
    echo 'Ethereum Client Version: ' . $version . PHP_EOL;
});

if (!isset($web3)) {
    die("Error: Web3 is not initialized.");
}

$eth = $web3->eth;
$eth->accounts(function ($err, $accounts) use ($eth) { // Pass $eth into the callback
    if ($err !== null) {
        echo "Error fetching accounts: " . $err->getMessage();
        return;
    }

    if (empty($accounts)) {
        echo "No accounts found.";
        return;
    }

    $fromAddress = $accounts[0];
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toAddress']) && isset($_POST['amount'])) {
        $toAddress = $_POST['toAddress'];
        $amount = '0x' . dechex($_POST['amount'] * 1e18); // Convert ETH to Wei in hex
        
        $tx = [
            'from' => $fromAddress,
            'to' => $toAddress,
            'value' => $amount,
            'gas' => '0x5208', // Gas limit (21,000 in hex)
            'gasPrice' => '0x3B9ACA00' // Gas price (1 Gwei in hex)
        ];
    
        $eth->sendTransaction($tx, function ($err, $txHash) {
            if ($err !== null) {
                echo "Transaction Error: " . $err->getMessage();
                return;
            }
            echo "Transaction Hash: " . $txHash . PHP_EOL;
        });
    }
    
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ethereum Transaction</title>
    </head>
    <body>
        <h1>Ethereum Transaction</h1>
        <p>Connected Account: ' . $fromAddress . '</p>
        <form method="POST">
            <label>Recipient Address:</label>
            <input type="text" name="toAddress" required><br>
            <label>Amount (ETH):</label>
            <input type="number" name="amount" step="0.01" required><br>
            <button type="submit">Send ETH</button>
        </form>
    </body>
    </html>';
});






?>