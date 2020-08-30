<?php


namespace App\Controllers;


use App\Models\User;
use Zend\Diactoros\Request;
use Respect\Validation\Validator as v;

class UserController extends BaseController
{

    public function createAction(){

        return $this->renderHTML('create_user.twig');

    }

    public function saveAction(  $request ){

        $responseMessage =null;
        if ($request->getMethod() == 'POST') {
            $postData = $request->getParsedBody();
            $userValidations = v::key('email',v::stringType()->notEmpty())
                                ->key('password', v::stringType()->notEmpty());

            try {
                $userValidations->assert($postData);

                $user = new User();
                $user->email = $postData['email'];
                $encriptpass = password_hash($postData['password'],PASSWORD_DEFAULT);
                $user->password = $encriptpass;
                $user->save();

                $responseMessage = 'Saved';
            } catch (\Exception $e){
                $responseMessage = $e->getMessage();
            }

            return $this->renderHTML('create_user.twig',[
                'responseMessage'=>$responseMessage
            ]);

        }

    }


}