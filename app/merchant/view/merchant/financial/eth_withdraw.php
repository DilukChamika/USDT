<?php
require '../../../../../vendor/autoload.php';

use Web3\Web3;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\HttpRequestManager;
use phpseclib\Math\BigInteger;

header('Content-Type: application/json');

$web3 = new Web3(new HttpProvider('http://127.0.0.1:8555'));
$eth = $web3->eth;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Fetch Ethereum accounts
    $eth->accounts(function ($err, $accounts) use ($eth) {
        if ($err !== null) {
            echo json_encode(["error" => "Error fetching accounts: " . $err->getMessage()]);
            return;
        }

        if (empty($accounts)) {
            echo json_encode(["error" => "No accounts found."]);
            return;
        }

        $fromAddress = $accounts[0];

        // Fetch balance
        $eth->getBalance($fromAddress, function ($err, $balance) use ($fromAddress) {
            if ($err !== null) {
                echo json_encode(["error" => "Error fetching balance: " . $err->getMessage()]);
                return;
            }

            $balanceInEther = bcdiv($balance->toString(), '1000000000000000000', 18);
            echo json_encode([
                "fromAddress" => $fromAddress,
                "balanceInEther" => $balanceInEther
            ]);
        });
    });
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['toAddress']) || !isset($_POST['amount'])) {
        echo json_encode(["error" => "Missing recipient address or amount."]);
        return;
    }

    $toAddress = $_POST['toAddress'];
    $amountInEther = $_POST['amount'];
    $amountInWei = '0x' . dechex($amountInEther * 1e18);

    // Fetch the sender's account
    $eth->accounts(function ($err, $accounts) use ($eth, $toAddress, $amountInWei) {
        if ($err !== null) {
            echo json_encode(["error" => "Error fetching accounts: " . $err->getMessage()]);
            return;
        }

        if (empty($accounts)) {
            echo json_encode(["error" => "No accounts found."]);
            return;
        }

        $fromAddress = $accounts[0];
        
        $tx = [
            'from' => $fromAddress,
            'to' => $toAddress,
            'value' => $amountInWei,
            'gas' => '0x5208', // Gas limit (21,000 in hex)
            'gasPrice' => '0x3B9ACA00' // Gas price (1 Gwei in hex)
        ];

        $eth->sendTransaction($tx, function ($err, $txHash) {
            if ($err !== null) {
                echo json_encode(["error" => "Transaction Error: " . $err->getMessage()]);
                return;
            }
            echo json_encode(["success" => "Transaction successful! Hash: " . $txHash]);
        });
    });
}
