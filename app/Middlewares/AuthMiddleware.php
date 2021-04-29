<?php
namespace App\Middlewares;


use App\Validations\LogInValidation;

class AuthMiddleware
{
    private LogInValidation $checkPassword;

    public function handle():void{
        $this->checkPassword = new LogInValidation();
        if(!isset($_SESSION['name']) || $this->checkPassword == false ){
            header("Location: /");
        }
    }

}