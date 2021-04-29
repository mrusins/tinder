<?php
namespace App\Validations;

class LogInValidation
{
    public function checkUserName(array $post, array $session):bool{
        $isValid = true;
        if (isset($post) && !isset($session['name'])){
            $isValid = false;
        }
        return $isValid;
    }

    public function checkUserPassword($postPassword, $passwordInDB):bool{
        $isValid = false;
        if ( password_verify($postPassword, $passwordInDB)){
            $isValid = true;
        }
        return $isValid;
    }

    public function isAuthSuccess(array $post, array $session):bool{
        if ( isset($session['status']) && $session['status'] === false){
            return false;
        }
        return true;
    }

}

