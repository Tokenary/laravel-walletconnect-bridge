<?php
declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Test session approved response/request data
    |--------------------------------------------------------------------------
    */
    'session_data_approved' => '{"data":{"accounts":["0xf6a13bb32586e2afaf93c017bc4b715ce9ec4d2c"],"approved":true}}',
    /*
    |--------------------------------------------------------------------------
    | Test call data for transaction request
    |--------------------------------------------------------------------------
    */
    'call_data_new' => '{"data":{"id":1543442851744241,"jsonrpc":"2.0","params":[{"from":"0xF6A13BB32586e2aFAF
    93c017bc4b715ce9eC4d2C","to":"0x0aD9Fb61a07BAC25625382B63693644497f1B204","nonce":"0x6","gasPrice":"0xee6b2800",
    "gasLimit":"0x5208", "gas":"0x5208","value":"0x174876e800","data":"0x"}],"method":"eth_sendTransaction"}}',
    /*
    |--------------------------------------------------------------------------
    | Test call success status data
    |--------------------------------------------------------------------------
    */
    'call_status_success' => '{"data":{"result":"0x5bbf941493c7da755408b1a
    c24f4b02d916fef1f12c18839ab441da3f9e8f550","approved":true}}',
    /*
    |--------------------------------------------------------------------------
    | Test symmetric key for encrypt/decrypt
    |--------------------------------------------------------------------------
    */
    'symKey' => 'bcbe70e22b4f4a0e543f338d074c6d0bd5312d6044fb54d79bac99b22f1512ba',
    /*
    |--------------------------------------------------------------------------
    | Test IV for encrypt/decrypt
    |--------------------------------------------------------------------------
    */
    'IV' => '2312062ffd72ed772f4b17860bec630f',
     /*
    |--------------------------------------------------------------------------
    | Test dapp_name
    |--------------------------------------------------------------------------
    */
    'dapp_name' => 'Tokenary test'
];
