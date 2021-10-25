@extends('body',['pageTitle'=>'aaa'])



@section('centersec')


@foreach($posts as $post)

<div class="celement" style="border:1px solid ; margin:5px" data-idx="{{$post->id}}">
<h1 contenteditable="true">{{$post->title}}</h1>

<div contenteditable="true">
{{$post->text}}
</div>
</div>


@endforeach


@stop

