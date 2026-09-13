<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\KhqrService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KhqrController extends Controller
{
    public function __construct(
        protected KhqrService $khqrService,
    ) {}

    /**
     * Generate KHQR code for an order.
     */
    public function generate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::findOrFail($validated['order_id']);

        $qrData = $this->khqrService->generateQr(
            amount: $order->total_amount,
            orderId: $order->order_number,
        );

        // Update order with KHQR data
        $order->update([
            'payment_method' => 'khqr',
            'khqr_md5' => $qrData['md5'],
            'khqr_expires_at' => $qrData['expires_at'],
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'qr' => $qrData['qr'],
                'qr_image' => $qrData['qr_image'],
                'md5' => $qrData['md5'],
                'expires_at' => $qrData['expires_at'],
                'amount' => $qrData['amount'],
                'currency' => $qrData['currency'],
                'order_number' => $order->order_number,
            ],
        ]);
    }

    /**
     * Check KHQR payment status.
     */
    public function checkStatus(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'md5' => 'required|string',
        ]);

        $result = $this->khqrService->checkPaymentStatus($validated['md5']);

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }

    /**
     * Generate KHQR for a new order (before order is created).
     * Used for pre-payment flow.
     */
    public function generateForCheckout(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'order_number' => 'required|string',
        ]);

        $qrData = $this->khqrService->generateQr(
            amount: $validated['amount'],
            orderId: $validated['order_number'],
        );

        return response()->json([
            'success' => true,
            'data' => [
                'qr' => $qrData['qr'],
                'qr_image' => $qrData['qr_image'],
                'md5' => $qrData['md5'],
                'expires_at' => $qrData['expires_at'],
                'amount' => $qrData['amount'],
                'currency' => $qrData['currency'],
            ],
        ]);
    }
}
