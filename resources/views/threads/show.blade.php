@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a href="#">{{$thread->creator->name}}</a> posted: {{$thread->title}}
                </div>

                <div class="panel-body">
                    {{$thread->body}}
                </div>
            </div>
            @foreach($replies as $reply)
                @include('threads.reply')
            @endforeach
            {{$replies->links()}}
            @if(auth()->check())
                <form method="POST" action="{{$thread->path()}}/replies">
                {{csrf_field()}}
                <div class="form-group">
                    <textarea class="form-control" rows="5" id="body" name="body" placeholder="Have something to say?"></textarea>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary" type="submit">Post</button>
                </div>
                </form>
            @else
            <p class="text-center">Please <a href="{{route('login')}}">sign in</a> to participate in this discussion</p>
            @endif
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-body">
                    This thread was published {{$thread->created_at->diffForHumans()}} by
                    <a href="#">{{$thread->creator->name}}</a>, and currently has {{$thread->replies_count}} {{str_plural('comment', $thread->replies_count)}}.
                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection
