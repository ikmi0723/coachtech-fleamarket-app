@extends('layouts.app')

@section('content')
<div class="profile-wrapper">
    <div class="profile-container">
        <h1 class="profile-title">プロフィール設定</h1>

        {{-- 画像アップロードがあるので enctype を追加 --}}
        <form action="{{ url('/mypage/profile') }}" method="POST" enctype="multipart/form-data" class="profile-form" novalidate>
            @csrf

            {{-- プロフィール画像エリア --}}
            <div class="profile-image-group">
                {{-- 現在のプロフィール画像があれば表示、なければダミー表示 --}}
                @if (!empty($user->image_path))
                <img
                    src="{{ asset('storage/' . $user->image_path) }}"
                    alt="プロフィール画像"
                    class="profile-image-preview">
                @else
                <div class="profile-image-preview profile-image-preview--empty"></div>
                @endif

                {{-- 画像選択 --}}
                <div class="profile-image-input-area">
                    <label for="image" class="profile-image-select-button">画像を選択する</label>
                    <input
                        id="image"
                        type="file"
                        name="image"
                        class="profile-image-input"
                        accept=".jpg,.jpeg,.png">
                </div>
            </div>

            @error('image')
            <p class="error">{{ $message }}</p>
            @enderror

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