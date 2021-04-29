<?php

namespace App\Repositories;



interface DBInterface{

    public function getLastFromWallet();
    public function addFromAPI(Stock $new);
    public function updatePic(int $userID, string $picName);
    public function login(string $search);
    public function getPicsFromDB(string $name);
    public function updateLikes(int $userID, string $likes);
    public function getLikes(string $userID);


}