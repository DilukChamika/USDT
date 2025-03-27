<?php

require_once 'C:\xampp\htdocs\USDT\app\common\init.php';
require_once 'C:\xampp\htdocs\USDT\app\common\model\EscrowWallet.php';

$db = EscrowWallet::getConnection();

$wallets = $db->query("SELECT * FROM escrow_wallets WHERE balance > 0")->fetchAll(PDO::FETCH_ASSOC);

foreach ($wallets as $escrow) {
    $userId = $escrow['user_id'];
    $amount = $escrow['balance'];

    // credit to actual wallet
    $stmt = $db->prepare("UPDATE user_wallets SET balance = balance + ? WHERE user_id = ?");
    $stmt->execute([$amount, $userId]);

    // reset escrow balance
    EscrowWallet::resetBalance($userId);

    echo "Processed payout for user #$userId, amount: $amount\n";
}
