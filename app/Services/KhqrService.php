<?php

namespace App\Services;

use App\Models\Setting;
use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Common\Version;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use KHQR\BakongKHQR;
use KHQR\Helpers\KHQRData;
use KHQR\Models\IndividualInfo;
use KHQR\Models\SourceInfo;

class KhqrService
{
    protected string $accountId;

    protected string $merchantName;

    protected string $merchantCity;

    protected string $accessToken;

    protected int $currency;

    protected int $qrExpirationMinutes;

    protected bool $testMode;

    public function __construct()
    {
        $this->accountId = (string) Setting::get('bakong_account_id', config('bakong.account_id', ''));
        $this->merchantName = (string) Setting::get('bakong_merchant_name', config('bakong.merchant_name', 'Bong Heng Cafe'));
        $this->merchantCity = (string) Setting::get('bakong_merchant_city', config('bakong.merchant_city', 'Phnom Penh'));
        $this->accessToken = config('bakong.access_token', '');
        $this->currency = config('bakong.currency', 'USD') === 'KHR' ? KHQRData::CURRENCY_KHR : KHQRData::CURRENCY_USD;
        $this->qrExpirationMinutes = (int) config('bakong.qr_expiration_minutes', 5);
        $this->testMode = (bool) config('bakong.test_mode', false);
    }

    /**
     * Generate KHQR code for a payment.
     *
     * @return array{qr: string, qr_image: string, md5: string, expires_at: string, amount: float, currency: string}
     */
    public function generateQr(float $amount, string $orderId): array
    {
        $expirationTimestamp = strval(floor((now()->addMinutes($this->qrExpirationMinutes))->timestamp * 1000));

        $individualInfo = new IndividualInfo(
            bakongAccountID: $this->accountId,
            merchantName: $this->merchantName,
            merchantCity: $this->merchantCity,
            currency: $this->currency,
            amount: $amount,
            billNumber: $orderId,
            storeLabel: $this->merchantName,
            terminalLabel: 'POS-01',
            purposeOfTransaction: 'Payment',
            languagePreference: 'KH',
            merchantNameAlternateLanguage: $this->merchantName,
            merchantCityAlternateLanguage: $this->merchantCity,
            expirationTimestamp: $expirationTimestamp,
        );

        $response = BakongKHQR::generateIndividual($individualInfo);

        $qr = $response->data['qr'] ?? '';
        $md5 = $response->data['md5'] ?? '';

        // Generate QR code image as base64
        $qrImage = $this->generateQrImage($qr);

        return [
            'qr' => $qr,
            'qr_image' => $qrImage,
            'md5' => $md5,
            'expires_at' => now()->addMinutes($this->qrExpirationMinutes)->toIso8601String(),
            'amount' => $amount,
            'currency' => $this->currency === KHQRData::CURRENCY_KHR ? 'KHR' : 'USD',
        ];
    }

    /**
     * Generate QR code image as base64 data URI.
     */
    protected function generateQrImage(string $data): string
    {
        $options = new QROptions([
            'version' => Version::AUTO,
            'eccLevel' => EccLevel::L,
            'outputBase64' => true,
            'addQuietzone' => true,
            'quietzoneSize' => 2,
        ]);

        $qrcode = new QRCode($options);

        return $qrcode->render($data);
    }

    /**
     * Check payment status by MD5 hash.
     *
     * @return array{paid: bool, data: array|null, error: string|null}
     */
    public function checkPaymentStatus(string $md5): array
    {
        if (empty($this->accessToken)) {
            return [
                'paid' => false,
                'data' => null,
                'error' => 'Bakong access token not configured',
            ];
        }

        try {
            $bakong = new BakongKHQR($this->accessToken);
            $result = $bakong->checkTransactionByMD5($md5, $this->testMode);

            if (isset($result['responseCode']) && $result['responseCode'] === 0) {
                return [
                    'paid' => true,
                    'data' => $result['data'] ?? null,
                    'error' => null,
                ];
            }

            return [
                'paid' => false,
                'data' => $result['data'] ?? null,
                'error' => $result['responseMessage'] ?? 'Payment not found',
            ];
        } catch (\Exception $e) {
            return [
                'paid' => false,
                'data' => null,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Generate deep link for mobile payment.
     */
    public function generateDeepLink(string $qr): ?string
    {
        try {
            $sourceInfo = new SourceInfo;
            $sourceInfo->appIconUrl = url('/images/logo.svg');
            $sourceInfo->appName = $this->merchantName;
            $sourceInfo->appDeepLinkCallback = url('/payment/callback');

            $response = BakongKHQR::generateDeepLink($qr, $sourceInfo, $this->testMode);

            return $response->data['shortLink'] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Verify Bakong account exists.
     */
    public function verifyAccount(string $accountId): bool
    {
        try {
            $response = BakongKHQR::checkBakongAccount($accountId, $this->testMode);

            return isset($response->data['exist']) && $response->data['exist'] === true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
