<?php

namespace app\helpers;

use Web3\Web3;
use Web3\Contract;
use Web3\Providers\HttpProvider;

class Web3Helper
{
    private static $web3;
    private static $contract;

    public static function getWeb3()
    {
        if (!self::$web3) {
            $provider = new HttpProvider(env('WEB3_PROVIDER'));
            self::$web3 = new Web3($provider);
        }
        return self::$web3;
    }

    public static function getContract($abi, $address)
    {
        if (!self::$contract) {
            self::$contract = new Contract(self::getWeb3()->provider, $abi);
            self::$contract->at($address);
        }
        return self::$contract;
    }

    public static function getAccounts()
    {
        return [
            env('ACCOUNT_1'),
            env('ACCOUNT_2'),
            // Add more accounts as needed
        ];
    }
}