<?php

namespace App\Controllers;

use App\Services\MyPicsService;
use App\Services\UploadService;
use Twig\Environment;

class UserAdminController
{
    private Environment $environment;
    private UploadService $uploadService;
    private MyPicsService $myPicsService;

    public function __construct(Environment $environment,
                                UploadService $tinderUploadService,
                                MyPicsService $myPicsService)
    {
        $this->environment = $environment;
        $this->uploadService = $tinderUploadService;
        $this->myPicsService = $myPicsService;
    }

    public function admin(): void
    {
        $sessionName = '';
        if (isset($_SESSION['name'])) {
            $sessionName = $_SESSION['name'];
        }
        $this->myPicsService->myPics($sessionName);
        echo $this->environment->render('admin.twig', ['username' => $_SESSION['name'],
            'pics' => $this->myPicsService->myPics($sessionName), 'picture' => $_SESSION['error']]);
    }

    public function upload()
    {
        $this->uploadService->upload($_POST);
        header("Location: /admin");

    }
}