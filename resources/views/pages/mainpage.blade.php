@extends('template.app')
@section('content')
    <div class="page-content">
        @forelse($materials as $material)
            <div class="content">
                <p class="content-date">{{ $material->date }}</p>
                <a href="product.html"><p class="content-name">{{ $material->name }}</p></a>
                <div class="content-tag">
                    <p class="tag-name">{{ $material->tag->name }}</p>
                </div>
                <p class="content-text">{{ $material->text }}</p>
                

                @auth
                    <div class="content-ui">
                        <div class="ui-items">
                            <form action="{{ route('add.like', $material->id) }}" method="post">
                                @csrf
                                <button type="submit">
                                    <img class="ui-like" src="assets/icons/like.png" alt="like">
                                </button>
                            </form>
                            <p class="ui-score">{{ $material->rating }}</p>
                            <form action="{{ route('add.dislike', $product->id) }}" method="post">
                                @csrf
                                <button type="submit">
                                    <img class="ui-dislike" src="assets/icons/dislike.png" alt="like">
                                </button>
                            </form>
                            <form action="{{ route('add.dislike', $product->id) }}" method="post">
                                @csrf
                                <button type="submit">
                                    <img class="ui-comment" src="assets/icons/comment.png" alt="comment">
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>

            @empty
            <h4>Статей ещё нет</h4>
        @endforelse
    </div>
@endsection
