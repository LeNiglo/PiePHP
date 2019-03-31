<h1 class="mb-5">
    {{ $post->title }}
    <small>by <a href="{{ route('profile', ['id' => $post->user_id]) }}">{{ $post->user->name }}</a></small>
</h1>

{{-- TODO: Style this element --}}
<p>{{ $post->content }}</p>
