# Longswipe Payment PHP Integration

A PHP plugin for integrating Longswipe payment voucher system into your application. This plugin provides simple methods to validate and process voucher payments.

## Requirements

- PHP 7.4 or higher
- curl extension
- json extension

## Installation

Install the package via composer:

```bash
composer require longswipe/longswipe-payment
```

### Quick Start

### Accepted Currency Abbreviation

| Enum |
| :--- | :--------- |
| USD  | US Dollar  |
| EUR  | Euro       |
| NGN  | Naira      |
| GBP  | Pounds     |
| USDC | USD Coin   |
| USDT | USD Tether |

### Expected Params for fetching currency details and process payment

| Params                 |                                  |
| :--------------------- | :------------------------------- |
| voucherCode            | Voucher code                     |
| lockPin                | Voucher lock pin (optional)      |
| amount                 | Amount to redeem                 |
| toCurrencyAbbreviation | Currency to redeem to            |
| walletAddress          | Crypto wallet address (optional) |

```php
// PHP code block
use Longswipe\Payment\LongswipeClient;
use Longswipe\Payment\Exceptions\LongswipeException;

// Initialize the client
$client = new LongswipeClient('your-api-key', true); // true for sandbox, false for production

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
```

### Detailed Usage

- Initialize the Client

```php
// For sandbox environment
$client = new LongswipeClient('your-api-key', true);

// For production environment
$client = new LongswipeClient('your-api-key', false);
```

- Verify voucher (Use Public API Key)

```php
try {
    $params = [
        'voucherCode' => 'VOUCHER123'
    ];

    $voucherDetails = $client->verifyVoucher($params);

    if ($voucherDetails['status'] === 'success') {
        // Process voucher details
        $voucher = $voucherDetails['data'];
    }
} catch (LongswipeException $e) {
    echo "Error: " . $e->getMessage();
    if ($e->getErrorData()) {
        print_r($e->getErrorData());
    }
}
```

- Fetch supported crypto networks (Use Public API Key)

```php
try {
    $networks = $client->fetchSupportedCryptoNetworks();

    if ($networks['status'] === 'success') {
        $networkList = $networks['data'];
    }
} catch (LongswipeException $e) {
    echo "Error: " . $e->getMessage();
    if ($e->getErrorData()) {
        print_r($e->getErrorData());
    }
}
```

- Fetch supported currencies (Use Public API Key)

```php
try {

    $currencies = $client->fetchSupportedCurrencies();

    if ($currencies['status'] === 'success') {
        $currencyList = $currencies['data'];
    }
} catch (LongswipeException $e) {
    echo "Error: " . $e->getMessage();
    if ($e->getErrorData()) {
        print_r($e->getErrorData());
    }
}
```

- Fetch Voucher Details and Charges (Use Public API Key)

```php
try {
    $params = [
        'voucherCode' => 'VOUCHER123',
        'amount' => 1000,
        'toCurrencyAbbreviation' => 'USD', // USD, EUR, NGN, GBP, USDC, USDT
        'lockPin' => '1234', // Optional
        'walletAddress' => '0x123...' // Optional
    ];

    $voucherDetails = $client->fetchVoucherDetails($params);

    if ($voucherDetails['status'] === 'success') {
        // Process voucher details
        $charges = $voucherDetails['data']['charges'];
        $voucher = $voucherDetails['data']['voucher'];
    }
} catch (LongswipeException $e) {
    echo "Error: " . $e->getMessage();
    if ($e->getErrorData()) {
        print_r($e->getErrorData());
    }
}
```

- Process Payment (Use Public API Key)

