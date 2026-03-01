<h1>会員登録</h1>

<form method="POST" action="{{ route('register') }}">
    @csrf

    <div>
        <label>お名前</label>
        <input type="text" name="name">
        @error('name')
        <div style="color:red">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label>メールアドレス</label>
        <input type="email" name="email">
        @error('email')
        <div style="color:red">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label>パスワード</label>
        <input type="password" name="password">
        @error('password')
        <div style="color:red">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label>確認用パスワード</label>
        <input type="password" name="password_confirmation">
        @error('password_confirmation')
        <div style="color:red">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit">登録</button>
</form>