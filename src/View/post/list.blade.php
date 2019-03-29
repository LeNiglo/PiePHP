<div class="row">
    @foreach ($posts as $post)
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $post->title }}</h5>
                    <p class="card-text">{{ strlen($post->content) > 75 ? substr($post->content, 0, 75) . "..." : $post->content }}</p>
                    <a href="{{ route('/posts/'.$post->id) }}" class="btn btn-primary">Read More</a>
                </div>
            </div>
        </div>
    @endforeach
</div>