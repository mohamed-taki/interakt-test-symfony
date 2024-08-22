<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ErrorsController extends AbstractController
{
    #[Route('/404', name:'app_not_found')]
    public function showContentNotFoundPage(): Response{
        return $this->render('widgets/errors/404.html.twig');
    }
}
