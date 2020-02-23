<?php

namespace App\Model;

use PiePHP\Core\Entity;

class PostModel extends Entity
{
    protected static $_fields = ['title', 'content', 'user_id'];

    public function user()
    {
        return $this->belongsTo(\App\Model\UserModel::class);
    }

    public function tags()
    {
        return $this->belongsToMany(\App\Model\TagModel::class, 'posts_tags');
    }
}
