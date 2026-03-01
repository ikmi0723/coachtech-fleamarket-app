<h1>ログイン</h1>

<form method="POST" action="{{ route('login') }}">
    @csrf

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

    <button type="submit">ログイン</button>
</form>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">ログアウト</button>
</form>