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
            {{-- ロゴ --}}
            <div class="logo">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="COACHTECH">
                </a>
            </div>

            {{-- 商品検索フォーム --}}
            <form class="search" action="{{ url('/') }}" method="GET">
                <input
                    type="text"
                    name="keyword"
                    placeholder="なにをお探しですか？"
                    value="{{ request('keyword') }}">
            </form>

            {{-- 右側ナビ --}}
            <nav class="nav">
                {{-- ログアウトは POST で送信する --}}
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-button">ログアウト</button>
                </form>

                <a href="{{ url('/mypage') }}">マイページ</a>
                <a href="{{ url('/sell') }}" class="sell-btn">出品</a>
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

</body>

</html>