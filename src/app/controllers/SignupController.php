<?php
namespace MyApp\Controllers;

use Phalcon\Mvc\Controller;
use MyApp\Models\Users;

class SignupController extends Controller
{

    public function IndexAction() {
        // defalut action
    }

    public function registerAction()
    {
        $user = new Users();

        $user->assign(
            $this->request->getPost(),
            [
                'name',
                'email',
                'password'
            ]
        );
        $success = $user->save();
        $this->view->success = $success;
        if ($success) {
            $this->view->message = "Register succesfully";
        } else {
            $this->view->message = "Not Register due to following reason: <br>" . implode("<br>", $user->getMessages());
        }
    }
}
