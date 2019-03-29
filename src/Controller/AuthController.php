<?php

namespace Controller;

use \Core\Controller;

use \Model\UserModel;

/**
*
*/
class AuthController extends Controller
{
    public function login()
    {
        $error = null;
        if ($this->request->method() === 'POST') {
            $user = UserModel::query()->where('email', $this->request->email)->first();
            if ($user) {
                if (password_verify($this->request->password, $user->password)) {
                    $_SESSION['user_id'] = $user->id;
                    $this->redirect('/');
                } else {
                    $error = 'Invalid password.';
                }
            } else {
                $error = 'User not found.';
            }
        }
        dump($error);
        $this->render('user.auth.login', [
            'error' => $error,
        ]);
    }

    public function register()
    {
        $error = null;
        if ($this->request->method() === 'POST') {
            $user = new UserModel([
                'name' => $this->request->name,
                'email' => $this->request->email,
                'password' => password_hash($this->request->password, PASSWORD_BCRYPT),
            ]);
            $user->save();
            $this->redirect('/login');
        }
        $this->render('user.auth.register', [
            'error' => $error,
        ]);
    }
}
