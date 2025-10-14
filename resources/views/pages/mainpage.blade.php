@php
    use Illuminate\Support\Str;
@endphp

@extends('template.app')
@section('content')
@csrf
    <div class="page-content">
        @forelse($materials as $material)
            @php
                $likesum = $material->like->sum('value');
                $scoreClass = $likesum > 0 ? 'positive-score' : ($likesum < 0 ? 'negative-score' : 'neutral-score');
                
                $userLike = auth()->check() ? $material->like->where('user_id', auth()->id())->first() : null;
                $userLikeValue = $userLike ? $userLike->value : 0;
                
                $previewText = Str::limit($material->text, 450, '...');
            @endphp
            
            <div class="content">
                <p class="content-date">{{ $material->date }}</p>
                <a href="product.html"><p class="content-name">{{ $material->title }}</p></a>
                <div class="content-tag">
                    <p class="tag-name">{{ $material->tag->title ?? 'Без тега' }}</p>
                </div>
                <p class="content-text">{{ $previewText }}</p>
                
                @auth
                    <div class="content-ui">
                        <div class="ui-items">
                            <form action="{{ route('add.like', $material->id) }}" method="post">
                                @csrf
                                <button class="ui-submit_button" type="submit">
                                    <img class="ui-like {{ $userLikeValue == 1 ? 'active-like' : '' }}" src="{{ asset('img/like.png') }}" alt="like">
                                </button>
                            </form>
                            <p class="ui-score {{ $scoreClass }}">{{ $likesum }}</p>
                            <form action="{{ route('add.dislike', $material->id) }}" method="post">
                                @csrf
                                <button class="ui-submit_button" type="submit">
                                    <img class="ui-dislike {{ $userLikeValue == -1 ? 'active-dislike' : '' }}" src="{{ asset('img/dislike.png') }}" alt="dislike">
                                </button>
                            </form>
                            <form action="{{ route('add.comment', $material->id) }}" method="post">
                                @csrf
                                <button class="ui-submit_button" type="submit">
                                    <img class="ui-comment" src="{{ asset('img/comment.png') }}" alt="comment">
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>
        @empty
            <h4 class="empty-text">Статей ещё нет</h4>
        @endforelse
    </div>
@endsection