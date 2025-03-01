<?php

namespace Longswipe\Payment;

use Longswipe\Payment\Exceptions\LongswipeException;


class LongswipeClient {
    private string $apiKey;
    private string $baseUrl;

    public function __construct(string $apiKey, bool $isSandbox = false) {
        $this->apiKey = $apiKey;
        $this->baseUrl = $isSandbox 
            ? 'https://sandbox.longswipe.com'
            : 'https://api.longswipe.com';
    }

    /**
     * Create a new customer
     * @param array $params [
     *      'email' => string (required),
     *      'name' => string (required)
     * ]
     */
    public function createCustomer(array $params): array {
        return $this->makeRequest('merchant-integrations-server/add-new-customer', $params);
    }

    /**
     * Create an invoice
     * @param array $params [
     *      'blockchainNetworkId' => string (required),
     *      'currencyId' => string (required),
     *      'dueDate' => string (required),
     *      'invoiceDate' => string (required),
     *      'invoiceItems' => array (required) [
     *          [
     *              'description' => string,
     *              'quantity' => int,
     *              'unitPrice' => float
     *          ]
     *      ],
     *      'merchantUserId' => string (required)
     * ]
     */
    public function createInvoice(array $params): array {
        return $this->makeRequest('merchant-integrations-server/create-invoice', $params);
    }

    /**
     * Update customer details
     * @param array $params [
     *      'id' => string (required),
     *      'email' => string (optional),
     *      'name' => string (optional)
     * ]
     */
    public function updateCustomer(array $params): array {
        return $this->makeRequest('merchant-integrations-server/update-customer', $params, 'PATCH');
    }

    /**
     * Delete a customer
     * @param string $customerId
     */
    public function deleteCustomer(string $customerId): array {
        return $this->makeRequest("merchant-integrations-server/delete-customer/$customerId", [], 'DELETE');
    }

    /**
     * Fetch all customers
     * @param array $params [
     *      'page' => int (optional),
     *      'limit' => int (optional),
     *      'search' => string (optional)
     * ]
     */
    public function fetchCustomers(array $params = []): array {
        return $this->makeRequest('merchant-integrations-server/fetch-customers', $params, 'GET');
    }

    /**
     * Fetch all supported currencies
     * @param array $params []
     * 
     * @return array
     *  - code: int
     *  - data: array
     *    - currencies: array
     *      - abbreviation: string
     *      - currency: string
     *      - currencyType: string
     *      - id: string
     *      - image: string
     *      - isActive: bool
     *      - symbol: string
     *  - message: string
     *  - status: string
     */
    public function fetchSupportedCurrencies(array $params = []): array {
        return $this->makeRequest('merchant-integrations-server/fetch-supported-currencies', $params, 'GET');
    }

    /**
     * Fetch all supported crypto networks
     * @param array $params []
     * 
     * @return array
     *  - code: int
     *  - data: array
     *  - message: string
     *  - status: string
     */
    public function fetchSupportedCryptoNetworks(array $params = []): array {
        return $this->makeRequest('merchant-integrations-server/fetch-supported-cryptonetworks', $params, 'GET');
    }

    /**
     * Fetch customer by email
     * @param array $params [
     *      'email' => string (required)
     * ]
     */
    public function fetchCustomerByEmail(array $params): array {
        return $this->makeRequest('merchant-integrations-server/fetch-customer-by-email', $params, 'GET');
    }

    /**
     * Required parameters for fetchVoucherDetails:
     * @param array $params [
     *      'voucherCode' => string (required) - The code of the voucher to fetch
     *      'amount' => int (required) - Amount to redeem
     *      'toCurrencyAbbreviation' => string (required) - Currency Code to receive
     *      'lockPin' => string (optional) - PIN if voucher is locked
     *      'walletAddress' => string (optional) - Wallet address for redemption
     * ]
     * 
     * Response structure:
     * - code: int
     * - data: array
     *   - charges: array
     *     - exchangeRate: int
     *     - fromCurrency: array (currency details)
     *     - isPercentageCharge: bool
     *     - percentageCharge: int
     *     - processingFee: int
     *     - swapAmount: int
     *     - toAmount: int
     *     - toCurrency: array (currency details)
     *   - voucher: array (voucher details)
     * - message: string
     * - status: string
     * 
     * @throws LongswipeException
     */
    public function fetchVoucherDetails(array $params): array {
        return $this->makeRequest(
            'merchant-integrations/fetch-voucher-redemption-charges',
            $params
        );
    }

    /**
     * Required parameters for fetchVoucherDetails:
     * @param array $params [
     *      'voucherCode' => string (required) - The code of the voucher to fetch
     * ]
     * 
     * Response structure:
     * - code: int
     * - data: object
     *   - voucher: object (voucher details)
     * - message: string
     * - status: string
     * 
     * @throws LongswipeException
     */
    public function verifyVoucher(array $params): array {
        return $this->makeRequest(
            'merchant-integrations/verify-voucher',
            $params
        );
    }


     /**
     * Required parameters for processVoucherPayment:
     * @param array $params [
     *      'voucherCode' => string (required) - The code of the voucher to redeem
     *      'amount' => int (required) - Amount to redeem
     *      'toCurrencyAbbreviation' => string (required) - Currency Code to receive
     *      'lockPin' => string (optional) - PIN if voucher is locked
     *      'walletAddress' => string (optional) - Wallet address for redemption
     * ]
     * 
     * Response structure:
     * - code: int
     * - message: string
     * - status: string
     * 
     * @throws LongswipeException
     */
    public function processVoucherPayment(array $params): array {
        return $this->makeRequest(
            'merchant-integrations/redeem-voucher',
            $params
        );
    }

    private function makeRequest(string $endpoint, array $params, string $method = 'POST'): array {
        $ch = curl_init("{$this->baseUrl}/$endpoint");
        
        $headers = [
            'Authorization: Bearer ' . $this->apiKey,
            'Content-Type: application/json',
            'Accept: application/json'
        ];

        $curlOptions = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers
        ];

        if ($method === 'POST' || $method === 'PATCH') {
            $curlOptions[CURLOPT_CUSTOMREQUEST] = $method;
            $curlOptions[CURLOPT_POSTFIELDS] = json_encode($params);
        } else if ($method === 'DELETE') {
            $curlOptions[CURLOPT_CUSTOMREQUEST] = 'DELETE';
        } else if ($method === 'GET' && !empty($params)) {
            $endpoint .= '?' . http_build_query($params);
            curl_setopt($ch, CURLOPT_URL, "{$this->baseUrl}/$endpoint");
        }

        curl_setopt_array($ch, $curlOptions);

        $response = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($statusCode !== 200) {
            $errorData = json_decode($response, true);
            throw new LongswipeException(
                $errorData['message'] ?? 'Unknown error',
                $statusCode,
                $errorData
            );
        }

        return json_decode($response, true);
    }
}