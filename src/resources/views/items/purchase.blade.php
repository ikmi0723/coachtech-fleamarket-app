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

            <form action="{{ url('/purchase/' . $item->id) }}" method="POST" novalidate>
                @csrf

                <div class="purchase-section">
                    <label class="purchase-label">支払い方法</label>
                    <select name="payment_method" class="purchase-select">
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
                    <span>{{ old('payment_method') ? old('payment_method') : '未選択' }}</span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection