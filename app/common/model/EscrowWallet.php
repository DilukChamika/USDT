<?php

namespace app\common\model;



class EscrowWallet {
    public static function getByUserId($userId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM escrow_wallets WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function resetBalance($userId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE escrow_wallets SET balance = 0 WHERE user_id = ?");
        $stmt->execute([$userId]);
    }
}