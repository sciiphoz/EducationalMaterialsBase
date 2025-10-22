@extends('template.app')
@section('title', $material->title)
@section('description', Str::limit($material->section->first()->content ?? 'Материал по программированию', 160))
@section('content')
    <div class="page-content">
        @php
            $likesum = $material->like->sum('value');
            $scoreClass = $likesum > 0 ? 'positive-score' : ($likesum < 0 ? 'negative-score' : 'neutral-score');
            
            $userLike = auth()->check() ? $material->like->where('user_id', auth()->id())->first() : null;
            $userLikeValue = $userLike ? $userLike->value : 0;
        @endphp

        <div class="material-content">
            <h1 class="material-title">{{ $material->title }}</h1>
            <div class="material-meta">
                <span class="material-date">{{ $material->date }}</span>
                <span class="material-tag">{{ $material->tag->title ?? 'Без тега' }}</span>
                @if($material->isDisabled)
                    <span class="material-private">🔒 Приватный</span>
                @endif
            </div>

            <div class="material-sections">
                @foreach($material->section->sortBy('order') as $section)
                    <div class="section section-{{ $section->type }}">
                        @if($section->isText())
                            <div class="text-section">
                                {!! $section->display_content !!}
                            </div>
                        @elseif($section->isCode())
                            <div class="code-section">
                                <div class="code-header">
                                    <span class="code-language">{{ $section->language }}</span>
                                </div>
                                <pre><code class="language-{{ $section->language }}">{{ $section->display_content }}</code></pre>
                            </div>
                        @elseif($section->isImage())
                            <div class="image-section">
                                <img src="{{ $section->image_url }}" 
                                     alt="{{ $section->image_alt ?? '' }}"
                                     class="material-image"
                                     loading="lazy">
                                @if($section->image_alt)
                                    <p class="image-caption">{{ $section->image_alt }}</p>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

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

        {{-- Комментарии --}}
        @if(!$material->isDisabled)
            <div class="comment-section">
                <h4>Комментарии ({{ $material->comment->count() }})</h4>

                @auth
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
        @else
            <div class="comments-disabled">
                <p>Комментарии к этому материалу отключены</p>
            </div>
        @endif
    </div>
@endsection