```php
try {
    $params = [
        'voucherCode' => 'VOUCHER123',
        'amount' => 1000,
        'toCurrencyAbbreviation' => 'USD', // USD, EUR, NGN, GBP, USDC, USDT
        'lockPin' => '1234', // Optional
        'walletAddress' => '0x123...' // Optional
    ];

    $paymentResult = $client->processVoucherPayment($params);

    if ($paymentResult['status'] === 'success') {
        // Payment successful
        echo "Payment processed successfully!";
    }
} catch (LongswipeException $e) {
    echo "Error: " . $e->getMessage();
    if ($e->getErrorData()) {
        print_r($e->getErrorData());
    }
}
```

### Create new customer (Use Secret API Key)

| Params |                |
| :----- | :------------- |
| email  | Customer email |
| name   | Customer name  |

```php
// PHP code block
use Longswipe\Payment\LongswipeClient;
use Longswipe\Payment\Exceptions\LongswipeException;

// Initialize the client
$client = new LongswipeClient('your-api-key', true); // true for sandbox, false for production

// Example parameters
$params = [
    'email' => 'johndoe@email.com',
    'name' => 'John Doe',
];

try {

    $newCustomer = $client->createCustomer($customerParams);

} catch (LongswipeException $e) {
    echo "Error: " . $e->getMessage();
    echo "Code: " . $e->getCode();
    var_dump($e->getErrorData());
}

```

### Create an invoice (Use Secret API Key)

```php
// PHP code block
use Longswipe\Payment\LongswipeClient;
use Longswipe\Payment\Exceptions\LongswipeException;

// Initialize the client
$client = new LongswipeClient('your-api-key', true); // true for sandbox, false for production

// Example parameters
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

try {

    $newInvoice = $client->createInvoice($invoiceParams);

} catch (LongswipeException $e) {
    echo "Error: " . $e->getMessage();
    echo "Code: " . $e->getCode();
    var_dump($e->getErrorData());
}

```

### Update customer (Use Secret API Key)

```php
// PHP code block
use Longswipe\Payment\LongswipeClient;
use Longswipe\Payment\Exceptions\LongswipeException;

// Initialize the client
$client = new LongswipeClient('your-api-key', true); // true for sandbox, false for production

// Example parameters
$updateParams = [
        'id' => 'customer-123',
        'name' => 'John Updated Doe',
        'email' => 'john.updated@example.com'
    ];

try {

    $updatedCustomer = $client->updateCustomer($updateParams);

} catch (LongswipeException $e) {
    echo "Error: " . $e->getMessage();
    echo "Code: " . $e->getCode();
    var_dump($e->getErrorData());
}

```

### Fetch customers (Use Secret API Key)

```php
// PHP code block
use Longswipe\Payment\LongswipeClient;
use Longswipe\Payment\Exceptions\LongswipeException;

// Initialize the client
$client = new LongswipeClient('your-api-key', true); // true for sandbox, false for production

try {

    $customers = $client->fetchCustomers([
        'page' => 1,
        'limit' => 20,
        'search' => 'john'
    ]);

} catch (LongswipeException $e) {
    echo "Error: " . $e->getMessage();
    echo "Code: " . $e->getCode();
    var_dump($e->getErrorData());
}

```

### Fetch customer by email (Use Secret API Key)

```php
// PHP code block
use Longswipe\Payment\LongswipeClient;
use Longswipe\Payment\Exceptions\LongswipeException;

// Initialize the client
$client = new LongswipeClient('your-api-key', true); // true for sandbox, false for production

try {

    $customerByEmail = $client->fetchCustomerByEmail([
        'email' => 'john.doe@example.com'
    ]);

} catch (LongswipeException $e) {
    echo "Error: " . $e->getMessage();
    echo "Code: " . $e->getCode();
    var_dump($e->getErrorData());
}

```

### Delete customer (Use Secret API Key)

```php
// PHP code block
use Longswipe\Payment\LongswipeClient;
use Longswipe\Payment\Exceptions\LongswipeException;

// Initialize the client
$client = new LongswipeClient('your-api-key', true); // true for sandbox, false for production

try {

    $customerByEmail = $client->deleteCustomer('customer-123');

} catch (LongswipeException $e) {
    echo "Error: " . $e->getMessage();
    echo "Code: " . $e->getCode();
    var_dump($e->getErrorData());
}

```

