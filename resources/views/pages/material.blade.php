@extends('template.app')
@section('content')
    <div class="page-content">
        @php
            $likesum = $material->like->sum('value');
            $scoreClass = $likesum > 0 ? 'positive-score' : ($likesum < 0 ? 'negative-score' : 'neutral-score');
            
            $userLike = auth()->check() ? $material->like->where('user_id', auth()->id())->first() : null;
            $userLikeValue = $userLike ? $userLike->value : 0;
        @endphp

        <div class="content">
            <p class="content-date">{{ $material->date }}</p>
            <p class="content-name">{{ $material->title }}</p>
            <div class="content-tag">
                <p class="tag-name">{{ $material->tag->title ?? 'Без тега' }}</p>
            </div>
            <p class="content-text">{{ $material->text }}</p>
            
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
                    </div>
                </div>
            @endauth
        </div>

        <div class="comment-section">
            <h4>Комментарии ({{ $material->comment->count() }})</h4>

            @auth
                @if(!$material->isDisabled)
                    <div class="comment-form">
                        <form action="{{ route('add.comment', $material->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <textarea name="text" class="comment-textarea" placeholder="Напишите комментарий..." required></textarea>
                            </div>
                            <button type="submit" class="comment-submit-btn">Отправить</button>
                        </form>
                    </div>
                @else
                    <div class="comments-disabled">
                        <p>Комментарии к этому материалу отключены</p>
                    </div>
                @endif
            @else
                <p class="comment-login-message">
                    <a href="{{ route('view.login') }}">Войдите</a>, чтобы оставить комментарий
                </p>
            @endauth

            {{-- Список комментариев --}}
            <div class="comment-items">
                @forelse($material->comment as $comment)
                    <div class="comment-item">
                        <div class="comment-user">
                            <img src="{{ asset('img/user.png') }}" alt="icon" class="comment-icon">
                            <p class="comment-name">{{ $comment->user->name ?? 'Пользователь' }}</p>
                        </div>
                        
                        <p class="comment-text">{{ $comment->text }}</p>
                        <p class="comment-date">{{ $comment->created_at->format('d.m.Y H:i') }}</p>
                    </div>

                    @if(!$loop->last)
                        <hr>
                    @endif
                @empty
                    <p class="no-comments">Комментариев пока нет</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection