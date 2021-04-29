<?php

namespace App\Controllers;

use App\Services\AuthorizeService;
use App\Validations\LogInValidation;
use Twig\Environment;
use App\Models\SessionModel;

class AuthController
{

    private Environment $environment;
    private AuthorizeService $authorizeService;
    private SessionModel $sessionModel;
    private LogInValidation $logInValidation;

    public function __construct(Environment $environment,
                                AuthorizeService $tinderAuthorizeService,
                                LogInValidation $logInValidation)
    {
        $this->environment = $environment;
        $this->authorizeService = $tinderAuthorizeService;
        $this->logInValidation = $logInValidation;
        if (isset($_SESSION['token'])) {
            $this->sessionModel = new SessionModel($_SESSION['id'], $_SESSION['name'], $_SESSION['gender'], $_SESSION['token'], $_SESSION['next']);
        }
    }

    public function session(): SessionModel
    {

        return $this->sessionModel;
    }

    public function auth(): void
    {
        $this->authorizeService->authorize($_POST);
        echo $this->environment->render('login.twig',
            ['error' => $this->logInValidation->isAuthSuccess($_POST, $_SESSION)]);


    }
}