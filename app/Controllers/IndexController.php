<?php

namespace App\Controllers;


use App\Services\LikesService;
use App\Services\MyPicsService;
use App\Services\ShowNextProfileService;
use App\Services\UploadService;
use App\Services\UsersPicsService;

use Twig\Environment;

class IndexController
{

    private Environment $environment;
    private UsersPicsService $userPicsService;
    private ShowNextProfileService $showNextProfileService;
    private LikesService $likesService;
    public function __construct(Environment $environment,

                                UsersPicsService $userPicsService,
                                ShowNextProfileService $showNextProfileService,
                                LikesService $likesService)
    {
        $this->environment = $environment;
        $this->userPicsService = $userPicsService;
        $this->showNextProfileService = $showNextProfileService;
        $this->likesService = $likesService;
    }

    public function index(): void
    {
        $this->showNextProfileService->next();
        $this->userPicsService->userPics();
        $this->likesService->storeLikes($_POST);

        echo $this->environment->render('indexView.twig', ['username' => $_SESSION['name'],
            'pics' => $this->userPicsService->userPics(), 'myLikes' => $this->likesService->getMyLikes()]);
    }



}