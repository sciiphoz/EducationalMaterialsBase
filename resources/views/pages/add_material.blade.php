@extends('template.app')
@section('content')
    <div class="container">
        <div class="add-product-form">
            <h1>Создание</h1>
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('material.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                
                <div class="material-settings">
                    <div class="settings-item">
                        <label for="tag_id">Тег</label>
                        <select name="tag_id" id="tag_id" required>
                            <option value="">Выберите тег</option>
                            @foreach($tags as $tag)
                                <option value="{{ $tag->id }}" {{ old('tag_id') == $tag->id ? 'selected' : '' }}>
                                    {{ $tag->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="settings-item">
                        <label for="isPrivate">Приватный?</label>
                        <input type="checkbox" name="isPrivate" id="isPrivate" value="1" {{ old('isPrivate') ? 'checked' : '' }}>
                    </div>

                    <div class="settings-item">
                        <label for="isDisabled">Запретить комментарии?</label>
                        <input type="checkbox" name="isDisabled" id="isDisabled" value="1" {{ old('isDisabled') ? 'checked' : '' }}>
                    </div>
                </div>

                <label for="title">Название</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required>

                <label for="text">Текст</label>
                <textarea id="text" name="text" rows="16" required>{{ old('text') }}</textarea>

                <button type="submit" class="end-create-button">Добавить материал</button>
            </form>
        </div>
    </div>
@endsection