@extends('template.app')
@section('content')
    <h1>Каталог</h1>
    <div>
        @forelse($materials as $material)
            {{ $material->title }}
            <br>
            {{ $material->text }}
            <br>
            {{ $material->category->title }}
            @auth
                <form action="{{ route('add.like', $material->id) }}" method="post">
                    @csrf
                    <button type="submit">Лайк</button>
                </form>
                {{ $material->rating }}
                <form action="{{ route('add.dislike', $product->id) }}" method="post">
                    @csrf
                    <button type="submit">Дизлайк</button>
                </form>
            @endauth
            <form action="{{ route('open.full', $material->id) }}" method="post">
                @csrf
                <button type="submit">Читать полностью</button>
            </form>
        @empty
            <h4>Статей ещё нет</h4>
        @endforelse
    </div>
@endsection