## API Response Models

### Fetch Voucher Details Response

```json
{
  "code": 0,
  "data": {
    "charges": {
      "amount": 0,
      "amountInWei": 0,
      "balanceAfterCharges": 0,
      "balanceAfterChargesInWei": 0,
      "gasLimitInWei": 0,
      "gasPriceInWei": 0,
      "processingFee": 0,
      "processingFeeInWei": 0,
      "totalGasCost": 0,
      "totalGasCostAndProcessingFee": 0,
      "totalGasCostAndProcessingFeeInWei": 0,
      "totalGasCostInWei": 0
    },
    "voucher": {
      "amount": 0,
      "balance": 0,
      "code": "string",
      "createdAt": "string",
      "createdForExistingUser": true,
      "createdForMerchant": true,
      "createdForNonExistingUser": true,
      "cryptoVoucherDetails": {
        "balance": "string",
        "codeHash": "string",
        "creator": "string",
        "isRedeemed": true,
        "transactionHash": "string",
        "value": "string"
      },
      "generatedCurrency": {
        "abbrev": "string",
        "currencyType": "string",
        "id": "string",
        "image": "string",
        "isActive": true,
        "name": "string",
        "symbol": "string"
      }
    }
  },
  "message": "string",
  "status": "string"
}
```

### Fetch customers response

```json
{
  "code": 0,
  "data": {
    "customer": [
      {
        "email": "string",
        "id": "string",
        "merchantID": "string",
        "name": "string"
      }
    ],
    "limit": 0,
    "page": 0,
    "total": 0
  },
  "message": "string",
  "status": "string"
}
```

### Fetch customer response

```json
{
  "code": 0,
  "customer": {
    "email": "string",
    "id": "string",
    "merchantID": "string",
    "name": "string"
  },
  "message": "string",
  "status": "string"
}
```

### Fetch supported crypto network response

```json
{
  "code": 0,
  "data": [
    {
      "blockExplorerUrl": "string",
      "chainID": "string",
      "cryptocurrencies": [
        {
          "currencyAddress": "string",
          "currencyData": {
            "abbrev": "string",
            "currencyType": "string",
            "id": "string",
            "image": "string",
            "isActive": true,
            "name": "string",
            "symbol": "string"
          },
          "currencyDecimals": "string",
          "currencyName": "string",
          "id": "string",
          "longswipeContractAddress": "string",
          "networkID": "string",
          "status": true
        }
      ],
      "id": "string",
      "networkName": "string",
      "networkType": "EVM",
      "rpcUrl": "string"
    }
  ],
  "message": "string",
  "status": "string"
}
```

### Fetch supported currency response

```json
{
  "code": 0,
  "data": {
    "currencies": [
      {
        "abbreviation": "string",
        "createdAt": "string",
        "currency": "string",
        "currencyType": "string",
        "id": "string",
        "image": "string",
        "isActive": true,
        "symbol": "string"
      }
    ]
  },
  "message": "string",
  "status": "string"
}
```

### Other Response

```json
{
  "code": 0,
  "message": "string",
  "status": "string"
}
```

## Error Handling

The plugin uses the `LongswipeException` class for error handling. Always wrap your API calls in try-catch blocks:

```php
try {
    // Your API call here
} catch (LongswipeException $e) {
    echo "Error Code: " . $e->getCode() . "\n";
    echo "Error Message: " . $e->getMessage() . "\n";
    if ($e->getErrorData()) {
        echo "Additional Error Data: ";
        print_r($e->getErrorData());
    }
}
```

## Support

For support, please contact:

- Email: support@longswipe.com
- GitHub Issues: [Create an issue](https://github.com/ndenisj/longswipe-payment/issues)

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.
