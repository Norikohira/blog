@extends('layouts.app')

@section('content')
    @forelse($all_posts as $post)
        <div class="mt-2 border border-2 rounded py-3 px-4">
            <a href="{{ route('post.show', $post->id) }}">
                <h2 class="h4">{{ $post->title }}</h2>
            </a>
            <h3 class="h6 text-muted">{{ $post->user->name }}</h3>
            <p class="fw-light mb-0">{{ $post->body }}</p>

            <!-- ACTION BUTTONS -->
            <!-- If the OWNER of the post is the AUTH user, show edit and delete buttons -->
            @if(Auth::user()->id === $post->user_id)
                <div class="mt-2 text-end">
                    <a href="{{ route('post.edit', $post->id) }}" class="btn btn-warning btn-sm" title="Edit"><i class="fa-solid fa-edit"></i></a>

                    <form action="{{ route('post.destroy', $post->id) }}" method="post" class="d-inline">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger btn-sm" title="Delete"><i class="fa-solid fa-trash"></i></button>
                    </form>

                </div>
            @endif
        </div>
    @empty
        <div class="" style="margin-top: 100px">
            <h2 class="text-muted text-center">No posts yet</h2>
            <p class="text-center">
                <a href="{{ route('post.create') }}" class="text-decoration-none">
                    Create a new post
                </a>
            </p>
        </div>
    @endforelse
@endsection
