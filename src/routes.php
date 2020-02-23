<?php

use PiePHP\Core\Router;

Router::connect('/register', 'AuthController@register')->name('register');
Router::connect('/login', 'AuthController@login')->name('login');
Router::connect('/logout', 'AuthController@logout')->name('logout');

Router::connect('/u', 'UserController@showMe')->name('my_profile');
Router::connect('/u/{id}', 'UserController@show')->name('profile');

Router::connect('/', 'PostController@index')->name('index');
Router::connect('/posts', 'PostController@list')->name('posts_list');
Router::connect('/posts/{id}', 'PostController@show')->params(['id' => '[0-9]+'])->name('posts_detail');
Router::connect('/posts/submit', 'PostController@submit')->name('posts_submit');

Router::connect(
    '/hello/{name}', function ($name) {
        dd("Hello ${name}");
    }
)->name('hello');
