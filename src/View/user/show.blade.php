<h1 class="page-header">User Show :)</h1>

<div class="well">
    <h3>
        Email : {{ $user->email }}
        <small>(Name : {{ $user->name }})</small>
    </h3>
</div>

@if (count($users) > 0)
    @if (false)
        LOL
    @endif
    <hr>
@endif

@foreach($users as $u)
    <div class="well">
        <h3>
            Email : {{ $u->email }}
            <small>(Name : {{ $u->name }})</small>
        </h3>
    </div>
@endforeach
