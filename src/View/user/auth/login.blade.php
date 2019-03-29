<form action="{{ route('/login') }}" method="post">
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
