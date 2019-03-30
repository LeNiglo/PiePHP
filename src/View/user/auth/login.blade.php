<form action="{{ route('/login') }}" method="post">

    @isset($error)
        <div class="alert alert-danger" role="alert">{{ $error }}</div>
    @endisset

    <div class="form-group">
        <label for="email">Email</label>
        <input class="form-control" type="email" name="email" id="email" />
    </div>

    <div class="form-group">
        <label for="password">Password</label>
        <input class="form-control" type="password" name="password" id="password" />
    </div>

    <button type="submit" class="btn btn-primary">
        Login
    </button>
</form>
