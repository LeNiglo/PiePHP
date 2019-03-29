<?php

namespace Model;

use \Core\Entity;

/**
*
*/
class PostModel extends Entity
{
    protected static $_fields = ['title', 'content', 'user_id'];

    public function user()
    {
        return $this->belongsTo('\Model\UserModel');
    }

    public function tags()
    {
        return $this->belongsToMany('\Model\TagModel', 'posts_tags');
    }
}
