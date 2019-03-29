<?php

namespace Controller;

use \Core\Controller;

use \Model\UserModel;
use \Model\PostModel;

/**
*
*/
class PostController extends Controller
{
    public function submit()
    {
        if ($this->request->method() === 'POST') {
            $post = new PostModel([
                'title' => $this->request->title,
                'content' => $this->request->content,
                'user_id' => $this->request->user_id,
            ]);
            $post->save();
            $this->redirect($this->request->back());
        }
        $this->redirect('/');
    }
}
