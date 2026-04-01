@extends('layouts.app')

@section('content')
<div class="items-wrapper">

    <div class="items-tabs">
        {{-- おすすめタブ --}}
        <a
            href="{{ url('/?tab=recommend&keyword=' . request('keyword')) }}"
            class="tab {{ request('tab', 'recommend') === 'recommend' ? 'active' : '' }}">
            おすすめ
        </a>

        {{-- マイリストタブ --}}
        <a
            href="{{ url('/') . '?tab=mylist&keyword=' . request('keyword') }}"
            class="tab {{ request('tab') === 'mylist' ? 'active' : '' }}">
            マイリスト
        </a>
    </div>

    <div class="items-container">
        @forelse ($items as $item)
        <a href="{{ url('/item/' . $item->id) }}" class="item-link">
            <div class="item-card">
                <div class="item-image">
                    {{-- 購入済み商品の場合は Sold ラベルを表示 --}}
                    @if ($item->purchase)
                    <span class="sold-label">Sold</span>
                    @endif

                    <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}">
                </div>

                <p class="item-name">{{ $item->name }}</p>
            </div>
        </a>
        @empty
        @if ($tab === 'mylist' && auth()->check())
        <p class="items-empty">マイリストに商品がありません</p>
        @elseif ($tab === 'recommend')
        <p class="items-empty">該当する商品がありません</p>
        @endif
        @endforelse
    </div>

</div>
@endsection