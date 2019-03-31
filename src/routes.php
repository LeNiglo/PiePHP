<?php

use \Core\Router;

Router::connect('/register', 'AuthController@register');
Router::connect('/login', 'AuthController@login');
Router::connect('/logout', 'AuthController@logout');

Router::connect('/u', 'UserController@show_me');
Router::connect('/u/{id}', 'UserController@show');

Router::connect('/', 'PostController@index');
Router::connect('/posts', 'PostController@list');
Router::connect('/posts/{id}', 'PostController@show')->params(['id' => '[0-9]+']);
Router::connect('/posts/submit', 'PostController@submit');

Router::connect('/hello/{name}', function ($name) {
    dd("Hello $name");
})->name('hello');
