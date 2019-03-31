<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>PiePHP</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="{{ route('index') }}">My Blog</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('posts_list') }}">Posts</a>
                    </li>
                    @if ($_SESSION['user_id'])
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('my_profile') }}">My Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}">Logout</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>
    </header>
    <div class="container pt-3">
        {!! $_view !!}
    </div>
    <footer></footer>
    <script src="{{ asset('js/jquery.slim.min.js') }}" charset="utf-8"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" charset="utf-8"></script>
</body>
</html>
