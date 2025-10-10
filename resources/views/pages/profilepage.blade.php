@extends('template.app')
@section('content')

<div class="container">
    <div class="profile">
        <div class="profile-card">
            <img src="assets/icons/user_big.png" alt="icon" class="profile-icon">

            <div class="profile-info">
                <p>{{auth()->user()->login}}</p>
            </div>
        </div>

        <hr>

        <div class="content">
                <p class="content-date">{{ $material->date }}</p>
                <a href="product.html"><p class="content-name">{{ $material->title }}</p></a>
                <div class="content-tag">
                    <p class="tag-name">{{ $material->tag->name }}</p>
                </div>
                
                <form action="{{ route('open.full', $material->id) }}" method="post">
                    @csrf
                    <button type="button" class="content-button">Подробнее</button>
                </form>

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
            </div>

        <a href="add_product.html"><button class="create-button" type="button">Создать</button></a>
    </div>
</div>
