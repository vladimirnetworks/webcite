@extends('main',['pageTitle'=>'aaa'])

@section('body')


@foreach($posts as $post)

<div style="padding:5px" class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative" data-api="/api/posts" data-idx="{{$post->id}}">
<h1 contenteditable="true" data-field="title">{{$post->title}}</h1>

<div>
<div contenteditable="true" data-field="text">
{{$post->text}}
</div>
</div>


</div>


@endforeach


@stop

