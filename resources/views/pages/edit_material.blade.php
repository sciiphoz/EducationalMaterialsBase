@extends('template.app')
@section('content')
    <div class="container">
        <div class="add-product-form">
            <h1>Изменение</h1>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="material-settings">
                    <div class="settings-item">
                        <label for="private">Приватный?</label>
                        <input type="checkbox" name="private" id="private"></input>
                    </div>

                    <div class="settings-item">
                        <label for="disable-comments">Запретить комментарии?</label>
                        <input type="checkbox" name="disable-comments" id="disable-comments"></input>
                    </div>
                </div>


                <label for="name">Название</label>
                <input type="text" id="name" name="name" required>

                <label for="text">Текст</label>
                <textarea id="text" name="text" rows="16" required></textarea>

                <div class="actions">
                    <button type="submit" class="end-create-button">Изменить</button>
                    <button type="submit" class="delete-button">Удалить</button>
                </div>
            </form>
        </div>
    </div>
@endsection