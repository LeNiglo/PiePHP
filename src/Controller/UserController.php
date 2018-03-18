<?php

namespace Controller;

use \Core\Controller;
use \Model\UserModel;
use \Model\PostModel;
use \Model\AgencyModel;

/**
 *
 */
class UserController extends Controller
{

    public function index()
    {
        $user = UserModel::find(2);
        $users = UserModel::findAll();

        $this->render('user.show', [
            'users' => $users,
            'user' => $user,
        ]);
    }

    public function show($id)
    {
        dd(UserModel::find($id));
    }

    public function posts()
    {
        $agency = AgencyModel::find(1);
        foreach ($agency->posts as &$p) {
            $p->tags;
            unset($p);
        }
        dd($agency);
    }
}
