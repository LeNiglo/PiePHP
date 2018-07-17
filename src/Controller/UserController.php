<?php

namespace Controller;

use \Core\Controller;
use \Model\UserModel;
use \Model\PostModel;
use \Model\AgencyModel;

use \Core\Database\QueryBuilder;

/**
*
*/
class UserController extends Controller
{

    public function index()
    {
        echo '<pre>';
        // dd(QueryBuilder::table('user')->where('id_user', 1)->orWhere('email', 'LIKE', "%epitech.eu")->get());
        dd(QueryBuilder::table('user')->whereIn('id_user', [42, 2, 3])->get());
    }

    public function list()
    {
        $user = UserModel::find(2);
        $users = UserModel::findAll();

        $user->name = "Cintia";
        $user->save();

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
            foreach ($p->tags as $t) {
                echo "<p>" . $t->name . "</p>";
            }
            unset($p);
        }
        dd($agency);
    }
}
