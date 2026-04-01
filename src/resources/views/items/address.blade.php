@extends('layouts.app')

@section('content')
<div class="profile-wrapper">
    <div class="profile-container">
        <h1 class="profile-title">住所の変更</h1>

        <form action="{{ url('/purchase/address/' . $item->id) }}" method="POST" class="profile-form" novalidate>
            @csrf

            <div class="profile-form-group">
                <label class="profile-label">郵便番号</label>
                <input
                    type="text"
                    name="postcode"
                    class="profile-input"
                    value="{{ old('postcode', $address['postcode']) }}">
                @error('postcode')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="profile-form-group">
                <label class="profile-label">住所</label>
                <input
                    type="text"
                    name="address"
                    class="profile-input"
                    value="{{ old('address', $address['address']) }}">
                @error('address')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="profile-form-group">
                <label class="profile-label">建物名</label>
                <input
                    type="text"
                    name="building"
                    class="profile-input"
                    value="{{ old('building', $address['building']) }}">
            </div>

            <button type="submit" class="profile-submit-button">更新する</button>
        </form>
    </div>
</div>
@endsection