<?php

namespace app\merchant\controller\order;


// require '../../../vendor/autoload.php';
// require_once APP_PATH . '../vendor/autoload.php';

use app\common\controller\MerchantController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;
use EasyAdmin\tool\CommonTool;
use jianyan\excel\Excel;
use think\facade\Db;
use think\facade\Cache;
use Web3\Web3;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\HttpRequestManager;

/**
 * @ControllerAnnotation(title="收款地址")
 */
class Ownaddress extends MerchantController
{
    

    use \app\merchant\traits\Curd;

    /**
     * 允许修改的字段
     * @var array
     */
     /**
     * 允许修改的字段
     * @var array
     */
    protected $allowModifyFields = [
        'status',
    ];

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\common\model\OwnAddress();
        $this->merchantmodel = new \app\common\model\MerchantMerchant();
    }

    /**
     * @NodeAnotation(title="列表")
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            list($page, $limit, $where, $excludes) = $this->buildTableParames();
            $where[] = ['merchant_id', '=', session("merchant.id")];
            $count = $this->model->where($where)->count();
            $list = $this->model->where($where)->page($page, $limit)->order($this->sort)->select()->toArray();
            $data = [
                'code'  => 0,
                'msg'   => '',
                'count' => $count,
                'data'  => $list,
            ];
            return json($data);
        }
        // Ganache connection details
        $rpcUrl = 'http://127.0.0.1:8555';
        $accountAddress = '0xd38d3129D4359F93FcFE5323c40Af90491da7B32';

        try {
            // Initialize Web3 with the simplified HttpProvider
            $web3 = new Web3(new HttpProvider($rpcUrl));
            $eth = $web3->eth;

            // Verify connection (optional, for debugging)
            $web3->clientVersion(function ($err, $version) {
                if ($err !== null) {
                    throw new \Exception('Connection Error: ' . $err->getMessage());
                }
                // Uncomment to debug: echo 'Client Version: ' . $version . PHP_EOL;
            });

            // Fetch balance
            $balanceInWei = null;
            $eth->getBalance($accountAddress, function ($err, $balance) use (&$balanceInWei) {
                if ($err !== null) {
                    $balanceInWei = 'Error: ' . $err->getMessage();
                } else {
                    $balanceInWei = $balance->toString();
                }
            });

            $balanceInEth = $balanceInWei === null || strpos($balanceInWei, 'Error') === 0
                ? 'N/A'
                : bcdiv($balanceInWei, '1000000000000000000', 18);

            // Prepare view data
            $data = [
                'fromAddress' => $accountAddress,
                'balance' => $balanceInEth,
                'txHash' => '',
                'error' => '',
            ];

            // Handle form submission
            if ($this->request->isPost()) {
                $toAddress = $this->request->post('toAddress');
                $amount = $this->request->post('amount');

                if (!$toAddress || !$amount) {
                    $data['error'] = 'Missing toAddress or amount';
                } else {
                    // Convert amount from ETH to Wei (hex format)
                    $amountInWei = '0x' . dechex($amount * 1e18);

                    $tx = [
                        'from' => $accountAddress,
                        'to' => $toAddress,
                        'value' => $amountInWei,
                        'gas' => '0x5208', // 21,000 gas
                        'gasPrice' => '0x3B9ACA00' // 1 Gwei
                    ];

                    $eth->sendTransaction($tx, function ($err, $txHash) use (&$data) {
                        if ($err !== null) {
                            $data['error'] = 'Transaction Error: ' . $err->getMessage();
                        } else {
                            $data['txHash'] = $txHash;
                        }
                    });
                }
            }
        } catch (\Exception $e) {
            $data = [
                'fromAddress' => $accountAddress,
                'balance' => 'N/A',
                'txHash' => '',
                'error' => 'Web3 Error: ' . $e->getMessage(),
            ];
        }


        // Render the view with the data
        return $this->fetch('order/ownaddress/index', $data);
    }

    /**
     * @NodeAnotation(title="添加")
     */
    public function add()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $rule = [
                'address|地址' => 'require',
                'chain_type|地址' => 'require',
            ];
            $this->validate($post, $rule);
            $this->checkData($post);
            if (!is_trc_address($post['address']) && !is_erc_address($post['address'])) {
                $this->error('地址格式错误');
            }

            $merchant = $this->merchantmodel->where("id",session("merchant.id"))->find();

            //判断地址是否已经存在   
            $is_addressexit = $this->model->where("address",$post['address'])->field("id")->find();
            if (!empty($is_addressexit['id'])) {
                $this->error('地址已存在');
            }
            // 生成二维码
            require_once root_path() . "vendor/phpqrcode/phpqrcode.php";
            $qRcode = new \QRcode();
            $dir="phpqrcode/".date('Y-m-d');
            if (!is_dir($dir)) mkdir($dir);
            $post['img'] = '/'.$dir.'/' . time().rand(1111,9999) . '.jpg';
            $imgdata = $post['address'];//网址或者是文本内容
            // 纠错级别：L、M、Q、H
            $level = 'L';
            // 点的大小：1到10,用于手机端4就可以了
            $size = 4;
            // 生成的文件名
            $outfile = root_path() . "public" . $post['img']; //保存二维码的路径 false=不生成文件
            $qRcode->png($imgdata, $outfile, $level, $size);
            $post['merchant_id']=$merchant['id'];
            $post['merchantname']=$merchant['merchantname'];
            $post['create_time']=time();
            $post['update_time']=time();
            try {
                $save = $this->model->allowField(['address','create_time','update_time','merchant_id','merchantname','chain_type','img'])->save($post);
            } catch (\Exception $e) {
                $this->error('保存失败:'.$e->getMessage());
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        return $this->fetch();
    }
    /**
     * @NodeAnotation(title="属性修改")
     */
    public function modify()
    {
        $post = $this->request->post();
        $rule = [
            'id|ID'    => 'require',
            'field|字段' => 'require',
            'value|值'  => 'require',
        ];
        $this->validate($post, $rule);
        $row = $this->model->find($post['id']);
        if (!$row) {
            $this->error('数据不存在');
        }
        if (!in_array($post['field'], $this->allowModifyFields)) {
            $this->error('该字段不允许修改：' . $post['field']);
        }
       
        try {
            $row->save([
                $post['field'] => $post['value'],
            ]);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->success('保存成功');
    }

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