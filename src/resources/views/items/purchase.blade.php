@extends('layouts.app')

@section('content')
<div class="purchase-wrapper">
    <div class="purchase-container">

        {{-- 左側：商品情報 --}}
        <div class="purchase-left">
            <div class="purchase-item">
                <div class="purchase-item-image">
                    <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}">
                </div>

                <div class="purchase-item-info">
                    <h1 class="purchase-item-name">{{ $item->name }}</h1>
                    <p class="purchase-item-price">¥{{ number_format($item->price) }}</p>
                </div>
            </div>

            <form action="{{ url('/purchase/' . $item->id . '/checkout') }}" method="POST" novalidate>
                @csrf

                {{-- 支払い方法選択 --}}
                <div class="purchase-section">
                    <label class="purchase-label">支払い方法</label>
                    <select name="payment_method" id="payment_method" class="purchase-select">
                        <option value="">選択してください</option>
                        <option value="convenience" {{ old('payment_method') === 'convenience' ? 'selected' : '' }}>コンビニ支払い</option>
                        <option value="card" {{ old('payment_method') === 'card' ? 'selected' : '' }}>カード支払い</option>
                    </select>
                    @error('payment_method')
                    <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="purchase-section">
                    <div class="purchase-address-header">
                        <p class="purchase-label">配送先</p>
                        <a href="{{ url('/purchase/address/' . $item->id) }}" class="purchase-address-link">変更する</a>
                    </div>

                    <p class="purchase-address-text">
                        〒{{ $shippingAddress['postcode'] ?? '未設定' }}
                    </p>
                    <p class="purchase-address-text">
                        {{ $shippingAddress['address'] ?? '住所が登録されていません' }}
                        {{ $shippingAddress['building'] ?? '' }}
                    </p>
                </div>

                <button type="submit" class="purchase-submit-button">購入する</button>
            </form>
        </div>

        {{-- 右側：購入情報まとめ --}}
        <div class="purchase-right">
            <div class="purchase-summary">
                <div class="purchase-summary-row">
                    <span>商品代金</span>
                    <span>¥{{ number_format($item->price) }}</span>
                </div>

                <div class="purchase-summary-row">
                    <span>支払い方法</span>
                    {{-- JavaScriptで即時反映させる表示欄 --}}
                    <span id="payment_method_text">
                        @if (old('payment_method') === 'convenience')
                        コンビニ支払い
                        @elseif (old('payment_method') === 'card')
                        カード支払い
                        @else
                        未選択
                        @endif
                    </span>
                </div>
            </div>
        </div>

    </div>
</div>
{{-- 支払い方法の即時反映用スクリプト --}}
<script>
    // 支払い方法のselect要素
    const paymentMethodSelect = document.getElementById('payment_method');

    // 右側の支払い方法表示欄
    const paymentMethodText = document.getElementById('payment_method_text');

    // valueを表示用テキストに変換する関数
    function updatePaymentMethodText(value) {
        if (value === 'convenience') {
            paymentMethodText.textContent = 'コンビニ支払い';
        } else if (value === 'card') {
            paymentMethodText.textContent = 'カード支払い';
        } else {
            paymentMethodText.textContent = '未選択';
        }
    }

    // プルダウン変更時に即時反映する
    paymentMethodSelect.addEventListener('change', function() {
        updatePaymentMethodText(this.value);
    });

    // 画面表示時にも現在の選択値を反映する
    updatePaymentMethodText(paymentMethodSelect.value);
</script>
@endsection