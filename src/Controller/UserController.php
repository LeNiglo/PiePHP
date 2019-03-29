<?php

namespace Controller;

use \Core\Controller;
use \Model\UserModel;
use \Model\PostModel;

use \Core\Database\QueryBuilder;

/**
*
*/
class UserController extends Controller
{
    public function index()
    {
        if (!$_SESSION['user_id']) {
            $this->redirect('/login');
        }
        $user = UserModel::find($_SESSION['user_id']);

        $this->render('welcome', [
            'user' => $user,
        ]);
    }

    public function list()
    {
        $user = UserModel::find(2);
        $users = UserModel::findAll();

        if ($user) {
            $user->name = "Marine Moynet";
            $user->save();
        }

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
        $users = UserModel::findAll();
        foreach ($users as $p) {
            foreach ($p->posts as $p) {
                $p->tags;
            }
        }
        dd($users);
    }
}
