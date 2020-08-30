<?php


namespace App\Controllers;


use App\Models\User;
use Zend\Diactoros\Response\RedirectResponse;
class AuthController extends BaseController
{
    public function getLogin(){
        return $this->renderHTML('login.twig');
    }

    public function authAction( $request ){
        $postData = $request->getParsedBody();
        $responseMessage=null;
        $user = User::where('email',$postData['email'])->first();

        if($user){
            if(password_verify($postData['password'],$user->password)){
                $_SESSION['userId'] =$user->id;
                return new RedirectResponse('admin');
            } else {
                $responseMessage= 'bad credentials';
            }
        } else {
            $responseMessage= 'Bad credentials';
        }

        return $this->renderHTML('login.twig',[
            'responseMessage'=>$responseMessage
        ]);
    }

    public function getLogOut(){
        unset($_SESSION['userId']);
        return new RedirectResponse('login');
    }
}