@extends('layouts.app')
@section('title', $article->title)

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow">
    <h1 class="text-3xl font-bold text-indigo-700 mb-3">{{ $article->title }}</h1>
    <p class="text-gray-500 mb-2">✍️ {{ $article->author_name }} • {{ $article->published_at?->format('Y-m-d') }}</p>
    <p class="text-gray-700 leading-loose mt-6">{!! nl2br(e($article->content)) !!}</p>
</div>
@endsection
