
<?php




require_once 'C:\xampp\htdocs\USDT\app\common\init.php'; // init DB, auth, etc.
require_once '../model/EscrowWallet.php';

$userId = Auth::userId();
$amount = $_POST['amount'];

EscrowWallet::deposit($userId, $amount);

$response = [
    'status' => 'success',
    'message' => 'Deposited to escrow',
];
echo json_encode($response);
