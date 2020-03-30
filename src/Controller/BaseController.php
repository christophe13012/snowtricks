<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Note;
use App\Entity\Tricks;

class BaseController extends AbstractController
{
    /**
     * @Route("/", methods={"GET","HEAD"}, name="accueil")
     */
    public function getAccueil()
    {
        $tricks = $this->getDoctrine()
        ->getRepository(Tricks::class)
        ->findBy(array(), array('id' => 'DESC'),3);

        return $this->render('index.html.twig',[
            "tricks" => $tricks
        ]);
    }
}
