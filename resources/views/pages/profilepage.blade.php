@extends('template.app')
@section('content')

<h1>Профиль</h1>


<h4>{{auth()->user()->email}}</h4>
<h5>{{auth()->user()->login}}</h5>