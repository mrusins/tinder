<?php
namespace app\Models;

class UserModel
{
    private int $id;
    private string $username;
    private string $sex;

    public function __construct(int $id, string $username, string $sex){

        $this->id = $id;
        $this->username = $username;
        $this->sex = $sex;
    }
    public function getId():int{
        return $this->id;
    }

    public function getUsername():string{
        return $this->username;
    }

    public function getSex():string{
        return $this->sex;
    }
}

