<?php

use \Core\Router;

Router::connect('/register', ['c' => 'auth', 'a' => 'register']);
Router::connect('/login', ['c' => 'auth', 'a' => 'login']);

Router::connect('/', ['c' => 'user', 'a' => 'index']);
Router::connect('/u/{id}', ['c' => 'user', 'a' => 'show']);
Router::connect('/user/{id}/show', ['c' => 'user', 'a' => 'show', 'p' => ['id' => '[0-9]+']]);

Router::connect('/posts', ['c' => 'user', 'a' => 'posts']);

Router::connect('/post/submit', ['c' => 'post', 'a' => 'submit']);
