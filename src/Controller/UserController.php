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
        if (!\Auth::check()) {
            $this->redirect('/login');
        }
        $user = \Auth::user();

        $this->render('welcome', [
            'user' => $user,
        ]);
    }

    public function show_me()
    {
        if (!\Auth::check()) {
            $this->redirect('/login');
        }
        return $this->show(\Auth::id());
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
