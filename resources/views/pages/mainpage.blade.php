@extends('template.app')
@section('content')
@csrf
    <div class="page-content">
        <div class="search-filters">
            <form action="{{ route('view.mainpage') }}" method="GET" class="search-form">
                <div class="search-row">
                    <div class="search-group">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Поиск по названию..." class="search-input">
                        <button type="submit" class="search-button">
                            <img src="{{ asset('img/search.png') }}" alt="search" class="search-icon">
                        </button>
                    </div>

                    <div class="filter-group">
                        <label for="sort">Сортировка:</label>
                        <select name="sort" id="sort" onchange="this.form.submit()">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Сначала новые</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Сначала старые</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>По популярности</option>
                        </select>
                    </div>

                    <div class="filter-row">
                        <div class="filter-group">
                            <label for="tag">Фильтр по тегам:</label>
                            <select name="tag" id="tag" onchange="this.form.submit()">
                                <option value="">Все теги</option>
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->id }}" {{ request('tag') == $tag->id ? 'selected' : '' }}>
                                        {{ $tag->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        @if(request()->hasAny(['search', 'sort', 'tag']))
                            <a href="{{ route('view.mainpage') }}" class="reset-filters">Сбросить фильтры</a>
                        @endif
                    </div>
                </div>


            </form>
        </div>

        @if(request('search') && $materials->count() > 0)
            <div class="search-results-info">
                Найдено материалов: {{ $materials->total() }}
            </div>
        @endif

        @forelse($materials as $material)
            @php
                $likesum = $material->like->sum('value');
                $scoreClass = $likesum > 0 ? 'positive-score' : ($likesum < 0 ? 'negative-score' : 'neutral-score');
                
                $userLike = auth()->check() ? $material->like->where('user_id', auth()->id())->first() : null;
                $userLikeValue = $userLike ? $userLike->value : 0;
                
                $previewText = Str::limit($material->text, 150, '...');
            @endphp
            
            <div class="content">
                <p class="content-date">{{ $material->date }}</p>
                <a href="{{ route('material.show', $material->id) }}">
                    <p class="content-name">{{ $material->title }}</p>
                </a>
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
            <div class="empty-results">
                @if(request('search') || request('tag'))
                    <h4 class="empty-text">По вашему запросу ничего не найдено</h4>
                    <p>Попробуйте изменить параметры поиска или <a href="{{ route('view.mainpage') }}">сбросить фильтры</a></p>
                @else
                    <h4 class="empty-text">Статей ещё нет</h4>
                @endif
            </div>
        @endforelse

        {{-- Пагинация --}}
        @if($materials->hasPages())
            <div class="pagination">
                {{ $materials->links() }}
            </div>
        @endif
    </div>
@endsection