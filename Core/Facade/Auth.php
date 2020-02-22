<?php

namespace Core\Facade;

class Auth
{
    const AUTH_KEY = 'piephp_user_id';
    const DEFAULT_AUTH_MODEL = '\Model\UserModel';

    private static $_user = null;

    public static function check()
    {
        $auth_class = env('AUTH_MODEL', self::DEFAULT_AUTH_MODEL);

        if (!is_null(static::$_user)) {
            return true;
        }
        if (isset($_SESSION[self::AUTH_KEY])) {
            $u = $auth_class()::find($_SESSION[self::AUTH_KEY]);
            if (!is_null($u)) {
                static::$_user = $u;

                return true;
            }

            return false;
        }

        return false;
    }

    public static function attempt($email, $password)
    {
        $error = false;
        $auth_class = env('AUTH_MODEL', self::DEFAULT_AUTH_MODEL);
        $auth_id = $auth_class::getId();

        $user = $auth_class::query()->where('email', $email)->first();
        if ($user) {
            if (password_verify($password, $user->password)) {
                static::$_user = $user;
                $_SESSION[self::AUTH_KEY] = static::$_user->{$auth_id};

                return true;
            }
            $error = 'Invalid password.';
        } else {
            $error = 'User not found.';
        }

        return $error;
    }

    public static function user()
    {
        if (is_null(static::$_user)) {
            if (false === static::check()) {
                return null;
            }
        }

        return static::$_user;
    }

    public static function id()
    {
        $auth_class = env('AUTH_MODEL', self::DEFAULT_AUTH_MODEL);
        $auth_id = $auth_class::getId();

<<<<<<< HEAD
        return static::user()->{$auth_id};
=======
        return static::user()->$auth_id;
>>>>>>> master
    }

    public static function logout()
    {
        unset($_SESSION[self::AUTH_KEY]);
    }
}
