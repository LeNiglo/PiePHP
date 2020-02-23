<?php

namespace App\Controller;

use App\Model\UserModel;
use PiePHP\Core\Controller;

class AuthController extends Controller
{
    public function login()
    {
        if (\Auth::check()) {
            $this->redirect('/u');
        }
        $error = null;
        if ('POST' === $this->request->method()) {
            $error = \Auth::attempt($this->request->email, $this->request->password);
            if (true === $error) {
                $this->redirect('/u');
            }
        }
        $this->render(
            'user.auth.login', [
            'error' => $error,
            ]
        );
    }

    public function register()
    {
        if (\Auth::check()) {
            $this->redirect('/u');
        }
        $error = null;
        if ('POST' === $this->request->method()) {
            $user = new UserModel(
                [
                'name' => $this->request->name,
                'email' => $this->request->email,
                'password' => password_hash($this->request->password, PASSWORD_BCRYPT),
                ]
            );
            $user->save();
            $this->redirect('/login');
        }
        $this->render(
            'user.auth.register', [
            'error' => $error,
            ]
        );
    }

    public function logout()
    {
        \Auth::logout();
        $this->redirect('/login');
    }
}
