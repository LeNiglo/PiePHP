<?php

use \Core\Router;

Router::connect('/', ['c' => 'user', 'a' => 'index']);
Router::connect('/list', ['c' => 'user', 'a' => 'list']);
Router::connect('/u/{id}', ['c' => 'user', 'a' => 'show']);
Router::connect('/user/{id}/show', ['c' => 'user', 'a' => 'show', 'p' => ['id' => '[0-9]+']]);
Router::connect('/posts', ['c' => 'user', 'a' => 'posts']);
