
@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <a href="#"> {{ $thread->owner->name }}</a> posted:
                        {{ $thread->title }}
                    </div>

                    <div class="card-body">
                        {{ $thread->body }}
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            @foreach($thread->replies as $reply)
                @include('threads.reply');
        @endforeach
        </div>
        @if (auth()->check())
            <form method="POST" action="/threads/{{ $thread->id }}/replies">
                {{ csrf_field() }}
                <div class="form-group">
                    <textarea name="body" id="body" class="form-control" rows="5" placeholder="put your reply here"></textarea>
                </div>
                <button id="submit" type="submit" class="btn btn-success">Submit</button>
            </form>
        @else
        <p class="text-center"><a class="text-center" href="{{ route('login') }}">login</a> to post your reply.</p>
        @endif
    </div>
@endsection
