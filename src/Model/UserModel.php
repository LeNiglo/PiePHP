<?php

namespace App\Model;

use PiePHP\Core\Entity;

class UserModel extends Entity
{
    protected static $_table = 'user';
    protected static $_id = 'id';
    protected static $_fields = ['name', 'email', 'password'];

    public function posts()
    {
        return $this->hasMany(\App\Model\PostModel::class);
    }
}
