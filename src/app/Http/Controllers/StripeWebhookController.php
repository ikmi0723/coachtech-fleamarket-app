<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Purchase;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use UnexpectedValueException;
use Illuminate\Http\Request;

class StripeWebhookController extends Controller
{
    /**
     * Stripe Webhook を受信する
     */
    public function handle(Request $request)
    {
        // Stripe から送られた生のリクエスト本文
        $payload = $request->getContent();

        // Stripe-Signature ヘッダー
        $signature = $request->header('Stripe-Signature');

        // .env に設定した Webhook 用シークレット
        $webhookSecret = config('services.stripe.webhook_secret');

        try {
            // Stripe から送信された正しいイベントか署名検証する
            $event = Webhook::constructEvent(
                $payload,
                $signature,
                $webhookSecret
            );
        } catch (UnexpectedValueException $e) {
            // 不正なペイロード
            return response('Invalid payload', 400);
        } catch (SignatureVerificationException $e) {
            // 署名検証失敗
            return response('Invalid signature', 400);
        }

        // 即時決済の完了イベント
        if ($event->type === 'checkout.session.completed') {
            $this->savePurchaseFromCheckoutSession($event->data->object);
        }

        // コンビニ支払いなど遅延型決済の完了イベント
        if ($event->type === 'checkout.session.async_payment_succeeded') {
            $this->savePurchaseFromCheckoutSession($event->data->object);
        }

        return response('Webhook handled', 200);
    }

    /**
     * Checkout Session の metadata をもとに Purchase を保存する
     */
    private function savePurchaseFromCheckoutSession($session)
    {

        $metadata = $session->metadata;

        $itemId = $metadata->item_id ?? null;
        $userId = $metadata->user_id ?? null;

        if (!$itemId || !$userId) {
            return;
        }

        // 同じ商品がすでに購入済みなら二重登録しない
        $alreadyPurchased = Purchase::where('item_id', $itemId)->exists();

        if ($alreadyPurchased) {
            return;
        }

        $item = Item::find($itemId);

        if (!$item) {
            return;
        }

        Purchase::create([
            'user_id' => $userId,
            'item_id' => $itemId,
            'payment_method' => $metadata->payment_method ?? null,
            'postcode' => $metadata->postcode ?? null,
            'address' => $metadata->address ?? null,
            'building' => $metadata->building ?? null,
        ]);
    }
}
