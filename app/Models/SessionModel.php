<?php
namespace App\Models;


class SessionModel
{
    private int $id;
    private string $name;
    private string $gender;
    private string $token;
    private int $next;

    public function __construct(int $id, string $name, string $gender, string $token, int $next){

        $this->id = $id;
        $this->name = $name;
        $this->gender = $gender;
        $this->token = $token;
        $this->next = $next;
    }


    public function getId():int{
        return $this->id;
    }

    public function getUsername():string{
        return $this->name;
    }

    public function getGender():string{
        return $this->gender;
    }

    public function getToken():string{
        return $this->token;
    }

    public function getNext():int{
        return $this->next;
    }


}

