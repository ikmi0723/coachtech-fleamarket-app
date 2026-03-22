@extends('layouts.app')

@section('content')
<div class="sell-wrapper">
    <div class="sell-container">
        <h1 class="sell-title">商品の出品</h1>

        <form action="{{ url('/sell') }}" method="POST" class="sell-form">
            @csrf

            {{-- 商品画像 --}}
            <div class="sell-section">
                <h2 class="sell-section-title">商品画像</h2>
                <div class="sell-image-box">
                    <button type="button" class="sell-image-button">画像を選択する</button>
                </div>
            </div>

            {{-- 商品の詳細 --}}
            <div class="sell-section">
                <h2 class="sell-section-title">商品の詳細</h2>

                <div class="sell-form-group">
                    <label class="sell-label">カテゴリー</label>
                    <div class="sell-category-list">
                        @foreach ($categories as $category)
                        <label class="sell-category-item">
                            <input
                                type="checkbox"
                                name="categories[]"
                                value="{{ $category->id }}"
                                {{ is_array(old('categories')) && in_array($category->id, old('categories')) ? 'checked' : '' }}>
                            <span>{{ $category->name }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('categories')
                    <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sell-form-group">
                    <label class="sell-label">商品の状態</label>
                    <select name="condition" class="sell-select">
                        <option value="">選択してください</option>
                        <option value="良好" {{ old('condition') === '良好' ? 'selected' : '' }}>良好</option>
                        <option value="目立った傷や汚れなし" {{ old('condition') === '目立った傷や汚れなし' ? 'selected' : '' }}>目立った傷や汚れなし</option>
                        <option value="やや傷や汚れあり" {{ old('condition') === 'やや傷や汚れあり' ? 'selected' : '' }}>やや傷や汚れあり</option>
                        <option value="状態が悪い" {{ old('condition') === '状態が悪い' ? 'selected' : '' }}>状態が悪い</option>
                    </select>
                    @error('condition')
                    <p class="error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- 商品名と説明 --}}
            <div class="sell-section">
                <h2 class="sell-section-title">商品名と説明</h2>

                <div class="sell-form-group">
                    <label class="sell-label">商品名</label>
                    <input type="text" name="name" class="sell-input" value="{{ old('name') }}">
                    @error('name')
                    <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sell-form-group">
                    <label class="sell-label">ブランド名</label>
                    <input type="text" name="brand" class="sell-input" value="{{ old('brand') }}">
                </div>

                <div class="sell-form-group">
                    <label class="sell-label">商品の説明</label>
                    <textarea name="description" class="sell-textarea">{{ old('description') }}</textarea>
                    @error('description')
                    <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sell-form-group">
                    <label class="sell-label">販売価格</label>
                    <input type="text" name="price" class="sell-input" value="{{ old('price') }}">
                    @error('price')
                    <p class="error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <button type="submit" class="sell-submit-button">出品する</button>
        </form>
    </div>
</div>
@endsection