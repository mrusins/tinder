<?php
namespace App\Services;


use App\Repositories\DBInterface;
use App\Repositories\MySQLRepository;

class MyPicsService
{
    private array $userPics = [];

    private DBInterface $tinderDB;

    public function __construct(DBInterface $tinderDB){

        $this->tinderDB = $tinderDB;
    }

    public function myPics(string $sessionName):array
    {
        if($this->tinderDB->getPicsFromDB($sessionName) != null){
            $this->getOppositeGender();
            $userPicUrl = $this->tinderDB->getPicsFromDB($_SESSION['name']);
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