<?php

namespace Model;

use \Core\Entity;

/**
*
*/
class UserModel extends Entity
{
    protected static $_table = 'user';
    protected static $_id = 'id_user';
    protected $_fields = ['name', 'email', 'password', 'agency_id'];

    public function posts()
    {
        return $this->hasMany('\Model\PostModel');
    }

    public function agency()
    {
        return $this->belongsTo('\Model\AgencyModel');
    }
}
