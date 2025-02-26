<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Longswipe\Payment\LongswipeClient;
use Longswipe\Payment\Exceptions\LongswipeException;

// Initialize the client with sandbox mode
$client = new LongswipeClient('your-api-key-here', true);



try {
     // 1. Create a new customer
     $customerParams = [
        'email' => 'john.doe@example.com',
        'name' => 'John Doe'
    ];
    $newCustomer = $client->createCustomer($customerParams);
    echo "Customer created successfully\n";

    // 2. Create an invoice
    $invoiceParams = [
        'blockchainNetworkId' => 'network-123',
        'currencyId' => 'USD',
        'dueDate' => '2025-03-26',
        'invoiceDate' => '2025-02-26',
        'invoiceItems' => [
            [
                'description' => 'Service payment',
                'quantity' => 1,
                'unitPrice' => 100.00
            ]
        ],
        'merchantUserId' => 'merchant-123'
    ];
    $newInvoice = $client->createInvoice($invoiceParams);
    echo "Invoice created successfully\n";

    // 3. Update customer
    $updateParams = [
        'id' => 'customer-123',
        'name' => 'John Updated Doe',
        'email' => 'john.updated@example.com'
    ];
    $updatedCustomer = $client->updateCustomer($updateParams);
    echo "Customer updated successfully\n";

    // 4. Fetch customers
    $customers = $client->fetchCustomers([
        'page' => 1,
        'limit' => 20,
        'search' => 'john'
    ]);
    echo "Customers fetched successfully\n";

    // 5. Fetch customer by email
    $customerByEmail = $client->fetchCustomerByEmail([
        'email' => 'john.doe@example.com'
    ]);
    echo "Customer fetched by email successfully\n";

    // 6. Delete customer
    $deleteResult = $client->deleteCustomer('customer-123');
    echo "Customer deleted successfully\n";
    
    // Example parameters to fetch voucher details and process payment
    $params = [
        'voucherCode' => 'VOUCHER123',
        'amount' => 1000,
        'toCurrencyAbbreviation' => 'USD', // USD, EUR, NGN, GBP, USDC, USDT
        'lockPin' => '1234', // Optional
        'walletAddress' => '0x123...' // Optional
    ];
    // Fetch voucher details
    $voucherDetails = $client->fetchVoucherDetails($params);
    
    // If details are okay, process the payment
    $paymentResult = $client->processVoucherPayment($params);
    
} catch (LongswipeException $e) {
    echo "Error: " . $e->getMessage();
    echo "Code: " . $e->getCode();
    var_dump($e->getErrorData());
}

