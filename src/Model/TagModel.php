<?php

namespace App\Model;

use PiePHP\Core\Entity;

class TagModel extends Entity
{
    protected static $_fields = ['name'];

    public function posts()
    {
        return $this->belongsToMany(\App\Model\PostModel::class, 'posts_tags');
    }
}
