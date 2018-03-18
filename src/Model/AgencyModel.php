<?php

namespace Model;

use \Core\Entity;

/**
 *
 */
class AgencyModel extends Entity
{
    protected static $_table = 'agencies';
    protected $_fields = ['name',];

    public function posts()
    {
        return $this->hasManyThrough('\Model\PostModel', '\Model\UserModel');
    }

    public function users()
    {
        return $this->hasMany('\Model\UserModel', 'agency_id');
    }
}
