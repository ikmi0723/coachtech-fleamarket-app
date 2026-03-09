<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>COACHTECH</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

    <header class="header">
        <div class="header-inner">
            <div class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="COACHTECH">
            </div>

            <div class="search">
                <input type="text" placeholder="なにをお探しですか？">
            </div>

            <nav class="nav">
                <a href="#">ログアウト</a>
                <a href="#">マイページ</a>
                <a href="#" class="sell-btn">出品</a>
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

</body>

</html>