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
    'receivingCurrencyId' => '2eedd32', // Replace with actual receiving currency ID,
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

- 1. Initialize the Client

```php
// For sandbox environment
$client = new LongswipeClient('your-api-key', true);

// For production environment
$client = new LongswipeClient('your-api-key', false);
```

- 2. Fetch Voucher Details

```php
try {
    $params = [
        'voucherCode' => 'VOUCHER123',
        'amount' => 1000,
        'receivingCurrencyId' => '2eedd32', // Replace with actual receiving currency ID,
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

- 3. Process Payment

```php
try {
    $params = [
        'voucherCode' => 'VOUCHER123',
        'amount' => 1000,
        'receivingCurrencyId' => '2eedd32', // Replace with actual receiving currency ID,
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

### Process Payment Response

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
