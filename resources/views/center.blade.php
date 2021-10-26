@extends('body',['pageTitle'=>'aaa'])



@section('centersec')


@foreach($posts as $post)

<div style="border:1px solid ; margin:5px" data-api="/api/posts" data-idx="{{$post->id}}">
<h1 contenteditable="true" data-field="title">{{$post->title}}</h1>

<div>
<div contenteditable="true" data-field="text">
{{$post->text}}
</div>
</div>


</div>


@endforeach


@stop

