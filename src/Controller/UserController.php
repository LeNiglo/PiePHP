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

    public function show_me()
    {
        return $this->show($_SESSION['user_id']);
    }

    public function show($id)
    {
        $user = UserModel::query()->where(UserModel::getId(), $id)->orWhere('name', $id)->first();
        if (is_null($user)) {
            $this->redirect($this->request->back());
        }
        $this->render('user.show', [
            'user' => $user,
        ]);
    }
}
