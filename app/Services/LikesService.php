<?php
namespace App\Services;


use App\Repositories\DBInterface;
use App\Repositories\MySQLRepository;

class LikesService
{

    private DBInterface $tinderDB;

    public function __construct(DBInterface $tinderDB){

        $this->tinderDB = $tinderDB;
    }

    public function getOppositeGender():array{
        return $this->tinderDB->getOppositeGender($_SESSION['gender']);
    }


    public function storeLikes(array $post):void
    {
        $likeUserId = $this->getOppositeGender()[$_SESSION['next']]['username'];
        if(isset($post['like'])){
            $this->tinderDB->updateLikes($_SESSION['id'], $likeUserId );
        }
    }

    public function getMyLikes():array{
        if ($this->tinderDB->getLikes($_SESSION['id'])!= null){
            return $this->tinderDB->getLikes($_SESSION['id']);
        }
        return [];
    }

}