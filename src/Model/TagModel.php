<?php

namespace Model;

use \Core\Entity;

/**
*
*/
class TagModel extends Entity
{
    protected static $_fields = ['name'];

    public function posts()
    {
        return $this->belongsToMany(\Model\PostModel::class, 'posts_tags');
    }
}
