@extends('layouts.app')

@section('content')
<div class="mypage-wrapper">
    <div class="mypage-container">

        {{-- ユーザー情報エリア --}}
        <div class="mypage-header">
            <div class="mypage-user">
                {{-- プロフィール画像 --}}
                @if (!empty($user->image_path))
                <img src="{{ asset('storage/' . $user->image_path) }}" alt="プロフィール画像" class="mypage-user__image">
                @else
                <div class="mypage-user__image mypage-user__image--empty"></div>
                @endif

                {{-- ユーザー名 --}}
                <h1 class="mypage-user__name">{{ $user->name }}</h1>
            </div>

            {{-- プロフィール編集ボタン --}}
            <a href="{{ url('/mypage/profile') }}" class="mypage-edit-button">プロフィールを編集</a>
        </div>

        {{-- タブエリア --}}
        <div class="mypage-tabs">
            {{-- 修正：出品タブ --}}
            <a href="{{ url('/mypage?tab=sell') }}"
                class="mypage-tab {{ $tab === 'sell' ? 'mypage-tab--active' : '' }}">
                出品した商品
            </a>

            {{-- 修正：購入タブ --}}
            <a href="{{ url('/mypage?tab=buy') }}"
                class="mypage-tab {{ $tab === 'buy' ? 'mypage-tab--active' : '' }}">
                購入した商品
            </a>
        </div>

        {{-- 商品一覧 --}}
        <div class="mypage-items">
            @if ($tab === 'buy')
            {{-- 購入した商品一覧を表示 --}}
            @forelse ($buyItems as $item)
            <div class="mypage-item-card">
                <a href="{{ url('/item/' . $item->id) }}" class="mypage-item-card__link">
                    <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" class="mypage-item-card__image">
                    <p class="mypage-item-card__name">{{ $item->name }}</p>
                </a>
            </div>
            @empty
            <p class="mypage-empty-message">購入した商品はありません。</p>
            @endforelse
            @else
            {{-- 出品した商品一覧を表示 --}}
            @forelse ($sellItems as $item)
            <div class="mypage-item-card">
                <a href="{{ url('/item/' . $item->id) }}" class="mypage-item-card__link">
                    <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" class="mypage-item-card__image">
                    <p class="mypage-item-card__name">{{ $item->name }}</p>
                </a>
            </div>
            @empty
            <p class="mypage-empty-message">出品した商品はありません。</p>
            @endforelse
            @endif
        </div>
    </div>
</div>
@endsection