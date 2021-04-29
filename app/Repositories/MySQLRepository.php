<?php

namespace App\Repositories;


use Simplon\Mysql\PDOConnector;
use Simplon\Mysql\Mysql;

class MySQLRepository implements DBInterface
{
    private Mysql $dbConn;

    public function __construct()
    {

        $pdo = new PDOConnector(
            'localhost', // server
            '',      // user
            '',      // password
            'tinder'   // database
        );
        $pdoConn = $pdo->connect('utf8', []); // charset, options
        $this->dbConn = new Mysql($pdoConn);
    }


    public function login(string $search)  //if null returned -> fatal error
    {
        return $result = $this->dbConn->
        fetchRow('SELECT * FROM users
WHERE username = :name', ['name' => $search]);
    }


    public function getOppositeGender(string $gender):array{
        if($gender == 'him'){
            return $result = $this->dbConn->
            fetchRowMany('SELECT username FROM users
WHERE sex = :name', ['name' => 'her']);
        } elseif ($gender == 'her') {
            return $result = $this->dbConn->
            fetchRowMany('SELECT username FROM users
WHERE sex = :name', ['name' => 'him']);
        }
    }

    public function getLikes(string $userID){

            return  $this->dbConn->
            fetchRowMany('SELECT likes FROM users_likes
WHERE user_id = :name', ['name' => $userID]);
        }

    public function getPicsFromDB(string $name)
    {
        return $result = $this->dbConn->
        fetchRowMany('SELECT * FROM users_pic
WHERE name = :name', ['name' => $name]);
    }

    public function getLastFromWallet():array
    {
        return $result = $this->dbConn->
        fetchRow('SELECT total FROM Wallet where id=(select max(id) from Wallet);');
    }

    public function updatePic(int $userID, string $picName): void
    {
        $this->dbConn->insert('users_pic', ['url'=>$picName, 'name'=>$_SESSION['name']] );
    }

    public function updateLikes(int $userID, string $likes): void
    {
        $this->dbConn->insert('users_likes', ['likes'=>$likes, 'user_id'=>$userID] );
    }

    public function addFromAPI(Stock $new): void
    {
        $this->dbConn->insert('Stocks', ['name'=>$new->name,'actual_price'=>$new->actualPrice,'starting_price'=>$new->startingPrice,
            'final_price'=>$new->finalPrice,'total'=>$new->total]);

    }

}