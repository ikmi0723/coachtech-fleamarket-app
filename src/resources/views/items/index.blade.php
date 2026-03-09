@extends('layouts.app')

@section('content')
<div class="items-wrapper">

    <div class="items-tabs">
        <span class="tab active">おすすめ</span>
        <span class="tab">マイリスト</span>
    </div>

    <div class="items-container">
        @foreach ($items as $item)
        <a href="{{ url('/item/' . $item->id) }}" class="item-link">
            <div class="item-card">
                <div class="item-image">
                    <img src="{{ asset('images/noimage.png') }}" alt="{{ $item->name }}">
                </div>
                <p class="item-name">{{ $item->name }}</p>
            </div>
        </a>
        @endforeach
    </div>

</div>
@endsection