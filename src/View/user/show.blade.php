<h1 class="page-header">User Show :)</h1>

@isset($user)
    <div class="well">
        <h3>
            Email : {{ $user->email }}
            <small>(Name : {{ $user->name }})</small>
        </h3>
    </div>
@endisset

@if (count($users) > 0)
    <hr>
@endif

@foreach($users as $u)
    <div class="well" id="user-{{ $u->id }}">
        <h3>
            Email : {{ $u->email }}
            <small>(Name : {{ $u->name }})</small>
        </h3>
    </div>
@endforeach
