<?php

namespace app\merchant\controller\order;

use app\common\controller\MerchantController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;
use EasyAdmin\tool\CommonTool;
use jianyan\excel\Excel;
use think\facade\Db;
use think\facade\Cache;
use think\Controller;

class TransactionController extends MerchantController
{
    public function getAccount()
    {
        // Hardcoded test response (replace with real Web3 logic later)
        $response = [
            'fromAddress' => '0x1234567890abcdef1234567890abcdef12345678',
            'balance' => '10.5'
        ];
        
        return json($response);
    }

    public function send()
    {
        $toAddress = $this->request->post('toAddress');
        $amount = $this->request->post('amount');

        if (!$toAddress || !$amount) {
            return json(['error' => 'Missing toAddress or amount']);
        }

        // Hardcoded test response (replace with real Web3 logic later)
        $response = [
            'txHash' => '0x' . bin2hex(random_bytes(32)) // Random 64-character hex string
        ];

        return json($response);
    }
}