@extends('layouts.auth')

@section('content')
<div class="auth-container">
    <h2>会員登録</h2>

    <form method="POST" action="{{ route('register') }}" novalidate>
        @csrf

        <div class="form-group">
            <label>お名前</label>
            <input type="text" name="name" value="{{ old('name') }}">
            @error('name')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label>メールアドレス</label>
            <input type="email" name="email" value="{{ old('email') }}">
            @error('email')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label>パスワード</label>
            <input type="password" name="password">
            @error('password')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label>確認用パスワード</label>
            <input type="password" name="password_confirmation">
            @error('password_confirmation')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn-primary">登録する</button>
    </form>

    <p class="auth-link">
        <a href="{{ route('login') }}">ログインはこちら</a>
    </p>
</div>
@endsection