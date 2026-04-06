@extends('layouts.app')

@section('content')
<div class="detail-wrapper">

    {{-- エラーメッセージ表示 --}}
    @if (session('error'))
    <div class="flash-message flash-message--error">
        {{ session('error') }}
    </div>
    @endif

    {{-- 成功メッセージ表示 --}}
    @if (session('message'))
    <div class="flash-message flash-message--success">
        {{ session('message') }}
    </div>
    @endif

    {{-- 商品詳細の上段：左に画像、右に商品情報を表示 --}}
    <div class="detail-container">

        {{-- 商品画像エリア --}}
        <div class="detail-image">
            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}">
        </div>

        {{-- 商品情報エリア --}}
        <div class="detail-content">

            {{-- 追加：購入済みの場合は商品名の上に Sold を表示 --}}
            @if ($item->purchase)
            <div class="detail-sold-wrap">
                <span class="item-detail__sold">Sold</span>
            </div>
            @endif

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
                    <span>{{ $item->comments->count() }}</span>
                </div>
            </div>

            {{-- 購入ボタン
                 後で購入画面へのリンクに変更予定 --}}
            <a href="{{ url('/purchase/' . $item->id) }}" class="detail-purchase-link">
                <button type="button" class="detail-purchase-btn">購入手続きへ</button>
            </a>

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

            {{-- コメントエリア --}}
            <div class="detail-section">
                <h2 class="detail-section-title">コメント({{ $item->comments->count() }})</h2>

                {{-- コメント一覧 --}}
                <div class="detail-comment-list">
                    @foreach ($item->comments as $comment)
                    <div class="detail-comment-item">
                        <p class="detail-comment-user">{{ $comment->user->name }}</p>
                        <p class="detail-comment-body">{{ $comment->content }}</p>
                    </div>
                    @endforeach
                </div>

                {{-- コメント入力フォーム --}}
                <div class="detail-comment-form">
                    <form action="{{ url('/item/' . $item->id . '/comment') }}" method="POST" novalidate>
                        @csrf

                        <label class="detail-comment-label" for="content">商品へのコメント</label>
                        <textarea id="content" name="content" class="detail-comment-textarea">{{ old('content') }}</textarea>

                        @error('content')
                        <p class="error">{{ $message }}</p>
                        @enderror

                        <button type="submit" class="detail-comment-btn">コメントを送信する</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection