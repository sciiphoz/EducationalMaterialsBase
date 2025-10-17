@extends('template.app')
@section('content')
    <div class="container">
        <div class="add-product-form">
            <h1>Изменение</h1>
            
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

            <form action="{{ route('material.update', $material->id) }}" method="post">
                @csrf
                @method('PUT')
                
                <div class="material-settings">
                    <div class="settings-item">
                        <label for="tag_id">Тег</label>
                        <select name="tag_id" id="tag_id" required>
                            <option value="">Выберите тег</option>
                            @foreach($tags as $tag)
                                <option value="{{ $tag->id }}" {{ $material->tag_id == $tag->id ? 'selected' : '' }}>
                                    {{ $tag->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="settings-item">
                        <label for="isPrivate">Приватный?</label>
                        <input type="checkbox" name="isPrivate" id="isPrivate" value="1" {{ $material->isPrivate ? 'checked' : '' }}>
                    </div>

                    <div class="settings-item">
                        <label for="isDisabled">Запретить комментарии?</label>
                        <input type="checkbox" name="isDisabled" id="isDisabled" value="1" {{ $material->isDisabled ? 'checked' : '' }}>
                    </div>
                </div>

                <label for="title">Название</label>
                <input type="text" id="title" name="title" value="{{ old('title', $material->title) }}" required>

                <label for="text">Текст</label>
                <textarea id="text" name="text" rows="16" required>{{ old('text', $material->text) }}</textarea>

                <div class="actions">
                    <button type="submit" class="end-create-button">Изменить</button>
                    
                    <button type="button" class="delete-button" onclick="confirmDelete()">Удалить</button>
                </div>
            </form>

            <form id="delete-form" action="{{ route('material.destroy', $material->id) }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>

    <script>
        function confirmDelete() {
            if (confirm('Вы уверены, что хотите удалить этот материал? Это действие нельзя отменить.')) {
                document.getElementById('delete-form').submit();
            }
        }
    </script>
@endsection