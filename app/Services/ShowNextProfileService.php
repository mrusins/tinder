<?php
namespace App\Services;


use App\Repositories\DBInterface;
use App\Repositories\MySQLRepository;

class ShowNextProfileService
{
    private array $userPics = [];

    private DBInterface $tinderDB;

    public function __construct(DBInterface $tinderDB){

        $this->tinderDB = $tinderDB;
    }

    public function next(){
        if(isset($_POST['next'])){
            if($_SESSION['next']>=count($this->getOppositeGender())-1){
                $_SESSION['next'] = 0;
            }else {
                $_SESSION['next']++;
            }
        }
    }
    public function getOppositeGender():array{
        return $this->tinderDB->getOppositeGender($_SESSION['gender']);
    }

}