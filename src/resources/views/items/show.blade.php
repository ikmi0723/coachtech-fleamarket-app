@extends('layouts.app')

@section('content')
<div class="detail-wrapper">

    {{-- 商品詳細の上段：左に画像、右に商品情報を表示 --}}
    <div class="detail-container">

        {{-- 商品画像エリア --}}
        <div class="detail-image">
            <img src="{{ asset('images/noimage.png') }}" alt="{{ $item->name }}">
        </div>

        {{-- 商品情報エリア --}}
        <div class="detail-content">

            {{-- 商品名・ブランド名 --}}
            <h1 class="detail-name">{{ $item->name }}</h1>
            <p class="detail-brand">{{ $item->brand }}</p>

            {{-- 価格 --}}
            <p class="detail-price">¥{{ number_format($item->price) }}（税込）</p>

            {{-- いいね数・コメント数表示エリア--}}
            <div class="detail-icons">

                {{-- いいねアイコン --}}
                <div class="detail-icon-box">
                    <form action="{{ url('/item/' . $item->id . '/like') }}" method="POST">
                        @csrf
                        <button type="submit" class="like-button">
                            @if ($isLiked)
                            <img src="{{ asset('images/icon-heart-active.png') }}" alt="いいね済み">
                            @else
                            <img src="{{ asset('images/icon-heart-default.png') }}" alt="いいね">
                            @endif
                        </button>
                    </form>

                    <span>{{ $item->likes->count() }}</span>
                </div>

                {{-- コメントアイコン --}}
                <div class="detail-icon-box">
                    <img src="{{ asset('images/icon-comment.png') }}" alt="コメント">
                    <span>0</span>
                </div>
            </div>

            {{-- 購入ボタン
                 後で購入画面へのリンクに変更予定 --}}
            <button class="detail-purchase-btn">購入手続きへ</button>

            {{-- 商品説明 --}}
            <div class="detail-section">
                <h2 class="detail-section-title">商品説明</h2>
                <p class="detail-description">{{ $item->description }}</p>
            </div>

            {{-- 商品情報 --}}
            <div class="detail-section">
                <h2 class="detail-section-title">商品の情報</h2>

                <div class="detail-info-row">
                    <span class="detail-info-label">カテゴリー</span>

                    <div class="detail-category-list">
                        @forelse ($item->categories as $category)
                        <span class="detail-category-badge">{{ $category->name }}</span>
                        @empty
                        <span class="detail-info-value">未設定</span>
                        @endforelse
                    </div>
                </div>

                <div class="detail-info-row">
                    <span class="detail-info-label">商品の状態</span>
                    <span class="detail-info-badge">{{ $item->condition }}</span>
                </div>
            </div>

            {{-- コメントエリア
                 今はまだコメント未実装なので入力欄だけ骨組みを作る --}}
            <div class="detail-section">
                <h2 class="detail-section-title">コメント(0)</h2>

                <div class="detail-comment-form">
                    <label class="detail-comment-label" for="comment">商品へのコメント</label>
                    <textarea id="comment" class="detail-comment-textarea"></textarea>
                    <button class="detail-comment-btn">コメントを送信する</button>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection