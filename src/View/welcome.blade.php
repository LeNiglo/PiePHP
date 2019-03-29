<h1>Hello {{ $user->name }} !</h1>

@foreach ($user->posts as $post)
    <div class="col-md-6">
        <h3>{{ $post->title }}</h3>

        <p>{{ $post->content }}</p>
    </div>
@endforeach

<form action="{{ route('/post/submit') }}" method="post">
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
