<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Longswipe\Payment\LongswipeClient;
use Longswipe\Payment\Exceptions\LongswipeException;

// Initialize the client with sandbox mode
$client = new LongswipeClient('your-api-key-here', true);


// Example parameters
$params = [
    'voucherCode' => 'VOUCHER123',
    'amount' => 1000,
    'toCurrencyAbbreviation' => 'USD', // USD, EUR, NGN, GBP, USDC, USDT
    'lockPin' => '1234', // Optional
    'walletAddress' => '0x123...' // Optional
];

try {
    // Fetch voucher details
    $voucherDetails = $client->fetchVoucherDetails($params);
    
    // If details are okay, process the payment
    $paymentResult = $client->processVoucherPayment($params);
    
} catch (LongswipeException $e) {
    echo "Error: " . $e->getMessage();
    echo "Code: " . $e->getCode();
    var_dump($e->getErrorData());
}

