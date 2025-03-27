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














require '../vendor/autoload.php';

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
    // echo 'Ethereum Client Version: ' . $version . PHP_EOL;
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

    // Fetch the balance of the account
    $eth->getBalance($fromAddress, function ($err, $balance) use ($fromAddress, $eth) {
        if ($err !== null) {
            echo "Error fetching balance: " . $err->getMessage();
            return;
        }

        // Convert the balance from Wei to Ether
        $balanceInEther = bcdiv($balance->toString(), '1000000000000000000', 18);

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
            
            <!-- Bootstrap & Font Awesome CDN -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
        
            <style>
                body {
                    background-color: #f8f9fa;
                }
                .container {
                    max-width: 500px;
                    margin-top: 50px;
                    background: white;
                    padding: 20px;
                    border-radius: 10px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }
            </style>
        </head>
        <body>
        
        <div class="container">
            <h3 class="text-center"><i class="fab fa-ethereum"></i> Ethereum Transaction</h3>
            <p><strong>Connected Account:</strong> ' . $fromAddress . '</p>
            <p><strong>Available Balance:</strong> ' . $balanceInEther . ' ETH</p>
        
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Recipient Address</label>
                    <input type="text" name="toAddress" class="form-control" required>
                </div>
        
                <div class="mb-3">
                    <label class="form-label">Amount (ETH)</label>
                    <input type="number" name="amount" class="form-control" step="0.01" required>
                </div>
        
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-paper-plane"></i> Send ETH</button>
            </form>
        </div>
        
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        
        </body>
        </html>';
        
        
    });
});
?>