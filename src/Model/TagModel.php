<?php

namespace Model;

use \Core\Entity;

/**
 *
 */
class TagModel extends Entity
{
    protected $_fields = ['name'];

    public function posts()
    {
        return $this->belongsToMany('\Model\PostModel', 'posts_tags');
    }
}
