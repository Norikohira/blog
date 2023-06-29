@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div>
                    <div>
                        @if ($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="avatar" class="img-fluid mb-2" style="max-width: 200px;">
                        @else
                            <p>No profile picture available.</p>
                        @endif
                        <h4>{{ $user->name }}</h4>
                        <a href="{{ route('profile.edit') }}" class="text-primary" style="text-decoration: none;">Edit Profile</a>
                    </div>
                    <div class="mt-4">
                        @if ($posts->count() > 0)
                            @foreach ($posts as $post)
                                <div class="mt-2 border border-2 rounded py-3 px-4">
                                    <a href="{{ route('post.show', $post->id) }}">
                                        <h2 class="h4">{{ $post->title }}</h2>
                                    </a>
                                    <p class="fw-light mb-0">{{ $post->body }}</p>
                                </div>
                            @endforeach
                        @else
                            <p>No posts found.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
