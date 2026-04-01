@extends('layouts.app')

@section('content')
<div class="profile-wrapper">
    <div class="profile-container">
        <h1 class="profile-title">プロフィール設定</h1>

        <form action="{{ url('/mypage/profile') }}" method="POST" class="profile-form" novalidate>
            @csrf

            <div class="profile-form-group">
                <label class="profile-label">ユーザー名</label>
                <input
                    type="text"
                    name="name"
                    class="profile-input"
                    value="{{ old('name', $user->name) }}">
                @error('name')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="profile-form-group">
                <label class="profile-label">郵便番号</label>
                <input
                    type="text"
                    name="postcode"
                    class="profile-input"
                    value="{{ old('postcode', $user->postcode) }}">
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
                    value="{{ old('address', $user->address) }}">
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
                    value="{{ old('building', $user->building) }}">
            </div>

            <button type="submit" class="profile-submit-button">更新する</button>
        </form>
    </div>
</div>
@endsection