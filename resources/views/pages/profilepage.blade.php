@extends('template.app')
@section('content')

<div class="container">
    <div class="profile">
        <div class="profile-card">
            <img src="{{ asset('img/user_big.png') }}" alt="icon" class="profile-icon">
            <div class="profile-info">
                <p>{{ auth()->user()->name }}</p>
            </div>
        </div>

        <hr>

        {{-- Вывод материалов пользователя --}}
        @forelse($materials as $material)
            @php
                $likesum = $material->like->sum('value');
                $scoreClass = $likesum > 0 ? 'positive-score' : ($likesum < 0 ? 'negative-score' : 'neutral-score');
                
                $userLike = auth()->check() ? $material->like->where('user_id', auth()->id())->first() : null;
                $userLikeValue = $userLike ? $userLike->value : 0;
            @endphp

            <div class="content">
                <p class="content-date">{{ $material->date }}</p>
                <a href="{{ route('materials.show', $material->id) }}"><p class="content-name">{{ $material->title }}</p></a>
                <div class="content-tag">
                    <p class="tag-name">{{ $material->tag->title ?? 'Без тега' }}</p>
                </div>

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
                        <form action="{{ route('materials.update', $material->id) }}" method="post">
                            @csrf
                            <button class="ui-submit_button" type="submit">
                                <img class="ui-edit" src="{{ asset('img/edit.png') }}" alt="edit">
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-message">
                <p>Вы еще не создали ни одной статьи</p>
            </div>
        @endforelse

        <a href="{{ route('material.create') }}"><button class="create-button" type="button">Создать</button></a>
    </div>
</div>

@endsection