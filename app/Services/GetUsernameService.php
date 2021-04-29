<?php
namespace App\Services;


class GetUsernameService
{

    public function getUserName():string{
        if (isset($_SESSION['name'])){
            return "Welcome " . $_SESSION['name'];
        }
        return '';

    }

}