<?php
namespace App\Services;


use App\Repositories\DBInterface;
use App\Repositories\MySQLRepository;

class UsersPicsService
{
    private array $userPics = [];

    private DBInterface $tinderDB;

    public function __construct(DBInterface $tinderDB){

        $this->tinderDB = $tinderDB;
    }

    public function userPics():array
    {
        if($this->tinderDB->getPicsFromDB($_SESSION['name']) != null){
            $this->getOppositeGender();

            $userPicUrl = $this->tinderDB->getPicsFromDB($this->getOppositeGender()[$_SESSION['next']]['username']);
            foreach ($userPicUrl as $user){
                array_push($this->userPics, $user['url']);
            }
            return $this->userPics;
        }
        return [];
    }

    public function getOppositeGender():array{
        return $this->tinderDB->getOppositeGender($_SESSION['gender']);
    }

}