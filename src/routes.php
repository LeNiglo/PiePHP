<?php

use \Core\Router;

Router::connect('/register', ['c' => 'auth', 'a' => 'register']);
Router::connect('/login', ['c' => 'auth', 'a' => 'login']);

Router::connect('/', ['c' => 'user', 'a' => 'index']);
Router::connect('/u', ['c' => 'user', 'a' => 'show_me']);
Router::connect('/u/{id}', ['c' => 'user', 'a' => 'show']);

Router::connect('/posts', ['c' => 'post', 'a' => 'list']);
Router::connect('/posts/{id}', ['c' => 'post', 'a' => 'show', 'p' => ['id' => '[0-9]+']]);

Router::connect('/posts/submit', ['c' => 'post', 'a' => 'submit']);
