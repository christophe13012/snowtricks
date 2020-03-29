<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Note;


class BaseController extends AbstractController
{
    /**
     * @Route("/", methods={"GET","HEAD"}, name="accueil")
     */
    public function getAccueil()
    {
        return $this->render('index.html.twig');
    }
}
