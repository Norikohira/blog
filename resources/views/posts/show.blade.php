@extends('layouts.app')

@section('title', 'Show Post')

@section('content')
  <div class="mt-2 border border-2 rounded py-3 px-4 shadow-sm">
    <h2 class="h4">{{ $post->title }}</h2>
    <h3 class="h6 text-muted">{{ $post->user->name }}</h3>
    <p>{{ $post->body }}</p>

    <img src="{{ asset('/storage/images/' . $post->image) }}" alt="{{ $post->image }}" class="w-100 shadow">
  </div>

  <div class="mt-3">
    <form action="{{ route('comment.store') }}" method="POST">
      @csrf
      <input type="hidden" name="post_id" value="{{ $post->id }}">
      <div class="input-group mb-3">
        <input type="text" name="body" id="body" class="form-control" placeholder="Add a comment...">
        <button type="submit" class="btn btn-outline-secondary">Post</button>
      </div>
    </form>
  </div>

  @foreach ($post->comments as $comment)
    <div class="border border-2 rounded py-3 px-4 mt-3">
      <div class="d-flex justify-content-between">
        <div>
          <strong>{{ $comment->user->name }}</strong>
          <small>{{ $comment->created_at->format('Y-m-d H:i:s') }}</small>
        </div>
        <div>
          <form action="{{ route('comment.destroy', $comment->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash-can"></i></button>
          </form>
        </div>
      </div>
      <p>{{ $comment->body }}</p>
    </div>
  @endforeach
@endsection
