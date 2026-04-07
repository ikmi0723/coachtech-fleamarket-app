@extends('layouts.app')

@section('content')
<div class="verify-wrapper">
    <div class="verify-container">
        {{-- 認証案内メッセージ --}}
        <p class="verify-message">
            登録していただいたメールアドレスに認証メールを送付しました。<br>
            メール認証を完了してください。
        </p>

        {{-- 再送成功メッセージ --}}
        @if (session('status') === 'verification-link-sent')
        <p class="verify-success-message">
            認証メールを再送しました。
        </p>
        @endif

        {{-- 認証画面への案内ボタン --}}
        <div class="verify-button-area">
            <a href="http://localhost:8025" target="_blank" rel="noopener noreferrer" class="verify-button">
                認証はこちらから
            </a>
        </div>

        {{-- 認証メール再送フォーム --}}
        <form method="POST" action="{{ route('verification.send') }}" class="verify-resend-form">
            @csrf
            <button type="submit" class="verify-resend-button">
                認証メールを再送する
            </button>
        </form>
    </div>
</div>
@endsection