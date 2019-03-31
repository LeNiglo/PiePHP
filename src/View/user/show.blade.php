<h1 class="mb-5">Blog of {{ $user->name }}</h1>

<div class="row">
    @foreach ($user->posts as $post)
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $post->title }}</h5>
                    <p class="card-text">{{ strlen($post->content) > 75 ? substr($post->content, 0, 75) . "..." : $post->content }}</p>
                    <a href="{{ route('posts_detail', ['id' => $post->id]) }}" class="btn btn-primary">Read More</a>
                </div>
            </div>
        </div>
    @endforeach
</div>

@if (Auth::id() === $user->id)
    <hr />

    <form action="{{ route('posts_submit') }}" method="post">
        <input type="hidden" name="user_id" value="{{ $user->id }}" />

        <div class="form-group">
            <label for="title">Title</label>
            <input class="form-control" type="text" name="title" id="title" />
        </div>

        <div class="form-group">
            <label for="content">Content</label>
            <textarea class="form-control" name="content" id="content"></textarea>
        </div>

        <button type="submit" class="btn btn-dark">Post</button>
    </form>
@endif
