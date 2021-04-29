<?php
namespace App\Services;


use App\Models\SessionModel;
use App\Repositories\DBInterface;
use App\Validations\LogInValidation;

class AuthorizeService
{

    private string $token = '';
    private SessionModel $session;


    private DBInterface $tinderDB;
    private LogInValidation $logInValidation;


    public function __construct(DBInterface $tinderDB, LogInValidation $logInValidation){

        $this->tinderDB = $tinderDB;

        $this->logInValidation = $logInValidation;
    }

    public function authorize( array $post):string
    {

        if (isset($post['authorize'])) {
            $this->token = bin2hex(random_bytes(16));
            $result = $this->tinderDB->login($post['authorize']);
            if (isset($result['username']) &&
                $this->logInValidation->checkUserPassword($post['password'], $result['password']) == true) {
                $_SESSION['id'] = $result['id'];
                $_SESSION['name'] = $result['username'];
                $_SESSION['gender'] = $result['sex'];
                $_SESSION['token'] = $this->token;
                $_SESSION['next'] = 0;
                $_SESSION['password'] = $result['password'];
                $_SESSION['error'] = true;

            }
            $_SESSION['status'] = false;
            header("Location: /"); //TODO
        }
        if (isset($_SESSION['name'])) {
            $_SESSION['status'] = true;
            header("Location: /user");
            return $_SESSION['name'];
        }
        return false;
    }


}